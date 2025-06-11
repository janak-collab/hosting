<?php
namespace App\Models;

use App\Database\Connection;
use PDO;

class ITTicket {
    private $db;
    
    public function __construct() {
        $this->db = Connection::getInstance()->getConnection();
    }
    
    public function create($data) {
        // First, create table if it doesn't exist
        $this->createTableIfNotExists();
    
        $sql = "INSERT INTO it_support_tickets 
                (name, location, description, category, priority, status, ip_address, created_by, created_at) 
                VALUES (:name, :location, :description, :category, :priority, 'open', :ip_address, :created_by, NOW())";
    
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':name' => $data['name'],
            ':location' => $data['location'],
            ':description' => $data['description'],
            ':category' => $data['category'] ?? 'general',
            ':priority' => $data['priority'] ?? 'normal',
            ':ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            ':created_by' => $_SERVER['PHP_AUTH_USER'] ?? $_SERVER['REMOTE_USER'] ?? 'Unknown'
        ]);

            return $this->db->lastInsertId();
    }

    private function createTableIfNotExists() {
        $sql = "CREATE TABLE IF NOT EXISTS it_support_tickets (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            location VARCHAR(50) NOT NULL,
            description TEXT NOT NULL,
            category VARCHAR(20) DEFAULT 'general',
            priority VARCHAR(20) DEFAULT 'normal',
            status VARCHAR(20) DEFAULT 'open',
            ip_address VARCHAR(45) NULL,
            created_by VARCHAR(100) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE 
CURRENT_TIMESTAMP,
            resolved_at TIMESTAMP NULL,
            INDEX idx_status (status),
            INDEX idx_priority (priority),
            INDEX idx_created_at (created_at)
        )";
    
        $this->db->exec($sql);
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM it_support_tickets ORDER BY 
            FIELD(priority, 'critical', 'high', 'normal', 'low'),
            created_at DESC");
        return $stmt->fetchAll();
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM it_support_tickets WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    public function updateStatus($id, $status) {
        $sql = "UPDATE it_support_tickets SET status = :status";
        
        // If marking as resolved, set resolved_at timestamp
        if ($status === 'resolved' || $status === 'closed') {
            $sql .= ", resolved_at = NOW()";
        }
        
        $sql .= " WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':status' => $status,
            ':id' => $id
        ]);
    }
    
    public function updatePriority($id, $priority) {
        $sql = "UPDATE it_support_tickets SET priority = :priority WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':priority' => $priority,
            ':id' => $id
        ]);
    }
    
    public function getAllWithStatus($status = 'all') {
        if ($status === 'all') {
            return $this->getAll();
        }
        
        $sql = "SELECT * FROM it_support_tickets WHERE status = :status 
                ORDER BY FIELD(priority, 'critical', 'high', 'normal', 'low'), 
                created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':status' => $status]);
        return $stmt->fetchAll();
    }
    
    public function getStats() {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'open' THEN 1 ELSE 0 END) as open,
                SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress,
                SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved,
                SUM(CASE WHEN priority = 'critical' THEN 1 ELSE 0 END) as critical,
                SUM(CASE WHEN priority = 'high' THEN 1 ELSE 0 END) as high
                FROM it_support_tickets";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetch();
    }
    
    public function addComment($ticketId, $userName, $comment, $isInternal = false) {
        // Create comments table if not exists
        $this->createCommentsTableIfNotExists();
        
        $sql = "INSERT INTO it_ticket_comments 
                (ticket_id, user_name, comment, is_internal) 
                VALUES (:ticket_id, :user_name, :comment, :is_internal)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':ticket_id' => $ticketId,
            ':user_name' => $userName,
            ':comment' => $comment,
            ':is_internal' => $isInternal ? 1 : 0
        ]);
    }
    
    public function getComments($ticketId) {
        $this->createCommentsTableIfNotExists();
        
        $sql = "SELECT * FROM it_ticket_comments 
                WHERE ticket_id = :ticket_id 
                ORDER BY created_at ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':ticket_id' => $ticketId]);
        return $stmt->fetchAll();
    }
    
    private function createCommentsTableIfNotExists() {
        $sql = "CREATE TABLE IF NOT EXISTS it_ticket_comments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            ticket_id INT NOT NULL,
            user_name VARCHAR(100) NOT NULL,
            comment TEXT NOT NULL,
            is_internal BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_ticket_id (ticket_id)
        )";
        
        $this->db->exec($sql);
    }
    
    public function searchTickets($searchTerm) {
        $sql = "SELECT * FROM it_support_tickets 
                WHERE name LIKE :search 
                OR description LIKE :search 
                OR id = :id 
                ORDER BY created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':search' => '%' . $searchTerm . '%',
            ':id' => intval($searchTerm)
        ]);
        return $stmt->fetchAll();
    }

    /**
     * Get all tickets submitted by a specific user
     */
    public function getTicketsByUser($username) {
        $sql = "SELECT * FROM it_support_tickets 
                WHERE created_by = name = :username 
                ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);
        return $stmt->fetchAll();
    }
}
