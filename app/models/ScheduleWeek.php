<?php
class ScheduleWeek
{
    /** Normalize any date to its Monday (YYYY-MM-DD). */
    public static function mondayOf(string $dateYmd): string
    {
        $d = new DateTime($dateYmd);
        // PHP: 1=Mon..7=Sun
        $dow = (int)$d->format('N');
        $d->modify('-'.($dow-1).' days');
        return $d->format('Y-m-d');
    }

    /** Ensure a row exists in schedule_weeks and return {week_start, published}. */
    public function status(string $dateYmd): array
    {
        $db  = db_connect();
        $wk  = self::mondayOf($dateYmd);

        // upsert
        $db->prepare("
            INSERT INTO schedule_weeks (week_start, published)
            VALUES (:w, 0)
            ON DUPLICATE KEY UPDATE week_start = week_start
        ")->execute([':w'=>$wk]);

        $stmt = $db->prepare("SELECT week_start, published FROM schedule_weeks WHERE week_start = :w");
        $stmt->execute([':w'=>$wk]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: ['week_start'=>$wk,'published'=>0];

        return [
            'week_start' => $row['week_start'],
            'published'  => (int)$row['published']
        ];
    }

    public function setPublished(string $dateYmd, int $published): bool
    {
        $db = db_connect();
        $wk = self::mondayOf($dateYmd);
        $stmt = $db->prepare("
            INSERT INTO schedule_weeks (week_start, published)
            VALUES (:w, :p)
            ON DUPLICATE KEY UPDATE published = VALUES(published)
        ");
        return $stmt->execute([':w'=>$wk, ':p'=>$published ? 1 : 0]);
    }
}
