<?php
class Employee {
    private function db(): PDO { return db_connect(); }

    public function all(): array {
        return $this->db()->query("SELECT * FROM employees ORDER BY is_active DESC, name ASC")
            ->fetchAll(PDO::FETCH_ASSOC);
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