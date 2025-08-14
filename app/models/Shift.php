
<?php
class Shift {
    private function db(): PDO { return db_connect(); }

    public function forWeek(string $weekStart): array {
        $start = new DateTime($weekStart.' 00:00:00');
        $end   = (clone $start)->modify('+6 day')->setTime(23,59,59);
        $st = $this->db()->prepare("
          SELECT s.id, s.employee_id, s.start_dt, s.end_dt, s.notes, s.status,
                 e.name AS employee_name, e.role AS employee_role
          FROM shifts s
          JOIN employees e ON e.id = s.employee_id
          WHERE s.start_dt BETWEEN ? AND ?
          ORDER BY s.start_dt
        ");
        $st->execute([$start->format('Y-m-d H:i:s'), $end->format('Y-m-d H:i:s')]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(int $employeeId, string $startDt, string $endDt, ?string $notes): int {
        $st = $this->db()->prepare("INSERT INTO shifts (employee_id, start_dt, end_dt, notes) VALUES (?, ?, ?, ?)");
        $st->execute([$employeeId, $startDt, $endDt, $notes]);
        return (int)$this->db()->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $set = [];
        $values = [];

        if (isset($data['start_dt'])) { $set[] = 'start_dt=?'; $values[] = $data['start_dt']; }
        if (isset($data['end_dt'])) { $set[] = 'end_dt=?'; $values[] = $data['end_dt']; }
        if (isset($data['notes'])) { $set[] = 'notes=?'; $values[] = $data['notes']; }
        if (isset($data['status'])) { $set[] = 'status=?'; $values[] = $data['status']; }

        if (empty($set)) return false;

        $values[] = $id;
        $st = $this->db()->prepare("UPDATE shifts SET " . implode(',', $set) . " WHERE id=?");
        return $st->execute($values);
    }

    public function delete(int $id): bool {
        $st = $this->db()->prepare("DELETE FROM shifts WHERE id=?");
        return $st->execute([$id]);
    }

    public function getByEmployee(int $employeeId, string $date): array {
        $st = $this->db()->prepare("
            SELECT * FROM shifts 
            WHERE employee_id = ? AND DATE(start_dt) = ?
            ORDER BY start_dt
        ");
        $st->execute([$employeeId, $date]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }
}
<?php

class Shift {
    private function db(): PDO {
        return db_connect();
    }

    public function forWeek(string $mondayDate): array {
        $stmt = $this->db()->prepare("
            SELECT s.*, e.name as employee_name, e.role as employee_role
            FROM shifts s
            LEFT JOIN employees e ON s.employee_id = e.id
            WHERE DATE(s.start_dt) >= ? AND DATE(s.start_dt) < DATE_ADD(?, INTERVAL 7 DAY)
            ORDER BY s.start_dt ASC
        ");
        $stmt->execute([$mondayDate, $mondayDate]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(int $employeeId, string $startDt, string $endDt, ?string $notes): int {
        $stmt = $this->db()->prepare("
            INSERT INTO shifts (employee_id, start_dt, end_dt, notes, created_at) 
            VALUES (?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$employeeId, $startDt, $endDt, $notes]);
        return (int)$this->db()->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $fields = [];
        $values = [];
        
        if (isset($data['start_dt'])) {
            $fields[] = 'start_dt = ?';
            $values[] = $data['start_dt'];
        }
        if (isset($data['end_dt'])) {
            $fields[] = 'end_dt = ?';
            $values[] = $data['end_dt'];
        }
        if (isset($data['notes'])) {
            $fields[] = 'notes = ?';
            $values[] = $data['notes'];
        }

        if (empty($fields)) return false;

        $values[] = $id;
        $stmt = $this->db()->prepare("UPDATE shifts SET " . implode(', ', $fields) . " WHERE id = ?");
        return $stmt->execute($values);
    }

    public function delete(int $id): bool {
        $stmt = $this->db()->prepare("DELETE FROM shifts WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function findById(int $id): ?array {
        $stmt = $this->db()->prepare("SELECT * FROM shifts WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
}
