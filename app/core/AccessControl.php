<?php

/**
 * AccessControl - Centralized Role-Based Access Control (RBAC) System
 * 
 * This class handles all permission checking based on the centralized access.php config.
 * It supports authentication, role-based, and department-based access control.
 */
class AccessControl
{
    private static $config = null;
    private static $userCache = null;
    
    /**
     * Load the access control configuration
     */
    private static function loadConfig(): array
    {
        if (self::$config === null) {
            $configPath = __DIR__ . '/../config/access.php';
            if (!file_exists($configPath)) {
                throw new Exception('Access control configuration file not found');
            }
            self::$config = require $configPath;
        }
        return self::$config;
    }
    
    /**
     * Get current user information with roles and departments
     */
    private static function getCurrentUser(): ?array
    {
        if (self::$userCache !== null) {
            return self::$userCache;
        }
        
        if (!isset($_SESSION['auth']) || !$_SESSION['auth']) {
            return null;
        }
        
        $userId = (int)($_SESSION['id'] ?? 0);
        if (!$userId) {
            return null;
        }
        
        try {
            $db = db_connect();
            
            // Get user basic info with access_level
            $userStmt = $db->prepare("
                SELECT u.id, u.username, u.access_level, u.full_name
                FROM users u 
                WHERE u.id = :user_id
            ");
            $userStmt->execute([':user_id' => $userId]);
            $user = $userStmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                return null;
            }
            
            // Set access_level - default to 1 (Regular User) if not set
            $user['access_level'] = (int)($user['access_level'] ?? 1);
            
            // TERMINATION RULE: Invalidate session if access_level = 0 (terminated user)
            if ($user['access_level'] === 0) {
                session_unset();
                session_destroy();
                return null;
            }
            
            // Try to find linked employee record to get a role title
            $employee = self::findEmployeeForUser($userId, $user);

            if ($employee) {
                $user['employee_id'] = $employee['id'];
                $user['role_title'] = $employee['role_title'] ?? 'Staff';
            } else {
                $user['employee_id'] = null;
                $user['role_title'] = 'Staff';
            }

            // Departments and manager status are based on department_managers in this app
            // A user is considered a manager for a department if they appear in department_managers for that department
            $dmStmt = $db->prepare("
                SELECT d.name as department_name
                FROM department_managers dm
                JOIN departments d ON dm.department_id = d.id
                WHERE dm.user_id = :uid AND COALESCE(d.is_active,1)=1
            ");
            try {
                $dmStmt->execute([':uid' => $userId]);
                $dmDepartments = $dmStmt->fetchAll(PDO::FETCH_COLUMN);
            } catch (Throwable $e) {
                $dmDepartments = [];
            }

            $user['departments'] = $dmDepartments; // departments where the user is a manager
            $user['is_manager'] = !empty($dmDepartments);
            
            // Determine effective roles
            $roles = [];
            if ($user['access_level'] >= 4) {
                $roles[] = 'admin';
            }
            if (!empty($user['is_manager'])) {
                $roles[] = 'manager';
            }
            $roles[] = strtolower($user['role_title']);
            
            $user['roles'] = array_unique($roles);
            
            self::$userCache = $user;
            return $user;
            
        } catch (Exception $e) {
            error_log("AccessControl: Error getting user info: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Find employee record for a user using multiple strategies
     */
    private static function findEmployeeForUser(int $userId, array $user): ?array
    {
        $db = db_connect();
        
        // Strategy 1: Direct user_id link (if column exists)
        try {
            $stmt = $db->prepare("SELECT * FROM employees WHERE user_id = :user_id LIMIT 1");
            $stmt->execute([':user_id' => $userId]);
            $employee = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($employee) {
                return $employee;
            }
        } catch (Exception $e) {
            // user_id column might not exist, continue with other strategies
        }
        
        // Strategy 2: Email match (if user has email)
        if (!empty($user['email'])) {
            try {
                $stmt = $db->prepare("SELECT * FROM employees WHERE LOWER(email) = LOWER(:email) LIMIT 1");
                $stmt->execute([':email' => $user['email']]);
                $employee = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($employee) {
                    return $employee;
                }
            } catch (Exception $e) {
                // Continue with next strategy
            }
        }
        
        // Strategy 3: Name match
        $nameMatches = [];
        if (!empty($user['full_name'])) {
            $nameMatches[] = $user['full_name'];
        }
        if (!empty($user['username'])) {
            $nameMatches[] = $user['username'];
        }
        
        foreach ($nameMatches as $name) {
            try {
                $stmt = $db->prepare("SELECT * FROM employees WHERE TRIM(LOWER(name)) = TRIM(LOWER(:name)) LIMIT 1");
                $stmt->execute([':name' => $name]);
                $employee = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($employee) {
                    return $employee;
                }
            } catch (Exception $e) {
                // Continue with next name
            }
        }
        
        return null;
    }
    
    /**
     * Check if current user has access to a specific resource
     * 
     * @param string $resource The resource key from access config
     * @param string $type The type of resource (navigation, controllers, actions)
     * @return bool
     */
    public static function hasAccess(string $resource, string $type = 'navigation'): bool
    {
        $config = self::loadConfig();
        $user = self::getCurrentUser();
        
        // If no user is logged in, deny access
        if (!$user) {
            return false;
        }
        
        // Get the access rule for this resource
        $rule = $config[$type][$resource] ?? null;
        
        if (!$rule) {
            // No rule defined - default to deny
            return false;
        }
        
        return self::evaluateRule($rule, $user);
    }
    
    /**
     * Check if current user has access to a controller action
     * 
     * @param string $controller
     * @param string $action
     * @return bool
     */
    public static function hasControllerAccess(string $controller, string $action = 'index'): bool
    {
        $config = self::loadConfig();
        $user = self::getCurrentUser();
        
        if (!$user) {
            return false;
        }
        
        // Check specific action rule first
        $actionKey = "{$controller}.{$action}";
        if (isset($config['actions'][$actionKey])) {
            return self::evaluateRule($config['actions'][$actionKey], $user);
        }
        
        // Check controller-level rule
        $controllerRule = $config['controllers'][$controller] ?? null;
        
        if (is_array($controllerRule)) {
            // Controller has action-specific rules
            $rule = $controllerRule[$action] ?? null;
        } else {
            // Controller has a single rule for all actions
            $rule = $controllerRule;
        }
        
        if (!$rule) {
            // No rule defined - default to deny
            return false;
        }
        
        return self::evaluateRule($rule, $user);
    }
    
    /**
     * Evaluate an access rule against user permissions
     * 
     * @param string $rule The access rule (e.g., "auth", "role:manager", "role:admin & dept:Food Pantry")
     * @param array $user User information
     * @return bool
     */
    private static function evaluateRule(string $rule, array $user): bool
    {
        $rule = trim($rule);
        
        // Handle AND conditions
        if (strpos($rule, ' & ') !== false) {
            $conditions = explode(' & ', $rule);
            foreach ($conditions as $condition) {
                if (!self::evaluateRule(trim($condition), $user)) {
                    return false;
                }
            }
            return true;
        }
        
        // Handle OR conditions (if needed in future)
        if (strpos($rule, ' | ') !== false) {
            $conditions = explode(' | ', $rule);
            foreach ($conditions as $condition) {
                if (self::evaluateRule(trim($condition), $user)) {
                    return true;
                }
            }
            return false;
        }
        
        // Handle simple rules
        if ($rule === 'auth') {
            return true; // User is already authenticated if we got here
        }
        
        if (strpos($rule, 'role:') === 0) {
            $allowedRoles = explode(',', substr($rule, 5));
            $allowedRoles = array_map('trim', $allowedRoles);
            $allowedRoles = array_map('strtolower', $allowedRoles);
            
            foreach ($user['roles'] as $userRole) {
                if (in_array(strtolower($userRole), $allowedRoles)) {
                    return true;
                }
            }
            return false;
        }
        
        if (strpos($rule, 'dept:') === 0) {
            $allowedDept = trim(substr($rule, 5));
            return in_array($allowedDept, $user['departments']);
        }
        
        if (strpos($rule, 'level:') === 0) {
            $requiredLevel = (int)trim(substr($rule, 6));
            return ($user['access_level'] ?? 0) >= $requiredLevel;
        }
        
        // Unknown rule type - deny access
        return false;
    }
    
    /**
     * Enforce access control for a controller action
     * Redirects to appropriate page if access is denied
     * 
     * @param string $controller
     * @param string $action
     * @param string $resourceName Human-readable resource name for error messages
     */
    public static function enforceAccess(string $controller, string $action = 'index', string $resourceName = null): void
    {
        if (!self::hasControllerAccess($controller, $action)) {
            $user = self::getCurrentUser();
            
            if (!$user) {
                // Not logged in
                $_SESSION['toast'] = [
                    'type' => 'error',
                    'title' => 'Authentication Required',
                    'message' => 'Please log in to access ' . ($resourceName ?? $controller) . '.'
                ];
                $_SESSION['intended_url'] = $_SERVER['REQUEST_URI'];
                header('Location: /login');
                exit;
            } else {
                // Logged in but no permission
                $_SESSION['toast'] = [
                    'type' => 'error',
                    'title' => 'Access Denied',
                    'message' => 'You do not have permission to access ' . ($resourceName ?? $controller) . '.'
                ];
                header('Location: /home');
                exit;
            }
        }
    }
    
    /**
     * Get current user's roles (for debugging/display)
     */
    public static function getCurrentUserRoles(): array
    {
        $user = self::getCurrentUser();
        return $user ? $user['roles'] : [];
    }
    
    /**
     * Get current user's departments (for debugging/display)
     */
    public static function getCurrentUserDepartments(): array
    {
        $user = self::getCurrentUser();
        return $user ? $user['departments'] : [];
    }
    
    /**
     * Get current user's access level (0-4)
     */
    public static function getCurrentUserAccessLevel(): int
    {
        $user = self::getCurrentUser();
        return $user ? (int)($user['access_level'] ?? 0) : 0;
    }
    
    /**
     * Check if user has minimum access level
     */
    public static function hasMinimumLevel(int $requiredLevel): bool
    {
        return self::getCurrentUserAccessLevel() >= $requiredLevel;
    }
    
    /**
     * Get current user's allowed department IDs
     */
    public static function getUserDepartmentIds(): array
    {
        $user = self::getCurrentUser();
        if (!$user || !isset($user['employee_id'])) {
            return [];
        }
        
        try {
            $db = db_connect();
            $stmt = $db->prepare("
                SELECT department_id 
                FROM employee_department 
                WHERE employee_id = :emp_id
            ");
            $stmt->execute([':emp_id' => $user['employee_id']]);
            return $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
        } catch (Exception $e) {
            error_log("AccessControl: Error getting user departments: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get access level description
     */
    public static function getAccessLevelDescription(int $level): string
    {
        $descriptions = [
            0 => 'Inactive - No access',
            1 => 'Regular User - Dashboard, Chat, Time Clock, My Shifts, Reminders',
            2 => 'Power User - Dashboard, Chat, Time Clock, My Shifts, Reminders',
            3 => 'Team Lead - Dashboard, Chat, Team, Schedule, Reminders, Admin Reports',
            4 => 'Department Admin - Dashboard, Chat, Time Clock, My Shifts, Reminders, Admin Reports, Departments & Roles (View Only)'
        ];
        return $descriptions[$level] ?? 'Unknown';
    }
    
    /**
     * Check if user can access inactive status (level 0 means cannot login)
     */
    public static function canLogin(): bool
    {
        return self::getCurrentUserAccessLevel() > 0;
    }
    
    /**
     * Clear user cache (useful for testing or when user data changes)
     */
    public static function clearUserCache(): void
    {
        self::$userCache = null;
    }
}
