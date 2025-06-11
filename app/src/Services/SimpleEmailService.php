<?php
namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class SimpleEmailService {
    private $mailer;
    
    public function __construct() {
        $this->mailer = new PHPMailer(true);
        
        try {
            // Use local mail server (no auth needed)
            $this->mailer->isSMTP();
            $this->mailer->Host = 'localhost';
            $this->mailer->Port = 25;
            $this->mailer->SMTPAuth = false;
            $this->mailer->SMTPSecure = false;
            
            // Set default sender
            $this->mailer->setFrom(
                $_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@gmpm.us',
                $_ENV['MAIL_FROM_NAME'] ?? 'GMPM Support'
            );
            
        } catch (Exception $e) {
            error_log("Mailer setup error: " . $e->getMessage());
        }
    }
    
    public function sendTicketNotification($ticket) {
        try {
            $this->mailer->clearAddresses();
            $this->mailer->addAddress($_ENV['IT_EMAIL'] ?? 'IT.request@greatermarylandpainmanagement.com');
            
            $this->mailer->Subject = "[{$ticket['priority']}] IT Support Ticket #{$ticket['id']}";
            $this->mailer->Body = $this->getTicketEmailBody($ticket);
            $this->mailer->isHTML(false);
            
            return $this->mailer->send();
        } catch (Exception $e) {
            error_log("Failed to send ticket email: " . $e->getMessage());
            return false;
        }
    }
    
    private function getTicketEmailBody($ticket) {
        return "New IT Support Request\n" .
               "====================\n\n" .
               "Ticket ID: #{$ticket['id']}\n" .
               "Priority: " . strtoupper($ticket['priority'] ?? 'normal') . "\n" .
               "Category: " . ucfirst($ticket['category'] ?? 'general') . "\n" .
               "Name: {$ticket['name']}\n" .
               "Location: {$ticket['location']}\n" .
               "Submitted: " . date('F j, Y g:i A') . "\n\n" .
               "Issue Description:\n" .
               "{$ticket['description']}\n\n" .
               "Please respond within the SLA timeframe based on priority level.";
    }
}
