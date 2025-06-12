<?php
namespace App\Controllers;
use App\Services\AuthService;

class AdminController extends BaseController {
    private $authService;
    
    public function __construct() {
        parent::__construct();
        $this->authService = new AuthService();
    }
    
    public function index() {
        // Use the correct method name from BaseController
        $this->requireAdmin();
        
        // Redirect to admin tickets (since that's what's working)
        header('Location: /admin/tickets');
        exit;
    }
    
    public function dashboard() {
        $this->requireAdmin();
        
        // Get dashboard data
        $data = [
            'user' => $this->getCurrentUser(), // Use correct method name
            'stats' => $this->getDashboardStats()
        ];
        
        // Load the view
        require_once __DIR__ . '/../../templates/views/admin/dashboard.php';
    }
    
    public function showLogin() {
        // If already logged in as admin, redirect
        if ($this->isAdmin()) {
            header('Location: /admin/tickets');
            exit;
        }
        
        // Show login form
        require_once __DIR__ . '/../../templates/views/admin/login.php';
    }
    
    private function getDashboardStats() {
        // Placeholder for dashboard stats
        return [
            'total_tickets' => 0,
            'open_tickets' => 0,
            'phone_notes' => 0
        ];
    }
}
