<?php
require_once 'app/database.php';

try {
    $db = db_connect();

    // Create employees table
    $db->exec("
        CREATE TABLE IF NOT EXISTS employees (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(150) UNIQUE,
            role VARCHAR(100) DEFAULT 'Staff',
            wage DECIMAL(10,2) NULL,
            role_title VARCHAR(100) NULL,
            is_active TINYINT(1) DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Create shifts table
    $db->exec("
        CREATE TABLE IF NOT EXISTS shifts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            employee_id INT NOT NULL,
            start_dt DATETIME NOT NULL,
            end_dt DATETIME NOT NULL,
            notes TEXT NULL,
            status VARCHAR(20) DEFAULT 'scheduled',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE
        )
    ");

    // Create departments table
    $db->exec("
        CREATE TABLE IF NOT EXISTS departments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            is_active TINYINT(1) DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_name (name)
        )
    ");

    // Create schedule weeks table for publishing
    $db->exec("
        CREATE TABLE IF NOT EXISTS schedule_weeks (
            id INT AUTO_INCREMENT PRIMARY KEY,
            week_start DATE NOT NULL,
            published TINYINT(1) DEFAULT 0,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            UNIQUE KEY unique_week (week_start)
        )
    ");

    // Create roles table
    $db->exec("
        CREATE TABLE IF NOT EXISTS roles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            UNIQUE KEY unique_name (name)
        )
    ");

    // Create department_roles junction table
    $db->exec("
        CREATE TABLE IF NOT EXISTS department_roles (
            department_id INT,
            role_id INT,
            PRIMARY KEY (department_id, role_id),
            FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE,
            FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
        )
    ");

    // Update employees table to include wage and role_title
    $db->exec("
        ALTER TABLE employees 
        ADD COLUMN IF NOT EXISTS wage DECIMAL(10,2) NULL,
        ADD COLUMN IF NOT EXISTS role_title VARCHAR(100) NULL,
        MODIFY COLUMN role VARCHAR(100) NULL
    ");

    // Create employee_department junction table
    $db->exec("
        CREATE TABLE IF NOT EXISTS employee_department (
            employee_id INT,
            department_id INT,
            is_manager TINYINT(1) DEFAULT 0,
            PRIMARY KEY (employee_id, department_id),
            FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
            FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
        )
    ");

    // Update shifts table to include status
    $db->exec("
        ALTER TABLE shifts 
        ADD COLUMN IF NOT EXISTS status VARCHAR(20) DEFAULT 'scheduled'
    ");

    // Create schedules table for publish tracking
    $db->exec("
        CREATE TABLE IF NOT EXISTS schedules (
            week_start DATE PRIMARY KEY,
            published TINYINT(1) DEFAULT 0,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");

    // Insert default roles
    $defaultRoles = ['Manager', 'Coordinator', 'Support Worker', 'Volunteer', 'Admin', 'Staff'];
    foreach ($defaultRoles as $role) {
        $db->prepare("INSERT IGNORE INTO roles (name) VALUES (?)")->execute([$role]);
    }

    // Insert default departments
    $defaultDepartments = ['General', 'Support', 'Administration', 'Operations'];
    foreach ($defaultDepartments as $dept) {
        $db->prepare("INSERT IGNORE INTO departments (name, is_active, created_at) VALUES (?, 1, NOW())")->execute([$dept]);
    }

    // Insert sample employees for testing
    $db->exec("
        INSERT IGNORE INTO employees (id, name, email, role, role_title, is_active) VALUES 
        (1, 'John Smith', 'john@example.com', 'Manager', 'Store Manager', 1),
        (2, 'Jane Doe', 'jane@example.com', 'Staff', 'Sales Associate', 1),
        (3, 'Bob Johnson', 'bob@example.com', 'Staff', 'Cashier', 1),
        (4, 'Alice Brown', 'alice@example.com', 'Supervisor', 'Department Supervisor', 1)
    ");

    echo "Database tables created successfully!\n";

} catch (Exception $e) {
    echo "Error setting up database: " . $e->getMessage() . "\n";
}