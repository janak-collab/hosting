<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/src/bootstrap.php';

use App\Database\Connection;

$db = Connection::getInstance()->getConnection();
$stmt = $db->query("SELECT username, password_hash, active FROM users WHERE username = 'admin'");
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo "Username: " . $user['username'] . "\n";
echo "Active: " . $user['active'] . "\n";
echo "Password hash length: " . strlen($user['password_hash']) . "\n";
echo "Hash starts with: " . substr($user['password_hash'], 0, 7) . "\n";

// Generate a new hash for comparison
$newHash = password_hash('admin123', PASSWORD_DEFAULT);
echo "\nNew hash would be: " . substr($newHash, 0, 7) . "...\n";
echo "New hash length: " . strlen($newHash) . "\n";
