<?php
namespace App\Controllers;

use App\Models\ITTicket;
use App\Models\PhoneNote;
use App\Models\User;
use App\Services\AuthService;

class DashboardController {
    private $ticketModel;
    private $phoneNoteModel;
    private $userModel;
    private $authService;

    public function __construct() {
        $this->authService = new AuthService();
        $this->userModel = new User();
    }

    public function index() {
        // Check authentication
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="GMPM Portal"');
            header('HTTP/1.0 401 Unauthorized');
            exit;
        }

        // Set no-cache headers
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');

        // Get current username and user data
        $currentUser = $_SERVER['PHP_AUTH_USER'];
        
        // Get user from database
        $userData = $this->userModel->getByUsername($currentUser);
        $userRole = $userData['role'] ?? 'user';
        $isAdmin = in_array($userRole, ['admin', 'super_admin']);
        $isSuperAdmin = $userRole === 'super_admin';

        // Store role in session for other components
        $_SESSION['user_role'] = $userRole;
        $_SESSION['user_id'] = $userData['id'] ?? null;

        // Get statistics
        $stats = $this->getStatistics($isAdmin);

        // Get admin-specific data if admin
        $adminData = [];
        if ($isAdmin) {
            // Initialize models only when needed
            $this->ticketModel = new ITTicket();
            $this->phoneNoteModel = new PhoneNote();
            $adminData = $this->getAdminData();
        }

        // Load the dashboard view
        require_once dirname(__DIR__) . '/../templates/views/dashboard/index.php';
    }

    /**
     * Get admin-specific dashboard data
     */
    private function getAdminData() {
        $data = [];

        try {
            // Get ticket statistics
            $data['ticketStats'] = $this->ticketModel->getStats();

            // Get recent tickets (last 5)
            $recentTickets = $this->ticketModel->getAllWithStatus('all');
            $data['recentTickets'] = array_slice($recentTickets, 0, 5);

            // Get recent phone notes (last 5)
            $recentNotes = $this->phoneNoteModel->getAll(1, '', 'all');
            $data['recentPhoneNotes'] = array_slice($recentNotes, 0, 5);

            // Calculate today's activity
            $today = date('Y-m-d');
            $data['todayActivity'] = [
                'tickets' => 0,
                'phoneNotes' => 0,
                'openTickets' => $data['ticketStats']['open'] ?? 0,
                'criticalIssues' => ($data['ticketStats']['critical'] ?? 0) + ($data['ticketStats']['high'] ?? 0)
            ];

            // Count today's items
            foreach ($data['recentTickets'] as $ticket) {
                if (substr($ticket['created_at'], 0, 10) === $today) {
                    $data['todayActivity']['tickets']++;
                }
            }

            foreach ($data['recentPhoneNotes'] as $note) {
                if (substr($note['created_at'], 0, 10) === $today) {
                    $data['todayActivity']['phoneNotes']++;
                }
            }

        } catch (\Exception $e) {
            error_log("Error getting admin data: " . $e->getMessage());
            $data['error'] = "Unable to load some admin data";
        }

        return $data;
    }

    private function getStatistics($isAdmin = false) {
        // Basic stats for all users
        $stats = [
            'appointments' => rand(30, 60),
            'pending_forms' => rand(5, 20),
            'new_patients' => rand(3, 12),
            'procedures' => rand(15, 30)
        ];

        // If admin, replace with real data where available
        if ($isAdmin) {
            try {
                if (!$this->ticketModel) {
                    $this->ticketModel = new ITTicket();
                }
                $ticketStats = $this->ticketModel->getStats();
                $stats['pending_forms'] = $ticketStats['open'] ?? $stats['pending_forms'];
            } catch (\Exception $e) {
                // Keep random stats if DB fails
            }
        }

        return $stats;
    }

    // API endpoint for live stats
    public function getStats() {
        header('Content-Type: application/json');
        header('Cache-Control: no-cache, no-store, must-revalidate');

        $currentUser = $_SERVER['PHP_AUTH_USER'] ?? '';
        $userData = $this->userModel->getByUsername($currentUser);
        $userRole = $userData['role'] ?? 'user';
        $isAdmin = in_array($userRole, ['admin', 'super_admin']);

        echo json_encode($this->getStatistics($isAdmin));
    }

    // For role-specific dashboards (future feature)
    public function roleSpecific($role) {
        // Check authentication
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="GMPM Portal"');
            header('HTTP/1.0 401 Unauthorized');
            exit;
        }

        // Redirect to main dashboard for now
        header('Location: /');
        exit;
    }
}
