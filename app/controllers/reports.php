<?php

class Reports extends Controller {

    public function index() {
        // Comprehensive access control check
        $this->checkAccess('Admin Reports Dashboard');

        try {
            $db = db_connect();

            // Get overview statistics
            $stats = $this->getOverviewStats($db);

            // Get all reminders with user info
            $allReminders = $this->getAllReminders($db);

            // Get user with most reminders
            $topUser = $this->getTopUser($db);

            // Get login statistics
            $loginStats = $this->getLoginStats($db);

            // Get recent activity
            $recentActivity = $this->getRecentActivity($db);

            $data = [
                'stats' => $stats,
                'allReminders' => $allReminders,
                'topUser' => $topUser,
                'loginStats' => $loginStats,
                'recentActivity' => $recentActivity
            ];

            $this->view('reports/index', $data);

        } catch (Exception $e) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'title' => 'System Error',
                'message' => 'Unable to load reports. Please try again later.'
            ];
            header('Location: /home');
            exit;
        }
    }

    public function allReminders() {
        // Comprehensive access control check
        $this->checkAccess('All Reminders Report');

        try {
            $db = db_connect();
            $reminders = $this->getAllReminders($db);
            $this->view('reports/all-reminders', ['reminders' => $reminders]);
        } catch (Exception $e) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'title' => 'Load Error',
                'message' => 'Unable to load reminders. Please try again later.'
            ];
            header('Location: /reports');
            exit;
        }
    }

    public function userStats() {
        // Comprehensive access control check
        $this->checkAccess('User Statistics Report');

        try {
            $db = db_connect();
            $userStats = $this->getUserReminderStats($db);
            $this->view('reports/user-stats', ['userStats' => $userStats]);
        } catch (Exception $e) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'title' => 'Load Error',
                'message' => 'Unable to load user statistics. Please try again later.'
            ];
            header('Location: /reports');
            exit;
        }
    }

    public function loginReport() {
        // Comprehensive access control check
        $this->checkAccess('Login Report');

        try {
            $db = db_connect();
            $loginStats = $this->getDetailedLoginStats($db);
            $this->view('reports/login-report', ['loginStats' => $loginStats]);
        } catch (Exception $e) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'title' => 'Load Error',
                'message' => 'Unable to load login report. Please try again later.'
            ];
            header('Location: /reports');
            exit;
        }
    }

    /**
     * Comprehensive access control check
     * Handles authentication and authorization with proper redirects and messages
     */
    private function checkAccess($resource = 'Admin Reports') {
        // Check if user is logged in
        if (!isset($_SESSION['auth']) || !$_SESSION['auth']) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'title' => 'Authentication Required',
                'message' => 'Please log in to access ' . $resource . '.'
            ];

            // Store intended URL for redirect after login
            $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'];

            header('Location: /login');
            exit;
        }

        // Check if user has admin privileges
        if (!$this->isAdmin()) {
            $_SESSION['toast'] = [
                'type' => 'error',
                'title' => 'Access Denied',
                'message' => 'You do not have permission to access ' . $resource . '. Admin privileges required.'
            ];

            // Log the unauthorized access attempt
            $this->logAccessAttempt($_SESSION['username'], $resource, 'denied');

            header('Location: /home');
            exit;
        }

        // Log successful access
        $this->logAccessAttempt($_SESSION['username'], $resource, 'granted');
    }

    /**
     * Check if current user is admin
     */
    private function isAdmin() {
        return isset($_SESSION['username']) && 
               !empty($_SESSION['username']) && 
               strtolower(trim($_SESSION['username'])) === 'admin';
    }

    /**
     * Log access attempts for security monitoring
     */
    private function logAccessAttempt($username, $resource, $status) {
        try {
            $db = db_connect();

            // Create access_logs table if it doesn't exist
            $db->exec("CREATE TABLE IF NOT EXISTS access_logs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(255) NOT NULL,
                resource VARCHAR(255) NOT NULL,
                status ENUM('granted', 'denied') NOT NULL,
                ip_address VARCHAR(45) NOT NULL,
                user_agent TEXT,
                timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_username (username),
                INDEX idx_timestamp (timestamp),
                INDEX idx_status (status)
            )");

            $stmt = $db->prepare("INSERT INTO access_logs (username, resource, status, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $username,
                $resource,
                $status,
                $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
            ]);

        } catch (Exception $e) {
            // Log error but don't break the application
            error_log("Access log error: " . $e->getMessage());
        }
    }

    private function getOverviewStats($db) {
        $stats = [];

        // Total users
        $stmt = $db->query("SELECT COUNT(*) as total_users FROM users");
        $stats['total_users'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_users'];

        // Total reminders
        $stmt = $db->query("SELECT COUNT(*) as total_reminders FROM notes WHERE deleted = 0");
        $stats['total_reminders'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_reminders'];

        // Completed reminders
        $stmt = $db->query("SELECT COUNT(*) as completed_reminders FROM notes WHERE deleted = 0 AND completed = 1");
        $stats['completed_reminders'] = $stmt->fetch(PDO::FETCH_ASSOC)['completed_reminders'];

        // Pending reminders
        $stats['pending_reminders'] = $stats['total_reminders'] - $stats['completed_reminders'];

        // Total logins (good attempts)
        $stmt = $db->query("SELECT COUNT(*) as total_logins FROM login_logs WHERE status = 'good'");
        $stats['total_logins'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_logins'];

        return $stats;
    }

    private function getAllReminders($db) {
        $stmt = $db->prepare("
            SELECT n.*, u.username 
            FROM notes n 
            JOIN users u ON n.user_id = u.id 
            WHERE n.deleted = 0 
            ORDER BY n.created_at DESC 
            LIMIT 50
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getTopUser($db) {
        $stmt = $db->prepare("
            SELECT u.username, COUNT(n.id) as reminder_count
            FROM users u
            LEFT JOIN notes n ON u.id = n.user_id AND n.deleted = 0
            GROUP BY u.id, u.username
            ORDER BY reminder_count DESC
            LIMIT 1
        ");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function getLoginStats($db) {
        $stmt = $db->prepare("
            SELECT username, 
                   COUNT(*) as login_count,
                   MAX(timestamp) as last_login
            FROM login_logs 
            WHERE status = 'good'
            GROUP BY username
            ORDER BY login_count DESC
            LIMIT 10
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getUserReminderStats($db) {
        $stmt = $db->prepare("
            SELECT u.username, 
                   COUNT(n.id) as total_reminders,
                   SUM(CASE WHEN n.completed = 1 THEN 1 ELSE 0 END) as completed,
                   SUM(CASE WHEN n.completed = 0 THEN 1 ELSE 0 END) as pending
            FROM users u
            LEFT JOIN notes n ON u.id = n.user_id AND n.deleted = 0
            GROUP BY u.id, u.username
            ORDER BY total_reminders DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getDetailedLoginStats($db) {
        $stmt = $db->prepare("
            SELECT username, status, COUNT(*) as attempt_count
            FROM login_logs
            GROUP BY username, status
            ORDER BY username, status
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function getRecentActivity($db) {
        try {
            $stmt = $db->prepare("
                SELECT 'reminder_created' as activity_type, 
                       CAST(u.username AS CHAR(255)) as username, 
                       CAST(n.subject AS CHAR(255)) as details, 
                       n.created_at as activity_time
                FROM notes n
                JOIN users u ON n.user_id = u.id
                WHERE n.deleted = 0
                UNION ALL
                SELECT 'login' as activity_type,
                       CAST(username AS CHAR(255)) as username,
                       CAST(CONCAT('Login attempt: ', status) AS CHAR(255)) as details,
                       timestamp as activity_time
                FROM login_logs
                ORDER BY activity_time DESC
                LIMIT 20
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Fallback: Get recent activity from each table separately
            $activities = [];

            // Get recent reminders
            $stmt = $db->prepare("
                SELECT 'reminder_created' as activity_type, 
                       u.username, 
                       n.subject as details, 
                       n.created_at as activity_time
                FROM notes n
                JOIN users u ON n.user_id = u.id
                WHERE n.deleted = 0
                ORDER BY n.created_at DESC
                LIMIT 10
            ");
            $stmt->execute();
            $activities = array_merge($activities, $stmt->fetchAll(PDO::FETCH_ASSOC));

            // Get recent logins
            $stmt = $db->prepare("
                SELECT 'login' as activity_type,
                       username,
                       CONCAT('Login attempt: ', status) as details,
                       timestamp as activity_time
                FROM login_logs
                ORDER BY timestamp DESC
                LIMIT 10
            ");
            $stmt->execute();
            $activities = array_merge($activities, $stmt->fetchAll(PDO::FETCH_ASSOC));

            // Sort by activity_time
            usort($activities, function($a, $b) {
                return strtotime($b['activity_time']) - strtotime($a['activity_time']);
            });

            return array_slice($activities, 0, 20);
        }
    }
} 