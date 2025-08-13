<?php

// Handle direct API calls to this file
if (basename($_SERVER['SCRIPT_NAME']) === 'schedule.php' && isset($_GET['a'])) {
    require_once dirname(__DIR__) . '/init.php';
    $schedule = new Schedule();
    $schedule->api();
    exit;
}

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
        if (empty($_SESSION['auth'])) { 
            http_response_code(401); 
            echo json_encode(['error'=>'Auth required']); 
            exit; 
        }
        header('Content-Type: application/json; charset=utf-8');
        
        // Handle direct script access
        if (basename($_SERVER['SCRIPT_NAME']) === 'schedule.php') {
            $a = $_GET['a'] ?? '';
        } else {
            $a = $_GET['a'] ?? '';
        }
        
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
                    $id = (int)($_GET['id'] ?? 0);
                    echo json_encode(['ok'=>$this->Employee->delete($id)]); break;

                // Shifts
                case 'shifts.week':
                    $week = $_GET['week'] ?? date('Y-m-d');
                    $w = ScheduleWeek::mondayOf($week);
                    $rows = $this->Shift->forWeek($w);
                    echo json_encode(['week_start'=>$w,'shifts'=>$rows,'is_admin'=>$this->isAdmin()]); break;

                case 'shifts.create':
                    $this->guardAdmin();
                    $in = $this->json();
                    $id = $this->Shift->create((int)$in['employee_id'], $in['start_dt'], $in['end_dt'], $in['notes'] ?? null);
                    echo json_encode(['ok'=>true,'id'=>$id]); break;

                case 'shifts.delete':
                    $this->guardAdmin();
                    $id = (int)($_GET['id'] ?? 0);
                    echo json_encode(['ok'=>$this->Shift->delete($id)]); break;

                // Publishing
                case 'publish.status':
                    $week = $_GET['week'] ?? date('Y-m-d');
                    $status = $this->Week->status($week);
                    $status['is_admin'] = $this->isAdmin();
                    echo json_encode($status); break;

                case 'publish.set':
                    $this->guardAdmin();
                    $in = $this->json();
                    $this->Week->setPublished($in['week'], $in['published']);
                    echo json_encode(['ok'=>true]); break;

                // Users/Admins
                case 'users.list':
                    $this->guardAdmin();
                    echo json_encode($this->model('User')->all()); break;

                case 'users.setAdmin':
                    $this->guardAdmin();
                    $in = $this->json();
                    echo json_encode(['ok'=>$this->model('User')->setAdmin($in['id'], $in['is_admin'])]); break;

                default:
                    echo json_encode(['error'=>'Unknown action']); break;
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error'=>$e->getMessage()]);
        }
    }

    private function guardAdmin() {
        if (!$this->isAdmin()) throw new Exception('Admin access required');
    }

    private function isAdmin(): bool {
        return !empty($_SESSION['is_admin']);
    }

    private function json(): array {
        return json_decode(file_get_contents('php://input'), true) ?: [];
    }
}