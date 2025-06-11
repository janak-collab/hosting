<?php
namespace App\Controllers;

class PortalController {
    
    public function index() {
        // Set headers
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        
        $user = $_SERVER['PHP_AUTH_USER'] ?? 'Guest';
        
        // Load view
        require_once APP_PATH . '/resources/views/portal/index.php';
    }
    
    public function status() {
        // Set headers
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Content-Type: text/html; charset=UTF-8');
        
        // System status checks
        $checks = [
            'PHP Version' => phpversion(),
            'FastRoute' => class_exists('\FastRoute\Dispatcher') ? 'Installed' : 'Missing',
            'Session' => session_status() === PHP_SESSION_ACTIVE ? 'Active' : 'Inactive',
            'Database' => $this->checkDatabase() ? 'Connected' : 'Error',
            'Environment' => $_ENV['APP_ENV'] ?? 'Not set'
        ];
        
        // Check if view file exists
        $viewFile = APP_PATH . '/resources/views/status.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            // Simple inline status display if view doesn't exist
            echo '<!DOCTYPE html>
<html>
<head>
    <title>System Status - GMPM</title>
    <link rel="stylesheet" href="/assets/css/form-styles.css">
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>System Status</h1>
            </div>
            <div class="form-content">
                <table style="width: 100%; border-collapse: collapse;">';
            
            foreach ($checks as $check => $status) {
                $color = strpos($status, 'Missing') === false && strpos($status, 'Error') === false ? '#38a169' : '#e53e3e';
                echo '<tr style="border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 0.75rem; font-weight: 600;">' . htmlspecialchars($check) . '</td>
                    <td style="padding: 0.75rem; text-align: right; color: ' . $color . ';">' . htmlspecialchars($status) . '</td>
                </tr>';
            }
            
            echo '</table>
                <div class="form-actions" style="margin-top: 2rem;">
                    <a href="/" class="btn btn-primary">Back to Portal</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>';
        }
    }
    
    private function checkDatabase() {
        try {
            $db = \App\Database\Connection::getInstance()->getConnection();
            return $db !== null;
        } catch (\Exception $e) {
            return false;
        }
    }
}
