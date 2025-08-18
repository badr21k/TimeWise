<?php
class Shift
{
    /**
     * Return all shifts within the Monday..Sunday window, joined with employee info.
     */
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
}
