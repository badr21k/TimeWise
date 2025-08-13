<?php

class Schedule extends Controller
{
    private $Employee;
    private $Shift;
    private $Week;
    private $Department;

    public function __construct() {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $this->Employee = $this->model('Employee');
        $this->Shift = $this->model('Shift');
        $this->Week = $this->model('ScheduleWeek');
        $this->Department = $this->model('Department');
    }

    private function isAdmin(): bool {
        // Check if 'auth' key exists and if 'is_admin' within it is true (or 1)
        return isset($_SESSION['auth']['is_admin']) && $_SESSION['auth']['is_admin'] == 1;
    }

    private function guardAdmin(): void {
        // This method checks if the user is authenticated and if they are an admin.
        // If not authenticated, it throws an 'Authentication required.' error.
        // If authenticated but not an admin, it throws 'Only admins can modify admin privileges.'
        if (!isset($_SESSION['auth']) || !$_SESSION['auth']) {
            throw new Exception('Authentication required.');
        }
        if (!isset($_SESSION['auth']['is_admin']) || (int)$_SESSION['auth']['is_admin'] !== 1) {
            throw new Exception('Only admins can modify admin privileges.');
        }
    }

    public function index() {
        if (empty($_SESSION['auth'])) {
            header('Location: /login');
            exit;
        }
        $this->view('schedule/index');
    }

    public function api() {
        // Ensure we're authenticated
        if (!isset($_SESSION['auth']) || !$_SESSION['auth']) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized']);
            exit;
        }

        // Set JSON header
        header('Content-Type: application/json');

        $action = $_GET['a'] ?? '';

        try {
            switch ($action) {
                // Employee Management
                case 'employees.list':
                    $this->apiEmployeesList();
                    break;

                case 'employees.create':
                    $this->apiEmployeesCreate();
                    break;

                case 'employees.update':
                    $this->apiEmployeesUpdate();
                    break;

                case 'employees.delete':
                    $this->apiEmployeesDelete();
                    break;

                case 'employees.assign_department':
                    $this->guardAdmin();
                    $data = $this->json();
                    $this->Employee->assignToDepartment(
                        (int)$data['employee_id'],
                        $data['department'],
                        $data['is_manager'] ?? false
                    );
                    echo json_encode(['success' => true]);
                    break;

                // Shift Management
                case 'shifts.week':
                    $this->apiShiftsWeek();
                    break;

                case 'shifts.create':
                    $this->apiShiftsCreate();
                    break;

                case 'shifts.update':
                    $this->apiShiftsUpdate();
                    break;

                case 'shifts.delete':
                    $this->apiShiftsDelete();
                    break;

                case 'shifts.check_conflict':
                    $data = $this->json();
                    $hasConflict = $this->Shift->hasConflict(
                        (int)$data['employee_id'],
                        $data['start_dt'],
                        $data['end_dt'],
                        $data['exclude_id'] ?? null
                    );
                    echo json_encode(['has_conflict' => $hasConflict]);
                    break;

                // Department Management
                case 'departments.list':
                    echo json_encode($this->Department->all());
                    break;

                case 'departments.create':
                    $this->guardAdmin();
                    $data = $this->json();
                    $id = $this->Department->create(
                        $data['name'],
                        $data['roles'] ?? []
                    );
                    echo json_encode(['success' => true, 'id' => $id]);
                    break;

                case 'departments.delete':
                    $this->guardAdmin();
                    $success = $this->Department->delete((int)$_GET['id']);
                    echo json_encode(['success' => $success]);
                    break;

                case 'roles.list':
                    echo json_encode($this->Department->getRoles());
                    break;

                // Schedule Publishing
                case 'publish.status':
                    $week = $_GET['week'] ?? date('Y-m-d');
                    $status = $this->Week->status($week);
                    $status['is_admin'] = $this->isAdmin() ? 1 : 0;
                    echo json_encode($status);
                    break;

                case 'publish.set':
                    $this->guardAdmin();
                    $data = $this->json();
                    $this->Week->setPublished($data['week'], (bool)$data['published']);
                    echo json_encode(['success' => true]);
                    break;

                case 'publish.history':
                    echo json_encode($this->Week->getPublishedWeeks());
                    break;

                // User Management
                case 'users.list':
                    $this->guardAdmin();
                    $st = db_connect()->query("SELECT id, username, full_name, is_admin FROM users ORDER BY username");
                    echo json_encode($st->fetchAll(PDO::FETCH_ASSOC));
                    break;

                case 'users.setAdmin':
                    $this->guardAdmin();
                    $data = $this->json();
                    $st = db_connect()->prepare("UPDATE users SET is_admin = ? WHERE id = ?");
                    $st->execute([(int)!!$data['is_admin'], (int)$data['id']]);
                    echo json_encode(['success' => true]);
                    break;

                default:
                    http_response_code(404);
                    echo json_encode(['error' => 'Unknown action: ' . $action]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
        }
        exit;
    }

    private function apiEmployeesList() {
        try {
            $employees = $this->Employee->all();
            echo json_encode($employees ?: []);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to load employees']);
        }
    }

    private function apiEmployeesCreate() {
        $this->guardAdmin();
        try {
            $data = $this->json();

            // Validate required fields
            if (empty(trim($data['name'] ?? ''))) {
                throw new Exception('Name is required');
            }

            $id = $this->Employee->create(
                trim($data['name']),
                !empty($data['email']) ? trim($data['email']) : null,
                !empty($data['role']) ? trim($data['role']) : 'Staff',
                !empty($data['department']) ? trim($data['department']) : null,
                !empty($data['wage']) ? (float)$data['wage'] : null
            );
            echo json_encode(['success' => true, 'id' => $id]);
        } catch (Exception $e) {
            http_response_code(422);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function apiEmployeesUpdate() {
        $this->guardAdmin();
        $data = $this->json();
        $success = $this->Employee->update((int)$data['id'], $data);
        echo json_encode(['success' => $success]);
    }

    private function apiEmployeesDelete() {
        $this->guardAdmin();
        $success = $this->Employee->delete((int)$_GET['id']);
        echo json_encode(['success' => $success]);
    }

    private function apiShiftsWeek() {
        try {
            $week = $_GET['week'] ?? date('Y-m-d');
            $weekStart = ScheduleWeek::mondayOf($week);
            $shifts = $this->Shift->forWeek($weekStart);
            $summary = $this->Shift->getWeeklySummary($weekStart);
            echo json_encode([
                'week_start' => $weekStart,
                'shifts' => $shifts,
                'summary' => $summary,
                'is_admin' => $this->isAdmin() ? 1 : 0
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to load shifts']);
        }
    }

    private function apiShiftsCreate() {
        $this->guardAdmin();
        try {
            $data = $this->json();
            $id = $this->Shift->create(
                (int)$data['employee_id'],
                $data['start_dt'],
                $data['end_dt'],
                $data['notes'] ?? null
            );
            echo json_encode(['success' => true, 'id' => $id]);
        } catch (Exception $e) {
            http_response_code(422);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function apiShiftsUpdate() {
        $this->guardAdmin();
        $data = $this->json();
        $success = $this->Shift->update((int)$data['id'], $data);
        echo json_encode(['success' => $success]);
    }

    private function apiShiftsDelete() {
        $this->guardAdmin();
        $success = $this->Shift->delete((int)$_GET['id']);
        echo json_encode(['success' => $success]);
    }

    private function apiSchedulePublish() {
        $this->guardAdmin();
        $data = $this->json();
        $this->Week->setPublished($data['week'], (bool)$data['published']);
        echo json_encode(['success' => true]);
    }

    private function json(): array {
        $raw = file_get_contents('php://input') ?: '';
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }
}