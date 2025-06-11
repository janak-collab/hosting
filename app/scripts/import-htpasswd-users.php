<?php
// Import existing htpasswd users to database
// Run this from: /home/gmpmus/app/scripts/import-htpasswd-users.php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/bootstrap.php';

use App\Database\Connection;

// Path to htpasswd file
$htpasswdPath = '/home/gmpmus/.htpasswds/passwd';

if (!file_exists($htpasswdPath)) {
    die("Error: htpasswd file not found at: $htpasswdPath\n");
}

try {
    $db = Connection::getInstance()->getConnection();
    
    // Read htpasswd file
    $lines = file($htpasswdPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $importedCount = 0;
    $skippedCount = 0;
    
    echo "Starting htpasswd import...\n";
    echo "Found " . count($lines) . " entries in htpasswd file\n\n";
    
    foreach ($lines as $line) {
        // Skip comments
        if (strpos($line, '#') === 0) {
            continue;
        }
        
        // Parse username:hash
        $parts = explode(':', $line, 2);
        if (count($parts) !== 2) {
            echo "Skipping invalid line: $line\n";
            continue;
        }
        
        $username = trim($parts[0]);
        
        // Check if user already exists
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        if ($stmt->fetch()) {
            echo "User '$username' already exists - skipping\n";
            $skippedCount++;
            continue;
        }
        
        // Determine default role
        $role = 'user'; // Default role
        $fullName = ucfirst($username); // Default full name
        
        // Special role assignments
        if ($username === 'jvidyarthi') {
            $role = 'super_admin';
            $fullName = 'System Administrator';
        } elseif ($username === 'admin') {
            $role = 'super_admin';
            $fullName = 'Administrator';
        }
        
        // Insert user
        $stmt = $db->prepare("
            INSERT INTO users (username, email, full_name, role, is_active, created_by, notes)
            VALUES (?, ?, ?, ?, 1, 1, 'Imported from htpasswd')
        ");
        
        if ($stmt->execute([$username, null, $fullName, $role])) {
            echo "✓ Imported user: $username (role: $role)\n";
            
            // Add audit log entry
            $userId = $db->lastInsertId();
            $auditStmt = $db->prepare("
                INSERT INTO user_audit_log (user_id, performed_by, action, new_value, ip_address)
                VALUES (?, 1, 'created', 'Imported from htpasswd', '127.0.0.1')
            ");
            $auditStmt->execute([$userId]);
            
            $importedCount++;
        } else {
            echo "✗ Failed to import user: $username\n";
        }
    }
    
    echo "\n========================================\n";
    echo "Import complete!\n";
    echo "Imported: $importedCount users\n";
    echo "Skipped: $skippedCount users (already existed)\n";
    echo "========================================\n";
    
    // Show current user stats
    $stmt = $db->query("
        SELECT role, COUNT(*) as count 
        FROM users 
        WHERE is_active = 1 
        GROUP BY role
    ");
    
    echo "\nCurrent active users by role:\n";
    while ($row = $stmt->fetch()) {
        echo "- " . ucfirst(str_replace('_', ' ', $row['role'])) . ": " . $row['count'] . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
