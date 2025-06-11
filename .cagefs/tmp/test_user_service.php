<?php
// Test UserService directly
require_once '/home/gmpmus/app/vendor/autoload.php';
require_once '/home/gmpmus/app/src/bootstrap.php';

use App\Services\UserService;
use App\Models\User;

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set up test session
$_SESSION['user_id'] = 1;
$_SESSION['user_username'] = 'jvidyarthi';
$_SESSION['user_role'] = 'super_admin';

echo "Testing UserService createUser method...\n\n";

$userService = new UserService();
$userModel = new User();

// Test data
$userData = [
    'username' => 'servicetest' . time(),
    'full_name' => 'Service Test User',
    'email' => 'servicetest@gmpm.us',
    'role' => 'user',
    'is_active' => 1,
    'notes' => 'Created via test script',
    'created_by' => 1
];
$password = 'ServiceTest123@Pass';
$createdBy = 1;

echo "Test Data:\n";
print_r($userData);
echo "Password: $password\n";
echo "Created By: $createdBy\n\n";

try {
    // Test password validation first
    echo "Testing password validation...\n";
    $validPassword = $userService->validatePassword($password);
    echo "Password valid: " . ($validPassword ? 'YES' : 'NO') . "\n\n";
    
    // Check if username exists
    echo "Checking if username exists...\n";
    $exists = $userModel->getByUsername($userData['username']);
    echo "Username exists: " . ($exists ? 'YES' : 'NO') . "\n\n";
    
    if (!$exists) {
        echo "Attempting to create user...\n";
        $userId = $userService->createUser($userData, $password, $createdBy);
        
        if ($userId) {
            echo "SUCCESS! User created with ID: $userId\n";
            
            // Verify in database
            $newUser = $userModel->getById($userId);
            echo "\nUser in database:\n";
            print_r([
                'id' => $newUser['id'],
                'username' => $newUser['username'],
                'full_name' => $newUser['full_name'],
                'email' => $newUser['email'],
                'role' => $newUser['role']
            ]);
            
            // Check htpasswd
            echo "\nChecking htpasswd file...\n";
            $htpasswdContent = file_get_contents('/home/gmpmus/.htpasswds/passwd');
            if (strpos($htpasswdContent, $userData['username']) !== false) {
                echo "User found in htpasswd!\n";
            } else {
                echo "User NOT found in htpasswd!\n";
            }
        } else {
            echo "FAILED to create user!\n";
        }
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
