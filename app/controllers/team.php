<?php
// app/controllers/Team.php
class Team extends Controller
{
    private PDO $db;

    public function __construct() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        if (empty($_SESSION['auth'])) { header('Location: /login'); exit; }
        $this->db = db_connect();
        $this->ensureTables();
    }

    /* GET /team */
    public function index() {
        if (class_exists('AccessControl')) {
            AccessControl::enforceAccess('team', 'index', 'Team Roster');
        }
        $this->view('team/index');
    }

    /* JSON API: /team/api?a=... */
    public function api() {
        if (empty($_SESSION['auth'])) {
            http_response_code(401);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error'=>'Auth required']);
            return;
        }

        // Centralized RBAC for Team controller (managers only)
        if (class_exists('AccessControl') && !AccessControl::hasControllerAccess('team')) {
            http_response_code(403);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error'=>'Access denied']);
            return;
        }

        header('Content-Type: application/json; charset=utf-8');
        $a = $_GET['a'] ?? '';

        try {
            switch ($a) {

                case 'bootstrap': // roster + options
                    echo json_encode([
                        'roster' => $this->roster(),
                        'departments' => $this->departments(),  // for optional UI use
                        'roles' => $this->roles(),
                    ]);
                    break;

                /* ===== HIRE ===== */
                case 'hire':
                    $this->guardAdmin();
                    $in = $this->json();

                    $username  = trim($in['username'] ?? '');
                    $full_name = trim($in['full_name'] ?? '');
                    $email     = trim($in['email'] ?? '');
                    $phone     = trim($in['phone'] ?? '');
                    $role      = trim($in['role_title'] ?? '');
                    $wage      = (float)($in['wage'] ?? 0);
                    $rate      = in_array(($in['rate'] ?? 'hourly'), ['hourly','salary']) ? $in['rate'] : 'hourly';
                    $access_level = (int)($in['access_level'] ?? 1);
                    $departments = $in['departments'] ?? [];
                    $start_dt  = trim($in['start_date'] ?? '') ?: date('Y-m-d');

                    if ($username === '') throw new Exception('Username is required');

                    // 1) Create user
                    //    - If password provided, use it, else generate random and force reset later if you want.
                    $password  = $in['password'] ?? bin2hex(random_bytes(6));
                    $phash     = password_hash($password, PASSWORD_BCRYPT);

                    // Unique username
                    $stmt = $this->db->prepare("SELECT id FROM users WHERE username=:u LIMIT 1");
                    $stmt->execute([':u'=>$username]);
                    if ($stmt->fetchColumn()) throw new Exception('Username already exists');

                    $this->db->prepare("
                        INSERT INTO users (username, full_name, password, access_level)
                        VALUES (:u, NULLIF(:fn,''), :p, :al)
                    ")->execute([
                        ':u'=>$username, ':fn'=>$full_name, ':p'=>$phash, ':al'=>$access_level
                    ]);
                    $user_id = (int)$this->db->lastInsertId();

                    // 2) Upsert/Insert employee row for that user
                    $this->db->prepare("
                        INSERT INTO employees (
                            user_id, name, email, phone, role_title, wage, rate,
                            start_date, is_active, eligible_for_rehire
                        ) VALUES (:uid, NULLIF(:n,''), NULLIF(:e,''), NULLIF(:ph,''), NULLIF(:r,''),
                                  :wage, :rate, :sd, 1, 1)
                        ON DUPLICATE KEY UPDATE
                            name = VALUES(name),
                            email = VALUES(email),
                            phone = VALUES(phone),
                            role_title = VALUES(role_title),
                            wage = VALUES(wage),
                            rate = VALUES(rate),
                            start_date = VALUES(start_date),
                            is_active = 1,
                            terminated_at = NULL,
                            termination_reason = NULL,
                            termination_note = NULL,
                            eligible_for_rehire = 1
                    ")->execute([
                        ':uid'=>$user_id, ':n'=>$full_name, ':e'=>$email, ':ph'=>$phone,
                        ':r'=>$role, ':wage'=>$wage, ':rate'=>$rate, ':sd'=>$start_dt
                    ]);
                    
                    $employee_id = (int)$this->db->lastInsertId();
                    
                    // 3) Assign departments if provided
                    if (!empty($departments) && is_array($departments) && $employee_id > 0) {
                        // First clear existing department assignments
                        $this->db->prepare("DELETE FROM employee_department WHERE employee_id = :eid")
                                 ->execute([':eid' => $employee_id]);
                        
                        // Then insert new assignments
                        $stmt = $this->db->prepare("
                            INSERT INTO employee_department (employee_id, department_id) 
                            VALUES (:eid, :did)
                        ");
                        foreach ($departments as $dept_id) {
                            $dept_id = (int)$dept_id;
                            if ($dept_id > 0) {
                                $stmt->execute([':eid' => $employee_id, ':did' => $dept_id]);
                            }
                        }
                    }

                    echo json_encode([
                        'ok'=>true,
                        'user_id'=>$user_id,
                        'temp_password' => $password   // show once if you want to display it
                    ]);
                    break;

                /* ===== TERMINATE ===== */
                case 'terminate':
                    $this->guardAdmin();
                    $in = $this->json();
                    $user_id   = (int)($in['user_id'] ?? 0);
                    $reason    = trim($in['reason'] ?? '');
                    $note      = trim($in['note'] ?? '');
                    $term_dt   = trim($in['termination_date'] ?? date('Y-m-d'));
                    $rehire_ok = (int)($in['eligible_for_rehire'] ?? 1);
                    if ($user_id <= 0) throw new Exception('user_id required');

                    $this->db->prepare("
                        UPDATE employees
                           SET is_active = 0,
                               terminated_at = :td,
                               termination_reason = NULLIF(:r,''),
                               termination_note   = NULLIF(:n,''),
                               eligible_for_rehire = :ok
                         WHERE user_id = :uid
                    ")->execute([
                        ':td'=>$term_dt, ':r'=>$reason, ':n'=>$note, ':ok'=>$rehire_ok, ':uid'=>$user_id
                    ]);

                    echo json_encode(['ok'=>true]);
                    break;

                /* Rehire/reactivate an employee */
                case 'rehire':
                    $this->guardAdmin();
                    $in = $this->json();
                    $user_id  = (int)($in['user_id'] ?? 0);
                    $start_dt = trim($in['start_date'] ?? date('Y-m-d'));
                    if ($user_id <= 0) throw new Exception('user_id required');

                    $this->db->prepare("
                        UPDATE employees
                           SET is_active = 1,
                               start_date = :sd,
                               terminated_at = NULL,
                               termination_reason = NULL,
                               termination_note   = NULL,
                               eligible_for_rehire = 1
                         WHERE user_id = :uid
                    ")->execute([':sd'=>$start_dt, ':uid'=>$user_id]);

                    echo json_encode(['ok'=>true]);
                    break;

                /* Update quick fields (role, wage, rate, access level) */
                case 'update':
                    $this->guardAdmin();
                    $in = $this->json();
                    $user_id = (int)($in['user_id'] ?? 0);
                    if ($user_id <= 0) throw new Exception('user_id required');

                    // Employees table updates
                    $role = isset($in['role_title']) ? trim($in['role_title']) : null;
                    $wage = isset($in['wage']) ? (float)$in['wage'] : null;
                    $rate = isset($in['rate']) && in_array($in['rate'], ['hourly','salary']) ? $in['rate'] : null;

                    if ($role !== null || $wage !== null || $rate !== null) {
                        $this->db->prepare("
                            UPDATE employees
                               SET role_title = COALESCE(NULLIF(:r,''), role_title),
                                   wage       = COALESCE(:w, wage),
                                   rate       = COALESCE(:rate, rate)
                             WHERE user_id = :uid
                        ")->execute([
                            ':r'=>$role, ':w'=>$wage, ':rate'=>$rate, ':uid'=>$user_id
                        ]);
                    }

                    echo json_encode(['ok'=>true]);
                    break;

                /* Simple search list refresh */
                case 'list':
                    echo json_encode(['roster'=>$this->roster()]);
                    break;

                default:
                    echo json_encode(['error'=>'Unknown action']);
            }
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error'=>$e->getMessage()]);
        }
    }

    /* ===== Helpers ===== */

    private function guardAdmin(): void {
        $accessLevel = class_exists('AccessControl') ? AccessControl::getCurrentUserAccessLevel() : 1;
        if ($accessLevel < 3) {
            throw new Exception('Team Lead access (Level 3+) required');
        }
    }
    
    private function json(): array {
        return json_decode(file_get_contents('php://input'), true) ?: [];
    }

    private function roster(): array {
        // Join users + employees; show active first
        $sql = "
            SELECT 
                u.id            AS user_id,
                COALESCE(NULLIF(u.full_name,''), u.username) AS name,
                u.username,
                e.email,
                e.phone,
                COALESCE(e.role_title, '') AS role_title,
                COALESCE(e.wage, 0) AS wage,
                COALESCE(e.rate, 'hourly') AS rate,
                COALESCE(e.start_date, '') AS start_date,
                COALESCE(e.terminated_at, '') AS terminated_at,
                COALESCE(e.termination_reason, '') AS termination_reason,
                COALESCE(e.termination_note, '')   AS termination_note,
                COALESCE(e.eligible_for_rehire, 1) AS eligible_for_rehire,
                COALESCE(e.is_active, 1) AS is_active,
                COALESCE(u.access_level, 1) AS access_level
            FROM users u
            LEFT JOIN employees e ON e.user_id = u.id
            ORDER BY is_active DESC, name ASC
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    private function roles(): array {
        return $this->db->query("SELECT id, name FROM roles WHERE COALESCE(is_active,1)=1 ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
    }
    private function departments(): array {
        return $this->db->query("SELECT id, name FROM departments WHERE COALESCE(is_active,1)=1 ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

    private function ensureTables(): void {
        // USERS already exists in your app.

        // EMPLOYEES base
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS employees (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT UNIQUE,
                name VARCHAR(150),
                email VARCHAR(250),
                phone VARCHAR(50),
                role_title VARCHAR(100),
                wage DECIMAL(10,2) DEFAULT 0,
                rate ENUM('hourly','salary') DEFAULT 'hourly',
                start_date DATE NULL,
                is_active TINYINT DEFAULT 1,
                terminated_at DATE NULL,
                termination_reason VARCHAR(150) NULL,
                termination_note TEXT NULL,
                eligible_for_rehire TINYINT DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT fk_emp_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        // Safe adds for columns that might not be present in an older schema
        $safeAdds = [
          "ALTER TABLE employees ADD COLUMN IF NOT EXISTS user_id INT UNIQUE",
          "ALTER TABLE employees ADD COLUMN IF NOT EXISTS phone VARCHAR(50)",
          "ALTER TABLE employees ADD COLUMN IF NOT EXISTS wage DECIMAL(10,2) DEFAULT 0",
          "ALTER TABLE employees ADD COLUMN IF NOT EXISTS rate ENUM('hourly','salary') DEFAULT 'hourly'",
          "ALTER TABLE employees ADD COLUMN IF NOT EXISTS start_date DATE NULL",
          "ALTER TABLE employees ADD COLUMN IF NOT EXISTS terminated_at DATE NULL",
          "ALTER TABLE employees ADD COLUMN IF NOT EXISTS termination_reason VARCHAR(150) NULL",
          "ALTER TABLE employees ADD COLUMN IF NOT EXISTS termination_note TEXT NULL",
          "ALTER TABLE employees ADD COLUMN IF NOT EXISTS eligible_for_rehire TINYINT DEFAULT 1",
          "ALTER TABLE employees ADD COLUMN IF NOT EXISTS is_active TINYINT DEFAULT 1",
          "ALTER TABLE employees ADD CONSTRAINT IF NOT EXISTS fk_emp_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE"
        ];
        foreach ($safeAdds as $sql) { try { $this->db->exec($sql); } catch (Throwable $e) {} }
    }
}
