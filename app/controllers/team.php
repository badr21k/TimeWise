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

                case 'bootstrap': // New structure: departments, roles, users
                    $departments = $this->getAllDepartments();
                    $roles = $this->getAllRolesWithDepartment();
                    $users = $this->getAllUsers();
                    
                    echo json_encode([
                        'departments' => $departments,
                        'roles' => $roles,
                        'users' => $users,
                        'access_level' => (int)($_SESSION['access_level'] ?? 1),
                        'user_department_ids' => class_exists('AccessControl') ? AccessControl::getUserDepartmentIds() : []
                    ]);
                    break;
                    
                /* Get roles for a specific department */
                case 'department_roles':
                    $deptId = (int)($_GET['dept_id'] ?? 0);
                    if (!$deptId) {
                        echo json_encode(['roles' => []]);
                    } else {
                        echo json_encode(['roles' => $this->getRolesForDepartment($deptId)]);
                    }
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
                    $department_id = isset($in['department_id']) ? (int)$in['department_id'] : null; // Single department
                    $role_id = isset($in['role_id']) ? (int)$in['role_id'] : null; // Single role
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
                    
                    // 3) Verify department access and assign single department if provided
                    if ($employee_id > 0) {
                        // Level 4 can only assign employees to their own departments
                        $this->guardDepartmentAccess($department_id);
                        
                        // First clear existing department assignments (single department only)
                        $this->db->prepare("DELETE FROM employee_department WHERE employee_id = :eid")
                                 ->execute([':eid' => $employee_id]);
                        
                        // Then insert new assignment (single department only)
                        if ($department_id > 0) {
                            $this->db->prepare("
                                INSERT INTO employee_department (employee_id, department_id) 
                                VALUES (:eid, :did)
                            ")->execute([':eid' => $employee_id, ':did' => $department_id]);
                        }
                    }

                    echo json_encode([
                        'ok'=>true,
                        'user_id'=>$user_id,
                        'employee_id'=>$employee_id,
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
                    
                    // Get employee's current department to verify access
                    $stmt = $this->db->prepare("
                        SELECT ed.department_id 
                        FROM employees e
                        LEFT JOIN employee_department ed ON ed.employee_id = e.id
                        WHERE e.user_id = :uid 
                        LIMIT 1
                    ");
                    $stmt->execute([':uid' => $user_id]);
                    $currentDeptId = $stmt->fetchColumn();
                    
                    // SECURITY: Level 4 must have access to employee's department to terminate them
                    $this->guardDepartmentAccess($currentDeptId ?: null);

                    // Update employee record
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
                    
                    // TERMINATION RULE: Set access_level = 0 to block all access
                    $this->db->prepare("
                        UPDATE users
                           SET access_level = 0
                         WHERE id = :uid
                    ")->execute([':uid'=>$user_id]);

                    echo json_encode(['ok'=>true]);
                    break;

                /* Rehire/reactivate an employee */
                case 'rehire':
                    $this->guardAdmin();
                    $in = $this->json();
                    $user_id  = (int)($in['user_id'] ?? 0);
                    $start_dt = trim($in['start_date'] ?? date('Y-m-d'));
                    $new_access_level = (int)($in['access_level'] ?? 2); // Default to Power User on rehire
                    if ($user_id <= 0) throw new Exception('user_id required');
                    
                    // Get employee's current department to verify access
                    $stmt = $this->db->prepare("
                        SELECT ed.department_id 
                        FROM employees e
                        LEFT JOIN employee_department ed ON ed.employee_id = e.id
                        WHERE e.user_id = :uid 
                        LIMIT 1
                    ");
                    $stmt->execute([':uid' => $user_id]);
                    $currentDeptId = $stmt->fetchColumn();
                    
                    // SECURITY: Level 4 must have access to employee's department to rehire them
                    $this->guardDepartmentAccess($currentDeptId ?: null);

                    // Update employee record
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
                    
                    // REHIRE RULE: Restore access_level (default to 2 - Power User)
                    $this->db->prepare("
                        UPDATE users
                           SET access_level = :al
                         WHERE id = :uid
                    ")->execute([':al'=>$new_access_level, ':uid'=>$user_id]);

                    echo json_encode(['ok'=>true]);
                    break;

                /* Update employee details including department and role */
                case 'update':
                    $this->guardAdmin();
                    $in = $this->json();
                    $user_id = (int)($in['user_id'] ?? 0);
                    if ($user_id <= 0) throw new Exception('user_id required');

                    // Get employee_id and current department
                    $stmt = $this->db->prepare("
                        SELECT e.id, ed.department_id 
                        FROM employees e
                        LEFT JOIN employee_department ed ON ed.employee_id = e.id
                        WHERE e.user_id = :uid 
                        LIMIT 1
                    ");
                    $stmt->execute([':uid' => $user_id]);
                    $empData = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if (!$empData) throw new Exception('Employee not found');
                    
                    $employee_id = (int)$empData['id'];
                    $currentDeptId = $empData['department_id'] ? (int)$empData['department_id'] : null;
                    
                    // SECURITY: Level 4 must have access to employee's current department to update them
                    $this->guardDepartmentAccess($currentDeptId);

                    // 1) Update employee table fields
                    $full_name = isset($in['full_name']) ? trim($in['full_name']) : null;
                    $email = isset($in['email']) ? trim($in['email']) : null;
                    $role = isset($in['role_title']) ? trim($in['role_title']) : null;
                    $wage = isset($in['wage']) ? (float)$in['wage'] : null;
                    $rate = isset($in['rate']) && in_array($in['rate'], ['hourly','salary']) ? $in['rate'] : null;

                    if ($full_name !== null || $email !== null || $role !== null || $wage !== null || $rate !== null) {
                        $this->db->prepare("
                            UPDATE employees
                               SET name = COALESCE(NULLIF(:n,''), name),
                                   email = COALESCE(NULLIF(:e,''), email),
                                   role_title = COALESCE(NULLIF(:r,''), role_title),
                                   wage       = COALESCE(:w, wage),
                                   rate       = COALESCE(:rate, rate)
                             WHERE user_id = :uid
                        ")->execute([
                            ':n'=>$full_name, ':e'=>$email, ':r'=>$role, 
                            ':w'=>$wage, ':rate'=>$rate, ':uid'=>$user_id
                        ]);
                    }
                    
                    // 2) Update user table fields (full_name, access_level)
                    $u_full_name = isset($in['full_name']) ? trim($in['full_name']) : null;
                    $access_level = isset($in['access_level']) ? (int)$in['access_level'] : null;
                    
                    if ($u_full_name !== null || $access_level !== null) {
                        $this->db->prepare("
                            UPDATE users
                               SET full_name = COALESCE(NULLIF(:fn,''), full_name),
                                   access_level = COALESCE(:al, access_level)
                             WHERE id = :uid
                        ")->execute([
                            ':fn'=>$u_full_name, ':al'=>$access_level, ':uid'=>$user_id
                        ]);
                    }
                    
                    // 3) Update department assignment (single department only)
                    if (isset($in['department_id'])) {
                        $department_id = $in['department_id'] ? (int)$in['department_id'] : null;
                        
                        // Level 4 must have access to the NEW department they're assigning
                        $this->guardDepartmentAccess($department_id);
                        
                        // Clear existing assignments
                        $this->db->prepare("DELETE FROM employee_department WHERE employee_id = :eid")
                                 ->execute([':eid' => $employee_id]);
                        
                        // Insert new assignment if provided
                        if ($department_id > 0) {
                            $this->db->prepare("
                                INSERT INTO employee_department (employee_id, department_id)
                                VALUES (:eid, :did)
                            ")->execute([':eid' => $employee_id, ':did' => $department_id]);
                        }
                    }

                    echo json_encode(['ok'=>true]);
                    break;

                /* Change employee's department */
                case 'change_department':
                    $this->guardAdmin();
                    $in = $this->json();
                    $user_id = (int)($in['user_id'] ?? 0);
                    $new_department_id = (int)($in['department_id'] ?? 0);
                    
                    if ($user_id <= 0) throw new Exception('user_id required');
                    if ($new_department_id <= 0) throw new Exception('department_id required');
                    
                    // Get user info
                    $stmt = $this->db->prepare("
                        SELECT id, username, full_name FROM users WHERE id = :uid LIMIT 1
                    ");
                    $stmt->execute([':uid' => $user_id]);
                    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if (!$userData) throw new Exception('User not found');
                    
                    // Get or create employee record
                    $stmt = $this->db->prepare("
                        SELECT id FROM employees WHERE user_id = :uid LIMIT 1
                    ");
                    $stmt->execute([':uid' => $user_id]);
                    $employee_id = $stmt->fetchColumn();
                    
                    // If no employee record exists, create one
                    if (!$employee_id) {
                        $name = $userData['full_name'] ?: $userData['username'];
                        $this->db->prepare("
                            INSERT INTO employees (user_id, name, is_active, eligible_for_rehire, wage, rate, start_date)
                            VALUES (:uid, :name, 1, 1, 0, 'hourly', CURDATE())
                        ")->execute([':uid' => $user_id, ':name' => $name]);
                        $employee_id = (int)$this->db->lastInsertId();
                    }
                    
                    // Verify access to the new department
                    $this->guardDepartmentAccess($new_department_id);
                    
                    // Clear existing department assignments
                    $this->db->prepare("DELETE FROM employee_department WHERE employee_id = :eid")
                             ->execute([':eid' => $employee_id]);
                    
                    // Insert new department assignment
                    $this->db->prepare("
                        INSERT INTO employee_department (employee_id, department_id)
                        VALUES (:eid, :did)
                    ")->execute([':eid' => $employee_id, ':did' => $new_department_id]);
                    
                    echo json_encode(['ok'=>true]);
                    break;

                /* Simple search list refresh */
                case 'list':
                    echo json_encode(['users'=>$this->getAllUsers()]);
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
        $accessLevel = class_exists('AccessControl') ? (int)AccessControl::getCurrentUserAccessLevel() : 1;
        // Allow Level 1 (Full Admin), Level 3 (Team Lead), Level 4 (Department Admin)
        if ($accessLevel !== 1 && $accessLevel !== 3 && $accessLevel !== 4) {
            throw new Exception('Admin access required (Level 1, 3, or 4)');
        }
    }
    
    /**
     * Verify user has access to a specific department
     * Level 1: Full access to all departments
     * Level 3 & 4: Only their assigned departments
     */
    private function guardDepartmentAccess(?int $deptId): void {
        if (!$deptId) {
            throw new Exception('Department ID required');
        }
        
        $accessLevel = class_exists('AccessControl') ? (int)AccessControl::getCurrentUserAccessLevel() : 1;
        
        // Level 1 (Full Admin) has access to all departments
        if ($accessLevel === 1) {
            return;
        }
        
        // Level 3 and 4: Check if department is in their assigned list
        if ($accessLevel === 3 || $accessLevel === 4) {
            $userDeptIds = class_exists('AccessControl') ? AccessControl::getUserDepartmentIds() : [];
            
            if (empty($userDeptIds) || !in_array($deptId, $userDeptIds)) {
                throw new Exception('Access denied to this department');
            }
            return;
        }
        
        throw new Exception('Insufficient access level');
    }
    
    private function json(): array {
        return json_decode(file_get_contents('php://input'), true) ?: [];
    }

    /**
     * Get ALL users for roster display (no filtering by access level)
     * Returns: id, name, email, department_id, role_id, access_level, status
     */
    private function getAllUsers(): array {
        $sql = "
            SELECT 
                u.id,
                COALESCE(NULLIF(u.full_name,''), u.username) AS name,
                u.username,
                COALESCE(e.email, '') AS email,
                COALESCE(e.phone, '') AS phone,
                COALESCE(ed.department_id, 0) AS department_id,
                COALESCE(d.name, '') AS department_name,
                0 AS role_id,
                COALESCE(e.role_title, '') AS role_title,
                COALESCE(u.access_level, 1) AS access_level,
                COALESCE(e.is_active, 1) AS status,
                COALESCE(e.wage, 0) AS wage,
                COALESCE(e.rate, 'hourly') AS rate,
                COALESCE(e.start_date, '') AS start_date,
                COALESCE(e.terminated_at, '') AS terminated_at,
                COALESCE(e.termination_reason, '') AS termination_reason,
                COALESCE(e.eligible_for_rehire, 1) AS eligible_for_rehire
            FROM users u
            LEFT JOIN employees e ON e.user_id = u.id
            LEFT JOIN employee_department ed ON ed.employee_id = e.id
            LEFT JOIN departments d ON d.id = ed.department_id
            ORDER BY 
                COALESCE(d.name, 'ZZZ'),
                COALESCE(e.is_active, 1) DESC,
                u.full_name, 
                u.username
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get ALL departments
     * Returns: id, name, status
     */
    private function getAllDepartments(): array {
        $sql = "
            SELECT 
                id,
                name,
                COALESCE(is_active, 1) AS status
            FROM departments
            ORDER BY name ASC
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get ALL roles with department_id
     * Returns: id, department_id, name
     */
    private function getAllRolesWithDepartment(): array {
        $sql = "
            SELECT 
                r.id,
                dr.department_id,
                r.name,
                COALESCE(r.is_active, 1) AS status
            FROM roles r
            LEFT JOIN department_roles dr ON dr.role_id = r.id
            ORDER BY r.name ASC
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    private function roles(): array {
        return $this->db->query("SELECT id, name FROM roles WHERE COALESCE(is_active,1)=1 ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
    }
    private function departments(): array {
        return $this->db->query("SELECT id, name FROM departments WHERE COALESCE(is_active,1)=1 ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get all departments for dropdown
     */
    private function departmentsAll(): array {
        return $this->db->query("SELECT id, name FROM departments WHERE COALESCE(is_active,1)=1 ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get roles for a specific department
     */
    private function getRolesForDepartment(int $deptId): array {
        $stmt = $this->db->prepare("
            SELECT r.id, r.name
            FROM department_roles dr
            JOIN roles r ON r.id = dr.role_id
            WHERE dr.department_id = :dept_id
              AND COALESCE(r.is_active,1) = 1
            ORDER BY r.name ASC
        ");
        $stmt->execute([':dept_id' => $deptId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
