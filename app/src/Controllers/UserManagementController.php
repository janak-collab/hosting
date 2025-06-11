<?php
namespace App\Controllers;

use App\Models\User;
use App\Models\UserAuditLog;
use App\Services\AuthService;
use App\Services\UserService;
use App\Services\ValidationService;

class UserManagementController {
    private $userModel;
    private $auditLog;
    private $authService;
    private $userService;
    private $validator;
    
    public function __construct() {
        $this->userModel = new User();
        $this->auditLog = new UserAuditLog();
        $this->authService = new AuthService();
        $this->userService = new UserService();
        $this->validator = new ValidationService();
    }
    
    /**
     * Display user list
     */
    public function index() {
        // Check authorization
        if (!$this->authService->requireRole('super_admin')) {
            header('Location: /');
            exit;
        }
        
        // Set headers
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        
        // Generate CSRF token
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        // Load the view
        require_once dirname(__DIR__) . '/../templates/views/admin/users/index.php';
    }
    
    /**
     * Show create user form
     */
    public function create() {
        // Check authorization
        if (!$this->authService->requireRole('super_admin')) {
            header('Location: /');
            exit;
        }
        
        // Set headers
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        
        // Generate CSRF token
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        // Load the view
        require_once dirname(__DIR__) . '/../templates/views/admin/users/create.php';
    }
    
    /**
     * Show edit user form
     */
    public function edit($id) {
        // Check authorization
        if (!$this->authService->requireRole('super_admin')) {
            header('Location: /');
            exit;
        }
        
        // Set headers
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        
        // Get user data
        $user = $this->userModel->getById($id);
        if (!$user) {
            header('Location: /admin/users');
            exit;
        }
        
        // Get recent audit log entries
        $user['audit_log'] = $this->auditLog->getLogsForUser($id, 10);
        
        // Generate CSRF token
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        // Load the view
        require_once dirname(__DIR__) . '/../templates/views/admin/users/edit.php';
    }
    
    /**
     * Handle user creation (form submission)
     */
    public function store() {
        // This will be handled by the API endpoint
        header('Location: /admin/users');
    }
    
    /**
     * Handle user update (form submission)
     */
    public function update($id) {
        // This will be handled by the API endpoint
        header('Location: /admin/users');
    }
    
    /**
     * Handle user deletion
     */
    public function delete($id) {
        // This will be handled by the API endpoint
        header('Location: /admin/users');
    }
    
    /**
     * Show user activity log
     */
    public function activity($id) {
        // Check authorization
        if (!$this->authService->requireRole('super_admin')) {
            header('Location: /');
            exit;
        }
        
        $user = $this->userModel->getById($id);
        if (!$user) {
            header('Location: /admin/users');
            exit;
        }
        
        $activityLog = $this->auditLog->getLogsForUser($id, 100);
        
        // For now, redirect to edit page
        header('Location: /admin/users/edit/' . $id);
    }
    
    /**
     * API: Check username availability
     */
    public function checkUsername() {
        header('Content-Type: application/json');
        
        $username = $_GET['username'] ?? '';
        
        if (empty($username)) {
            $this->jsonResponse(['success' => false, 'error' => 'Username is required']);
            return;
        }
        
        $available = !$this->userService->userExists($username);
        
        $this->jsonResponse([
            'success' => true,
            'available' => $available
        ]);
    }
    
    /**
     * API: Unlock user account
     */
    public function unlock($id) {
        header('Content-Type: application/json');
        
        // Check authorization
        if (!$this->authService->requireRole('super_admin')) {
            $this->jsonResponse(['success' => false, 'error' => 'Unauthorized'], 403);
            return;
        }
        
        try {
            $this->userModel->unlockUser($id);
            
            // Log the action
            $this->auditLog->log($id, 'unlocked', null, null, $_SESSION['user_id'] ?? null);
            
            $this->jsonResponse(['success' => true]);
        } catch (\Exception $e) {
            $this->jsonResponse(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    /**
     * API: Sync with htpasswd
     */
    public function syncHtpasswd() {
        header('Content-Type: application/json');
        
        // Check authorization
        if (!$this->authService->requireRole('super_admin')) {
            $this->jsonResponse(['success' => false, 'error' => 'Unauthorized'], 403);
            return;
        }
        
        try {
            $result = $this->userService->syncFromHtpasswd();
            $this->jsonResponse([
                'success' => true,
                'imported' => $result['imported'],
                'updated' => $result['updated']
            ]);
        } catch (\Exception $e) {
            $this->jsonResponse(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    /**
     * Send JSON response
     */
    private function jsonResponse($data, $status = 200) {
        http_response_code($status);
        echo json_encode($data);
        exit;
    }
}
