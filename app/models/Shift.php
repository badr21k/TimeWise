
<?php

class Shift {
    private $db;
    
    public function __construct() {
        $this->db = db_connect();
        $this->createTable();
    }
    
    private function createTable() {
        try {
            $this->db->exec("
                CREATE TABLE IF NOT EXISTS shifts (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    employee_id INT NOT NULL,
                    shift_date DATE NOT NULL,
                    start_time TIME,
                    end_time TIME,
                    status VARCHAR(20) DEFAULT 'scheduled',
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
                    INDEX idx_employee_date (employee_id, shift_date)
                )
            ");
        } catch (Exception $e) {
            error_log("Error creating shifts table: " . $e->getMessage());
        }
    }
    
    public function getWeekShifts($weekStart) {
        try {
            $weekEnd = date('Y-m-d', strtotime($weekStart . ' +6 days'));
            $stmt = $this->db->prepare("
                SELECT s.*, e.name as employee_name 
                FROM shifts s 
                JOIN employees e ON s.employee_id = e.id 
                WHERE s.shift_date BETWEEN ? AND ?
                ORDER BY s.shift_date, s.start_time
            ");
            $stmt->execute([$weekStart, $weekEnd]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting week shifts: " . $e->getMessage());
            return [];
        }
    }
    
    public function create($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO shifts (employee_id, shift_date, start_time, end_time, status) 
                VALUES (?, ?, ?, ?, ?)
            ");
            return $stmt->execute([
                $data['employee_id'],
                $data['shift_date'],
                $data['start_time'] ?? null,
                $data['end_time'] ?? null,
                $data['status'] ?? 'scheduled'
            ]);
        } catch (Exception $e) {
            error_log("Error creating shift: " . $e->getMessage());
            return false;
        }
    }
    
    public function update($id, $data) {
        try {
            $stmt = $this->db->prepare("
                UPDATE shifts 
                SET employee_id = ?, shift_date = ?, start_time = ?, end_time = ?, status = ?
                WHERE id = ?
            ");
            return $stmt->execute([
                $data['employee_id'],
                $data['shift_date'],
                $data['start_time'] ?? null,
                $data['end_time'] ?? null,
                $data['status'] ?? 'scheduled',
                $id
            ]);
        } catch (Exception $e) {
            error_log("Error updating shift: " . $e->getMessage());
            return false;
        }
    }
    
    public function delete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM shifts WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            error_log("Error deleting shift: " . $e->getMessage());
            return false;
        }
    }
}
