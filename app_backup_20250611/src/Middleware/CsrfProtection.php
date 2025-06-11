<?php
namespace App\Middleware;

use App\Services\Logger;

class CsrfProtection implements MiddlewareInterface {
    private $exemptMethods = ['GET', 'HEAD', 'OPTIONS'];
    private $exemptPaths = [
        '/api/public/'
    ];
    
    public function handle($request, $next) {
        // Generate token if not exists
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        // Skip CSRF check for exempt methods
        if (in_array($_SERVER['REQUEST_METHOD'], $this->exemptMethods)) {
            return $next($request);
        }
        
        // Skip CSRF check for exempt paths
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        foreach ($this->exemptPaths as $path) {
            if (strpos($uri, $path) === 0) {
                return $next($request);
            }
        }
        
        // Verify CSRF token
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        
        if (!$this->tokensMatch($token)) {
            Logger::channel('security')->error('CSRF token mismatch', [
                'ip' => $_SERVER['REMOTE_ADDR'],
                'uri' => $_SERVER['REQUEST_URI'],
                'method' => $_SERVER['REQUEST_METHOD']
            ]);
            
            $this->sendCsrfFailureResponse();
            return null;
        }
        
        // Regenerate token after successful validation
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        
        return $next($request);
    }
    
    private function tokensMatch($token) {
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    private function sendCsrfFailureResponse() {
        header('HTTP/1.0 419 Page Expired');
        
        // If AJAX request, send JSON
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'error' => 'CSRF token mismatch. Please refresh the page and try again.'
            ]);
        } else {
            if (file_exists(RESOURCE_PATH . '/views/errors/419.php')) {
                include RESOURCE_PATH . '/views/errors/419.php';
            } else {
                echo 'CSRF token mismatch. Please refresh the page and try again.';
            }
        }
        exit;
    }
}
