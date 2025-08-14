<?php
// app/controllers/schedule.php

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

    /* --------------------------
       PAGE
    ---------------------------*/
    public function index() {
        if (empty($_SESSION['auth'])) { header('Location: /login'); exit; }
        $this->view('schedule/index');
    }

    /* --------------------------
       API ROUTER
       /schedule/api?a=...
    ---------------------------*/
    public function api() {
        if (empty($_SESSION['auth'])) {
            return $this->jsonSend(['error' => 'Auth required'], 401);
        }

        // Always return JSON and NOTHING else
        header('Content-Type: application/json; charset=utf-8');
        if (ob_get_length()) ob_end_clean();

        $a = $_GET['a'] ?? '';

        try {
            switch ($a) {
                /* ---- Employees ---- */
                case 'employees.list': {
                    $rows = $this->Employee->all();
                    return $this->jsonSend($rows);
                }

                case 'employees.create': {
                    $this->guardAdmin();
                    $in  = $this->jsonInput();
                    $id  = $this->Employee->create(
                        trim($in['name'] ?? ''),
                        $in['email'] ?? null,
                        $in['role']  ?? 'Staff'
                    );
                    return $this->jsonSend(['ok' => true, 'id' => $id]);
                }

                case 'employees.update': {
                    $this->guardAdmin();
                    $in  = $this->jsonInput();
                    $ok  = $this->Employee->update((int)$in['id'], $in);
                    return $this->jsonSend(['ok' => $ok]);
                }

                case 'employees.delete': {
                    $this->guardAdmin();
                    $id = (int)($_GET['id'] ?? 0);
                    $ok = $this->Employee->delete($id);
                    return $this->jsonSend(['ok' => $ok]);
                }

                /* ---- Shifts ---- */
                case 'shifts.week': {
                    $week   = $_GET['week'] ?? date('Y-m-d');
                    $monday = ScheduleWeek::mondayOf($week); // method in your model
                    $rows   = $this->Shift->forWeek($monday);
                    return $this->jsonSend([
                        'week_start' => $monday,
                        'shifts'     => $rows,
                        'is_admin'   => $this->isAdmin()
                    ]);
                }

                case 'shifts.create': {
                    $this->guardAdmin();
                    $in = $this->jsonInput();
                    $id = $this->Shift->create(
                        (int)$in['employee_id'],
                        $in['start_dt'],
                        $in['end_dt'],
                        $in['notes'] ?? null
                    );
                    return $this->jsonSend(['ok' => true, 'id' => $id]);
                }

                case 'shifts.delete': {
                    $this->guardAdmin();
                    $id = (int)($_GET['id'] ?? 0);
                    $ok = $this->Shift->delete($id);
                    return $this->jsonSend(['ok' => $ok]);
                }

                /* ---- Publish ---- */
                case 'publish.status': {
                    $week   = $_GET['week'] ?? date('Y-m-d');
                    $status = $this->Week->status($week); // ['week_start'=>..., 'published'=>0/1]
                    $status['is_admin'] = $this->isAdmin();
                    return $this->jsonSend($status);
                }

                case 'publish.set': {
                    $this->guardAdmin();
                    $in = $this->jsonInput(); // {week, published}
                    $this->Week->setPublished($in['week'], (int)$in['published']);
                    return $this->jsonSend(['ok' => true]);
                }

                /* ---- Users/Admin ---- */
                case 'users.list': {
                    $this->guardAdmin();
                    $rows = $this->model('User')->all();
                    return $this->jsonSend($rows);
                }

                case 'users.setAdmin': {
                    $this->guardAdmin();
                    $in = $this->jsonInput(); // {id, is_admin}
                    $ok = $this->model('User')->setAdmin((int)$in['id'], (int)$in['is_admin']);
                    return $this->jsonSend(['ok' => $ok]);
                }

                default:
                    return $this->jsonSend(['error' => 'Unknown action'], 400);
            }
        } catch (Throwable $e) {
            return $this->jsonSend(['error' => $e->getMessage()], 500);
        }
    }

    /* --------------------------
       Helpers
    ---------------------------*/
    private function guardAdmin() {
        if (!$this->isAdmin()) {
            throw new Exception('Admin access required');
        }
    }

    private function isAdmin(): bool {
        // You set this on login (you already have users.is_admin in DB)
        return isset($_SESSION['is_admin']) && (int)$_SESSION['is_admin'] === 1;
    }

    private function jsonInput(): array {
        $raw = file_get_contents('php://input');
        $arr = json_decode($raw, true);
        return is_array($arr) ? $arr : [];
    }

    private function jsonSend(array $payload, int $status = 200) {
        http_response_code($status);
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
        exit; // CRITICAL to avoid HTML bleed into JSON
    }
}
