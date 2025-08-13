
<?php
class ScheduleWeek {
    private function db(): PDO { return db_connect(); }

    public static function mondayOf(string $date): string {
        $d = new DateTime($date);
        $dayOfWeek = (int)$d->format('w'); // 0 = Sunday, 1 = Monday, etc.
        $daysFromMonday = ($dayOfWeek === 0) ? 6 : $dayOfWeek - 1;
        $d->modify("-{$daysFromMonday} days");
        return $d->format('Y-m-d');
    }

    public function status(string $week): array {
        $monday = self::mondayOf($week);
        $st = $this->db()->prepare("SELECT published FROM schedule_weeks WHERE week_start = ?");
        $st->execute([$monday]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        
        return [
            'week' => $monday,
            'published' => $row ? (bool)$row['published'] : false
        ];
    }

    public function setPublished(string $week, bool $published): bool {
        $monday = self::mondayOf($week);
        $st = $this->db()->prepare("
            INSERT INTO schedule_weeks (week_start, published) 
            VALUES (?, ?) 
            ON DUPLICATE KEY UPDATE published = ?
        ");
        return $st->execute([$monday, $published ? 1 : 0, $published ? 1 : 0]);
    }

    public function getPublishedWeeks(): array {
        $st = $this->db()->prepare("
            SELECT week_start, published 
            FROM schedule_weeks 
            WHERE published = 1 
            ORDER BY week_start DESC
        ");
        $st->execute();
        return $st->fetchAll(PDO::FETCH_ASSOC);
    }
}
