<?php

class Reports extends Controller {

    public function index() {
        // Centralized RBAC
        if (class_exists('AccessControl')) {
            AccessControl::enforceAccess('reports', 'index', 'Admin Reports Dashboard');
        }

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

    /** Satisfaction per user (weekly average), with filters */
    public function satisfactionUsers() {
        if (class_exists('AccessControl')) {
            AccessControl::enforceAccess('reports', 'satisfactionUsers', 'Satisfaction (Users)');
        }

        $db = db_connect();

        // Users list for selector
        $users = $db->query("SELECT id, COALESCE(NULLIF(full_name,''), username) AS label FROM users ORDER BY label ASC")->fetchAll(PDO::FETCH_ASSOC);

        $userId = (int)($_GET['user_id'] ?? ($users[0]['id'] ?? 0));
        
        $to = $_GET['to'] ?? date('Y-m-d');
        // default from = 12 weeks ago
        $defaultFrom = (new DateTime($to))->modify('-12 weeks')->format('Y-m-d');
        // respect employee start_date if present (later of join and defaultFrom)
        $from = $_GET['from'] ?? $defaultFrom;

        $fromDate = new DateTime($from);
        // Pull employee start_date
        $startDate = null;
        if ($userId) {
            try {
                $stmt = $db->prepare("SELECT e.start_date FROM employees e WHERE e.user_id = :u LIMIT 1");
                $stmt->execute([':u'=>$userId]);
                $s = $stmt->fetchColumn();
                if ($s) {
                    $sd = new DateTime($s);
                    if ($sd > $fromDate) $fromDate = $sd;
                }
            } catch (Throwable $e) {}
        }

        $from = $fromDate->format('Y-m-d');

        $labels = [];
        $values = [];
        $series = [];

        if ($userId) {
            // Weekly average satisfaction for the selected user
            $sql = "
                SELECT 
                  DATE_SUB(te.entry_date, INTERVAL WEEKDAY(te.entry_date) DAY) AS week_start,
                  ROUND(AVG(te.satisfaction),2) AS avg_sat
                FROM time_entries te
                LEFT JOIN employees e ON e.id = te.employee_id
                WHERE te.user_id = :u
                  AND te.satisfaction IS NOT NULL
                  AND te.entry_date BETWEEN :from AND :to
                  AND (e.start_date IS NULL OR te.entry_date >= e.start_date)
                GROUP BY week_start
                ORDER BY week_start ASC
            ";
            try {
                $stmt = $db->prepare($sql);
                $stmt->execute([':u'=>$userId, ':from'=>$from, ':to'=>$to]);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Throwable $e) {
                $rows = [];
            }
            foreach ($rows as $r) {
                $labels[] = $r['week_start'];
                $values[] = (float)$r['avg_sat'];
            }
            $series = [
                'label' => 'Avg Satisfaction',
                'data'  => $values,
            ];
        }

        $this->view('reports/satisfaction-users', [
            'users'  => $users,
            'user_id'=> $userId,
            'from'   => $from,
            'to'     => $to,
            'labels' => $labels,
            'series' => $series,
        ]);
    }

    /** Satisfaction per department (weekly average) */
    public function satisfactionDepartments() {
        if (class_exists('AccessControl')) {
            AccessControl::enforceAccess('reports', 'satisfactionDepartments', 'Satisfaction (Departments)');
        }

        $db = db_connect();
        $to = $_GET['to'] ?? date('Y-m-d');
        $from = $_GET['from'] ?? (new DateTime($to))->modify('-12 weeks')->format('Y-m-d');

        // Weekly average by department
        $sql = "
            SELECT 
              d.name AS department,
              DATE_SUB(te.entry_date, INTERVAL WEEKDAY(te.entry_date) DAY) AS week_start,
              ROUND(AVG(te.satisfaction),2) AS avg_sat
            FROM time_entries te
            LEFT JOIN employees e ON e.id = te.employee_id
            LEFT JOIN employee_department ed ON ed.employee_id = e.id
            LEFT JOIN departments d ON d.id = ed.department_id
            WHERE te.satisfaction IS NOT NULL
              AND te.entry_date BETWEEN :from AND :to
              AND (e.start_date IS NULL OR te.entry_date >= e.start_date)
            GROUP BY d.name, week_start
            HAVING department IS NOT NULL
            ORDER BY week_start ASC, department ASC
        ";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([':from'=>$from, ':to'=>$to]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            $rows = [];
        }

        // Pivot to chart series
        $labels = [];
        $series = [];
        foreach ($rows as $r) {
            $wk = $r['week_start'];
            if (!in_array($wk, $labels, true)) $labels[] = $wk;
        }
        sort($labels);
        foreach ($rows as $r) {
            $dept = $r['department'];
            if (!isset($series[$dept])) $series[$dept] = array_fill_keys($labels, null);
            $series[$dept][$r['week_start']] = (float)$r['avg_sat'];
        }
        // Convert to arrays
        $seriesArr = [];
        foreach ($series as $dept => $dataMap) {
            $seriesArr[] = ['label'=>$dept, 'data'=>array_values($dataMap)];
        }

        $this->view('reports/satisfaction-departments', [
            'from' => $from,
            'to'   => $to,
            'labels' => $labels,
            'series' => $seriesArr,
        ]);
    }

    /** Weekly total hours per employee */
    public function hours() {
        // Centralized RBAC
        if (class_exists('AccessControl')) {
            AccessControl::enforceAccess('reports', 'hours', 'Weekly Hours');
        }

        $Week = $this->model('ScheduleWeek');
        $Shift = $this->model('Shift');

        $week = $_GET['week'] ?? date('Y-m-d');
        $monday = $Week::mondayOf($week);

        $rows = $Shift->hoursForWeekGrouped($monday);

        $this->view('reports/hours', [
            'week_start' => $monday,
            'rows' => $rows,
        ]);
    }

    /** Per-day breakdown for selected employee within a week */
    public function hoursEmployee() {
        // Centralized RBAC
        if (class_exists('AccessControl')) {
            AccessControl::enforceAccess('reports', 'hoursEmployee', 'Weekly Hours (Employee)');
        }

        $empId = (int)($_GET['employee_id'] ?? 0);
        if ($empId <= 0) { header('Location: /reports/hours'); exit; }

        $Week = $this->model('ScheduleWeek');
        $Shift = $this->model('Shift');

        $week = $_GET['week'] ?? date('Y-m-d');
        $monday = $Week::mondayOf($week);

        // Fetch per-day hours and employee label
        $perDay = $Shift->hoursPerDayForWeekEmployee($monday, $empId);

        // Fetch employee display name
        $db = db_connect();
        $stmt = $db->prepare("SELECT COALESCE(NULLIF(name,''), CONCAT('Employee #', id)) AS name FROM employees WHERE id = :id");
        $stmt->execute([':id' => $empId]);
        $empName = $stmt->fetchColumn() ?: ('Employee #' . $empId);

        $this->view('reports/hours-employee', [
            'week_start' => $monday,
            'employee_id' => $empId,
            'employee_name' => $empName,
            'per_day' => $perDay,
        ]);
    }

    public function allReminders() {
        // Centralized RBAC
        if (class_exists('AccessControl')) {
            AccessControl::enforceAccess('reports', 'allReminders', 'All Reminders Report');
        }

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
        // Centralized RBAC
        if (class_exists('AccessControl')) {
            AccessControl::enforceAccess('reports', 'userStats', 'User Statistics Report');
        }

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
        // Centralized RBAC
        if (class_exists('AccessControl')) {
            AccessControl::enforceAccess('reports', 'loginReport', 'Login Report');
        }

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