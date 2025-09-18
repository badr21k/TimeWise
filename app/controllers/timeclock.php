<?php
// app/controllers/timeclock.php
class timeclock extends Controller
{
    private PDO $db;

    public function __construct() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['auth'])) { header('Location: /login'); exit; }
        $this->db = db_connect();
        $this->ensureTables();
    }

    public function index() {
        if (class_exists('AccessControl')) {
            AccessControl::enforceAccess('timeclock', 'index', 'Time Clock');
        }
        $this->view('timeclock/index');
    }

    public function api() {
        if (empty($_SESSION['auth'])) {
            http_response_code(401);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error'=>'Auth required']);
            return;
        }
        if (class_exists('AccessControl') && !AccessControl::hasControllerAccess('timeclock')) {
            http_response_code(403);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error'=>'Access denied']);
            return;
        }

        header('Content-Type: application/json; charset=utf-8');
        $action = $_GET['a'] ?? '';
        try {
            switch ($action) {
                case 'status':
                    echo json_encode($this->status());
                    break;
                case 'clock.in':
                    echo json_encode($this->clockIn());
                    break;
                case 'break.start':
                    echo json_encode($this->breakStart());
                    break;
                case 'break.end':
                    echo json_encode($this->breakEnd());
                    break;
                case 'clock.out':
                    $satisfaction = isset($_POST['satisfaction']) ? (int)$_POST['satisfaction'] : null;
                    echo json_encode($this->clockOut($satisfaction));
                    break;
                default:
                    echo json_encode(['error'=>'Unknown action']);
            }
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error'=>$e->getMessage()]);
        }
    }

    private function ensureTables(): void
    {
        // Main time entries table
        $this->db->exec("CREATE TABLE IF NOT EXISTS time_entries (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            employee_id INT NULL,
            entry_date DATE NOT NULL,
            clock_in DATETIME NOT NULL,
            clock_out DATETIME NULL,
            total_break_minutes INT DEFAULT 0,
            satisfaction TINYINT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_user_date (user_id, entry_date),
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE SET NULL
        )");

        // Breaks per time entry
        $this->db->exec("CREATE TABLE IF NOT EXISTS time_entry_breaks (
            id INT AUTO_INCREMENT PRIMARY KEY,
            time_entry_id INT NOT NULL,
            break_start DATETIME NOT NULL,
            break_end DATETIME NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (time_entry_id) REFERENCES time_entries(id) ON DELETE CASCADE,
            INDEX idx_entry (time_entry_id)
        )");
    }

    private function currentUserId(): int { return (int)($_SESSION['id'] ?? 0); }

    private function resolveEmployeeForUser(): ?array {
        // Prefer direct employees.user_id link
        try {
            $stmt = $this->db->prepare("SELECT id, start_date FROM employees WHERE user_id = :uid LIMIT 1");
            $stmt->execute([':uid' => $this->currentUserId()]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (Throwable $e) { return null; }
    }

    private function today(): string { return date('Y-m-d'); }

    private function openEntry(): ?array {
        $stmt = $this->db->prepare("SELECT * FROM time_entries WHERE user_id = :u AND entry_date = :d AND clock_out IS NULL LIMIT 1");
        $stmt->execute([':u'=>$this->currentUserId(), ':d'=>$this->today()]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    private function hasActiveBreak(int $timeEntryId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM time_entry_breaks WHERE time_entry_id = :id AND break_end IS NULL ORDER BY break_start DESC LIMIT 1");
        $stmt->execute([':id'=>$timeEntryId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    private function status(): array
    {
        $entry = $this->openEntry();
        if (!$entry) return ['clocked_in'=>false, 'on_break'=>false, 'entry'=>null];
        $break = $this->hasActiveBreak((int)$entry['id']);
        return [
            'clocked_in' => true,
            'on_break' => (bool)$break,
            'entry' => [
                'id' => (int)$entry['id'],
                'clock_in' => $entry['clock_in'],
                'total_break_minutes' => (int)$entry['total_break_minutes'],
                'satisfaction' => $entry['satisfaction'] !== null ? (int)$entry['satisfaction'] : null,
            ]
        ];
    }

    private function clockIn(): array
    {
        if ($this->openEntry()) throw new Exception('Already clocked in.');
        $emp = $this->resolveEmployeeForUser();
        $empId = $emp ? (int)$emp['id'] : null;
        // Enforce start_date: cannot clock in before join date
        if (!empty($emp['start_date'])) {
            $join = new DateTime($emp['start_date']);
            $today = new DateTime($this->today());
            if ($join > $today) throw new Exception('Your start date is in the future. Clock-in not allowed yet.');
        }
        $stmt = $this->db->prepare("INSERT INTO time_entries (user_id, employee_id, entry_date, clock_in) VALUES (:u, :e, :d, NOW())");
        $stmt->execute([':u'=>$this->currentUserId(), ':e'=>$empId, ':d'=>$this->today()]);
        return ['ok'=>true];
    }

    private function breakStart(): array
    {
        $entry = $this->openEntry();
        if (!$entry) throw new Exception('Not clocked in.');
        if ($this->hasActiveBreak((int)$entry['id'])) throw new Exception('Break already in progress.');
        $stmt = $this->db->prepare("INSERT INTO time_entry_breaks (time_entry_id, break_start) VALUES (:id, NOW())");
        $stmt->execute([':id'=>(int)$entry['id']]);
        return ['ok'=>true];
    }

    private function breakEnd(): array
    {
        $entry = $this->openEntry();
        if (!$entry) throw new Exception('Not clocked in.');
        $active = $this->hasActiveBreak((int)$entry['id']);
        if (!$active) throw new Exception('No active break.');
        $this->db->prepare("UPDATE time_entry_breaks SET break_end = NOW() WHERE id = :id")
                 ->execute([':id'=>(int)$active['id']]);
        // Recompute total break minutes for entry
        $stmt = $this->db->prepare("SELECT SUM(TIMESTAMPDIFF(MINUTE, break_start, COALESCE(break_end, NOW()))) FROM time_entry_breaks WHERE time_entry_id = :id");
        $stmt->execute([':id'=>(int)$entry['id']]);
        $mins = (int)$stmt->fetchColumn();
        $this->db->prepare("UPDATE time_entries SET total_break_minutes = :m WHERE id = :id")
                 ->execute([':m'=>$mins, ':id'=>(int)$entry['id']]);
        return ['ok'=>true, 'total_break_minutes'=>$mins];
    }

    private function clockOut(?int $satisfaction): array
    {
        $entry = $this->openEntry();
        if (!$entry) throw new Exception('Not clocked in.');
        if ($this->hasActiveBreak((int)$entry['id'])) throw new Exception('End your break before clocking out.');
        if ($satisfaction !== null) {
            if ($satisfaction < 1 || $satisfaction > 5) throw new Exception('Satisfaction must be 1-5.');
        }
        $this->db->prepare("UPDATE time_entries SET clock_out = NOW(), satisfaction = COALESCE(:sat, satisfaction) WHERE id = :id")
                 ->execute([':sat'=>$satisfaction, ':id'=>(int)$entry['id']]);
        return ['ok'=>true];
    }
}
