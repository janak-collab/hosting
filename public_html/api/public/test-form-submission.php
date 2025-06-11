<?php
require_once '../../app/vendor/autoload.php';
require_once '../../app/src/bootstrap.php';

// This simulates what the controller should do
session_start();

// Check current session
echo "Current session ID: " . session_id() . "\n";
echo "Session data:\n";
print_r($_SESSION);

// Set up as super_admin
$_SESSION['user_id'] = 1;
$_SESSION['user_username'] = 'jvidyarthi';
$_SESSION['user_role'] = 'super_admin';
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

echo "\n\nSession after setup:\n";
print_r($_SESSION);

// Now test the controller
use App\Controllers\UserManagementController;
$controller = new UserManagementController();

// Simulate POST data
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [
    'csrf_token' => $_SESSION['csrf_token'],
    'username' => 'formtest' . time(),
    'full_name' => 'Form Test User',
    'email' => 'formtest@gmpm.us',
    'role' => 'user',
    'is_active' => '1',
    'password' => 'FormTest123@Pass',
    'confirm_password' => 'FormTest123@Pass',
    'notes' => 'Testing form submission'
];

echo "\n\nTesting controller store method...\n";
try {
    // We can't actually call store() because it redirects, but we can test the validation
    $userModel = new \App\Models\User();
    $userService = new \App\Services\UserService();
    
    // Check if username exists
    $exists = $userModel->getByUsername($_POST['username']);
    echo "Username exists: " . ($exists ? 'YES' : 'NO') . "\n";
    
    // Validate password
    $validPassword = $userService->validatePassword($_POST['password']);
    echo "Password valid: " . ($validPassword ? 'YES' : 'NO') . "\n";
    
    // If all good, create user
    if (!$exists && $validPassword) {
        $userData = [
            'username' => $_POST['username'],
            'full_name' => $_POST['full_name'],
            'email' => $_POST['email'],
            'role' => $_POST['role'],
            'is_active' => $_POST['is_active'],
            'notes' => $_POST['notes'],
            'created_by' => $_SESSION['user_id']
        ];
        
        $userId = $userService->createUser($userData, $_POST['password'], $_SESSION['user_id']);
        echo "\nUser created with ID: $userId\n";
    }
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
