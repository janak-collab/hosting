<?php
namespace App\Middleware;

use App\Services\Logger;

class IpWhitelist implements MiddlewareInterface {
    private $allowedIps = [];
    private $exemptPaths = [
        '/assets/',
        '/api/public/',
        '/errors/'
    ];
    
    public function __construct() {
        // Load allowed IPs from config
        $securityConfig = require CONFIG_PATH . '/security.php';
        $this->allowedIps = array_keys($securityConfig['ip_whitelist']);
    }
    
    public function handle($request, $next) {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $clientIp = $_SERVER['REMOTE_ADDR'];
        
        // Check if path is exempt
        foreach ($this->exemptPaths as $path) {
            if (strpos($uri, $path) === 0) {
                return $next($request);
            }
        }
        
        // Check if it's a static asset
        if (preg_match('/\.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2)$/i', $uri)) {
            return $next($request);
        }
        
        // Check IP whitelist
        if (!in_array($clientIp, $this->allowedIps)) {
            Logger::channel('security')->error('IP not whitelisted', [
                'ip' => $clientIp,
                'uri' => $uri,
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
            ]);
            
            header('HTTP/1.0 403 Forbidden');
            if (file_exists(RESOURCE_PATH . '/views/errors/403.php')) {
                include RESOURCE_PATH . '/views/errors/403.php';
            } else {
                echo 'Access Forbidden';
            }
            exit;
        }
        
        return $next($request);
    }
}
