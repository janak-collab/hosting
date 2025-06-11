<?php
namespace App\Controllers;

use App\Services\AuthService;

class AdminController extends BaseController {
    private $authService;
    
    public function __construct() {
        $this->authService = new AuthService();
    }
    
    public function index() {
        $this->requireAuth();
        $this->redirect('/admin/dashboard');
    }
    
    public function dashboard() {
        $this->requireAuth();
        
        // Get dashboard data
        $data = [
            'user' => $this->getUser(),
            'stats' => $this->getDashboardStats()
        ];
        
        $this->view('admin.dashboard', $data);
    }
    
    public function showLogin() {
        error_log("ShowLogin called");
        
        // If already logged in, redirect to dashboard
        if ($this->getUser()) {
            $this->redirect('/admin/dashboard');
            return;
        }
        
        $data = [
            'error' => $_SESSION['login_error'] ?? null
        ];
        
        unset($_SESSION['login_error']);
        
        $this->view('admin.login', $data);
    }
    
    public function handleLogin() {
        error_log("HandleLogin called");
        
        // Don't use requireGuest here - anyone should be able to POST to login
        
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        
        error_log("Login attempt - Username: " . $username);
        
        if (empty($username) || empty($password)) {
            error_log("Empty username or password");
            $_SESSION['login_error'] = 'Please enter both username and password';
            $this->redirect('/admin/login');
            return;
        }
        
        $authenticated = $this->authService->authenticate($username, $password);
        error_log("Authentication result: " . ($authenticated ? "SUCCESS" : "FAILED"));
        
        if ($authenticated) {
            error_log("Redirecting to /admin/dashboard");
            // Set the user session data
            $_SESSION['user'] = [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['admin_username'],
                'role' => $_SESSION['user_role']
            ];
            $this->redirect('/admin/dashboard');
        } else {
            error_log("Authentication failed, redirecting back to login");
            $_SESSION['login_error'] = 'Invalid username or password';
            $this->redirect('/admin/login');
        }
    }
    
    public function logout() {
        $this->authService->logout();
        $this->redirect('/admin/login');
    }
    
    private function getDashboardStats() {
        // Placeholder for dashboard statistics
        return [
            'total_tickets' => 0,
            'open_tickets' => 0,
            'phone_notes' => 0
        ];
    }
}
