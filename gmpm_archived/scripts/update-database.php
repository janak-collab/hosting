<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/bootstrap.php';

use App\Database\Connection;

try {
    $db = Connection::getInstance()->getConnection();
    
    echo "Updating database schema...\n";
    
    // Add new columns to existing table
    $updates = [
        "ALTER TABLE it_support_tickets ADD COLUMN category VARCHAR(20) DEFAULT 'general' AFTER description",
        "ALTER TABLE it_support_tickets ADD COLUMN priority VARCHAR(20) DEFAULT 'normal' AFTER category",
        "ALTER TABLE it_support_tickets ADD COLUMN ip_address VARCHAR(45) NULL AFTER status",
        "ALTER TABLE it_support_tickets ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER 
created_at",
        "ALTER TABLE it_support_tickets ADD COLUMN resolved_at TIMESTAMP NULL AFTER updated_at",
        
        // Add indexes for performance
        "ALTER TABLE it_support_tickets ADD INDEX idx_status (status)",
        "ALTER TABLE it_support_tickets ADD INDEX idx_priority (priority)",
        "ALTER TABLE it_support_tickets ADD INDEX idx_created_at (created_at)"
    ];
    
    foreach ($updates as $sql) {
        try {
            $db->exec($sql);
            echo "✓ Executed: " . substr($sql, 0, 50) . "...\n";
        } catch (PDOException $e) {
            echo "- Skipped (may already exist): " . substr($sql, 0, 50) . "...\n";
        }
    }
    
    // Create comments table
    $sql = "CREATE TABLE IF NOT EXISTS it_ticket_comments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ticket_id INT NOT NULL,
        user_name VARCHAR(100) NOT NULL,
        comment TEXT NOT NULL,
        is_internal BOOLEAN DEFAULT FALSE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_ticket_id (ticket_id)
    )";
    
    $db->exec($sql);
    echo "✓ Created comments table\n";
    
    echo "\n✅ Database update complete!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
