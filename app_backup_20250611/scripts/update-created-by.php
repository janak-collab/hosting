<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/bootstrap.php';

use App\Database\Connection;

$db = Connection::getInstance()->getConnection();

// Update empty created_by fields to a default user
$username = 'jvidyarthi'; // Change this to your actual username

$stmt = $db->prepare("UPDATE it_support_tickets SET created_by = :username 
WHERE created_by = '' OR created_by IS NULL");
$stmt->execute(['username' => $username]);

echo "Updated " . $stmt->rowCount() . " tickets\n";
