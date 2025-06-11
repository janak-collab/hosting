<?php
require_once '/home/gmpmus/app/vendor/autoload.php';

// Load .env file
if (file_exists('/home/gmpmus/app/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable('/home/gmpmus/app');
    $dotenv->load();
}

echo "DB Settings:\n";
echo "DB_HOST: " . ($_ENV['DB_HOST'] ?? 'not set') . "\n";
echo "DB_NAME: " . ($_ENV['DB_NAME'] ?? 'not set') . "\n";
echo "DB_USERNAME: " . ($_ENV['DB_USERNAME'] ?? 'not set') . "\n";
echo "DB_PASSWORD: " . (isset($_ENV['DB_PASSWORD']) ? '***set***' : 'not set') . "\n";

// Try to connect
try {
    $dsn = "mysql:host=" . ($_ENV['DB_HOST'] ?? 'localhost') . ";dbname=" . ($_ENV['DB_NAME'] ?? '');
    $pdo = new PDO($dsn, $_ENV['DB_USERNAME'] ?? '', $_ENV['DB_PASSWORD'] ?? '');
    echo "\nDatabase connection successful!\n";
    
    // Check if users table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->fetch()) {
        echo "Users table exists\n";
        
        // Count users
        $count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        echo "Number of users: $count\n";
    } else {
        echo "Users table does not exist\n";
    }
} catch (PDOException $e) {
    echo "\nDatabase connection failed: " . $e->getMessage() . "\n";
}
