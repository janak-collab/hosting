<?php
namespace App\Controllers;

class PortalController extends BaseController {
    
    /**
     * Show the main portal page
     */
    public function index() {
        $user = $_SERVER['PHP_AUTH_USER'] ?? 'User';
        return $this->view('portal.index', compact('user'));
    }
    
    /**
     * Show system status page
     */
    public function status() {
        $checks = [
            'PHP Version' => phpversion(),
            'Session Active' => session_status() === PHP_SESSION_ACTIVE ? 'Yes' : 'No',
            'App Directory' => is_dir(APP_PATH) ? 'Found' : 'Missing',
            'Vendor Directory' => is_dir(APP_PATH . '/vendor') ? 'Found' : 'Missing',
            'Storage Directory' => is_dir(STORAGE_PATH) ? 'Found' : 'Missing',
            'Environment File' => file_exists(APP_PATH . '/.env') ? 'Found' : 'Missing',
            'Your IP' => $_SERVER['REMOTE_ADDR'],
            'Authenticated' => isset($_SERVER['PHP_AUTH_USER']) ? 'Yes (as ' . $_SERVER['PHP_AUTH_USER'] . ')' : 'No'
        ];
        
        return $this->view('status', compact('checks'));
    }
    
    /**
     * View tickets page - shows user's own tickets or redirects admin to admin panel
     */
    public function viewTickets() {
        // Set successful response status
        http_response_code(200);
        // Check if user is authenticated via HTTP Basic Auth
        $currentUser = $_SERVER['PHP_AUTH_USER'] ?? null;

        if (!$currentUser) {
            header('HTTP/1.1 401 Unauthorized');
            header('WWW-Authenticate: Basic realm="GMPM Portal"');
            die('Authentication required');
        }

        // If user is admin, redirect to admin panel
        if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in']) {
            header('Location: /admin/tickets');
            exit;
        }

        // For regular users, get their tickets
        try {
            $ticketModel = new \App\Models\ITTicket();
            $tickets = $ticketModel->getTicketsByUser($currentUser);
        } catch (\Exception $e) {
            error_log("Error fetching user tickets: " . $e->getMessage());
            $tickets = [];
        }
        
        // Load the user tickets view
        require_once __DIR__ . '/../../templates/views/user-tickets.php';
        exit; // Prevent router from continuing
        exit; // Stop execution after loading view
    }

    // Dummy method to maintain structure
    private function _unused_viewTickets_original() {
        $viewData = [];
        return $this->view('tickets.user-index', $viewData);
    }
}
