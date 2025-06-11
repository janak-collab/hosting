<?php
namespace App\Services;

use App\Database\Connection;
use PDO;

class AuthService {
    private $db;
    
    public function __construct() {
        $this->db = Connection::getInstance()->getConnection();
        
        // Auto-initialize session for HTTP Basic Auth users
        if (isset($_SERVER['PHP_AUTH_USER']) && session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // If user is authenticated via HTTP Basic but no session exists, create it
        if (isset($_SERVER['PHP_AUTH_USER']) && !isset($_SESSION['user_id'])) {
            $this->initializeSessionFromHttpAuth();
        }
    }
    
    /**
     * Initialize session from HTTP Basic Auth
     */
    private function initializeSessionFromHttpAuth() {
        $user = $this->getCurrentUser();
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['admin_logged_in'] = in_array($user['role'], ['admin', 'super_admin']);
        }
    }

    /**
     * Get the current authenticated user from HTTP Basic Auth
     * and load their database profile
     */
    public function getCurrentUser() {
        // Get username from HTTP Basic Auth
        $username = $_SERVER['PHP_AUTH_USER'] ?? $_SERVER['REMOTE_USER'] ?? null;

        if (!$username) {
            return null;
        }

        // Load user from database
        $stmt = $this->db->prepare("
            SELECT id, username, email, full_name, role, active
            FROM users
            WHERE username = :username AND active = 1
        ");
        
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // If user doesn't exist in database but authenticated via htpasswd, create basic record
        if (!$user && $username) {
            $this->createUserFromHtpasswd($username);
            // Re-fetch the newly created user
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        return $user;
    }

    /**
     * Check if current user has a specific role
     */
    public function hasRole($requiredRole) {
        // First check session
        if (isset($_SESSION['user_role'])) {
            $userRole = $_SESSION['user_role'];
        } else {
            // Fall back to checking current user
            $user = $this->getCurrentUser();
            if (!$user) {
                return false;
            }
            $userRole = $user['role'] ?? 'user';
        }
        
        $roleHierarchy = [
            'user' => 1,
            'staff' => 2,
            'provider' => 3,
            'admin' => 4,
            'super_admin' => 5
        ];
        
        $userLevel = $roleHierarchy[$userRole] ?? 0;
        $requiredLevel = $roleHierarchy[$requiredRole] ?? 999;
        
        return $userLevel >= $requiredLevel;
    }

    /**
     * Require a minimum role or die with 403
     */
    public function requireRole($minRole) {
        if (!$this->hasRole($minRole)) {
            http_response_code(403);
            die('Access denied. You need ' . $minRole . ' privileges to access this area.');
        }
        return true;
    }

    /**
     * Check if user is admin or super_admin
     */
    public function isAdmin() {
        return $this->hasRole('admin');
    }

    /**
     * Create a basic user record for htpasswd users not in database
     */
    private function createUserFromHtpasswd($username) {
        $stmt = $this->db->prepare("
            INSERT INTO users (username, role, active, created_at)
            VALUES (:username, 'user', 1, NOW())
        ");
        $stmt->execute(['username' => $username]);
    }
    
    /**
     * Logout user
     */
    public function logout() {
        $_SESSION = [];
        session_destroy();
    }
    
    /**
     * Authenticate user (for form-based login)
     */
    public function authenticate($username, $password) {
        $stmt = $this->db->prepare("
            SELECT id, username, password_hash, role 
            FROM users 
            WHERE username = :username AND active = 1
        ");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }
        
        // Create session
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $user['username'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        
        return true;
    }
}
