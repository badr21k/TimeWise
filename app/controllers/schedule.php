
<?php

class Schedule extends Controller {

    public function index($params = []) {
        // Handle API requests first
        if (isset($_GET['a'])) {
            $this->handleAPI();
            return;
        }

        // Regular page view
        $this->view('schedule/index', []);
    }

    private function handleAPI() {
        // Set JSON header
        header('Content-Type: application/json');
        
        $action = $_GET['a'] ?? '';
        
        try {
            switch ($action) {
                case 'employees.list':
                    $this->apiEmployeesList();
                    break;
                    
                case 'shifts.week':
                    $this->apiShiftsWeek();
                    break;
                    
                case 'shifts.create':
                    $this->apiShiftsCreate();
                    break;
                    
                case 'shifts.delete':
                    $this->apiShiftsDelete();
                    break;
                    
                case 'publish.status':
                    $this->apiPublishStatus();
                    break;
                    
                case 'publish.set':
                    $this->apiPublishSet();
                    break;
                    
                default:
                    http_response_code(404);
                    echo json_encode(['error' => 'API endpoint not found']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    private function apiEmployeesList() {
        $employeeModel = $this->model('Employee');
        $employees = $employeeModel->all();
        
        // Check admin status
        $isAdmin = isset($_SESSION['is_admin']) && (int)$_SESSION['is_admin'] === 1;
        
        echo json_encode([
            'employees' => $employees,
            'isAdmin' => $isAdmin
        ]);
    }

    private function apiShiftsWeek() {
        $week = $_GET['week'] ?? date('Y-m-d');
        $shiftModel = $this->model('Shift');
        $shifts = $shiftModel->forWeek($week);
        
        echo json_encode(['shifts' => $shifts]);
    }

    private function apiShiftsCreate() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['employee_id'], $input['start_dt'], $input['end_dt'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }
        
        $shiftModel = $this->model('Shift');
        $id = $shiftModel->create(
            (int)$input['employee_id'],
            $input['start_dt'],
            $input['end_dt'],
            $input['notes'] ?? null
        );
        
        echo json_encode(['id' => $id, 'success' => true]);
    }

    private function apiShiftsDelete() {
        $id = $_GET['id'] ?? null;
        
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing shift ID']);
            return;
        }
        
        $shiftModel = $this->model('Shift');
        $success = $shiftModel->delete((int)$id);
        
        echo json_encode(['success' => $success]);
    }

    private function apiPublishStatus() {
        $week = $_GET['week'] ?? date('Y-m-d');
        $scheduleWeekModel = $this->model('ScheduleWeek');
        $status = $scheduleWeekModel->getPublishStatus($week);
        
        echo json_encode(['published' => $status]);
    }

    private function apiPublishSet() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }
        
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['week'], $input['published'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            return;
        }
        
        $scheduleWeekModel = $this->model('ScheduleWeek');
        $success = $scheduleWeekModel->setPublishStatus($input['week'], (bool)$input['published']);
        
        echo json_encode(['success' => $success]);
    }
}
