<?php
/**
 * Migration script to add user_id and start_date columns to employees table
 * This allows linking employees to user accounts and tracking their start dates
 */

require_once 'app/database.php';

try {
    $db = db_connect();
    
    echo "Adding user_id column to employees table...\n";
    
    // Check if user_id column already exists
    $stmt = $db->query("SHOW COLUMNS FROM employees LIKE 'user_id'");
    if ($stmt->rowCount() === 0) {
        $db->exec("
            ALTER TABLE employees 
            ADD COLUMN user_id INT NULL AFTER id,
            ADD CONSTRAINT fk_employee_user 
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
        ");
        echo "✓ Added user_id column\n";
    } else {
        echo "✓ user_id column already exists\n";
    }
    
    echo "Adding start_date column to employees table...\n";
    
    // Check if start_date column already exists
    $stmt = $db->query("SHOW COLUMNS FROM employees LIKE 'start_date'");
    if ($stmt->rowCount() === 0) {
        $db->exec("
            ALTER TABLE employees 
            ADD COLUMN start_date DATE NULL AFTER is_active
        ");
        echo "✓ Added start_date column\n";
    } else {
        echo "✓ start_date column already exists\n";
    }
    
    echo "\n✓ Migration completed successfully!\n";
    
} catch (Exception $e) {
    echo "✗ Error during migration: " . $e->getMessage() . "\n";
    exit(1);
}
