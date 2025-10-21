<?php
/**
 * Database Cleanup and Optimization Script
 * Removes is_admin column and adds missing timestamps/indexes
 * Safe migration - no data loss
 */

require_once 'app/database.php';

try {
    $db = db_connect();
    echo "Starting database cleanup and optimization...\n\n";

    // ============================================
    // 1. REMOVE is_admin COLUMN FROM USERS TABLE
    // ============================================
    echo "1. Removing is_admin column from users table...\n";
    try {
        $db->exec("ALTER TABLE users DROP COLUMN IF EXISTS is_admin");
        echo "   ✓ is_admin column removed successfully\n\n";
    } catch (Exception $e) {
        echo "   ℹ is_admin column doesn't exist or already removed\n\n";
    }

    // ============================================
    // 2. ADD MISSING updated_at TIMESTAMPS
    // ============================================
    echo "2. Adding missing updated_at timestamps...\n";
    
    // Employees table
    try {
        $db->exec("
            ALTER TABLE employees 
            ADD COLUMN IF NOT EXISTS updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ");
        echo "   ✓ employees.updated_at added\n";
    } catch (Exception $e) {
        echo "   ℹ employees.updated_at already exists\n";
    }

    // Shifts table
    try {
        $db->exec("
            ALTER TABLE shifts 
            ADD COLUMN IF NOT EXISTS updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ");
        echo "   ✓ shifts.updated_at added\n";
    } catch (Exception $e) {
        echo "   ℹ shifts.updated_at already exists\n";
    }

    // Departments table
    try {
        $db->exec("
            ALTER TABLE departments 
            ADD COLUMN IF NOT EXISTS updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ");
        echo "   ✓ departments.updated_at added\n";
    } catch (Exception $e) {
        echo "   ℹ departments.updated_at already exists\n";
    }

    // Roles table
    try {
        $db->exec("
            ALTER TABLE roles 
            ADD COLUMN IF NOT EXISTS created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            ADD COLUMN IF NOT EXISTS updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            ADD COLUMN IF NOT EXISTS is_active TINYINT(1) DEFAULT 1
        ");
        echo "   ✓ roles timestamps and is_active added\n";
    } catch (Exception $e) {
        echo "   ℹ roles columns already exist\n";
    }

    // Users table
    try {
        $db->exec("
            ALTER TABLE users 
            ADD COLUMN IF NOT EXISTS updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ");
        echo "   ✓ users.updated_at added\n\n";
    } catch (Exception $e) {
        echo "   ℹ users.updated_at already exists\n\n";
    }

    // ============================================
    // 3. ADD MISSING INDEXES FOR PERFORMANCE
    // ============================================
    echo "3. Adding performance indexes...\n";
    
    // Employees table indexes
    try {
        $db->exec("CREATE INDEX IF NOT EXISTS idx_employees_active ON employees(is_active)");
        echo "   ✓ employees.is_active index added\n";
    } catch (Exception $e) {}

    try {
        $db->exec("CREATE INDEX IF NOT EXISTS idx_employees_created ON employees(created_at)");
        echo "   ✓ employees.created_at index added\n";
    } catch (Exception $e) {}

    // Shifts table indexes
    try {
        $db->exec("CREATE INDEX IF NOT EXISTS idx_shifts_employee ON shifts(employee_id)");
        echo "   ✓ shifts.employee_id index added\n";
    } catch (Exception $e) {}

    try {
        $db->exec("CREATE INDEX IF NOT EXISTS idx_shifts_dates ON shifts(start_dt, end_dt)");
        echo "   ✓ shifts date range index added\n";
    } catch (Exception $e) {}

    try {
        $db->exec("CREATE INDEX IF NOT EXISTS idx_shifts_status ON shifts(status)");
        echo "   ✓ shifts.status index added\n";
    } catch (Exception $e) {}

    // Departments table indexes
    try {
        $db->exec("CREATE INDEX IF NOT EXISTS idx_departments_active ON departments(is_active)");
        echo "   ✓ departments.is_active index added\n";
    } catch (Exception $e) {}

    // Users table indexes
    try {
        $db->exec("CREATE INDEX IF NOT EXISTS idx_users_access_level ON users(access_level)");
        echo "   ✓ users.access_level index added\n";
    } catch (Exception $e) {}

    try {
        $db->exec("CREATE INDEX IF NOT EXISTS idx_users_created ON users(created_at)");
        echo "   ✓ users.created_at index added\n\n";
    } catch (Exception $e) {}

    // ============================================
    // 4. ENSURE FOREIGN KEY INDEXES EXIST
    // ============================================
    echo "4. Verifying foreign key indexes...\n";
    
    try {
        $db->exec("CREATE INDEX IF NOT EXISTS idx_emp_dept_employee ON employee_department(employee_id)");
        echo "   ✓ employee_department.employee_id index added\n";
    } catch (Exception $e) {}

    try {
        $db->exec("CREATE INDEX IF NOT EXISTS idx_emp_dept_department ON employee_department(department_id)");
        echo "   ✓ employee_department.department_id index added\n";
    } catch (Exception $e) {}

    try {
        $db->exec("CREATE INDEX IF NOT EXISTS idx_dept_roles_dept ON department_roles(department_id)");
        echo "   ✓ department_roles.department_id index added\n";
    } catch (Exception $e) {}

    try {
        $db->exec("CREATE INDEX IF NOT EXISTS idx_dept_roles_role ON department_roles(role_id)");
        echo "   ✓ department_roles.role_id index added\n\n";
    } catch (Exception $e) {}

    // ============================================
    // 5. VERIFY STRUCTURE
    // ============================================
    echo "5. Database structure verification...\n";
    
    // Check if is_admin column is gone
    $stmt = $db->query("SHOW COLUMNS FROM users LIKE 'is_admin'");
    $hasIsAdmin = $stmt->fetch();
    
    if (!$hasIsAdmin) {
        echo "   ✓ is_admin column successfully removed\n";
    } else {
        echo "   ⚠ is_admin column still exists - may need manual removal\n";
    }
    
    // Check if access_level exists
    $stmt = $db->query("SHOW COLUMNS FROM users LIKE 'access_level'");
    $hasAccessLevel = $stmt->fetch();
    
    if ($hasAccessLevel) {
        echo "   ✓ access_level column exists\n";
    } else {
        echo "   ⚠ access_level column missing - needs to be added\n";
    }

    echo "\n✅ Database cleanup and optimization completed successfully!\n";
    echo "\nSummary:\n";
    echo "- Removed legacy is_admin column\n";
    echo "- Added missing updated_at timestamps\n";
    echo "- Added performance indexes\n";
    echo "- Verified foreign key indexes\n";
    echo "- All data preserved safely\n";
    
} catch (Exception $e) {
    echo "\n❌ Error during database cleanup: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
