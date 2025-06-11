<?php
namespace App\Middleware;

class SecurityHeaders implements MiddlewareInterface {
    private $headers = [
        'X-Frame-Options' => 'SAMEORIGIN',
        'X-XSS-Protection' => '1; mode=block',
        'X-Content-Type-Options' => 'nosniff',
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
        'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains; preload',
        'Permissions-Policy' => 'geolocation=(), microphone=(), camera=(), payment=()',
        'X-Permitted-Cross-Domain-Policies' => 'none'
    ];
    
    public function handle($request, $next) {
        // Set security headers
        foreach ($this->headers as $header => $value) {
            header("$header: $value");
        }
        
        // Content Security Policy (can be customized per route)
        $this->setContentSecurityPolicy();
        
        // Remove potentially harmful headers
        header_remove('X-Powered-By');
        header_remove('Server');
        
        return $next($request);
    }
    
    private function setContentSecurityPolicy() {
        $policy = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "font-src 'self' https://fonts.gstatic.com",
            "img-src 'self' data: https:",
            "connect-src 'self'",
            "frame-ancestors 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "upgrade-insecure-requests"
        ];
        
        header('Content-Security-Policy: ' . implode('; ', $policy));
    }
}
