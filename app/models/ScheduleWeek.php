<?php
class ScheduleWeek {
    private function db(): PDO { return db_connect(); }

    public function getPublishStatus(string $week): bool {
        $st = $this->db()->prepare("SELECT published FROM schedule_weeks WHERE week_start = ?");
        $st->execute([$week]);
        $result = $st->fetch(PDO::FETCH_ASSOC);
        return $result ? (bool)$result['published'] : false;
    }

    public function setPublishStatus(string $week, bool $published): bool {
        $st = $this->db()->prepare("
            INSERT INTO schedule_weeks (week_start, published) 
            VALUES (?, ?) 
            ON DUPLICATE KEY UPDATE published = VALUES(published)
        ");
        return $st->execute([$week, $published ? 1 : 0]);
    }

    public function isPublished(string $week): bool {
        return $this->getPublishStatus($week);
    }
}