
<?php

class Employee {
    private function db(): PDO { return db_connect(); }

    public function all(): array {
        $sql = "
            SELECT e.*, 
                   d.name as department_name,
                   ed.is_manager,
                   dr.role_id,
                   r.name as role_name
            FROM employees e
            LEFT JOIN employee_department ed ON e.id = ed.employee_id
            LEFT JOIN departments d ON ed.department_id = d.id
            LEFT JOIN department_roles dr ON d.id = dr.department_id
            LEFT JOIN roles r ON dr.role_id = r.id
            ORDER BY e.is_active DESC, e.name ASC
        ";
        return $this->db()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(string $name, ?string $email, string $role, ?string $department = null, ?float $wage = null): int {
        $st = $this->db()->prepare("
            INSERT INTO employees (name, email, role_title, wage, is_active, created_at) 
            VALUES (?, ?, ?, ?, 1, NOW())
        ");
        $st->execute([$name, $email, $role, $wage]);
        $employeeId = (int)$this->db()->lastInsertId();

        // If department is provided, link employee to department
        if ($department) {
            $this->assignToDepartment($employeeId, $department);
        }

        return $employeeId;
    }

    public function update(int $id, array $fields): bool {
        $cols = []; $vals = [];
        foreach (['name', 'email', 'role_title', 'wage', 'is_active'] as $f) {
            if (array_key_exists($f, $fields)) { 
                $cols[] = "$f=?"; 
                $vals[] = $fields[$f]; 
            }
        }
        if (!$cols) return false;
        $vals[] = $id;
        $sql = "UPDATE employees SET " . implode(',', $cols) . " WHERE id=?";
        return $this->db()->prepare($sql)->execute($vals);
    }

    public function delete(int $id): bool {
        // First remove from employee_department
        $this->db()->prepare("DELETE FROM employee_department WHERE employee_id=?")->execute([$id]);
        // Then remove shifts
        $this->db()->prepare("DELETE FROM shifts WHERE employee_id=?")->execute([$id]);
        // Finally remove employee
        return $this->db()->prepare("DELETE FROM employees WHERE id=?")->execute([$id]);
    }

    public function assignToDepartment(int $employeeId, string $departmentName, bool $isManager = false): void {
        // First find or create department
        $st = $this->db()->prepare("SELECT id FROM departments WHERE name = ?");
        $st->execute([$departmentName]);
        $dept = $st->fetch();
        
        if (!$dept) {
            $st = $this->db()->prepare("INSERT INTO departments (name, is_active, created_at) VALUES (?, 1, NOW())");
            $st->execute([$departmentName]);
            $deptId = $this->db()->lastInsertId();
        } else {
            $deptId = $dept['id'];
        }

        // Remove existing department assignments
        $this->db()->prepare("DELETE FROM employee_department WHERE employee_id=?")->execute([$employeeId]);
        
        // Add new assignment
        $st = $this->db()->prepare("INSERT INTO employee_department (employee_id, department_id, is_manager) VALUES (?, ?, ?)");
        $st->execute([$employeeId, $deptId, $isManager ? 1 : 0]);
    }

    public function getByDepartment(string $departmentName): array {
        $sql = "
            SELECT e.*, ed.is_manager, d.name as department_name
            FROM employees e
            JOIN employee_department ed ON e.id = ed.employee_id
            JOIN departments d ON ed.department_id = d.id
            WHERE d.name = ? AND e.is_active = 1
            ORDER BY ed.is_manager DESC, e.name ASC
        ";
        $st = $this->db()->prepare($sql);
        $st->execute([$departmentName]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }
}
