<?php
session_start();
require_once '/home/gmpmus/app/vendor/autoload.php';
require_once '/home/gmpmus/app/src/bootstrap.php';

use App\Services\AuthService;

echo "=== Login Debug ===\n";
echo "Session ID: " . session_id() . "\n";
echo "Session status: " . session_status() . "\n";

// Test login
$auth = new AuthService();
$result = $auth->authenticate('admin', 'admin123');

echo "Auth result: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";
echo "Session after auth:\n";
print_r($_SESSION);

// Check if logged in
echo "\nIs authenticated: " . ($auth->isAuthenticated() ? 'YES' : 'NO') . "\n";
echo "Current user:\n";
print_r($auth->getCurrentUser());
