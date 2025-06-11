<?php
namespace App\Controllers;

class StatusController extends BaseController {
    
    /**
     * System status check endpoint
     */
    public function check() {
        $status = [
            'status' => 'ok',
            'application' => 'GMPM Portal',
            'version' => '2.0',
            'timestamp' => date('Y-m-d H:i:s'),
            'php_version' => phpversion(),
            'server' => [
                'software' => $_SERVER['SERVER_SOFTWARE'] ?? 'unknown',
                'hostname' => gethostname(),
            ],
            'environment' => $_ENV['APP_ENV'] ?? 'production',
            'components' => [
                'database' => $this->checkDatabase(),
                'sessions' => $this->checkSessions(),
                'storage' => $this->checkStorage(),
                'cache' => $this->checkCache()
            ]
        ];
        
        return $this->json($status);
    }
    
    /**
     * Public health check (no auth required)
     */
    public function health() {
        return $this->json([
            'status' => 'healthy',
            'timestamp' => time()
        ]);
    }
    
    private function checkDatabase() {
        try {
            $db = \App\Database\Connection::getInstance()->getConnection();
            $result = $db->query('SELECT 1')->fetch();
            return $result ? 'connected' : 'error';
        } catch (\Exception $e) {
            return 'disconnected';
        }
    }
    
    private function checkSessions() {
        return session_status() === PHP_SESSION_ACTIVE ? 'active' : 'inactive';
    }
    
    private function checkStorage() {
        $path = STORAGE_PATH;
        return is_dir($path) && is_writable($path) ? 'writable' : 'read-only';
    }
    
    private function checkCache() {
        $path = APP_PATH . '/storage/cache';
        return is_dir($path) && is_writable($path) ? 'enabled' : 'disabled';
    }
}
