
<?php

class Department {
    private function db(): PDO { return db_connect(); }

    public function all(): array {
        $sql = "
            SELECT d.*, 
                   COUNT(ed.employee_id) as employee_count,
                   GROUP_CONCAT(DISTINCT r.name) as roles
            FROM departments d
            LEFT JOIN employee_department ed ON d.id = ed.department_id
            LEFT JOIN department_roles dr ON d.id = dr.department_id
            LEFT JOIN roles r ON dr.role_id = r.id
            WHERE d.is_active = 1
            GROUP BY d.id, d.name, d.is_active, d.created_at
            ORDER BY d.name
        ";
        return $this->db()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(string $name, array $roles = []): int {
        $st = $this->db()->prepare("INSERT INTO departments (name, is_active, created_at) VALUES (?, 1, NOW())");
        $st->execute([$name]);
        $deptId = (int)$this->db()->lastInsertId();

        // Add roles if provided
        foreach ($roles as $roleName) {
            $this->addRole($deptId, $roleName);
        }

        return $deptId;
    }

    public function addRole(int $departmentId, string $roleName): void {
        // First find or create role
        $st = $this->db()->prepare("SELECT id FROM roles WHERE name = ?");
        $st->execute([$roleName]);
        $role = $st->fetch();
        
        if (!$role) {
            $st = $this->db()->prepare("INSERT INTO roles (name) VALUES (?)");
            $st->execute([$roleName]);
            $roleId = $this->db()->lastInsertId();
        } else {
            $roleId = $role['id'];
        }

        // Link role to department
        $st = $this->db()->prepare("INSERT IGNORE INTO department_roles (department_id, role_id) VALUES (?, ?)");
        $st->execute([$departmentId, $roleId]);
    }

    public function getRoles(): array {
        return $this->db()->query("SELECT * FROM roles ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete(int $id): bool {
        // Remove role associations
        $this->db()->prepare("DELETE FROM department_roles WHERE department_id=?")->execute([$id]);
        // Remove employee associations
        $this->db()->prepare("DELETE FROM employee_department WHERE department_id=?")->execute([$id]);
        // Mark department as inactive
        return $this->db()->prepare("UPDATE departments SET is_active=0 WHERE id=?")->execute([$id]);
    }
}
