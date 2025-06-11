<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/bootstrap.php';

use App\Database\Connection;

$db = Connection::getInstance()->getConnection();
$stmt = $db->query("SELECT DISTINCT created_by, COUNT(*) as count FROM 
it_support_tickets GROUP BY created_by");

echo "=== Created By Summary ===\n";
while ($row = $stmt->fetch()) {
    echo "User: '{$row['created_by']}' has {$row['count']} tickets\n";
}

echo "\n=== Current Auth Info ===\n";
echo "PHP_AUTH_USER: " . ($_SERVER['PHP_AUTH_USER'] ?? 'not set') . "\n";
echo "REMOTE_USER: " . ($_SERVER['REMOTE_USER'] ?? 'not set') . "\n";
