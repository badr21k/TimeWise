<?php
class Employee {
    private function db(): PDO { return db_connect(); }

    public function all(): array {
        $st = $this->db()->prepare("SELECT id, name, email, role, wage, is_active FROM employees WHERE is_active = 1 ORDER BY name");
        $st->execute();
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
<?php

class Employee {
    private function db(): PDO {
        return db_connect();
    }

    public function all(): array {
        $stmt = $this->db()->prepare("
            SELECT id, name, email, role as role_title, 
                   COALESCE(is_active, 1) as is_active,
                   wage, role_title as role
            FROM employees 
            ORDER BY name ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(string $name, ?string $email, string $role): int {
        $stmt = $this->db()->prepare("
            INSERT INTO employees (name, email, role, role_title, is_active, created_at) 
            VALUES (?, ?, ?, ?, 1, NOW())
        ");
        $stmt->execute([$name, $email, $role, $role]);
        return (int)$this->db()->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $fields = [];
        $values = [];
        
        if (isset($data['name'])) {
            $fields[] = 'name = ?';
            $values[] = $data['name'];
        }
        if (isset($data['email'])) {
            $fields[] = 'email = ?';
            $values[] = $data['email'];
        }
        if (isset($data['role'])) {
            $fields[] = 'role = ?';
            $fields[] = 'role_title = ?';
            $values[] = $data['role'];
            $values[] = $data['role'];
        }
        if (isset($data['is_active'])) {
            $fields[] = 'is_active = ?';
            $values[] = $data['is_active'];
        }
        if (isset($data['wage'])) {
            $fields[] = 'wage = ?';
            $values[] = $data['wage'];
        }

        if (empty($fields)) return false;

        $values[] = $id;
        $stmt = $this->db()->prepare("UPDATE employees SET " . implode(', ', $fields) . " WHERE id = ?");
        return $stmt->execute($values);
    }

    public function delete(int $id): bool {
        $stmt = $this->db()->prepare("DELETE FROM employees WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function findById(int $id): ?array {
        $stmt = $this->db()->prepare("SELECT * FROM employees WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
}
