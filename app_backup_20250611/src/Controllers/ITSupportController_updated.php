<?php
namespace App\Controllers;

use App\Models\ITTicket;
use App\Services\EmailService;
use App\Services\ValidationService;
use App\Services\RateLimiter;
use App\Services\AuthService;

class ITSupportController {
    private $ticketModel;
    private $validator;
    private $rateLimiter;
    private $authService;
    
    public function __construct() {
        $this->ticketModel = new ITTicket();
        $this->validator = new ValidationService();
        $this->rateLimiter = new RateLimiter();
        $this->authService = new AuthService();
    }
    
    /**
     * Display admin panel (for users with admin role)
     */
    public function showAdminPanel() {
        // Require admin role
        $this->authService->requireRole('admin');
        
        // Handle status updates
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticket_id'], $_POST['status'])) {
            $this->ticketModel->updateStatus($_POST['ticket_id'], $_POST['status']);
            header('Location: ' . $_SERVER['REQUEST_URI'] . '?updated=1');
            exit;
        }
        
        // Get tickets with optional filtering
        $status = $_GET['status'] ?? 'all';
        $tickets = $this->ticketModel->getAllWithStatus($status);
        $stats = $this->ticketModel->getStats();
        
        // Load admin view
        require_once __DIR__ . '/../../templates/views/admin/tickets.php';
    }
    
    // Remove handleAdminLogin and handleAdminLogout methods - not needed with htpasswd
    
    // Keep all other methods (showForm, handleSubmission, etc.)
}
