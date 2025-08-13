
<?php

class ScheduleWeek {
    private function db(): PDO { return db_connect(); }

    public static function mondayOf(string $date): string {
        $d = new DateTime($date);
        $weekday = $d->format('w');
        $daysToSubtract = ($weekday == 0) ? 6 : $weekday - 1;
        $d->modify("-{$daysToSubtract} days");
        return $d->format('Y-m-d');
    }

    public function status(string $week): array {
        $weekStart = self::mondayOf($week);
        $st = $this->db()->prepare("SELECT * FROM schedules WHERE week_start = ?");
        $st->execute([$weekStart]);
        $schedule = $st->fetch(PDO::FETCH_ASSOC);
        
        return [
            'week_start' => $weekStart,
            'published' => $schedule ? (bool)$schedule['published'] : false,
            'last_updated' => $schedule ? $schedule['updated_at'] : null
        ];
    }

    public function setPublished(string $week, bool $published): void {
        $weekStart = self::mondayOf($week);
        
        $st = $this->db()->prepare("
            INSERT INTO schedules (week_start, published, updated_at) 
            VALUES (?, ?, NOW())
            ON DUPLICATE KEY UPDATE 
            published = VALUES(published), 
            updated_at = VALUES(updated_at)
        ");
        $st->execute([$weekStart, $published ? 1 : 0]);
    }

    public function getPublishedWeeks(): array {
        $sql = "SELECT week_start, updated_at FROM schedules WHERE published = 1 ORDER BY week_start DESC";
        return $this->db()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
