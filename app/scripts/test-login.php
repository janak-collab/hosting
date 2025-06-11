<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/bootstrap.php';

$authService = new \App\Services\AuthService();

// Test with the admin credentials
$username = 'admin';
$password = 'admin123';  // Use the password from create-admin.php

echo "Testing login for: $username\n";

if ($authService->authenticate($username, $password)) {
    echo "✓ Authentication successful!\n";
    echo "Session data: ";
    print_r($_SESSION);
} else {
    echo "✗ Authentication failed\n";
}
