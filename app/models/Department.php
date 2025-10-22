<?php

class Department
{
    private PDO $db;

    public function __construct()
    {
        $this->db = db_connect();
        $this->ensureAuxTables();
    }

    /** Create helper tables if they don't exist */
    private function ensureAuxTables(): void
    {
        // roles
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS roles (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(120) UNIQUE,
                is_active TINYINT DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        // departments
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS departments (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(150) UNIQUE,
                is_active TINYINT DEFAULT 1,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        // department_roles (many-to-many)
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS department_roles (
                department_id INT NOT NULL,
                role_id INT NOT NULL,
                PRIMARY KEY (department_id, role_id),
                CONSTRAINT fk_dr_dept FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE,
                CONSTRAINT fk_dr_role FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        // department_managers (users as managers)
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS department_managers (
                department_id INT NOT NULL,
                user_id INT NOT NULL,
                PRIMARY KEY (department_id, user_id),
                CONSTRAINT fk_dm_dept FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");
    }

    /* ===== Read ===== */

    public function listWithCounts(): array
    {
        $sql = "
            SELECT d.id, d.name,
                   COALESCE(dr.role_count, 0) AS role_count
            FROM departments d
            LEFT JOIN (
                SELECT department_id, COUNT(*) AS role_count
                FROM department_roles
                GROUP BY department_id
            ) dr ON dr.department_id = d.id
            WHERE d.is_active = 1
            ORDER BY d.name ASC
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function rolesFor(int $deptId): array
    {
        $stmt = $this->db->prepare("
            SELECT r.id, r.name
            FROM department_roles dr
            JOIN roles r ON r.id = dr.role_id
            WHERE dr.department_id = :id
            ORDER BY r.name ASC
        ");
        $stmt->execute([':id'=>$deptId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function managersFor(int $deptId): array
    {
        // Users table: id, username, full_name, is_admin, ...
        $stmt = $this->db->prepare("
            SELECT u.id, COALESCE(NULLIF(TRIM(u.full_name),''), u.username) AS label
            FROM department_managers dm
            JOIN users u ON u.id = dm.user_id
            WHERE dm.department_id = :id
            ORDER BY label ASC
        ");
        $stmt->execute([':id'=>$deptId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function allRoles(): array
    {
        return $this->db->query("SELECT id, name FROM roles WHERE is_active = 1 ORDER BY name ASC")
                        ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function allUsersMin(): array
    {
        $sql = "SELECT id, COALESCE(NULLIF(TRIM(full_name),''), username) AS label FROM users ORDER BY label ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ===== Departments ===== */

    public function create(string $name): int
    {
        if ($name === '') throw new Exception('Department name required');
        $stmt = $this->db->prepare("INSERT INTO departments (name, is_active) VALUES (:n, 1)");
        $stmt->execute([':n'=>$name]);
        return (int)$this->db->lastInsertId();
    }

    public function rename(int $id, string $name): bool
    {
        if ($id <= 0 || $name === '') return false;
        $stmt = $this->db->prepare("UPDATE departments SET name = :n WHERE id = :id");
        return $stmt->execute([':n'=>$name, ':id'=>$id]);
    }

    public function delete(int $id): bool
    {
        if ($id <= 0) return false;
        $stmt = $this->db->prepare("DELETE FROM departments WHERE id = :id");
        return $stmt->execute([':id'=>$id]);
    }

    /* ===== Roles in Dept ===== */

    public function ensureRole(string $name, int $deptId = 0): int
    {
        if ($name === '') throw new Exception('Role name required');
        if ($deptId <= 0) throw new Exception('Department ID required');
        
        $sel = $this->db->prepare("SELECT id FROM roles WHERE department_id = :d AND name = :n");
        $sel->execute([':d'=>$deptId, ':n'=>$name]);
        $id = (int)$sel->fetchColumn();
        if ($id) return $id;

        $ins = $this->db->prepare("INSERT INTO roles (department_id, name, is_active) VALUES (:d, :n, 1)");
        $ins->execute([':d'=>$deptId, ':n'=>$name]);
        return (int)$this->db->lastInsertId();
    }

    public function attachRole(int $deptId, int $roleId): bool
    {
        if ($deptId <= 0 || $roleId <= 0) return false;
        $stmt = $this->db->prepare("
            INSERT IGNORE INTO department_roles (department_id, role_id)
            VALUES (:d, :r)
        ");
        return $stmt->execute([':d'=>$deptId, ':r'=>$roleId]);
    }

    public function detachRole(int $deptId, int $roleId): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM department_roles
            WHERE department_id = :d AND role_id = :r
        ");
        return $stmt->execute([':d'=>$deptId, ':r'=>$roleId]);
    }

    /* ===== Managers ===== */

    public function addManager(int $deptId, int $userId): bool
    {
        if ($deptId <= 0 || $userId <= 0) return false;
        $stmt = $this->db->prepare("
            INSERT IGNORE INTO department_managers (department_id, user_id)
            VALUES (:d, :u)
        ");
        return $stmt->execute([':d'=>$deptId, ':u'=>$userId]);
    }

    public function removeManager(int $deptId, int $userId): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM department_managers
            WHERE department_id = :d AND user_id = :u
        ");
        return $stmt->execute([':d'=>$deptId, ':u'=>$userId]);
    }
}
