<?php
class Employee {
    private function db(): PDO { return db_connect(); }

    public function all(): array {
        $st = $this->db()->query("
            SELECT id, name, email, role, wage, hire_date, 
                   COALESCE(is_active, 1) as is_active 
            FROM employees 
            ORDER BY name
        ");
        return $st->fetchAll(PDO::FETCH_ASSOC);
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