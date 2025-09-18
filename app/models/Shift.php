<?php
class Shift
{
    /** Return all shifts within week window, joined with employee info. */
    public function forWeek(string $mondayYmd): array
    {
        $db = db_connect();
        $monday = new DateTime($mondayYmd);
        $sunday = clone $monday; $sunday->modify('+6 day');

        $stmt = $db->prepare("
            SELECT 
                s.id,
                s.employee_id,
                s.start_dt,
                s.end_dt,
                s.notes,
                s.status,
                e.name           AS employee_name,
                e.role_title     AS employee_role
            FROM shifts s
            JOIN employees e ON e.id = s.employee_id
            WHERE DATE(s.start_dt) BETWEEN :monday AND :sunday
            ORDER BY e.name ASC, s.start_dt ASC
        ");
        $stmt->execute([
            ':monday' => $monday->format('Y-m-d'),
            ':sunday' => $sunday->format('Y-m-d')
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function forWeekEmployee(string $mondayYmd, int $employeeId): array
    {
        $db = db_connect();
        $monday = new DateTime($mondayYmd);
        $sunday = clone $monday; $sunday->modify('+6 day');

        $stmt = $db->prepare("
            SELECT id, employee_id, start_dt, end_dt, notes, status
            FROM shifts
            WHERE employee_id = :emp
              AND DATE(start_dt) BETWEEN :monday AND :sunday
            ORDER BY start_dt ASC
        ");
        $stmt->execute([
            ':emp' => $employeeId,
            ':monday' => $monday->format('Y-m-d'),
            ':sunday' => $sunday->format('Y-m-d')
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get(int $id): ?array
    {
        $db = db_connect();
        $stmt = $db->prepare("SELECT * FROM shifts WHERE id = :id");
        $stmt->execute([':id'=>$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function create(int $employeeId, string $startDt, string $endDt, ?string $notes): int
    {
        $db = db_connect();
        $stmt = $db->prepare("
            INSERT INTO shifts (employee_id, start_dt, end_dt, notes, status)
            VALUES (:emp, :start_dt, :end_dt, :notes, 'Scheduled')
        ");
        $stmt->execute([
            ':emp'      => $employeeId,
            ':start_dt' => $startDt,
            ':end_dt'   => $endDt,
            ':notes'    => $notes
        ]);
        return (int)$db->lastInsertId();
    }

    public function delete(int $id): bool
    {
        $db = db_connect();
        $stmt = $db->prepare("DELETE FROM shifts WHERE id = :id");
        return $stmt->execute([':id'=>$id]);
    }

    public function deleteForWeek(string $mondayYmd): int
    {
        $db = db_connect();
        $monday = new DateTime($mondayYmd);
        $sunday = clone $monday; $sunday->modify('+6 day');
        $stmt = $db->prepare("DELETE FROM shifts WHERE DATE(start_dt) BETWEEN :m AND :s");
        $stmt->execute([':m'=>$monday->format('Y-m-d'), ':s'=>$sunday->format('Y-m-d')]);
        return $stmt->rowCount();
    }

    public function deleteForWeekEmployee(string $mondayYmd, int $employeeId, ?array $days = null): int
    {
        $db = db_connect();
        $monday = new DateTime($mondayYmd);
        $sunday = clone $monday; $sunday->modify('+6 day');

        // Optional day filtering
        $extra = '';
        $params = [
            ':emp' => $employeeId,
            ':m' => $monday->format('Y-m-d'),
            ':s' => $sunday->format('Y-m-d')
        ];
        if ($days && count($days)) {
            // Build list of target dates inside week for those days
            $dates = [];
            foreach ($days as $dow) {
                $d = clone $monday; $d->modify(($dow===0?'+6 day':'+' . ($dow-1) . ' day'));
                $dates[] = $d->format('Y-m-d');
            }
            $in = implode(',', array_fill(0, count($dates), '?'));
            $extra = " AND DATE(start_dt) IN ($in)";
            $params = array_merge([$employeeId, $monday->format('Y-m-d'), $sunday->format('Y-m-d')], $dates);
            $sql = "DELETE FROM shifts WHERE employee_id = ? AND DATE(start_dt) BETWEEN ? AND ? AND DATE(start_dt) IN ($in)";
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        }

        $stmt = $db->prepare("DELETE FROM shifts WHERE employee_id = :emp AND DATE(start_dt) BETWEEN :m AND :s");
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    /**
     * Aggregate total hours per employee for a given week (Monday..Sunday)
     * Returns: [ ['employee_id'=>.., 'employee_name'=>.., 'total_hours'=>float], ... ]
     */
    public function hoursForWeekGrouped(string $mondayYmd): array
    {
        $db = db_connect();
        $monday = new DateTime($mondayYmd);
        $sunday = clone $monday; $sunday->modify('+6 day');

        $sql = "
            SELECT 
                e.id AS employee_id,
                COALESCE(NULLIF(e.name,''), CONCAT('Employee #', e.id)) AS employee_name,
                ROUND(SUM(TIMESTAMPDIFF(MINUTE, s.start_dt, s.end_dt)) / 60, 2) AS total_hours
            FROM shifts s
            JOIN employees e ON e.id = s.employee_id
            WHERE DATE(s.start_dt) BETWEEN :monday AND :sunday
              AND (e.start_date IS NULL OR DATE(s.start_dt) >= e.start_date)
            GROUP BY e.id, e.name
            ORDER BY employee_name ASC
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':monday' => $monday->format('Y-m-d'),
            ':sunday' => $sunday->format('Y-m-d')
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Per-day hours for a specific employee within a week. Returns Mon..Sun rows present.
     * Returns: [ ['date'=>Y-m-d, 'hours'=>float], ... ]
     */
    public function hoursPerDayForWeekEmployee(string $mondayYmd, int $employeeId): array
    {
        $db = db_connect();
        $monday = new DateTime($mondayYmd);
        $sunday = clone $monday; $sunday->modify('+6 day');

        $sql = "
            SELECT 
                DATE(s.start_dt) AS date,
                ROUND(SUM(TIMESTAMPDIFF(MINUTE, s.start_dt, s.end_dt)) / 60, 2) AS hours
            FROM shifts s
            JOIN employees e ON e.id = s.employee_id
            WHERE s.employee_id = :emp
              AND DATE(s.start_dt) BETWEEN :monday AND :sunday
              AND (e.start_date IS NULL OR DATE(s.start_dt) >= e.start_date)
            GROUP BY DATE(s.start_dt)
            ORDER BY DATE(s.start_dt) ASC
        ";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':emp' => $employeeId,
            ':monday' => $monday->format('Y-m-d'),
            ':sunday' => $sunday->format('Y-m-d')
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
