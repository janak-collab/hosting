<?php
namespace App\Middleware;

use App\Services\Logger;

class AdminAuth implements MiddlewareInterface {
    public function handle($request, $next) {
        // Check if user is logged in as admin
        if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
            Logger::channel('security')->warning('Unauthorized admin access attempt', [
                'ip' => $_SERVER['REMOTE_ADDR'],
                'uri' => $_SERVER['REQUEST_URI'],
                'user' => $_SESSION['authenticated_user'] ?? 'Unknown'
            ]);
            
            // Redirect to admin login
            header('Location: ' . url('/admin/login'));
            exit;
        }
        
        // Log admin access
        Logger::channel('admin')->info('Admin access', [
            'admin' => $_SESSION['admin_username'],
            'uri' => $_SERVER['REQUEST_URI']
        ]);
        
        return $next($request);
    }
}
