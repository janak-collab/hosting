<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $connection;
    
    private function __construct()
    {
        try {
            // Load environment variables
            Env::load();
            
            $host = Env::get('DB_HOST', 'localhost');
            $database = Env::get('DB_DATABASE');
            $username = Env::get('DB_USERNAME');
            $password = Env::get('DB_PASSWORD');
            $charset = Env::get('DB_CHARSET', 'utf8mb4');
            
            if (!$database || !$username || !$password) {
                throw new \Exception('Database credentials not properly configured in .env file');
            }
            
            $dsn = "mysql:host=$host;dbname=$database;charset=$charset";
            
            $this->connection = new PDO(
                $dsn,
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw new \Exception("Database connection failed. Check error logs.");
        }
    }
    
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->connection;
    }
    
    public function prepare($sql)
    {
        return $this->connection->prepare($sql);
    }
    
    public function query($sql, $params = [])
    {
        $stmt = $this->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
}
