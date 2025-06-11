<?php
namespace App\Controllers;

/**
 * Base Controller with common functionality
 */
abstract class BaseController {
    protected $adminRoster;
    
    public function __construct() {
        // Load admin roster
        $this->loadAdminRoster();
    }
    
    /**
     * Load admin roster from config
     */
    protected function loadAdminRoster() {
        $configFile = dirname(__DIR__) . '/../config/admins.php';
        if (file_exists($configFile)) {
            $config = require $configFile;
            $this->adminRoster = $config['admin_users'] ?? [];
        } else {
            // Default admins if config doesn't exist
            $this->adminRoster = ['admin', 'jvidyarthi'];
        }
    }
    
    /**
     * Check if current HTTP Auth user is admin
     */
    protected function isAdmin($username = null) {
        if ($username === null) {
            $username = $_SERVER['PHP_AUTH_USER'] ?? '';
        }
        
        // Check if username is in admin roster
        return in_array($username, $this->adminRoster, true);
    }
    
    /**
     * Require admin access or show 403
     */
    protected function requireAdmin() {
        if (!$this->isAdmin()) {
            header('HTTP/1.0 403 Forbidden');
            die('Access denied. Admin privileges required.');
        }
    }
    
    /**
     * Get current user
     */
    protected function getCurrentUser() {
        return $_SERVER['PHP_AUTH_USER'] ?? 'Unknown';
    }
    
    /**
     * Check if user has specific role
     */
    protected function hasRole($role, $username = null) {
        if ($username === null) {
            $username = $_SERVER['PHP_AUTH_USER'] ?? '';
        }
        
        $configFile = dirname(__DIR__) . '/../config/admins.php';
        if (file_exists($configFile)) {
            $config = require $configFile;
            $userRoles = $config['user_roles'][$username] ?? [];
            return in_array($role, $userRoles);
        }
        
        return false;
    }
    
    /**
     * Set no-cache headers
     */
    protected function setNoCacheHeaders() {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
    }
}
