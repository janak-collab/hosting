<?php
namespace App\Controllers;

use App\Models\ITTicket;
use App\Models\PhoneNote;

class UserTicketsController {
    private $ticketModel;
    private $phoneNoteModel;
    
    public function __construct() {
        $this->ticketModel = new ITTicket();
        $this->phoneNoteModel = new PhoneNote();
    }
    
    public function showUserTickets() {
        // Get current user
        $currentUser = $_SERVER['PHP_AUTH_USER'] ?? $_SERVER['REMOTE_USER'] ?? 'Unknown';
        
        // Debug: Log the username being used
        error_log("UserTicketsController: Looking for tickets by user: " . $currentUser);

        // Get user's IT tickets
        $itTickets = $this->ticketModel->getTicketsByUser($currentUser);
        
        // Get user's phone notes
        $phoneNotes = $this->phoneNoteModel->getNotesByUser($currentUser);
        
        // Load the view
        require_once __DIR__ . '/../../templates/views/user-tickets.php';
    }
}
