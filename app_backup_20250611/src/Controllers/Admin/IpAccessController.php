<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\IpAccessModel;
use App\Core\View;
use App\Core\Request;
use App\Core\Response;
use App\Services\Logger;

class IpAccessController extends BaseController
{
    protected $ipModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->ipModel = new IpAccessModel();
        
        // Ensure user is admin
        if (!$this->isAdmin()) {
            Response::redirect('/admin/login');
        }
        
        // Initialize CSRF token
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }
    
    /**
     * Display IP Access Manager
     */
    public function index()
    {
        // Check if we need to import from htaccess (first run)
        $dbRules = $this->ipModel->getAllRules();
        if (empty($dbRules)) {
            $htaccessIPs = $this->ipModel->getIPsFromHtaccess();
            if (!empty($htaccessIPs)) {
                $_SESSION['info'] = 'Detected existing IPs in .htaccess. Click "Import from .htaccess" to migrate them.';
            }
        }
        
        $data = [
            'title' => 'IP Access Manager',
            'rules' => $dbRules,
            'htaccessIPs' => $this->ipModel->getIPsFromHtaccess(),
            'serverInfo' => [
                'current_ip' => $_SERVER['REMOTE_ADDR'],
                'user' => $_SESSION['admin_username'] ?? 'Unknown',
                'server_software' => $_SERVER['SERVER_SOFTWARE'],
                'htaccess_exists' => file_exists(IpAccessModel::HTACCESS_PATH),
                'htaccess_writable' => is_writable(IpAccessModel::HTACCESS_PATH)
            ],
            'csrf_token' => $_SESSION['csrf_token']
        ];
        
        View::render('admin/ip-access-manager', $data);
    }
    
    /**
     * Add new IP rule
     */
    public function add(Request $request)
    {
        // CSRF validation
        if (!$this->validateCSRF($request->post('csrf_token'))) {
            $_SESSION['error'] = 'Invalid CSRF token';
            Response::redirect('/admin/ip-access-manager');
        }
        
        $ip = trim($request->post('ip_address'));
        $location = trim($request->post('location'));
        $description = trim($request->post('description', $location));
        
        // Validate IP
        if (!$this->ipModel->validateIP($ip)) {
            $_SESSION['error'] = "Invalid IP address: $ip";
            Response::redirect('/admin/ip-access-manager');
        }
        
        // Validate location
        if (empty($location)) {
            $_SESSION['error'] = "Location required for IP: $ip";
            Response::redirect('/admin/ip-access-manager');
        }
        
        // Auto-append "Office" if not already present in certain cases
        if (stripos($location, 'office') === false && 
            stripos($location, 'home') === false &&
            stripos($location, 'server') === false && 
            stripos($location, 'localhost') === false) {
            $location .= ' Office';
        }
        
        $data = [
            'ip_address' => $ip,
            'location' => $location,
            'description' => $description,
            'access_type' => 'allow',
            'is_active' => 1,
            'created_by' => $_SESSION['admin_id'] ?? 0
        ];
        
        if ($this->ipModel->addRule($data)) {
            $_SESSION['success'] = 'IP address added successfully!';
            Logger::channel('admin')->info('IP added', [
                'ip' => $ip,
                'location' => $location,
                'admin' => $_SESSION['admin_username']
            ]);
        } else {
            $_SESSION['error'] = 'Failed to add IP address';
        }
        
        Response::redirect('/admin/ip-access-manager');
    }
    
    /**
     * Update existing IP rules (bulk update)
     */
    public function bulkUpdate(Request $request)
    {
        // CSRF validation
        if (!$this->validateCSRF($request->post('csrf_token'))) {
            $_SESSION['error'] = 'Invalid CSRF token';
            Response::redirect('/admin/ip-access-manager');
        }
        
        $ips = $request->post('ips', []);
        $locations = $request->post('locations', []);
        $ids = $request->post('ids', []);
        
        $errors = [];
        $updated = 0;
        
        // First, mark all rules as inactive
        $this->ipModel->deactivateAllRules();
        
        // Process each IP
        foreach ($ips as $index => $ip) {
            $ip = trim($ip);
            $location = trim($locations[$index] ?? '');
            $id = $ids[$index] ?? null;
            
            if (empty($ip)) {
                continue;
            }
            
            // Validate IP
            if (!$this->ipModel->validateIP($ip)) {
                $errors[] = "Invalid IP address: $ip";
                continue;
            }
            
            // Validate location
            if (empty($location)) {
                $errors[] = "Location required for IP: $ip";
                continue;
            }
            
            // Auto-append "Office" if needed
            if (stripos($location, 'office') === false && 
                stripos($location, 'home') === false &&
                stripos($location, 'server') === false && 
                stripos($location, 'localhost') === false) {
                $location .= ' Office';
            }
            
            $data = [
                'ip_address' => $ip,
                'location' => $location,
                'description' => $location,
                'access_type' => 'allow',
                'is_active' => 1
            ];
            
            if ($id) {
                // Update existing
                if ($this->ipModel->updateRule($id, $data)) {
                    $updated++;
                }
            } else {
                // Add new
                if ($this->ipModel->addRule($data)) {
                    $updated++;
                }
            }
        }
        
        if (!empty($errors)) {
            $_SESSION['error'] = implode('<br>', $errors);
        } else {
            $_SESSION['success'] = "Successfully updated $updated IP addresses!";
            Logger::channel('admin')->info('Bulk IP update', [
                'count' => $updated,
                'admin' => $_SESSION['admin_username']
            ]);
        }
        
        Response::redirect('/admin/ip-access-manager');
    }
    
    /**
     * Delete IP rule
     */
    public function delete($id)
    {
        if ($this->ipModel->deleteRule($id)) {
            $_SESSION['success'] = 'IP address removed successfully';
            Logger::channel('admin')->info('IP deleted', [
                'id' => $id,
                'admin' => $_SESSION['admin_username']
            ]);
        } else {
            $_SESSION['error'] = 'Failed to remove IP address';
        }
        
        Response::redirect('/admin/ip-access-manager');
    }
    
    /**
     * Import IPs from .htaccess
     */
    public function importFromHtaccess()
    {
        $imported = $this->ipModel->importFromHtaccess();
        
        if ($imported > 0) {
            $_SESSION['success'] = "Successfully imported $imported IP addresses from .htaccess";
            Logger::channel('admin')->info('IPs imported from htaccess', [
                'count' => $imported,
                'admin' => $_SESSION['admin_username']
            ]);
        } else {
            $_SESSION['info'] = 'No new IP addresses to import';
        }
        
        Response::redirect('/admin/ip-access-manager');
    }
    
    /**
     * Force sync to .htaccess
     */
    public function syncToHtaccess()
    {
        if ($this->ipModel->syncToHtaccess()) {
            $_SESSION['success'] = 'Successfully synced IP addresses to .htaccess';
        } else {
            $_SESSION['error'] = 'Failed to sync to .htaccess. Check logs for details.';
        }
        
        Response::redirect('/admin/ip-access-manager');
    }
    
    /**
     * Download backup of current .htaccess
     */
    public function downloadBackup()
    {
        $htaccessPath = IpAccessModel::HTACCESS_PATH;
        
        if (!file_exists($htaccessPath)) {
            $_SESSION['error'] = '.htaccess file not found';
            Response::redirect('/admin/ip-access-manager');
        }
        
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="htaccess_backup_' . date('Y-m-d_H-i-s') . '.txt"');
        header('Content-Length: ' . filesize($htaccessPath));
        
        readfile($htaccessPath);
        exit;
    }
    
    /**
     * Validate CSRF token
     */
    private function validateCSRF($token)
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Check if user is admin
     */
    private function isAdmin()
    {
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }
}
