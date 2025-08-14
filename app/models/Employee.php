<?php
class Employee {
    private function db(): PDO { return db_connect(); }

    public function all(): array {
        $stmt = $this->db()->prepare("
            SELECT e.*, d.name as department_name, d.name as role_title
            FROM employees e 
            LEFT JOIN departments d ON e.department_id = d.id 
            WHERE e.is_active = 1
            ORDER BY e.name
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create(string $name, ?string $email, string $role): int {
        $st = $this->db()->prepare("INSERT INTO employees (name,email,role) VALUES (?,?,?)");
        $st->execute([$name, $email, $role]);
        return (int)$this->db()->lastInsertId();
    }
    public function update(int $id, array $data): bool {
        $set = [];
        $values = [];

        if (isset($data['name'])) { $set[] = 'name=?'; $values[] = $data['name']; }
        if (isset($data['email'])) { $set[] = 'email=?'; $values[] = $data['email']; }
        if (isset($data['role'])) { $set[] = 'role=?'; $values[] = $data['role']; }
        if (isset($data['is_active'])) { $set[] = 'is_active=?'; $values[] = $data['is_active']; }

        if (empty($set)) return false;

        $values[] = $id;
        $st = $this->db()->prepare("UPDATE employees SET " . implode(',', $set) . " WHERE id=?");
        return $st->execute($values);
    }

    public function delete(int $id): bool {
        $st = $this->db()->prepare("DELETE FROM employees WHERE id=?");
        return $st->execute([$id]);
    }
}