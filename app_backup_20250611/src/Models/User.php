<?php
namespace App\Models;

use App\Database\Connection;
use PDO;

class User {
    private $db;
    private $table = 'users';
    
    public function __construct() {
        $this->db = Connection::getInstance()->getConnection();
    }
    
    /**
     * Get all users with optional role filtering
     */
    public function getAllUsers($role = null) {
        $sql = "SELECT u.*, 
                (SELECT COUNT(*) FROM user_audit_log 
                 WHERE user_id = u.id AND action = 'login_failed' 
                 AND created_at > DATE_SUB(NOW(), INTERVAL 24 HOUR)) as recent_failures,
                CASE WHEN u.locked_until > NOW() THEN 1 ELSE 0 END as is_locked
                FROM {$this->table} u
                WHERE 1=1";
        
        $params = [];
        
        if ($role && $role !== 'all') {
            $sql .= " AND u.role = :role";
            $params[':role'] = $role;
        }
        
        $sql .= " ORDER BY u.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get user by ID
     */
    public function getById($id) {
        $sql = "SELECT *, 
                CASE WHEN locked_until > NOW() THEN 1 ELSE 0 END as is_locked
                FROM {$this->table} 
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get user by username
     */
    public function getByUsername($username) {
        $sql = "SELECT *, 
                CASE WHEN locked_until > NOW() THEN 1 ELSE 0 END as is_locked
                FROM {$this->table} 
                WHERE username = :username";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':username' => $username]);
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    /**
     * Create new user
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                (username, email, full_name, role, is_active, notes, created_by) 
                VALUES 
                (:username, :email, :full_name, :role, :is_active, :notes, :created_by)";
        
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            ':username' => $data['username'],
            ':email' => $data['email'] ?: null,
            ':full_name' => $data['full_name'],
            ':role' => $data['role'],
            ':is_active' => $data['is_active'] ?? 1,
            ':notes' => $data['notes'] ?: null,
            ':created_by' => $data['created_by']
        ]);
        
        return $result ? $this->db->lastInsertId() : false;
    }
    
    /**
     * Update user
     */
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET 
                email = :email,
                full_name = :full_name,
                role = :role,
                is_active = :is_active,
                notes = :notes
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':email' => $data['email'] ?: null,
            ':full_name' => $data['full_name'],
            ':role' => $data['role'],
            ':is_active' => $data['is_active'] ?? 1,
            ':notes' => $data['notes'] ?: null,
            ':id' => $id
        ]);
    }
    
    /**
     * Soft delete user (mark as inactive)
     */
    public function softDelete($id) {
        $sql = "UPDATE {$this->table} SET is_active = 0 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    /**
     * Check if username exists
     */
    public function usernameExists($username, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE username = :username";
        $params = [':username' => $username];
        
        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
            $params[':exclude_id'] = $excludeId;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Record failed login attempt
     */
    public function recordFailedLogin($username, $ip) {
        // Get user
        $user = $this->getByUsername($username);
        if (!$user) return;
        
        // Increment failed attempts
        $sql = "UPDATE {$this->table} SET failed_attempts = failed_attempts + 1 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $user['id']]);
        
        // Check if should lock
        $user['failed_attempts']++;
        if ($user['failed_attempts'] >= 5) {
            $sql = "UPDATE {$this->table} SET locked_until = DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $user['id']]);
        }
    }
    
    /**
     * Reset failed login attempts
     */
    public function resetFailedAttempts($userId) {
        $sql = "UPDATE {$this->table} SET failed_attempts = 0, locked_until = NULL WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $userId]);
    }
    
    /**
     * Check if user is locked
     */
    public function isLocked($username) {
        $user = $this->getByUsername($username);
        if (!$user) return false;
        
        return $user['is_locked'] == 1;
    }
    
    /**
     * Unlock user
     */
    public function unlock($id) {
        $sql = "UPDATE {$this->table} SET failed_attempts = 0, locked_until = NULL WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    /**
     * Get user stats
     */
    public function getStats() {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN role = 'super_admin' THEN 1 ELSE 0 END) as super_admins,
                SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) as admins,
                SUM(CASE WHEN role = 'user' THEN 1 ELSE 0 END) as users,
                SUM(CASE WHEN is_active = 1 THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive,
                SUM(CASE WHEN locked_until > NOW() THEN 1 ELSE 0 END) as locked
                FROM {$this->table}";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
