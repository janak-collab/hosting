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
            // Server settings
            $this->mailer->isSMTP();
            $this->mailer->Host = $_ENV['MAIL_HOST'] ?? 'localhost';
            
            // Only use authentication if not localhost
            if ($this->mailer->Host === 'localhost') {
                $this->mailer->SMTPAuth = false;
                $this->mailer->SMTPSecure = false;
                $this->mailer->Port = $_ENV['MAIL_PORT'] ?? 25;
            } else {
                $this->mailer->SMTPAuth = true;
                $this->mailer->Username = $_ENV['MAIL_USERNAME'] ?? '';
                $this->mailer->Password = $_ENV['MAIL_PASSWORD'] ?? '';
                $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $this->mailer->Port = $_ENV['MAIL_PORT'] ?? 587;
            }
            
            // Set default sender
            $fromAddress = $_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@gmpm.us';
            $fromName = $_ENV['MAIL_FROM_NAME'] ?? 'GMPM Support';
            $this->mailer->setFrom($fromAddress, $fromName);
            
        } catch (Exception $e) {
            error_log("Mailer setup error: " . $e->getMessage());
        }
    }
    
    /**
     * Send IT support ticket notification
     */
    public function sendTicketNotification($ticketData) {
        try {
            // Clear any previous recipients
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
            
            // Add recipient
            $itEmail = $_ENV['IT_EMAIL'] ?? 'IT.request@greatermarylandpainmanagement.com';
            $this->mailer->addAddress($itEmail);
            
            // Set priority for critical/high tickets
            if (isset($ticketData['priority']) && in_array($ticketData['priority'], ['critical', 'high'])) {
                $this->mailer->Priority = 1; // High priority
            }
            
            // Email content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $this->getTicketSubject($ticketData);
            $this->mailer->Body = $this->getTicketEmailTemplate($ticketData);
            $this->mailer->AltBody = $this->getTicketPlainText($ticketData);
            
            // Send email
            $this->mailer->send();
            return true;
            
        } catch (Exception $e) {
            error_log("Email send error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send phone note notification
     */
    public function sendPhoneNoteNotification($noteData) {
        try {
            // Clear any previous recipients
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
            
            // Add recipient (provider email)
            if (isset($noteData['provider_email'])) {
                $this->mailer->addAddress($noteData['provider_email']);
            } else {
                throw new Exception("No provider email specified");
            }
            
            // Email content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = "Phone Note - {$noteData['patient_name']} - " . date('m/d/Y');
            $this->mailer->Body = $this->getPhoneNoteEmailTemplate($noteData);
            $this->mailer->AltBody = $this->getPhoneNotePlainText($noteData);
            
            // Send email
            $this->mailer->send();
            return true;
            
        } catch (Exception $e) {
            error_log("Phone note email error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Send general notification email
     */
    public function sendEmail($to, $subject, $body, $isHtml = true) {
        try {
            // Clear any previous recipients
            $this->mailer->clearAddresses();
            $this->mailer->clearAttachments();
            
            // Add recipient
            $this->mailer->addAddress($to);
            
            // Email content
            $this->mailer->isHTML($isHtml);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $body;
            
            if ($isHtml) {
                $this->mailer->AltBody = strip_tags($body);
            }
            
            // Send email
            $this->mailer->send();
            return true;
            
        } catch (Exception $e) {
            error_log("General email error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get ticket email subject
     */
    private function getTicketSubject($data) {
        $priority = isset($data['priority']) ? strtoupper($data['priority']) : 'NORMAL';
        $category = isset($data['category']) ? ucfirst($data['category']) : 'General';
        return "[{$priority}] IT Support Ticket #{$data['id']} - {$category} - {$data['name']}";
    }
    
    /**
     * Get ticket email HTML template
     */
    private function getTicketEmailTemplate($data) {
        $priorityColors = [
            'low' => '#0050b3',
            'normal' => '#1890ff',
            'high' => '#d48806',
            'critical' => '#cf1322'
        ];
        
        $priorityColor = $priorityColors[$data['priority'] ?? 'normal'] ?? '#1890ff';
        
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #f26522; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
                .content { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 0 0 5px 5px; }
                .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin: 20px 0; }
                .info-item { background: white; padding: 10px; border-radius: 5px; }
                .label { font-weight: bold; color: #2c3e50; }
                .priority { display: inline-block; padding: 5px 15px; border-radius: 20px; color: white; font-weight: bold; background: ' . $priorityColor . '; }
                .description { background: white; padding: 15px; border-radius: 5px; margin-top: 20px; }
                .footer { text-align: center; padding: 10px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>New IT Support Request</h2>
                    <p>Ticket #' . $data['id'] . '</p>
                </div>
                <div class="content">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="label">Name:</span><br>
                            ' . htmlspecialchars($data['name']) . '
                        </div>
                        <div class="info-item">
                            <span class="label">Location:</span><br>
                            ' . htmlspecialchars($data['location']) . '
                        </div>
                        <div class="info-item">
                            <span class="label">Category:</span><br>
                            ' . htmlspecialchars(ucfirst($data['category'] ?? 'general')) . '
                        </div>
                        <div class="info-item">
                            <span class="label">Priority:</span><br>
                            <span class="priority">' . strtoupper($data['priority'] ?? 'normal') . '</span>
                        </div>
                    </div>
                    
                    <div class="description">
                        <p class="label">Issue Description:</p>
                        <p>' . nl2br(htmlspecialchars($data['description'])) . '</p>
                    </div>
                    
                    <div class="info-item" style="margin-top: 20px;">
                        <span class="label">Submitted:</span> ' . date('F j, Y g:i A') . '<br>
                        <span class="label">IP Address:</span> ' . ($data['ip_address'] ?? 'Unknown') . '
                    </div>
                </div>
                <div class="footer">
                    <p>Greater Maryland Pain Management - IT Support System</p>
                    <p>Please respond within the SLA timeframe based on priority level.</p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    /**
     * Get ticket plain text email
     */
    private function getTicketPlainText($data) {
        $priority = strtoupper($data['priority'] ?? 'normal');
        $category = ucfirst($data['category'] ?? 'general');
        
        return "New IT Support Request - Ticket #{$data['id']}\n" .
               "=====================================\n\n" .
               "Priority: {$priority}\n" .
               "Category: {$category}\n" .
               "Name: {$data['name']}\n" .
               "Location: {$data['location']}\n" .
               "Submitted: " . date('F j, Y g:i A') . "\n\n" .
               "Issue Description:\n" .
               "{$data['description']}\n\n" .
               "IP Address: " . ($data['ip_address'] ?? 'Unknown') . "\n\n" .
               "Please respond within the SLA timeframe based on priority level.";
    }
    
    /**
     * Get phone note email template
     */
    private function getPhoneNoteEmailTemplate($data) {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #f26522; color: white; padding: 20px; text-align: center; }
                .content { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
                .label { font-weight: bold; color: #2c3e50; }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="header">
                    <h2>Phone Note</h2>
                </div>
                <div class="content">
                    <p><span class="label">Patient:</span> ' . htmlspecialchars($data['patient_name']) . '</p>
                    <p><span class="label">DOB:</span> ' . htmlspecialchars($data['dob']) . '</p>
                    <p><span class="label">Phone:</span> ' . htmlspecialchars($data['phone']) . '</p>
                    <p><span class="label">Date/Time:</span> ' . date('F j, Y g:i A') . '</p>
                    <hr>
                    <p><span class="label">Message:</span></p>
                    <p>' . nl2br(htmlspecialchars($data['message'])) . '</p>
                </div>
            </div>
        </body>
        </html>';
    }
    
    /**
     * Get phone note plain text
     */
    private function getPhoneNotePlainText($data) {
        return "Phone Note\n" .
               "==========\n\n" .
               "Patient: {$data['patient_name']}\n" .
               "DOB: {$data['dob']}\n" .
               "Phone: {$data['phone']}\n" .
               "Date/Time: " . date('F j, Y g:i A') . "\n\n" .
               "Message:\n{$data['message']}";
    }
}
