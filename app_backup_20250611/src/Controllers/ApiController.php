<?php
namespace App\Controllers;

class ApiController extends BaseController {
    
    public function status() {
        $checks = [
            'status' => 'ok',
            'php_version' => phpversion(),
            'timestamp' => date('Y-m-d H:i:s'),
            'server_ip' => $_SERVER['SERVER_ADDR'] ?? 'unknown',
            'your_ip' => $_SERVER['REMOTE_ADDR'],
            'app_directory' => is_dir(APP_PATH) ? 'found' : 'missing',
            'vendor_directory' => is_dir(APP_PATH . '/vendor') ? 'found' : 'missing',
            'storage_directory' => is_dir(STORAGE_PATH) ? 'found' : 'missing',
            'environment_file' => file_exists(APP_PATH . '/.env') ? 'found' : 'missing'
        ];
        
        return $this->json($checks);
    }
    
    public function summary() {
        header('Content-Type: text/plain');
        
        echo "GMPM System Summary\n";
        echo "==================\n\n";
        echo "Current Time: " . date('Y-m-d H:i:s') . "\n";
        echo "Your IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
        echo "PHP Version: " . phpversion() . "\n\n";
        
        echo "Directory Status:\n";
        echo "- App: " . (is_dir(APP_PATH) ? '✓' : '✗') . "\n";
        echo "- Vendor: " . (is_dir(APP_PATH . '/vendor') ? '✓' : '✗') . "\n";
        echo "- Storage: " . (is_dir(STORAGE_PATH) ? '✓' : '✗') . "\n";
        echo "- Assets: " . (is_dir(PUBLIC_PATH . '/assets') ? '✓' : '✗') . "\n\n";
        
        echo "Key Files:\n";
        echo "- .env: " . (file_exists(APP_PATH . '/.env') ? '✓' : '✗') . "\n";
        echo "- index.php: " . (file_exists(PUBLIC_PATH . '/index.php') ? '✓' : '✗') . "\n";
        echo "- .htaccess: " . (file_exists(PUBLIC_PATH . '/.htaccess') ? '✓' : '✗') . "\n\n";
        
        echo "System is operational!\n";
        exit;
    }
}
