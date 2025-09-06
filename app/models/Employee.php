<?php
class Employee
{
    /* ===== existing CRUD you already had ===== */

    public function all(): array
    {
        $db = db_connect();
        // employees(id, name, email, role_title, is_active, created_at)
        $sql = "
            SELECT 
                id,
                name,
                email,
                role_title,
                is_active,
                created_at
            FROM employees
            ORDER BY name ASC
        ";
        return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(string $name, ?string $email, string $roleTitle = 'Staff'): int
    {
        $db = db_connect();
        $stmt = $db->prepare("
            INSERT INTO employees (name, email, role_title, is_active)
            VALUES (:name, :email, :role_title, 1)
        ");
        $stmt->execute([
            ':name'       => $name,
            ':email'      => $email,
            ':role_title' => $roleTitle
        ]);
        return (int)$db->lastInsertId();
    }

    public function update(int $id, array $in): bool
    {
        $db   = db_connect();
        $sets = [];
        $args = [':id' => $id];

        if (isset($in['name']))       { $sets[] = 'name = :name';               $args[':name']       = $in['name']; }
        if (isset($in['email']))      { $sets[] = 'email = :email';             $args[':email']      = $in['email']; }
        if (isset($in['role']) || isset($in['role_title'])) {
            $role = $in['role_title'] ?? $in['role'];
            $sets[] = 'role_title = :role_title';  $args[':role_title'] = $role;
        }
        if (isset($in['is_active']))  { $sets[] = 'is_active = :is_active';     $args[':is_active']  = (int)$in['is_active']; }

        if (!$sets) return true;

        $sql = "UPDATE employees SET ".implode(', ', $sets)." WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute($args);
    }

    public function delete(int $id): bool
    {
        $db = db_connect();
        $stmt = $db->prepare("DELETE FROM employees WHERE id = :id");
        return $stmt->execute([':id'=>$id]);
    }

    /* ===== lightweight lookup helpers for “My Shifts” mapping =====
       Only one of these needs to succeed for resolveEmployeeForCurrentUser() */
    public function findByUserId(int $userId): ?array {
        $db = db_connect();
        // This works if your employees table has a user_id column. If not, query returns no rows.
        $stmt = $db->prepare("SELECT * FROM employees WHERE user_id = :uid LIMIT 1");
        try {
            $stmt->execute([':uid'=>$userId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (Throwable $e) {
            // Column may not exist; ignore and fallback to other strategies
            return null;
        }
    }

    public function findByEmail(string $email): ?array {
        if ($email === '') return null;
        $db = db_connect();
        $stmt = $db->prepare("SELECT * FROM employees WHERE LOWER(email) = LOWER(:e) LIMIT 1");
        $stmt->execute([':e'=>$email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function findByName(string $name): ?array {
        if ($name === '') return null;
        $db = db_connect();
        $stmt = $db->prepare("SELECT * FROM employees WHERE TRIM(LOWER(name)) = TRIM(LOWER(:n)) LIMIT 1");
        $stmt->execute([':n'=>$name]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}
