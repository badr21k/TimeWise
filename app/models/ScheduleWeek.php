
<?php

class ScheduleWeek {
    private $db;
    
    public function __construct() {
        $this->db = db_connect();
        $this->createTable();
    }
    
    private function createTable() {
        try {
            $this->db->exec("
                CREATE TABLE IF NOT EXISTS schedules (
                    week_start DATE PRIMARY KEY,
                    published TINYINT(1) DEFAULT 0,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                )
            ");
        } catch (Exception $e) {
            error_log("Error creating schedules table: " . $e->getMessage());
        }
    }
    
    public function publishWeek($weekStart) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO schedules (week_start, published, updated_at) 
                VALUES (?, 1, NOW()) 
                ON DUPLICATE KEY UPDATE published = 1, updated_at = NOW()
            ");
            return $stmt->execute([$weekStart]);
        } catch (Exception $e) {
            error_log("Error publishing week: " . $e->getMessage());
            return false;
        }
    }
    
    public function isPublished($weekStart) {
        try {
            $stmt = $this->db->prepare("SELECT published FROM schedules WHERE week_start = ?");
            $stmt->execute([$weekStart]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? (bool)$result['published'] : false;
        } catch (Exception $e) {
            error_log("Error checking if week is published: " . $e->getMessage());
            return false;
        }
    }
    
    public function getPublishedWeeks() {
        try {
            $stmt = $this->db->query("SELECT week_start, updated_at FROM schedules WHERE published = 1 ORDER BY week_start DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Error getting published weeks: " . $e->getMessage());
            return [];
        }
    }
}
