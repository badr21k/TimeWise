<?php

class Schedule extends Controller
{
    private PDO $db;

    public function __construct() {
        parent::__construct();
        $this->requireAuth();
        $this->db = self::pdo();
    }

    // Single UI page with tabs
    public function index() {
        $this->render('schedule/index', ['title' => 'Team & Schedule']);
    }

    // JSON API
    public function api() {
        header('Content-Type: application/json; charset=utf-8');
        $a = $_GET['a'] ?? '';
        try {
            switch ($a) {
                case 'me':
                    echo json_encode(['is_admin' => $this->isAdminLive() ? 1 : 0]); break;

                // EMPLOYEES
                case 'employees.list':
                    $st = $this->db->query("SELECT id,name,email,role_title AS role,is_active,created_at FROM employees ORDER BY is_active DESC, name ASC");
                    echo json_encode($st->fetchAll(PDO::FETCH_ASSOC)); break;

                case 'employees.create':
                    $this->requireAdminLive();
                    $in = $this->json();
                    $st = $this->db->prepare("INSERT INTO employees(name,email,role_title,is_active) VALUES (?,?,?,1)");
                    $st->execute([trim($in['name']), $in['email'] ?? null, $in['role'] ?? 'Staff']);
                    echo json_encode(['ok'=>true,'id'=>(int)$this->db->lastInsertId()]); break;

                case 'employees.update':
                    $this->requireAdminLive();
                    $in = $this->json();
                    $fields=[]; $vals=[];
                    foreach (['name','email','role_title','is_active'] as $f) if (array_key_exists($f,$in)) { $fields[]="$f=?"; $vals[]=$in[$f]; }
                    if (!$fields) { echo json_encode(['ok'=>false]); break; }
                    $vals[]=(int)$in['id'];
                    $sql="UPDATE employees SET ".implode(',',$fields)." WHERE id=?";
                    $this->db->prepare($sql)->execute($vals);
                    echo json_encode(['ok'=>true]); break;

                case 'employees.delete':
                    $this->requireAdminLive();
                    $id=(int)($_GET['id'] ?? 0);
                    $this->db->prepare("DELETE FROM employees WHERE id=?")->execute([$id]);
                    echo json_encode(['ok'=>true]); break;

                // DEPARTMENTS
                case 'departments.list':
                    $q = $this->db->query("SELECT id,name,is_active FROM departments ORDER BY name");
                    echo json_encode($q->fetchAll(PDO::FETCH_ASSOC)); break;

                case 'departments.create':
                    $this->requireAdminLive();
                    $in = $this->json();
                    $this->db->prepare("INSERT INTO departments(name) VALUES (?)")->execute([trim($in['name'])]);
                    echo json_encode(['ok'=>true,'id'=>(int)$this->db->lastInsertId()]); break;

                case 'departments.update':
                    $this->requireAdminLive();
                    $in = $this->json();
                    $this->db->prepare("UPDATE departments SET name=?, is_active=? WHERE id=?")->execute([trim($in['name']), (int)$in['is_active'], (int)$in['id']]);
                    echo json_encode(['ok'=>true]); break;

                case 'departments.delete':
                    $this->requireAdminLive();
                    $this->db->prepare("DELETE FROM departments WHERE id=?")->execute([(int)$_GET['id']]);
                    echo json_encode(['ok'=>true]); break;

                // ROLES
                case 'roles.list':
                    $q = $this->db->query("SELECT id,name,is_active FROM roles ORDER BY name");
                    echo json_encode($q->fetchAll(PDO::FETCH_ASSOC)); break;

                case 'roles.create':
                    $this->requireAdminLive();
                    $in=$this->json();
                    $this->db->prepare("INSERT INTO roles(name) VALUES (?)")->execute([trim($in['name'])]);
                    echo json_encode(['ok'=>true,'id'=>(int)$this->db->lastInsertId()]); break;

                case 'roles.delete':
                    $this->requireAdminLive();
                    $this->db->prepare("DELETE FROM roles WHERE id=?")->execute([(int)$_GET['id']]);
                    echo json_encode(['ok'=>true]); break;

                // DEPARTMENT <-> ROLE mapping
                case 'dept.roles':
                    $dept=(int)($_GET['dept'] ?? 0);
                    $st=$this->db->prepare("SELECT r.id,r.name FROM department_roles dr JOIN roles r ON r.id=dr.role_id WHERE dr.department_id=? ORDER BY r.name");
                    $st->execute([$dept]);
                    echo json_encode($st->fetchAll(PDO::FETCH_ASSOC)); break;

                case 'dept.roles.add':
                    $this->requireAdminLive();
                    $in=$this->json();
                    $this->db->prepare("REPLACE INTO department_roles(department_id,role_id) VALUES(?,?)")->execute([(int)$in['department_id'], (int)$in['role_id']]);
                    echo json_encode(['ok'=>true]); break;

                case 'dept.roles.remove':
                    $this->requireAdminLive();
                    $in=$this->json();
                    $this->db->prepare("DELETE FROM department_roles WHERE department_id=? AND role_id=?")->execute([(int)$in['department_id'], (int)$in['role_id']]);
                    echo json_encode(['ok'=>true]); break;

                // EMPLOYEE <-> DEPARTMENT mapping
                case 'emp.depts':
                    $emp=(int)($_GET['emp'] ?? 0);
                    $st=$this->db->prepare("SELECT d.id,d.name,ed.is_manager FROM employee_department ed JOIN departments d ON d.id=ed.department_id WHERE ed.employee_id=? ORDER BY d.name");
                    $st->execute([$emp]);
                    echo json_encode($st->fetchAll(PDO::FETCH_ASSOC)); break;

                case 'emp.dept.add':
                    $this->requireAdminLive();
                    $in=$this->json();
                    $this->db->prepare("REPLACE INTO employee_department(employee_id,department_id,is_manager) VALUES(?,?,?)")->execute([(int)$in['employee_id'], (int)$in['department_id'], (int)!!($in['is_manager']??0)]);
                    echo json_encode(['ok'=>true]); break;

                case 'emp.dept.remove':
                    $this->requireAdminLive();
                    $in=$this->json();
                    $this->db->prepare("DELETE FROM employee_department WHERE employee_id=? AND department_id=?")->execute([(int)$in['employee_id'], (int)$in['department_id']]);
                    echo json_encode(['ok'=>true]); break;

                // SHIFTS (week)
                case 'shifts.week':
                    $week = $this->mondayOf($_GET['week'] ?? date('Y-m-d'));
                    $start = $week.' 00:00:00';
                    $end   = date('Y-m-d 23:59:59', strtotime($week.' +6 day'));
                    $st = $this->db->prepare("
                        SELECT s.*, e.name AS employee_name, e.role_title AS employee_role
                        FROM shifts s JOIN employees e ON e.id=s.employee_id
                        WHERE s.start_dt BETWEEN ? AND ?
                        ORDER BY e.name ASC, s.start_dt ASC
                    ");
                    $st->execute([$start,$end]);
                    echo json_encode(['week_start'=>$week, 'shifts'=>$st->fetchAll(PDO::FETCH_ASSOC), 'is_admin'=>$this->isAdminLive()?1:0]); break;

                case 'shifts.create':
                    $this->requireAdminLive();
                    $in=$this->json();
                    if (strtotime($in['end_dt']) <= strtotime($in['start_dt'])) throw new RuntimeException('End must be after start');
                    $this->db->prepare("INSERT INTO shifts(employee_id,start_dt,end_dt,notes,status) VALUES (?,?,?,?, 'Scheduled')")
                             ->execute([(int)$in['employee_id'],$in['start_dt'],$in['end_dt'],$in['notes'] ?? null]);
                    echo json_encode(['ok'=>true,'id'=>(int)$this->db->lastInsertId()]); break;

                case 'shifts.delete':
                    $this->requireAdminLive();
                    $this->db->prepare("DELETE FROM shifts WHERE id=?")->execute([(int)$_GET['id']]);
                    echo json_encode(['ok'=>true]); break;

                // PUBLISH
                case 'publish.status':
                    $w=$this->mondayOf($_GET['week'] ?? date('Y-m-d'));
                    $st=$this->db->prepare("SELECT week_start,published,updated_at FROM schedules WHERE week_start=?");
                    $st->execute([$w]);
                    $row=$st->fetch(PDO::FETCH_ASSOC);
                    echo json_encode(['week_start'=>$w,'published'=>(int)($row['published'] ?? 0),'updated_at'=>$row['updated_at'] ?? null,'is_admin'=>$this->isAdminLive()?1:0]); break;

                case 'publish.set':
                    $this->requireAdminLive();
                    $in=$this->json();
                    $w=$this->mondayOf($in['week']);
                    $this->db->prepare("INSERT INTO schedules(week_start,published) VALUES(?,?) ON DUPLICATE KEY UPDATE published=VALUES(published), updated_at=NOW()")
                             ->execute([$w, (int)!!$in['published']]);
                    echo json_encode(['ok'=>true]); break;

                // USERS (admin toggle)
                case 'users.list':
                    $this->requireAdminLive();
                    $q=$this->db->query("SELECT id,username,full_name,is_admin FROM users ORDER BY username");
                    echo json_encode($q->fetchAll(PDO::FETCH_ASSOC)); break;

                case 'users.setAdmin':
                    $this->requireAdminLive();
                    $in=$this->json();
                    $this->db->prepare("UPDATE users SET is_admin=? WHERE id=?")->execute([(int)!!$in['is_admin'], (int)$in['id']]);
                    echo json_encode(['ok'=>true]); break;

                default:
                    http_response_code(404); echo json_encode(['error'=>'Unknown action']);
            }
        } catch (Throwable $e) {
            http_response_code(422); echo json_encode(['error'=>$e->getMessage()]);
        }
    }

    // === helpers ===
    private function json(): array {
        $raw = file_get_contents('php://input') ?: '';
        $d = json_decode($raw, true);
        return is_array($d) ? $d : [];
    }

    private static function pdo(): PDO {
        $dsn = "mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_DATABASE.";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    }

    private function mondayOf(string $date): string {
        $d = new DateTime($date);
        $dow = (int)$d->format('N') - 1; // Mon=0
        if ($dow>0) $d->modify("-{$dow} day");
        return $d->format('Y-m-d');
    }

    // Live admin check (works without changing your login code)
    private function isAdminLive(): bool {
        if (!isset($_SESSION['auth']['username'])) return false;
        $st = $this->db->prepare("SELECT is_admin FROM users WHERE username=? LIMIT 1");
        $st->execute([$_SESSION['auth']['username']]);
        return (int)($st->fetchColumn() ?: 0) === 1;
    }
    private function requireAdminLive(): void {
        if (!$this->isAdminLive()) { http_response_code(403); echo json_encode(['error'=>'Admin only']); exit; }
    }
}
