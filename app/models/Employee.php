
<?php

class Employee {
    private $db;
    
    public function __construct() {
        $this->db = db_connect();
        $this->createTable();
    }
    
    private function createTable() {
        try {
            $this->db->exec("
                CREATE TABLE IF NOT EXISTS employees (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(100) NOT NULL,
                    email VARCHAR(100) UNIQUE,
                    phone VARCHAR(20),
                    role VARCHAR(100),
                    role_title VARCHAR(100),
                    wage DECIMAL(10,2),
                    is_active TINYINT(1) DEFAULT 1,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )
            ");
        } catch (Exception $e) {
            error_log("Error creating employees table: " . $e->getMessage());
        }
    }
    
    public function getAll() {
        try {
            $stmt = $this->db->query("SELECT * FROM employees WHERE is_active = 1 ORDER BY name");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting employees: " . $e->getMessage());
            return [];
        }
    }
    
    public function getById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM employees WHERE id = ? AND is_active = 1");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting employee by ID: " . $e->getMessage());
            return null;
        }
    }
    
    public function create($data) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO employees (name, email, phone, role, role_title, wage, is_active) 
                VALUES (?, ?, ?, ?, ?, ?, 1)
            ");
            $result = $stmt->execute([
                $data['name'] ?? '',
                $data['email'] ?? null,
                $data['phone'] ?? null,
                $data['role'] ?? 'Staff',
                $data['role_title'] ?? null,
                $data['wage'] ?? null
            ]);
            
            if ($result) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (Exception $e) {
            error_log("Error creating employee: " . $e->getMessage());
            return false;
        }
    }
    
    public function update($id, $data) {
        try {
            $stmt = $this->db->prepare("
                UPDATE employees 
                SET name = ?, email = ?, phone = ?, role = ?, role_title = ?, wage = ?, updated_at = NOW()
                WHERE id = ? AND is_active = 1
            ");
            return $stmt->execute([
                $data['name'] ?? '',
                $data['email'] ?? null,
                $data['phone'] ?? null,
                $data['role'] ?? 'Staff',
                $data['role_title'] ?? null,
                $data['wage'] ?? null,
                $id
            ]);
        } catch (Exception $e) {
            error_log("Error updating employee: " . $e->getMessage());
            return false;
        }
    }
    
    public function delete($id) {
        try {
            $stmt = $this->db->prepare("UPDATE employees SET is_active = 0 WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (Exception $e) {
            error_log("Error deleting employee: " . $e->getMessage());
            return false;
        }
    }
}
