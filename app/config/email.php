<?php
return [
    // Enable email sending
    'enabled' => true,
    
    // Log emails in addition to sending
    'log_emails' => true,
    
    // Where to log emails
    'log_path' => dirname(__DIR__) . '/storage/logs/emails.log'
];
