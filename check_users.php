<?php
require_once '/home/gmpmus/app/vendor/autoload.php';
require_once '/home/gmpmus/app/src/bootstrap.php';

use App\Models\User;

try {
    $user = new User();
    $users = $user->getAllUsers();
    
    echo "=== Current Users in Database ===\n";
    foreach($users as $u) {
        echo sprintf("Username: %-15s | Role: %-12s | Active: %s\n", 
            $u['username'], 
            $u['role'], 
            $u['is_active'] ? 'Yes' : 'No'
        );
    }
    
    // Check jvidyarthi specifically
    $jv = $user->getByUsername('jvidyarthi');
    if ($jv) {
        echo "\n=== jvidyarthi Details ===\n";
        echo "ID: " . $jv['id'] . "\n";
        echo "Role: " . $jv['role'] . "\n";
        echo "Active: " . ($jv['is_active'] ? 'Yes' : 'No') . "\n";
    } else {
        echo "\njvidyarthi not found in database!\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
