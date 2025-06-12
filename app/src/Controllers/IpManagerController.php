<?php
namespace App\Controllers;

use App\Services\IpManagerService;

class IpManagerController extends BaseController {
    private $ipManagerService;
    private $superAdmins = ['jvidyarthi', 'admin'];
    
    public function __construct() {
        parent::__construct();
        $this->ipManagerService = new IpManagerService();
    }
    
    public function showForm() {
        // CRITICAL: Require super admin access
        $currentUser = $this->getCurrentUser();
        if (!in_array($currentUser, $this->superAdmins)) {
            require_once __DIR__ . '/../../templates/views/errors/403-admin.php';
            exit;
        }
        
        // Set no-cache headers
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        
        // Get current IPs
        $currentIPs = $this->ipManagerService->getCurrentIPs();
        
        // Generate CSRF token
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        // Prepare view data
        $message = '';
        $error = '';
        $messageType = '';
        
        // Load the view
        require_once __DIR__ . '/../../templates/views/ip-address-manager/form.php';
    }
    
    public function update() {
        // CRITICAL: Require super admin access
        $currentUser = $this->getCurrentUser();
        if (!in_array($currentUser, $this->superAdmins)) {
            require_once __DIR__ . '/../../templates/views/errors/403-admin.php';
            exit;
        }
        
        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            die('Invalid CSRF token');
        }
        
        $message = '';
        $error = '';
        $messageType = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ips = [];
            
            if (isset($_POST['ips']) && is_array($_POST['ips'])) {
                foreach ($_POST['ips'] as $index => $ip) {
                    $ip = trim($ip);
                    $location = trim($_POST['locations'][$index] ?? '');
                    
                    if (!empty($ip)) {
                        if (!$this->ipManagerService->validateIP($ip)) {
                            $error = "Invalid IP address: $ip";
                            $messageType = 'error';
                            break;
                        }
                        
                        if (empty($location)) {
                            $error = "Location required for IP: $ip";
                            $messageType = 'error';
                            break;
                        }
                        
                        if (stripos($location, 'office') === false && stripos($location, 'home') === false) {
                            $location .= ' Office';
                        }
                        
                        $ips[] = ['ip' => $ip, 'location' => $location];
                    }
                }
            }
            
            if (empty($error) && !empty($ips)) {
                try {
                    $this->ipManagerService->updateHtaccess($ips);
                    $message = 'IP addresses updated successfully!';
                    $messageType = 'success';
                } catch (\Exception $e) {
                    $error = 'Error updating .htaccess: ' . $e->getMessage();
                    $messageType = 'error';
                }
            }
        }
        
        // Get current IPs after update
        $currentIPs = $this->ipManagerService->getCurrentIPs();
        
        // Load the view with results
        require_once __DIR__ . '/../../templates/views/ip-address-manager/form.php';
    }
}
