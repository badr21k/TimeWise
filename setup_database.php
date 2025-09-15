
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

    // Create users table
    $db->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            is_admin TINYINT(1) DEFAULT 0,
            full_name VARCHAR(200) NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Create login_logs table  
    $db->exec("
        CREATE TABLE IF NOT EXISTS login_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(100) NOT NULL,
            status ENUM('good', 'bad') NOT NULL,
            timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
            ip_address VARCHAR(45) NULL,
            INDEX idx_username (username),
            INDEX idx_timestamp (timestamp)
        )
    ");

    // Create chat_tokens table for secure authentication
    $db->exec("
        CREATE TABLE IF NOT EXISTS chat_tokens (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            token_hash VARCHAR(255) NOT NULL UNIQUE,
            expires_at DATETIME NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_token_hash (token_hash),
            INDEX idx_expires_at (expires_at)
        )
    ");

    // Insert default departments
    $defaultDepartments = ['General', 'Support', 'Administration', 'Operations'];
    foreach ($defaultDepartments as $dept) {
        $db->prepare("INSERT IGNORE INTO departments (name, is_active, created_at) VALUES (?, 1, NOW())")->execute([$dept]);
    }

    echo "Database setup completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error setting up database: " . $e->getMessage() . "\n";
}
