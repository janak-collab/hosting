<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Current user: " . ($_SERVER['PHP_AUTH_USER'] ?? 'Not set') . "\n";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "Script name: " . $_SERVER['SCRIPT_NAME'] . "\n\n";

// Test if constants are defined
echo "ROOT_PATH: " . (defined('ROOT_PATH') ? ROOT_PATH : 'Not defined') . "\n";
echo "APP_PATH: " . (defined('APP_PATH') ? APP_PATH : 'Not defined') . "\n\n";

// Check if files exist
$files = [
    '/app/src/Controllers/DashboardController.php',
    '/app/src/Controllers/FormsController.php',
    '/app/templates/views/dashboard/index.php'
];

foreach ($files as $file) {
    $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/..' . $file;
    echo "$file: " . (file_exists($fullPath) ? 'EXISTS' : 'NOT FOUND') . "\n";
}
