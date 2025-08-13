
<?php

class Shift {
    private function db(): PDO { return db_connect(); }

    public function forWeek(string $weekStart): array {
        $start = new DateTime($weekStart . ' 00:00:00');
        $end = (clone $start)->modify('+6 day')->setTime(23, 59, 59);
        
        $sql = "
            SELECT s.*, 
                   e.name AS employee_name, 
                   e.role_title AS employee_role,
                   e.wage,
                   d.name as department_name
            FROM shifts s
            JOIN employees e ON e.id = s.employee_id
            LEFT JOIN employee_department ed ON e.id = ed.employee_id
            LEFT JOIN departments d ON ed.department_id = d.id
            WHERE s.start_dt BETWEEN ? AND ?
            ORDER BY e.name ASC, s.start_dt ASC
        ";
        
        $st = $this->db()->prepare($sql);
        $st->execute([$start->format('Y-m-d H:i:s'), $end->format('Y-m-d H:i:s')]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(int $employeeId, string $startDt, string $endDt, ?string $notes = null): int {
        if (strtotime($endDt) <= strtotime($startDt)) {
            throw new RuntimeException('End time must be after start time');
        }

        // Check for conflicts
        if ($this->hasConflict($employeeId, $startDt, $endDt)) {
            throw new RuntimeException('Shift conflicts with existing schedule');
        }

        $st = $this->db()->prepare("
            INSERT INTO shifts (employee_id, start_dt, end_dt, notes, status, created_at) 
            VALUES (?, ?, ?, ?, 'scheduled', NOW())
        ");
        $st->execute([$employeeId, $startDt, $endDt, $notes]);
        return (int)$this->db()->lastInsertId();
    }

    public function update(int $id, array $fields): bool {
        $cols = []; $vals = [];
        foreach (['start_dt', 'end_dt', 'notes', 'status'] as $f) {
            if (array_key_exists($f, $fields)) { 
                $cols[] = "$f=?"; 
                $vals[] = $fields[$f]; 
            }
        }
        if (!$cols) return false;
        $vals[] = $id;
        $sql = "UPDATE shifts SET " . implode(',', $cols) . " WHERE id=?";
        return $this->db()->prepare($sql)->execute($vals);
    }

    public function delete(int $id): bool {
        return $this->db()->prepare("DELETE FROM shifts WHERE id=?")->execute([$id]);
    }

    public function hasConflict(int $employeeId, string $startDt, string $endDt, ?int $excludeShiftId = null): bool {
        $sql = "
            SELECT COUNT(*) as count 
            FROM shifts 
            WHERE employee_id = ? 
            AND status != 'cancelled'
            AND (
                (start_dt <= ? AND end_dt > ?) OR
                (start_dt < ? AND end_dt >= ?) OR
                (start_dt >= ? AND end_dt <= ?)
            )
        ";
        
        $params = [$employeeId, $startDt, $startDt, $endDt, $endDt, $startDt, $endDt];
        
        if ($excludeShiftId) {
            $sql .= " AND id != ?";
            $params[] = $excludeShiftId;
        }
        
        $st = $this->db()->prepare($sql);
        $st->execute($params);
        $result = $st->fetch();
        return $result['count'] > 0;
    }

    public function getWeeklySummary(string $weekStart): array {
        $start = new DateTime($weekStart . ' 00:00:00');
        $end = (clone $start)->modify('+6 day')->setTime(23, 59, 59);
        
        $sql = "
            SELECT 
                e.id,
                e.name,
                e.role_title,
                e.wage,
                COUNT(s.id) as shift_count,
                SUM(TIMESTAMPDIFF(MINUTE, s.start_dt, s.end_dt)) as total_minutes
            FROM employees e
            LEFT JOIN shifts s ON e.id = s.employee_id 
                AND s.start_dt BETWEEN ? AND ?
                AND s.status != 'cancelled'
            WHERE e.is_active = 1
            GROUP BY e.id, e.name, e.role_title, e.wage
            ORDER BY e.name
        ";
        
        $st = $this->db()->prepare($sql);
        $st->execute([$start->format('Y-m-d H:i:s'), $end->format('Y-m-d H:i:s')]);
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }
}
