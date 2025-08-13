<?php

class Shift {
    private function db(): PDO { return db_connect(); }

    public function forWeek(string $weekStart): array {
        $start = new DateTime($weekStart.' 00:00:00');
        $end   = (clone $start)->modify('+6 day')->setTime(23,59,59);
        $st = $this->db()->prepare("
          SELECT s.*, e.name AS employee_name, e.role AS employee_role
          FROM shifts s
          JOIN employees e ON e.id = s.employee_id
          WHERE s.start_dt BETWEEN ? AND ?
          ORDER BY e.name ASC, s.start_dt ASC
        ");
        $st->execute([$start->format('Y-m-d H:i:s'), $end->format('Y-m-d H:i:s')]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(int $employeeId, string $startDt, string $endDt, ?string $notes): int {
        if (strtotime($endDt) <= strtotime($startDt)) {
            throw new RuntimeException('End must be after start');
        }
        $st = $this->db()->prepare("INSERT INTO shifts (employee_id,start_dt,end_dt,notes) VALUES (?,?,?,?)");
        $st->execute([$employeeId,$startDt,$endDt,$notes]);
        return (int)$this->db()->lastInsertId();
    }

    public function delete(int $id): bool {
        return $this->db()->prepare("DELETE FROM shifts WHERE id=?")->execute([$id]);
    }
}
