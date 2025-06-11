#!/bin/bash

# First, let's check the exact line causing the issue
echo "=== Checking UserService line 144 ==="
sed -n '140,150p' /home/gmpmus/app/src/Services/UserService.php

# Fix the password validation to handle null/empty passwords
echo -e "\n=== Fixing password validation ==="
cat > /tmp/fix_password_validation.php << 'INNEREOF'
    private function validatePassword($password) {
        // Check if password is null or empty first
        if (empty($password)) {
            throw new Exception('Password is required');
        }
        
        // Check minimum length
        if (strlen($password) < 12) {
            throw new Exception('Password must be at least 12 characters long');
        }
        
        // Check for required character types
        if (!preg_match('/[A-Z]/', $password)) {
            throw new Exception('Password must contain at least one uppercase letter');
        }
        
        if (!preg_match('/[a-z]/', $password)) {
            throw new Exception('Password must contain at least one lowercase letter');
        }
        
        if (!preg_match('/[0-9]/', $password)) {
            throw new Exception('Password must contain at least one number');
        }
        
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            throw new Exception('Password must contain at least one special character');
        }
        
        return true;
    }
INNEREOF

# Backup and update the UserService
cp /home/gmpmus/app/src/Services/UserService.php /home/gmpmus/app/src/Services/UserService.php.backup2

# Find and replace the validatePassword method
echo -e "\n=== Updating UserService validatePassword method ==="
# This is complex, let's do it with PHP
php << 'PHP'
<?php
$file = '/home/gmpmus/app/src/Services/UserService.php';
$content = file_get_contents($file);

// Find the validatePassword method
$pattern = '/private function validatePassword\(\$password\)\s*\{[^}]+\}/s';
$replacement = file_get_contents('/tmp/fix_password_validation.php');

if (preg_match($pattern, $content)) {
    $content = preg_replace($pattern, trim($replacement), $content);
    file_put_contents($file, $content);
    echo "✓ validatePassword method updated\n";
} else {
    echo "✗ Could not find validatePassword method\n";
}
PHP

# Also disable error display in bootstrap.php to prevent warnings in JSON
echo -e "\n=== Disabling error display in production ==="
sed -i 's/ini_set('\''display_errors'\'', 1);/ini_set('\''display_errors'\'', 0);/g' /home/gmpmus/app/src/bootstrap.php

# Create a working create endpoint
echo -e "\n=== Creating working create endpoint ==="
cat > /home/gmpmus/public_html/api/users/create-user.php << 'INNEREOF'
<?php
// Disable error display for clean JSON
error_reporting(E_ALL);
ini_set('display_errors', 0);

session_start();
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

require_once '/home/gmpmus/app/vendor/autoload.php';
require_once '/home/gmpmus/app/src/bootstrap.php';

use App\Services\AuthService;
use App\Services\UserService;

try {
    $authService = new AuthService();
    $authService->requireRole('super_admin');
    
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Method not allowed', 405);
    }
    
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        throw new Exception('Invalid JSON input', 400);
    }
    
    // Validate required fields
    $required = ['username', 'password', 'full_name', 'email'];
    foreach ($required as $field) {
        if (empty($input[$field])) {
            throw new Exception("Field '$field' is required", 400);
        }
    }
    
    $userService = new UserService();
    $result = $userService->createUser($input, $_SESSION['user_id'] ?? 1);
    
    echo json_encode(['success' => true, 'data' => $result]);
    
} catch (Exception $e) {
    http_response_code($e->getCode() ?: 400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
INNEREOF

# Test the new create endpoint
echo -e "\n=== Testing new create endpoint ==="
TIMESTAMP=$(date +%s)
curl -X POST https://gmpm.us/api/users/create-user.php \
  -u "$USER:$PASS" \
  -H "Content-Type: application/json" \
  -d '{
    "username": "apiuser'$TIMESTAMP'",
    "password": "ApiUser123!@#",
    "full_name": "API Created User",
    "email": "api'$TIMESTAMP'@test.com",
    "role": "user"
  }' | jq .
