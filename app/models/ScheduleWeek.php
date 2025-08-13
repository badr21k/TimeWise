<?php

class ScheduleWeek {
    private function db(): PDO { return db_connect(); }

    public static function mondayOf(string $date): string {
        $d = new DateTime($date);
        $dow = (int)$d->format('N') - 1; if ($dow > 0) $d->modify("-{$dow} day");
        return $d->format('Y-m-d');
    }

    public function status(string $anyDateInWeek): array {
        $w = self::mondayOf($anyDateInWeek);
        $st = $this->db()->prepare("SELECT week_start,published,updated_at FROM schedules WHERE week_start=?");
        $st->execute([$w]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return ['week_start'=>$w,'published'=>(int)($row['published'] ?? 0),'updated_at'=>$row['updated_at'] ?? null];
    }

    public function setPublished(string $anyDateInWeek, bool $published): void {
        $w = self::mondayOf($anyDateInWeek);
        $st = $this->db()->prepare("
            INSERT INTO schedules (week_start,published) VALUES (?,?)
            ON DUPLICATE KEY UPDATE published=VALUES(published), updated_at=NOW()
        ");
        $st->execute([$w, $published?1:0]);
    }
}
