<?php
namespace App\Middleware;

use App\Services\Logger;

class RateLimit implements MiddlewareInterface {
    private $maxAttempts = 60;
    private $decayMinutes = 1;
    private $storage = [];
    
    public function __construct() {
        // Load rate limit config
        $securityConfig = require CONFIG_PATH . '/security.php';
        $this->maxAttempts = $securityConfig['rate_limit']['max_attempts'] ?? 60;
        $this->decayMinutes = $securityConfig['rate_limit']['window_minutes'] ?? 1;
    }
    
    public function handle($request, $next) {
        $identifier = $this->resolveRequestIdentifier();
        $key = 'rate_limit:' . $identifier;
        
        // Check rate limit
        if ($this->tooManyAttempts($key)) {
            Logger::channel('security')->warning('Rate limit exceeded', [
                'identifier' => $identifier,
                'ip' => $_SERVER['REMOTE_ADDR'],
                'uri' => $_SERVER['REQUEST_URI']
            ]);
            
            $this->sendRateLimitResponse();
            return null;
        }
        
        // Increment attempts
        $this->hit($key);
        
        // Add rate limit headers
        $this->addHeaders($key);
        
        return $next($request);
    }
    
    private function resolveRequestIdentifier() {
        // Use session ID if available, otherwise IP
        if (session_id()) {
            return session_id();
        }
        return $_SERVER['REMOTE_ADDR'] . ':' . ($_SERVER['HTTP_USER_AGENT'] ?? '');
    }
    
    private function tooManyAttempts($key) {
        $attempts = $this->getAttempts($key);
        return $attempts >= $this->maxAttempts;
    }
    
    private function hit($key) {
        $attempts = $this->getAttempts($key);
        $_SESSION[$key] = [
            'attempts' => $attempts + 1,
            'reset_at' => time() + ($this->decayMinutes * 60)
        ];
    }
    
    private function getAttempts($key) {
        if (!isset($_SESSION[$key])) {
            return 0;
        }
        
        // Check if expired
        if (time() > $_SESSION[$key]['reset_at']) {
            unset($_SESSION[$key]);
            return 0;
        }
        
        return $_SESSION[$key]['attempts'] ?? 0;
    }
    
    private function addHeaders($key) {
        $attempts = $this->getAttempts($key);
        $remaining = max(0, $this->maxAttempts - $attempts);
        
        header('X-RateLimit-Limit: ' . $this->maxAttempts);
        header('X-RateLimit-Remaining: ' . $remaining);
        
        if (isset($_SESSION[$key]['reset_at'])) {
            header('X-RateLimit-Reset: ' . $_SESSION[$key]['reset_at']);
        }
    }
    
    private function sendRateLimitResponse() {
        header('HTTP/1.0 429 Too Many Requests');
        header('Retry-After: ' . ($this->decayMinutes * 60));
        
        if (file_exists(RESOURCE_PATH . '/views/errors/429.php')) {
            include RESOURCE_PATH . '/views/errors/429.php';
        } else {
            echo json_encode([
                'error' => 'Too many requests. Please try again later.'
            ]);
        }
        exit;
    }
}
