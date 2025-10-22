<?php
// app/controllers/timeclock.php
class timeclock extends Controller
{
    private PDO $db;

    public function __construct() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['auth'])) { header('Location: /login'); exit; }
        
        $this->db = db_connect();
        if (!$this->db) {
            error_log('TimeClock: Database connection failed');
            throw new Exception('Database connection failed. Please try again later.');
        }
        
        $this->ensureTables();
    }

    /* -------------------- Routes -------------------- */

    public function index() {
        if (class_exists('AccessControl')) {
            AccessControl::enforceAccess('timeclock', 'index', 'Time Clock');
        }
        $this->view('timeclock/index');
    }

    public function api() {
        if (empty($_SESSION['auth'])) {
            return $this->jsonError('Auth required', 401);
        }
        if (class_exists('AccessControl') && !AccessControl::hasControllerAccess('timeclock')) {
            return $this->jsonError('Access denied', 403);
        }

        $action = $_GET['a'] ?? '';
        try {
            // read client time context (optional but fixes local-time issues)
            [$tzName, $clientIso] = $this->readClientContext();

            switch ($action) {
                case 'status':
                    return $this->jsonOk($this->status($tzName));
                case 'clock.in':
                    return $this->jsonOk($this->clockIn($tzName, $clientIso));
                case 'break.start':
                    return $this->jsonOk($this->breakStart($clientIso));
                case 'break.end':
                    return $this->jsonOk($this->breakEnd($clientIso));
                case 'clock.out':
                    $satisfaction = isset($_POST['satisfaction']) ? (int)$_POST['satisfaction'] : null;
                    return $this->jsonOk($this->clockOut($clientIso, $satisfaction));
                default:
                    return $this->jsonError('Unknown action', 400);
            }
        } catch (Throwable $e) {
            return $this->jsonError($e->getMessage(), 500);
        }
    }

    /* -------------------- Helpers -------------------- */

    private function jsonOk($data) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        return;
    }

    private function jsonError($msg, $code = 400) {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => $msg]);
        return;
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
        try {
            $stmt = $this->db->prepare("SELECT id, start_date FROM employees WHERE user_id = :uid LIMIT 1");
            $stmt->execute([':uid' => $this->currentUserId()]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (Throwable $e) { return null; }
    }

    /** Read tz context sent by the browser; defaults to UTC if missing */
    private function readClientContext(): array {
        $tzName   = $_POST['tz'] ?? $_GET['tz'] ?? 'UTC';
        $clientIso = $_POST['client_time_iso'] ?? $_GET['client_time_iso'] ?? null;
        // sanitize tz
        try { new DateTimeZone($tzName); } catch (\Throwable $e) { $tzName = 'UTC'; }
        return [$tzName, $clientIso];
    }

    /** Convert client ISO instant (UTC) to server UTC 'Y-m-d H:i:s' */
    private function clientInstantToUtc(?string $clientIso): string {
        try {
            if ($clientIso) {
                $dt = new DateTime($clientIso, new DateTimeZone('UTC')); // client_time_iso is UTC on frontend
                return $dt->format('Y-m-d H:i:s');
            }
        } catch (\Throwable $e) {}
        // fallback: server UTC now
        $dt = new DateTime('now', new DateTimeZone('UTC'));
        return $dt->format('Y-m-d H:i:s');
    }

    /** Given tz, return [startUtc, endUtc] for "today" in that tz */
    private function todayBoundsUtc(string $tzName): array {
        try { $tz = new DateTimeZone($tzName); } catch (\Throwable $e) { $tz = new DateTimeZone('UTC'); }
        $startLocal = new DateTime('today 00:00:00', $tz);
        $endLocal   = clone $startLocal; $endLocal->modify('+1 day');
        $startLocal->setTimezone(new DateTimeZone('UTC'));
        $endLocal->setTimezone(new DateTimeZone('UTC'));
        return [$startLocal->format('Y-m-d H:i:s'), $endLocal->format('Y-m-d H:i:s')];
    }

    /** Derive a DATE string for entry_date based on the client's time and tz */
    private function entryDateForClient(?string $clientIso, string $tzName): string {
        try {
            $tz = new DateTimeZone($tzName);
            $dt = $clientIso ? new DateTime($clientIso, new DateTimeZone('UTC')) : new DateTime('now', new DateTimeZone('UTC'));
            $dt->setTimezone($tz);
            return $dt->format('Y-m-d');
        } catch (\Throwable $e) {
            return gmdate('Y-m-d');
        }
    }

    /** Return the currently open entry (no clock_out), regardless of entry_date */
    private function openEntry(): ?array {
        $stmt = $this->db->prepare("SELECT * FROM time_entries WHERE user_id = :u AND clock_out IS NULL ORDER BY clock_in DESC LIMIT 1");
        $stmt->execute([':u'=>$this->currentUserId()]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    private function hasActiveBreak(int $timeEntryId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM time_entry_breaks WHERE time_entry_id = :id AND break_end IS NULL ORDER BY break_start DESC LIMIT 1");
        $stmt->execute([':id'=>$timeEntryId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /* -------------------- API Methods -------------------- */

    /** STATUS: includes entries_today so the UI can render the list */
    private function status(string $tzName): array
    {
        $entry = $this->openEntry();
        $onBreak = $entry ? (bool)$this->hasActiveBreak((int)$entry['id']) : false;

        // window for "today" in the user's tz (converted to UTC for querying)
        [$startUtc, $endUtc] = $this->todayBoundsUtc($tzName);

        $stmt = $this->db->prepare("
            SELECT id, clock_in, clock_out, total_break_minutes, satisfaction
            FROM time_entries
            WHERE user_id = :u AND clock_in >= :start AND clock_in < :end
            ORDER BY clock_in ASC
        ");
        $stmt->execute([
            ':u'     => $this->currentUserId(),
            ':start' => $startUtc,
            ':end'   => $endUtc,
        ]);
        $entries = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

        // Get schedule information
        $scheduleInfo = $this->getScheduleInfo($tzName);

        return [
            'clocked_in' => (bool)$entry,
            'on_break'   => $onBreak,
            'entry'      => $entry ? [
                'id' => (int)$entry['id'],
                'clock_in' => $entry['clock_in'],                // UTC stored; frontend renders local
                'clock_out'=> $entry['clock_out'],
                'total_break_minutes' => (int)$entry['total_break_minutes'],
                'satisfaction' => $entry['satisfaction'] !== null ? (int)$entry['satisfaction'] : null,
            ] : null,

            // list for the "Today's Shifts" table
            'entries_today' => array_map(function($r){
                return [
                    'id' => (int)$r['id'],
                    'clock_in' => $r['clock_in'],
                    'clock_out'=> $r['clock_out'],
                    'total_break_minutes' => (int)$r['total_break_minutes'],
                    'satisfaction' => $r['satisfaction'] !== null ? (int)$r['satisfaction'] : null,
                ];
            }, $entries),

            // Schedule information
            'today_schedule' => $scheduleInfo['today'],
            'next_schedule' => $scheduleInfo['next'],
        ];
    }

    /** Get today's and next scheduled shift for the current user */
    private function getScheduleInfo(string $tzName): array
    {
        $emp = $this->resolveEmployeeForUser();
        if (!$emp) {
            return ['today' => null, 'next' => null];
        }

        $employeeId = (int)$emp['id'];
        
        try {
            $tz = new DateTimeZone($tzName);
        } catch (\Throwable $e) {
            $tz = new DateTimeZone('UTC');
        }

        // Get today's date in user's timezone
        $todayLocal = new DateTime('today 00:00:00', $tz);
        $todayStr = $todayLocal->format('Y-m-d');

        // Get tomorrow's date
        $tomorrowLocal = clone $todayLocal;
        $tomorrowLocal->modify('+1 day');
        $tomorrowStr = $tomorrowLocal->format('Y-m-d');

        // Find today's shift
        $stmt = $this->db->prepare("
            SELECT id, employee_id, start_dt, end_dt, notes, status
            FROM shifts
            WHERE employee_id = :emp AND DATE(start_dt) = :today
            ORDER BY start_dt ASC
            LIMIT 1
        ");
        $stmt->execute([':emp' => $employeeId, ':today' => $todayStr]);
        $todayShift = $stmt->fetch(PDO::FETCH_ASSOC);

        // Find next shift (today after current time, or future days)
        $nowUtc = new DateTime('now', new DateTimeZone('UTC'));
        $stmt = $this->db->prepare("
            SELECT id, employee_id, start_dt, end_dt, notes, status
            FROM shifts
            WHERE employee_id = :emp AND start_dt > :now
            ORDER BY start_dt ASC
            LIMIT 1
        ");
        $stmt->execute([':emp' => $employeeId, ':now' => $nowUtc->format('Y-m-d H:i:s')]);
        $nextShift = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'today' => $todayShift ? $this->formatShiftForFrontend($todayShift, $tz) : null,
            'next' => $nextShift ? $this->formatShiftForFrontend($nextShift, $tz) : null,
        ];
    }

    /** Format shift data for frontend consumption */
    private function formatShiftForFrontend(array $shift, DateTimeZone $tz): array
    {
        try {
            // Parse UTC times from database
            $startUtc = new DateTime($shift['start_dt'], new DateTimeZone('UTC'));
            $endUtc = new DateTime($shift['end_dt'], new DateTimeZone('UTC'));
            
            // Convert to user's timezone
            $startUtc->setTimezone($tz);
            $endUtc->setTimezone($tz);

            return [
                'id' => (int)$shift['id'],
                'date' => $startUtc->format('Y-m-d'),
                'startAt' => $startUtc->format('c'), // ISO 8601 format
                'endAt' => $endUtc->format('c'),
                'start' => $startUtc->format('g:i A'),
                'end' => $endUtc->format('g:i A'),
                'notes' => $shift['notes'],
                'status' => $shift['status'],
            ];
        } catch (\Throwable $e) {
            return [
                'id' => (int)$shift['id'],
                'date' => null,
                'startAt' => null,
                'endAt' => null,
                'start' => '—',
                'end' => '—',
                'notes' => $shift['notes'],
                'status' => $shift['status'],
            ];
        }
    }

    /** CLOCK IN: records UTC time using client instant; entry_date uses client's local date */
    private function clockIn(string $tzName, ?string $clientIso): array
    {
        if ($this->openEntry()) throw new Exception('Already clocked in.');

        $emp = $this->resolveEmployeeForUser();
        $empId = $emp ? (int)$emp['id'] : null;

        // Enforce start_date: cannot clock in before join date (based on user's local date)
        if (!empty($emp['start_date'])) {
            $join = new DateTime($emp['start_date']);
            $nowLocal = new DateTime($this->entryDateForClient($clientIso, $tzName)); // as date
            if ($join > $nowLocal) throw new Exception('Your start date is in the future. Clock-in not allowed yet.');
        }

        $clockInUtc = $this->clientInstantToUtc($clientIso);
        $entryDate  = $this->entryDateForClient($clientIso, $tzName);

        $stmt = $this->db->prepare("
            INSERT INTO time_entries (user_id, employee_id, entry_date, clock_in, total_break_minutes, created_at, updated_at)
            VALUES (:u, :e, :d, :cin, 0, UTC_TIMESTAMP(), UTC_TIMESTAMP())
        ");
        $stmt->execute([
            ':u' => $this->currentUserId(),
            ':e' => $empId,
            ':d' => $entryDate,
            ':cin' => $clockInUtc,
        ]);

        return ['ok'=>true];
    }

    private function breakStart(?string $clientIso): array
    {
        $entry = $this->openEntry();
        if (!$entry) throw new Exception('Not clocked in.');
        if ($this->hasActiveBreak((int)$entry['id'])) throw new Exception('Break already in progress.');

        $startUtc = $this->clientInstantToUtc($clientIso);
        $stmt = $this->db->prepare("INSERT INTO time_entry_breaks (time_entry_id, break_start, created_at) VALUES (:id, :bs, UTC_TIMESTAMP())");
        $stmt->execute([':id'=>(int)$entry['id'], ':bs'=>$startUtc]);

        return ['ok'=>true];
    }

    private function breakEnd(?string $clientIso): array
    {
        $entry = $this->openEntry();
        if (!$entry) throw new Exception('Not clocked in.');
        $active = $this->hasActiveBreak((int)$entry['id']);
        if (!$active) throw new Exception('No active break.');

        $endUtc = $this->clientInstantToUtc($clientIso);
        $this->db->prepare("UPDATE time_entry_breaks SET break_end = :be WHERE id = :id")
                 ->execute([':be'=>$endUtc, ':id'=>(int)$active['id']]);

        // Recompute total break minutes (use UTC math)
        $stmt = $this->db->prepare("SELECT SUM(TIMESTAMPDIFF(MINUTE, break_start, COALESCE(break_end, UTC_TIMESTAMP()))) FROM time_entry_breaks WHERE time_entry_id = :id");
        $stmt->execute([':id'=>(int)$entry['id']]);
        $mins = (int)$stmt->fetchColumn();

        $this->db->prepare("UPDATE time_entries SET total_break_minutes = :m, updated_at = UTC_TIMESTAMP() WHERE id = :id")
                 ->execute([':m'=>$mins, ':id'=>(int)$entry['id']]);

        return ['ok'=>true, 'total_break_minutes'=>$mins];
    }

    private function clockOut(?string $clientIso, ?int $satisfaction): array
    {
        $entry = $this->openEntry();
        if (!$entry) throw new Exception('Not clocked in.');
        if ($this->hasActiveBreak((int)$entry['id'])) throw new Exception('End your break before clocking out.');
        if ($satisfaction !== null && ($satisfaction < 1 || $satisfaction > 5)) {
            throw new Exception('Satisfaction must be 1-5.');
        }

        $clockOutUtc = $this->clientInstantToUtc($clientIso);
        $this->db->prepare("
            UPDATE time_entries
            SET clock_out = :cout, satisfaction = COALESCE(:sat, satisfaction), updated_at = UTC_TIMESTAMP()
            WHERE id = :id
        ")->execute([':cout'=>$clockOutUtc, ':sat'=>$satisfaction, ':id'=>(int)$entry['id']]);

        return ['ok'=>true];
    }
}
