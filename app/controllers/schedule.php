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

    public function index() {
        if (empty($_SESSION['auth'])) { header('Location: /login'); exit; }
        $this->view('schedule/index');
    }

    public function api() {
        if (empty($_SESSION['auth'])) { http_response_code(401); echo json_encode(['error'=>'Auth required']); return; }
        header('Content-Type: application/json; charset=utf-8');
        $a = $_GET['a'] ?? '';
        try {
            switch ($a) {
                // Employees
                case 'employees.list':
                    echo json_encode($this->Employee->all()); break;

                case 'employees.create':
                    $this->guardAdmin();
                    $in = $this->json();
                    $id = $this->Employee->create(trim($in['name']), $in['email'] ?? null, $in['role'] ?? 'Staff');
                    echo json_encode(['ok'=>true,'id'=>$id]); break;

                case 'employees.update':
                    $this->guardAdmin();
                    $in = $this->json();
                    echo json_encode(['ok'=>$this->Employee->update((int)$in['id'], $in)]); break;

                case 'employees.delete':
                    $this->guardAdmin();
                    echo json_encode(['ok'=>$this->Employee->delete((int)$_GET['id'])]); break;

                // Shifts
                case 'shifts.week':
                    $week = $_GET['week'] ?? date('Y-m-d');
                    $w = ScheduleWeek::mondayOf($week);
                    $rows = $this->Shift->forWeek($w);
                    echo json_encode(['week_start'=>$w,'shifts'=>$rows,'is_admin'=>$this->isAdmin()?1:0]); break;

                case 'shifts.create':
                    $this->guardAdmin();
                    $in = $this->json();
                    $id = $this->Shift->create((int)$in['employee_id'], $in['start_dt'], $in['end_dt'], $in['notes'] ?? null);
                    echo json_encode(['ok'=>true,'id'=>$id]); break;

                case 'shifts.delete':
                    $this->guardAdmin();
                    echo json_encode(['ok'=>$this->Shift->delete((int)$_GET['id'])]); break;

                // Publish controls
                case 'publish.status':
                    $week = $_GET['week'] ?? date('Y-m-d');
                    echo json_encode($this->Week->status($week) + ['is_admin'=>$this->isAdmin()?1:0]); break;

                case 'publish.set':
                    $this->guardAdmin();
                    $in = $this->json();
                    $this->Week->setPublished($in['week'], !!$in['published']);
                    echo json_encode(['ok'=>true]); break;

                // Admin management
                case 'users.list':
                    $this->guardAdmin();
                    $st = db_connect()->query("SELECT id,username,full_name,is_admin FROM users ORDER BY username");
                    echo json_encode($st->fetchAll(PDO::FETCH_ASSOC)); break;

                case 'users.setAdmin':
                    $this->guardAdmin();
                    $in = $this->json();
                    $st = db_connect()->prepare("UPDATE users SET is_admin=? WHERE id=?");
                    $st->execute([(int)!!$in['is_admin'], (int)$in['id']]);
                    echo json_encode(['ok'=>true]); break;

                default:
                    http_response_code(404); echo json_encode(['error'=>'Unknown action']);
            }
        } catch (Throwable $e) {
            http_response_code(422); echo json_encode(['error'=>$e->getMessage()]);
        }
    }

    private function json(): array { $raw=file_get_contents('php://input')?:''; $d=json_decode($raw,true); return is_array($d)?$d:[]; }
    private function isAdmin(): bool { return !empty($_SESSION['is_admin']); }
    private function guardAdmin(): void { if (!$this->isAdmin()) { http_response_code(403); echo json_encode(['error'=>'Admin only']); exit; } }
}
