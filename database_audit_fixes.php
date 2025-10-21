<?php
/**
 * Database Audit Fixes
 * Comprehensive script to fix all database integrity issues found during audit
 */

require_once 'app/database.php';

try {
    $db = db_connect();
    echo "=== DATABASE AUDIT FIXES ===" . PHP_EOL . PHP_EOL;
    
    $fixes = [];
    $issues = [];
    
    // ============================================
    // FIX 1: Add UNIQUE constraint on users.username
    // ============================================
    echo "1. Checking users.username UNIQUE constraint..." . PHP_EOL;
    try {
        $stmt = $db->query("SHOW CREATE TABLE users");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (strpos($result['Create Table'], 'UNIQUE') === false || 
            (strpos($result['Create Table'], 'UNIQUE KEY') === false && 
             strpos($result['Create Table'], 'UNIQUE `username`') === false)) {
            
            // Check for duplicates first
            $stmt = $db->query("SELECT username, COUNT(*) as cnt FROM users GROUP BY username HAVING cnt > 1");
            $dupes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($dupes) > 0) {
                $issues[] = "CANNOT FIX: Duplicate usernames exist: " . json_encode($dupes);
                echo "   ⚠ ISSUE: Duplicate usernames found - manual cleanup required" . PHP_EOL;
            } else {
                $db->exec("ALTER TABLE users ADD UNIQUE KEY unique_username (username)");
                $fixes[] = "Added UNIQUE constraint on users.username";
                echo "   ✓ Added UNIQUE constraint on users.username" . PHP_EOL;
            }
        } else {
            echo "   ✓ UNIQUE constraint already exists" . PHP_EOL;
        }
    } catch (Exception $e) {
        $issues[] = "Error checking users.username: " . $e->getMessage();
        echo "   ✗ Error: " . $e->getMessage() . PHP_EOL;
    }
    
    // ============================================
    // FIX 2: Add foreign key on employees.user_id
    // ============================================
    echo PHP_EOL . "2. Checking employees.user_id foreign key..." . PHP_EOL;
    try {
        $stmt = $db->query("SHOW CREATE TABLE employees");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (strpos($result['Create Table'], 'FOREIGN KEY (`user_id`)') === false) {
            // Check for orphaned records first
            $stmt = $db->query("
                SELECT COUNT(*) as cnt 
                FROM employees 
                WHERE user_id IS NOT NULL 
                  AND user_id NOT IN (SELECT id FROM users)
            ");
            $orphans = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($orphans['cnt'] > 0) {
                $issues[] = "CANNOT FIX: " . $orphans['cnt'] . " orphaned employees.user_id records exist";
                echo "   ⚠ ISSUE: {$orphans['cnt']} orphaned records - manual cleanup required" . PHP_EOL;
            } else {
                $db->exec("
                    ALTER TABLE employees 
                    ADD CONSTRAINT fk_emp_user 
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
                ");
                $fixes[] = "Added foreign key constraint on employees.user_id";
                echo "   ✓ Added foreign key on employees.user_id" . PHP_EOL;
            }
        } else {
            echo "   ✓ Foreign key already exists" . PHP_EOL;
        }
    } catch (Exception $e) {
        $issues[] = "Error checking employees.user_id FK: " . $e->getMessage();
        echo "   ✗ Error: " . $e->getMessage() . PHP_EOL;
    }
    
    // ============================================
    // FIX 3: Add missing indexes on login_logs
    // ============================================
    echo PHP_EOL . "3. Adding missing indexes on login_logs..." . PHP_EOL;
    try {
        // Add index on username
        try {
            $db->exec("CREATE INDEX idx_login_username ON login_logs(username)");
            $fixes[] = "Added index on login_logs.username";
            echo "   ✓ Added index on username" . PHP_EOL;
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
                echo "   ✓ Index on username already exists" . PHP_EOL;
            } else {
                throw $e;
            }
        }
        
        // Add index on timestamp
        try {
            $db->exec("CREATE INDEX idx_login_timestamp ON login_logs(timestamp)");
            $fixes[] = "Added index on login_logs.timestamp";
            echo "   ✓ Added index on timestamp" . PHP_EOL;
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
                echo "   ✓ Index on timestamp already exists" . PHP_EOL;
            } else {
                throw $e;
            }
        }
        
        // Add composite index for login attempt queries
        try {
            $db->exec("CREATE INDEX idx_login_user_time ON login_logs(username, timestamp, status)");
            $fixes[] = "Added composite index on login_logs for login attempt queries";
            echo "   ✓ Added composite index for login queries" . PHP_EOL;
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
                echo "   ✓ Composite index already exists" . PHP_EOL;
            } else {
                throw $e;
            }
        }
    } catch (Exception $e) {
        $issues[] = "Error adding login_logs indexes: " . $e->getMessage();
        echo "   ✗ Error: " . $e->getMessage() . PHP_EOL;
    }
    
    // ============================================
    // FIX 4: Add created_at to users if missing
    // ============================================
    echo PHP_EOL . "4. Checking users.created_at column..." . PHP_EOL;
    try {
        $stmt = $db->query("SHOW COLUMNS FROM users LIKE 'created_at'");
        if (!$stmt->fetch()) {
            $db->exec("
                ALTER TABLE users 
                ADD COLUMN created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            ");
            $fixes[] = "Added created_at column to users table";
            echo "   ✓ Added created_at column" . PHP_EOL;
        } else {
            echo "   ✓ created_at column already exists" . PHP_EOL;
        }
    } catch (Exception $e) {
        $issues[] = "Error checking users.created_at: " . $e->getMessage();
        echo "   ✗ Error: " . $e->getMessage() . PHP_EOL;
    }
    
    // ============================================
    // FIX 5: Optimize shifts table indexes
    // ============================================
    echo PHP_EOL . "5. Optimizing shifts table indexes..." . PHP_EOL;
    try {
        // Add index on created_at for audit queries
        try {
            $db->exec("CREATE INDEX idx_shifts_created ON shifts(created_at)");
            $fixes[] = "Added index on shifts.created_at";
            echo "   ✓ Added index on created_at" . PHP_EOL;
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'Duplicate key name') !== false) {
                echo "   ✓ Index already exists" . PHP_EOL;
            } else {
                throw $e;
            }
        }
    } catch (Exception $e) {
        $issues[] = "Error optimizing shifts indexes: " . $e->getMessage();
        echo "   ✗ Error: " . $e->getMessage() . PHP_EOL;
    }
    
    // ============================================
    // SUMMARY
    // ============================================
    echo PHP_EOL . "=== SUMMARY ===" . PHP_EOL;
    echo "Fixes applied: " . count($fixes) . PHP_EOL;
    foreach ($fixes as $fix) {
        echo "  ✓ " . $fix . PHP_EOL;
    }
    
    if (count($issues) > 0) {
        echo PHP_EOL . "Issues requiring manual intervention: " . count($issues) . PHP_EOL;
        foreach ($issues as $issue) {
            echo "  ⚠ " . $issue . PHP_EOL;
        }
    }
    
    echo PHP_EOL . "✅ Database audit fixes completed!" . PHP_EOL;
    
} catch (Exception $e) {
    echo PHP_EOL . "❌ Fatal error: " . $e->getMessage() . PHP_EOL;
    echo "Stack trace:" . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
}
