<?php

class Employee {
    private function db(): PDO { return db_connect(); }

    public function all(): array {
        return $this->db()->query("SELECT * FROM employees ORDER BY is_active DESC, name ASC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(string $name, ?string $email, string $role): int {
        $st = $this->db()->prepare("INSERT INTO employees (name,email,role) VALUES (?,?,?)");
        $st->execute([$name, $email, $role]);
        return (int)$this->db()->lastInsertId();
    }

    public function update(int $id, array $fields): bool {
        $cols=[]; $vals=[];
        foreach (['name','email','role','is_active'] as $f) {
            if (array_key_exists($f,$fields)) { $cols[]="$f=?"; $vals[]=$fields[$f]; }
        }
        if (!$cols) return false;
        $vals[]=$id;
        $sql="UPDATE employees SET ".implode(',', $cols)." WHERE id=?";
        return $this->db()->prepare($sql)->execute($vals);
    }

    public function delete(int $id): bool {
        return $this->db()->prepare("DELETE FROM employees WHERE id=?")->execute([$id]);
    }
}
