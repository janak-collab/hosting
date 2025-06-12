<?php
namespace App\Middleware;

use App\Services\Logger;

class Auth implements MiddlewareInterface {
    public function handle($request, $next) {
        // Check if user is authenticated via HTTP Basic Auth
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            Logger::channel('security')->warning('Unauthenticated access attempt', [
                'ip' => $_SERVER['REMOTE_ADDR'],
                'uri' => $_SERVER['REQUEST_URI']
            ]);
            
            $this->sendAuthChallenge();
            return null;
        }
        
        // Store authenticated user in session
        $_SESSION['authenticated_user'] = $_SERVER['PHP_AUTH_USER'];
        
        // Continue to next middleware
        return $next($request);
    }
    
    private function sendAuthChallenge() {
        header('WWW-Authenticate: Basic realm="Greater Maryland Pain Management - Authorized Access Only"');
        header('HTTP/1.0 401 Unauthorized');
        
        if (file_exists(APP_PATH . '/templates/views/errors/401.php')) {
            include APP_PATH . '/templates/views/errors/401.php';
        } else {
            echo 'Unauthorized Access';
        }
        exit;
    }
}
