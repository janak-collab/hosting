<?php
namespace App\Models;

use App\Database\Connection;
use PDO;

class UserAuditLog {
    private $db;
    private $table = 'user_audit_log';
    
    public function __construct() {
        $this->db = Connection::getInstance()->getConnection();
    }
    
    /**
     * Log an action
     */
    public function log($userId, $action, $oldValue = null, $newValue = null, $performedBy = null) {
        $sql = "INSERT INTO {$this->table} 
                (user_id, performed_by, action, old_value, new_value, ip_address, user_agent) 
                VALUES 
                (:user_id, :performed_by, :action, :old_value, :new_value, :ip_address, :user_agent)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_id' => $userId,
            ':performed_by' => $performedBy ?: ($_SESSION['user_id'] ?? null),
            ':action' => $action,
            ':old_value' => $oldValue,
            ':new_value' => $newValue,
            ':ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            ':user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
    }
    
    /**
     * Get logs for a specific user
     */
    public function getLogsForUser($userId, $limit = 50) {
        $sql = "SELECT al.*, 
                u1.username as target_username,
                u1.full_name as target_full_name,
                u2.username as performed_by_username,
                u2.full_name as performed_by_full_name
                FROM {$this->table} al
                LEFT JOIN users u1 ON al.user_id = u1.id
                LEFT JOIN users u2 ON al.performed_by = u2.id
                WHERE al.user_id = :user_id
                ORDER BY al.created_at DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get recent activity across all users
     */
    public function getRecentActivity($limit = 100) {
        $sql = "SELECT al.*, 
                u1.username as target_username,
                u1.full_name as target_full_name,
                u2.username as performed_by_username,
                u2.full_name as performed_by_full_name
                FROM {$this->table} al
                LEFT JOIN users u1 ON al.user_id = u1.id
                LEFT JOIN users u2 ON al.performed_by = u2.id
                ORDER BY al.created_at DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get activity by action type
     */
    public function getByAction($action, $limit = 50) {
        $sql = "SELECT al.*, 
                u1.username as target_username,
                u1.full_name as target_full_name,
                u2.username as performed_by_username,
                u2.full_name as performed_by_full_name
                FROM {$this->table} al
                LEFT JOIN users u1 ON al.user_id = u1.id
                LEFT JOIN users u2 ON al.performed_by = u2.id
                WHERE al.action = :action
                ORDER BY al.created_at DESC
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':action', $action);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get failed login attempts for a user in the last N hours
     */
    public function getRecentFailedLogins($userId, $hours = 24) {
        $sql = "SELECT COUNT(*) 
                FROM {$this->table} 
                WHERE user_id = :user_id 
                AND action = 'login_failed' 
                AND created_at > DATE_SUB(NOW(), INTERVAL :hours HOUR)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':hours', $hours, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchColumn();
    }
    
    /**
     * Clean up old logs (optional, for maintenance)
     */
    public function cleanOldLogs($days = 90) {
        $sql = "DELETE FROM {$this->table} 
                WHERE created_at < DATE_SUB(NOW(), INTERVAL :days DAY)
                AND action NOT IN ('created', 'deleted', 'role_changed')";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':days', $days, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->rowCount();
    }
    
    /**
     * Get activity summary for dashboard
     */
    public function getActivitySummary() {
        $sql = "SELECT 
                COUNT(*) as total_events,
                COUNT(DISTINCT user_id) as affected_users,
                COUNT(DISTINCT performed_by) as active_admins,
                SUM(CASE WHEN action = 'created' THEN 1 ELSE 0 END) as users_created,
                SUM(CASE WHEN action = 'updated' THEN 1 ELSE 0 END) as users_updated,
                SUM(CASE WHEN action = 'deleted' THEN 1 ELSE 0 END) as users_deleted,
                SUM(CASE WHEN action = 'login_failed' THEN 1 ELSE 0 END) as failed_logins,
                SUM(CASE WHEN action = 'password_changed' THEN 1 ELSE 0 END) as password_changes,
                SUM(CASE WHEN action = 'role_changed' THEN 1 ELSE 0 END) as role_changes
                FROM {$this->table}
                WHERE created_at > DATE_SUB(NOW(), INTERVAL 30 DAY)";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
