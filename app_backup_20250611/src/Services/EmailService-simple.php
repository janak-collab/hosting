<?php
namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService {
    private $mailer;
    
    public function __construct() {
        $this->mailer = new PHPMailer(true);
        
        try {
            // Server settings - simplified
            $this->mailer->isSMTP();
            $this->mailer->Host = $_ENV['MAIL_HOST'] ?? 'localhost';
            $this->mailer->SMTPAuth = false;
            $this->mailer->Port = 25;
            
            // Set default sender
            $this->mailer->setFrom('noreply@gmpm.us', 'GMPM Support');
            
        } catch (Exception $e) {
            // Log but don't fail
            error_log("Mailer setup error: " . $e->getMessage());
        }
    }
    
    public function sendTicketNotification($ticketData) {
        // For now, just log the attempt
        error_log("Email notification attempted for ticket #" . ($ticketData['id'] ?? 'unknown'));
        
        // Return true to not block ticket creation
        return true;
    }
    
    public function sendPhoneNoteNotification($noteData) {
        error_log("Phone note email attempted");
        return true;
    }
    
    public function sendEmail($to, $subject, $body, $isHtml = true) {
        error_log("Email send attempted to: $to");
        return true;
    }
}
