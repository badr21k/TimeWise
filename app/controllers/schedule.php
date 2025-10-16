<?php

class Schedule extends Controller
{
    private $Employee;
    private $Shift;
    private $Week;

    public function __construct() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $this->Employee = $this->model('Employee');
        $this->Shift    = $this->model('Shift');
        $this->Week     = $this->model('ScheduleWeek');
    }

    /** Admin/team schedule grid (existing page) */
    public function index() {
        if (empty($_SESSION['auth'])) { header('Location: /login'); exit; }
        if (class_exists('AccessControl')) {
            AccessControl::enforceAccess('schedule', 'index', 'Schedule');
        }
        $this->view('schedule/index');
    }

    /** “My Shifts” page (personal view) */
    public function my() {
        if (empty($_SESSION['auth'])) { header('Location: /login'); exit; }
        if (class_exists('AccessControl')) {
            AccessControl::enforceAccess('schedule', 'my', 'My Shifts');
        }
        $this->view('schedule/my');
    }

    /** JSON API: /schedule/api?a=... */
    public function api() {
        if (empty($_SESSION['auth'])) {
            http_response_code(401);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => 'Auth required']);
            return;
        }

        header('Content-Type: application/json; charset=utf-8');
        $a = $_GET['a'] ?? '';

        try {
            switch ($a) {

                /* ----------------- Employees ----------------- */
                case 'employees.list':
                    $employees = $this->Employee->all();
                    
                    // Apply department scoping for Level 4 users (Department Admins)
                    if (class_exists('AccessControl')) {
                        $accessLevel = AccessControl::getCurrentUserAccessLevel();
                        if ($accessLevel === 4) {
                            $userDeptIds = AccessControl::getUserDepartmentIds();
                            if (!empty($userDeptIds)) {
                                $db = db_connect();
                                // Filter employees to only those in the user's departments
                                $employees = array_filter($employees, function($emp) use ($userDeptIds, $db) {
                                    // Get departments for this employee
                                    $stmt = $db->prepare("
                                        SELECT department_id 
                                        FROM employee_department 
                                        WHERE employee_id = :emp_id
                                    ");
                                    $stmt->execute([':emp_id' => $emp['id']]);
                                    $empDeptIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
                                    
                                    // Check if employee has any department in common with user
                                    return !empty(array_intersect($empDeptIds, $userDeptIds));
                                });
                                $employees = array_values($employees); // Re-index array
                            } else {
                                $employees = []; // No departments = no employees visible
                            }
                        }
                    }
                    
                    echo json_encode($employees);
                    break;

                case 'employees.create':
                    $this->guardAdmin();
                    $in = $this->json();
                    $id = $this->Employee->create(
                        trim($in['name'] ?? ''),
                        $in['email'] ?? null,
                        $in['role']  ?? 'Staff'
                    );
                    echo json_encode(['ok'=>true,'id'=>$id]);
                    break;

                case 'employees.update':
                    $this->guardAdmin();
                    $in = $this->json();
                    echo json_encode(['ok'=>$this->Employee->update((int)$in['id'], $in)]);
                    break;

                case 'employees.delete':
                    $this->guardAdmin();
                    $id = (int)($_GET['id'] ?? 0);
                    echo json_encode(['ok'=>$this->Employee->delete($id)]);
                    break;

                /* ----------------- Shifts: read ----------------- */
                case 'shifts.week': {
                    $week = $_GET['week'] ?? date('Y-m-d');
                    $w    = ScheduleWeek::mondayOf($week);
                    $rows = $this->Shift->forWeek($w);
                    
                    // Apply department scoping for Level 4 users (Department Admins)
                    if (class_exists('AccessControl')) {
                        $accessLevel = AccessControl::getCurrentUserAccessLevel();
                        if ($accessLevel === 4) {
                            $userDeptIds = AccessControl::getUserDepartmentIds();
                            if (!empty($userDeptIds)) {
                                $db = db_connect();
                                // Filter shifts to only those for employees in the user's departments
                                $rows = array_filter($rows, function($shift) use ($userDeptIds, $db) {
                                    $stmt = $db->prepare("
                                        SELECT department_id 
                                        FROM employee_department 
                                        WHERE employee_id = :emp_id
                                    ");
                                    $stmt->execute([':emp_id' => $shift['employee_id']]);
                                    $empDeptIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
                                    
                                    return !empty(array_intersect($empDeptIds, $userDeptIds));
                                });
                                $rows = array_values($rows);
                            } else {
                                $rows = [];
                            }
                        }
                    }
                    
                    echo json_encode(['week_start'=>$w,'shifts'=>$rows,'is_admin'=>$this->isAdmin()]);
                    break;
                }

                /* Personal shifts for current user */
                case 'shifts.my': {
                    $week = $_GET['week'] ?? date('Y-m-d');
                    $wk   = ScheduleWeek::mondayOf($week);
                    $emp  = $this->resolveEmployeeForCurrentUser();
                    if (!$emp) throw new Exception('No employee record linked to your account.');
                    $rows = $this->Shift->forWeekEmployee($wk, (int)$emp['id']);
                    echo json_encode(['week_start'=>$wk,'employee_id'=>(int)$emp['id'],'shifts'=>$rows]);
                    break;
                }

                /* Map current session user → employee row */
                case 'me.employee': {
                    $emp = $this->resolveEmployeeForCurrentUser();
                    if (!$emp) throw new Exception('No employee record linked to your account.');
                    echo json_encode(['employee_id' => (int)$emp['id'], 'employee_name' => $emp['name']]);
                    break;
                }

                /* ----------------- Shifts: write ----------------- */
                case 'shifts.create':
                    $this->guardAdmin();
                    $in = $this->json();
                    $id = $this->Shift->create(
                        (int)$in['employee_id'],
                        $in['start_dt'],
                        $in['end_dt'],
                        $in['notes'] ?? null
                    );
                    echo json_encode(['ok'=>true,'id'=>$id]);
                    break;

                case 'shifts.delete':
                    $this->guardAdmin();
                    $id = (int)($_GET['id'] ?? 0);
                    echo json_encode(['ok'=>$this->Shift->delete($id)]);
                    break;

                /* Copy entire week → week (like Homebase) */
                case 'shifts.copyWeek': {
                    $this->guardAdmin();
                    $in = $this->json();
                    $src = ScheduleWeek::mondayOf($in['source_week'] ?? date('Y-m-d'));
                    $dst = ScheduleWeek::mondayOf($in['target_week'] ?? date('Y-m-d'));
                    $overwrite = !empty($in['overwrite']);

                    // ensure target week row exists
                    $this->Week->status($dst);

                    if ($overwrite) {
                        $this->Shift->deleteForWeek($dst);
                    }

                    $rows = $this->Shift->forWeek($src);
                    $copied = 0;
                    $srcDate = new DateTime($src);
                    $dstDate = new DateTime($dst);
                    $diffDays = (int)$srcDate->diff($dstDate)->format('%r%a');

                    foreach ($rows as $r) {
                        $s = new DateTime($r['start_dt']); $s->modify(($diffDays>=0?'+':'') . $diffDays . ' day');
                        $e = new DateTime($r['end_dt']);   $e->modify(($diffDays>=0?'+':'') . $diffDays . ' day');
                        $this->Shift->create((int)$r['employee_id'], $s->format('Y-m-d H:i:s'), $e->format('Y-m-d H:i:s'), $r['notes']);
                        $copied++;
                    }
                    echo json_encode(['ok'=>true,'copied'=>$copied, 'source_week'=>$src, 'target_week'=>$dst]);
                    break;
                }

                /* Copy user → user within a week (optionally specific days, optionally overwrite) */
                case 'shifts.copyUserToUser': {
                    $this->guardAdmin();
                    $in = $this->json();
                    $week = ScheduleWeek::mondayOf($in['week'] ?? date('Y-m-d'));
                    $from = (int)$in['from_employee_id'];
                    $to   = (int)$in['to_employee_id'];
                    $days = isset($in['days']) && is_array($in['days']) ? array_values(array_map('intval',$in['days'])) : null;
                    $overwrite = !empty($in['overwrite']);

                    if ($overwrite) {
                        $this->Shift->deleteForWeekEmployee($week, $to, $days);
                    }

                    $rows = $this->Shift->forWeekEmployee($week, $from);
                    $copied = 0;
                    foreach ($rows as $r) {
                        $d = new DateTime($r['start_dt']);
                        $dow = (int)$d->format('w'); // 0=Sun..6=Sat
                        if ($days && !in_array($dow, $days, true)) continue;

                        $duration = (new DateTime($r['end_dt']))->getTimestamp() - $d->getTimestamp();
                        $start = $d->format('Y-m-d H:i:s');
                        $end   = date('Y-m-d H:i:s', $d->getTimestamp() + $duration);
                        $this->Shift->create($to, $start, $end, $r['notes']);
                        $copied++;
                    }
                    echo json_encode(['ok'=>true,'copied'=>$copied]);
                    break;
                }

                /* Copy a single shift to another user/date */
                case 'shifts.copyShift': {
                    $this->guardAdmin();
                    $in = $this->json();
                    $shiftId = (int)$in['shift_id'];
                    $toEmp   = (int)$in['to_employee_id'];
                    $targetDate = $in['target_date'] ?? null; // YYYY-MM-DD

                    $orig = $this->Shift->get($shiftId);
                    if (!$orig) throw new Exception('Shift not found');

                    $s = new DateTime($orig['start_dt']);
                    $e = new DateTime($orig['end_dt']);

                    if ($targetDate) {
                        $s = new DateTime($targetDate . ' ' . $s->format('H:i:s'));
                        $e = new DateTime($targetDate . ' ' . $e->format('H:i:s'));
                    }

                    $newId = $this->Shift->create($toEmp, $s->format('Y-m-d H:i:s'), $e->format('Y-m-d H:i:s'), $orig['notes']);
                    echo json_encode(['ok'=>true,'id'=>$newId]);
                    break;
                }

                /* ----------------- Publishing ----------------- */
                case 'publish.status':
                    $week = $_GET['week'] ?? date('Y-m-d');
                    $status = $this->Week->status($week);
                    $status['is_admin'] = $this->isAdmin();
                    echo json_encode($status);
                    break;

                case 'publish.set':
                    $this->guardAdmin();
                    $in = $this->json();
                    // Respect requested published flag
                    $this->Week->setPublished($in['week'], !empty($in['published']) ? 1 : 0);
                    echo json_encode(['ok'=>true]);
                    break;

                /* ----------------- Roles ----------------- */
                case 'roles.list':
                    echo json_encode($this->getActiveRoles());
                    break;

                /* ----------------- Admin users (optional) ----------------- */
                case 'users.list':
                    $this->guardAdmin();
                    echo json_encode($this->model('User')->all());
                    break;

                case 'users.setAdmin':
                    $this->guardAdmin();
                    $in = $this->json();
                    echo json_encode(['ok'=>$this->model('User')->setAdmin((int)$in['id'], (int)$in['is_admin'])]);
                    break;

                default:
                    echo json_encode(['error'=>'Unknown action']);
            }
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['error'=>$e->getMessage()]);
        }
    }

    /* ================= helpers ================= */

    private function guardAdmin() {
        if (!$this->isAdmin()) throw new Exception('Admin access required');
    }

    private function isAdmin(): bool {
        return isset($_SESSION['is_admin']) && (int)$_SESSION['is_admin'] === 1;
    }

    private function json(): array {
        return json_decode(file_get_contents('php://input'), true) ?: [];
    }

    /** Shared roles fetcher */
    private function getActiveRoles(): array {
        $db = db_connect();
        $stmt = $db->query("SELECT id, name FROM roles WHERE is_active = 1 ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Old direct roles endpoint kept for compatibility */
    public function listRoles() {
        if (empty($_SESSION['auth'])) {
            http_response_code(401);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => 'Auth required']);
            return;
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($this->getActiveRoles());
        exit;
    }

    /** Map the logged-in user to an employees row, using several strategies */
    private function resolveEmployeeForCurrentUser(): ?array {
        $user = $this->currentUserRow();
        if (!$user) return null;

        // 1) direct foreign key (if exists)
        $emp = $this->Employee->findByUserId((int)$user['id']);
        if ($emp) return $emp;

        // 2) email match
        if (!empty($user['email'])) {
            $emp = $this->Employee->findByEmail(trim($user['email']));
            if ($emp) return $emp;
        }

        // 3) name match
        $candidates = [];
        if (!empty($user['full_name'])) $candidates[] = $user['full_name'];
        if (!empty($user['username']))  $candidates[] = $user['username'];
        foreach ($candidates as $n) {
            $emp = $this->Employee->findByName(trim($n));
            if ($emp) return $emp;
        }
        return null;
    }

    /** Safer users lookup: works even if users.email column doesn’t exist */
    private function currentUserRow(): ?array {
        $uid = (int)($_SESSION['id'] ?? 0);
        if (!$uid) return null;
        $db = db_connect();

        // Try selecting email; if the column doesn't exist, fall back without it.
        try {
            $stmt = $db->prepare("SELECT id, username, full_name, email FROM users WHERE id = :id");
            $stmt->execute([':id' => $uid]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Throwable $e) {
            $stmt = $db->prepare("SELECT id, username, full_name FROM users WHERE id = :id");
            $stmt->execute([':id' => $uid]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
            if ($row) $row['email'] = null; // keep a consistent shape
        }

        return $row;
    }
}
