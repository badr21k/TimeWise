<?php
// app/controllers/departments.php
class departments extends Controller
{
    private PDO $db;

    public function __construct() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $this->db = db_connect();
        $this->ensurePivotTables(); // create missing pivots if needed
    }

    /* GET /departments */
    public function index() {
        if (empty($_SESSION['auth'])) { header('Location: /login'); exit; }
        if (class_exists('AccessControl')) {
            AccessControl::enforceAccess('departments', 'index', 'Departments & Roles');
        }
        $this->view('departments/index');
    }

    /* JSON API: /departments/api?a=... */
    public function api() {
        if (empty($_SESSION['auth'])) {
            http_response_code(401);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error'=>'Auth required']);
            return;
        }

        // Centralized RBAC for the whole controller (managers only)
        if (class_exists('AccessControl') && !AccessControl::hasControllerAccess('departments')) {
            http_response_code(403);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error'=>'Access denied']);
            return;
        }

        header('Content-Type: application/json; charset=utf-8');
        $a = $_GET['a'] ?? '';

        try {
            switch ($a) {
                /* ---------- Bootstrap ---------- */
                case 'bootstrap':
                    echo json_encode([
                        'roles'       => $this->listRoles(),
                        'users'       => $this->listUsers(),  // for Managers dropdown
                        'departments' => $this->listDepartmentsWithCounts(),
                        'access_level' => $this->getUserAccessLevel(),
                        'is_view_only' => $this->getUserAccessLevel() === 4,
                    ]);
                    break;

                /* ---------- Departments CRUD ---------- */
                case 'department.create':
                    $this->guardAdmin();
                    $in   = $this->json();
                    $name = trim($in['name'] ?? '');
                    if ($name === '') throw new Exception('Name required');
                    $stmt = $this->db->prepare("INSERT INTO departments (name, is_active) VALUES (:n, 1)");
                    $stmt->execute([':n'=>$name]);
                    echo json_encode(['ok'=>true, 'id'=>(int)$this->db->lastInsertId()]);
                    break;

                case 'department.rename':
                    $this->guardAdmin();
                    $in   = $this->json();
                    $id   = (int)($in['id'] ?? 0);
                    $name = trim($in['name'] ?? '');
                    if (!$id || $name==='') throw new Exception('Invalid rename');
                    $stmt = $this->db->prepare("UPDATE departments SET name=:n WHERE id=:id");
                    $stmt->execute([':n'=>$name, ':id'=>$id]);
                    echo json_encode(['ok'=>true]);
                    break;

                case 'department.delete':
                    $this->guardAdmin();
                    $id = (int)($_GET['id'] ?? 0);
                    if (!$id) throw new Exception('Invalid id');

                    // Grab affected managers first
                    $stmt = $this->db->prepare("SELECT DISTINCT user_id FROM department_managers WHERE department_id = :id");
                    $stmt->execute([':id'=>$id]);
                    $affected = $stmt->fetchAll(PDO::FETCH_COLUMN);

                    // Remove pivots then department
                    $this->db->prepare("DELETE FROM department_roles WHERE department_id=:id")->execute([':id'=>$id]);
                    $this->db->prepare("DELETE FROM department_managers WHERE department_id=:id")->execute([':id'=>$id]);
                    $this->db->prepare("DELETE FROM departments WHERE id=:id")->execute([':id'=>$id]);

                    // Recalc admin flags for everyone that was a manager here
                    foreach ($affected as $uid) {
                        $this->refreshAdminFlag((int)$uid);
                    }

                    echo json_encode(['ok'=>true]);
                    break;

                /* ---------- Roles attach/detach ---------- */
                case 'role.attach':
                    $this->guardAdmin();
                    $in = $this->json();
                    $deptId   = (int)($in['department_id'] ?? 0);
                    if (!$deptId) throw new Exception('department_id required');

                    $roleId   = (int)($in['role_id'] ?? 0);
                    $roleName = trim($in['role_name'] ?? '');

                    if (!$roleId && $roleName === '') throw new Exception('role_id or role_name required');

                    if (!$roleId) {
                        // find or create by name
                        $stmt = $this->db->prepare("SELECT id FROM roles WHERE name=:n LIMIT 1");
                        $stmt->execute([':n'=>$roleName]);
                        $roleId = (int)$stmt->fetchColumn();
                        if (!$roleId) {
                            $ins = $this->db->prepare("INSERT INTO roles (name, is_active) VALUES (:n, 1)");
                            $ins->execute([':n'=>$roleName]);
                            $roleId = (int)$this->db->lastInsertId();
                        }
                    }

                    // link if not already
                    $this->db->prepare("
                        INSERT IGNORE INTO department_roles (department_id, role_id)
                        VALUES (:d,:r)
                    ")->execute([':d'=>$deptId, ':r'=>$roleId]);

                    echo json_encode(['ok'=>true, 'role_id'=>$roleId]);
                    break;

                case 'role.detach':
                    $this->guardAdmin();
                    $in = $this->json();
                    $deptId = (int)($in['department_id'] ?? 0);
                    $roleId = (int)($in['role_id'] ?? 0);
                    if (!$deptId || !$roleId) throw new Exception('department_id and role_id required');
                    $this->db->prepare("DELETE FROM department_roles WHERE department_id=:d AND role_id=:r")
                             ->execute([':d'=>$deptId, ':r'=>$roleId]);
                    echo json_encode(['ok'=>true]);
                    break;

                /* ---------- Managers (users) ---------- */
                case 'manager.add':
                    $this->guardAdmin();
                    $in = $this->json();
                    $deptId = (int)($in['department_id'] ?? 0);
                    $userId = (int)($in['user_id'] ?? 0);
                    if (!$deptId || !$userId) throw new Exception('department_id and user_id required');

                    // Link user to department as manager
                    $this->db->prepare("
                        INSERT IGNORE INTO department_managers (department_id, user_id)
                        VALUES (:d,:u)
                    ")->execute([':d'=>$deptId, ':u'=>$userId]);

                    // Ensure admin flag is correct after change
                    $this->refreshAdminFlag($userId);

                    echo json_encode(['ok'=>true]);
                    break;

                case 'manager.remove':
                    $this->guardAdmin();
                    $in = $this->json();
                    $deptId = (int)($in['department_id'] ?? 0);
                    $userId = (int)($in['user_id'] ?? 0);
                    if (!$deptId || !$userId) throw new Exception('department_id and user_id required');

                    // Unlink manager
                    $this->db->prepare("
                        DELETE FROM department_managers
                        WHERE department_id=:d AND user_id=:u
                    ")->execute([':d'=>$deptId, ':u'=>$userId]);

                    // Flip admin off if they no longer manage any departments
                    $this->refreshAdminFlag($userId);

                    echo json_encode(['ok'=>true]);
                    break;

                /* ---------- Combined fetch for one department ---------- */
                case 'departments.roles.managers':
                    $id = (int)($_GET['id'] ?? 0);
                    if (!$id) throw new Exception('id required');

                    $roles    = $this->fetchRolesForDepartment($id);
                    $managers = $this->fetchManagersForDepartment($id);
                    echo json_encode(['roles'=>$roles, 'managers'=>$managers]);
                    break;

                default:
                    echo json_encode(['error'=>'Unknown action']);
            }
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error'=>$e->getMessage()]);
        }
    }

    /* ===== helpers ===== */

    private function guardAdmin() {
        // Level 4 users can view but not modify
        // Only admins (legacy) or higher access levels can modify
        if (!isset($_SESSION['is_admin']) || (int)$_SESSION['is_admin'] !== 1) {
            throw new Exception('Admin access required for modifications');
        }
    }
    
    private function getUserAccessLevel(): int {
        if (class_exists('AccessControl')) {
            return AccessControl::getCurrentUserAccessLevel();
        }
        return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] ? 4 : 1;
    }

    private function json(): array {
        return json_decode(file_get_contents('php://input'), true) ?: [];
    }

    private function listRoles(): array {
        $sql = "SELECT id, name FROM roles WHERE COALESCE(is_active,1)=1 ORDER BY name ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    private function listUsers(): array {
        // Use username if full_name is null
        $sql = "SELECT id,
                       COALESCE(NULLIF(full_name,''), username) AS label
                FROM users ORDER BY label ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    private function listDepartmentsWithCounts(): array {
        $accessLevel = $this->getUserAccessLevel();
        
        // Level 4 users only see departments they're assigned to
        if ($accessLevel === 4 && class_exists('AccessControl')) {
            $userDeptIds = AccessControl::getUserDepartmentIds();
            
            if (empty($userDeptIds)) {
                return []; // No departments assigned
            }
            
            $placeholders = implode(',', array_fill(0, count($userDeptIds), '?'));
            $sql = "
                SELECT d.id, d.name, 
                       COUNT(dr.role_id) AS role_count
                FROM departments d
                LEFT JOIN department_roles dr ON dr.department_id = d.id
                WHERE d.id IN ($placeholders)
                GROUP BY d.id, d.name
                ORDER BY d.name ASC
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($userDeptIds);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        
        // For all other users, show all departments
        $sql = "
            SELECT d.id, d.name, 
                   COUNT(dr.role_id) AS role_count
            FROM departments d
            LEFT JOIN department_roles dr ON dr.department_id = d.id
            GROUP BY d.id, d.name
            ORDER BY d.name ASC
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    private function fetchRolesForDepartment(int $deptId): array {
        $stmt = $this->db->prepare("
            SELECT r.id, r.name
            FROM department_roles dr
            JOIN roles r ON r.id = dr.role_id
            WHERE dr.department_id = :d
            ORDER BY r.name ASC
        ");
        $stmt->execute([':d'=>$deptId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function fetchManagersForDepartment(int $deptId): array {
        $stmt = $this->db->prepare("
            SELECT u.id, COALESCE(NULLIF(u.full_name,''), u.username) AS label
            FROM department_managers dm
            JOIN users u ON u.id = dm.user_id
            WHERE dm.department_id = :d
            ORDER BY label ASC
        ");
        $stmt->execute([':d'=>$deptId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function ensurePivotTables(): void {
        // department_roles (department_id, role_id)
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS department_roles (
                department_id INT NOT NULL,
                role_id INT NOT NULL,
                PRIMARY KEY (department_id, role_id),
                CONSTRAINT fk_dr_dept FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE,
                CONSTRAINT fk_dr_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
            )
        ");

        // department_managers (department_id, user_id)
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS department_managers (
                department_id INT NOT NULL,
                user_id INT NOT NULL,
                PRIMARY KEY (department_id, user_id),
                CONSTRAINT fk_dm_dept FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE,
                CONSTRAINT fk_dm_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ");
    }

    /** Count whether user manages any departments */
    private function userManagerCount(int $userId): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM department_managers WHERE user_id = :u");
        $stmt->execute([':u'=>$userId]);
        return (int)$stmt->fetchColumn();
    }

    /** Flip users.is_admin based on whether they manage >= 1 department and sync session if needed */
    private function refreshAdminFlag(int $userId): void {
        $isAdmin = $this->userManagerCount($userId) > 0 ? 1 : 0;
        $this->db->prepare("UPDATE users SET is_admin = :a WHERE id = :u")
                 ->execute([':a'=>$isAdmin, ':u'=>$userId]);

        if (!empty($_SESSION['id']) && (int)$_SESSION['id'] === $userId) {
            $_SESSION['is_admin'] = $isAdmin;
        }
    }
}
