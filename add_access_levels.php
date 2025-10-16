<?php
/**
 * Migration: Add access_level to users table
 * Converts is_admin (0/1) to access_level (0-4)
 */

require_once 'app/database.php';

try {
    $db = db_connect();
    
    echo "Starting access level migration...\n";
    
    // Check if access_level column exists
    $checkCol = $db->query("SHOW COLUMNS FROM users LIKE 'access_level'");
    $colExists = $checkCol->fetch() !== false;
    
    if (!$colExists) {
        echo "Adding access_level column to users table...\n";
        
        // Add access_level column
        $db->exec("
            ALTER TABLE users 
            ADD COLUMN access_level TINYINT DEFAULT 1 AFTER is_admin
        ");
        
        // Migrate existing is_admin values
        // is_admin = 1 becomes access_level = 4 (Department Admin)
        // is_admin = 0 becomes access_level = 1 (Regular user)
        $db->exec("
            UPDATE users 
            SET access_level = CASE 
                WHEN is_admin = 1 THEN 4 
                ELSE 1 
            END
        ");
        
        echo "✓ access_level column added and migrated\n";
    } else {
        echo "✓ access_level column already exists\n";
    }
    
    // Optional: Remove is_admin column after confirming migration
    // Uncomment below if you want to remove is_admin completely
    // $db->exec("ALTER TABLE users DROP COLUMN is_admin");
    // echo "✓ Removed is_admin column\n";
    
    echo "\nMigration completed successfully!\n";
    echo "\nAccess Levels:\n";
    echo "  0 = Inactive (cannot login)\n";
    echo "  1 = Regular User\n";
    echo "  2 = Power User\n";
    echo "  3 = Team Lead\n";
    echo "  4 = Department Admin\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
