<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/bootstrap.php';

use App\Database\Connection;

try {
    $db = Connection::getInstance()->getConnection();
    
    echo "Running user management database migration...\n\n";
    
    // Read the SQL file
    $sql = file_get_contents('/tmp/user_management_tables.sql');
    
    // Split by semicolons to run each statement separately
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach ($statements as $statement) {
        if (!empty($statement)) {
            echo "Executing: " . substr($statement, 0, 50) . "...\n";
            $db->exec($statement);
        }
    }
    
    echo "\n✅ Migration completed successfully!\n";
    
    // Check if jvidyarthi was created
    $stmt = $db->query("SELECT * FROM users WHERE username = 'jvidyarthi'");
    $user = $stmt->fetch();
    
    if ($user) {
        echo "✅ User 'jvidyarthi' exists with role: " . $user['role'] . "\n";
    }
    
    // Show all users
    echo "\nCurrent users in database:\n";
    $stmt = $db->query("SELECT id, username, role, is_active FROM users");
    while ($row = $stmt->fetch()) {
        echo "  - {$row['username']} (role: {$row['role']}, active: {$row['is_active']})\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
