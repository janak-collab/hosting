#!/bin/bash
# Create basic middleware classes

echo "Creating basic middleware classes..."

# Create Auth middleware
cat > app/src/Middleware/Auth.php << 'EOF'
<?php
namespace App\Middleware;

class Auth {
    public function handle($params, $next) {
        // Check if user is authenticated via HTTP Basic Auth
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            header('WWW-Authenticate: Basic realm="GMPM Portal"');
            header('HTTP/1.0 401 Unauthorized');
            echo 'Authentication required';
            exit;
        }
        
        // Continue to next middleware/handler
        return $next();
    }
}
EOF

# Create AdminAuth middleware
cat > app/src/Middleware/AdminAuth.php << 'EOF'
<?php
namespace App\Middleware;

class AdminAuth {
    public function handle($params, $next) {
        // Check if user is admin
        if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
            header('Location: /admin/login');
            exit;
        }
        
        return $next();
    }
}
EOF

# Create CsrfProtection middleware
cat > app/src/Middleware/CsrfProtection.php << 'EOF'
<?php
namespace App\Middleware;

class CsrfProtection {
    public function handle($params, $next) {
        // Skip CSRF check for GET requests
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return $next();
        }
        
        // Check CSRF token
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        
        if (!$token || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'CSRF token mismatch']);
            exit;
        }
        
        return $next();
    }
}
EOF

# Create RateLimit middleware
cat > app/src/Middleware/RateLimit.php << 'EOF'
<?php
namespace App\Middleware;

class RateLimit {
    private $maxAttempts = 60;
    private $decayMinutes = 1;
    
    public function handle($params, $next) {
        $key = 'rate_limit_' . md5($_SERVER['REMOTE_ADDR']);
        
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = [
                'attempts' => 0,
                'reset_at' => time() + ($this->decayMinutes * 60)
            ];
        }
        
        // Reset if time has passed
        if (time() > $_SESSION[$key]['reset_at']) {
            $_SESSION[$key] = [
                'attempts' => 0,
                'reset_at' => time() + ($this->decayMinutes * 60)
            ];
        }
        
        // Increment attempts
        $_SESSION[$key]['attempts']++;
        
        // Check if rate limited
        if ($_SESSION[$key]['attempts'] > $this->maxAttempts) {
            http_response_code(429);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Too many requests']);
            exit;
        }
        
        return $next();
    }
}
EOF

# Create IpWhitelist middleware
cat > app/src/Middleware/IpWhitelist.php << 'EOF'
<?php
namespace App\Middleware;

class IpWhitelist {
    public function handle($params, $next) {
        $allowedIps = config('security.ip_whitelist', []);
        $clientIp = $_SERVER['REMOTE_ADDR'];
        
        // Check if IP is in whitelist
        if (!isset($allowedIps[$clientIp])) {
            http_response_code(403);
            echo 'Access denied from IP: ' . $clientIp;
            exit;
        }
        
        return $next();
    }
}
EOF

echo "Basic middleware classes created!"
echo ""
echo "Created:"
echo "- Auth.php (HTTP Basic Auth check)"
echo "- AdminAuth.php (Admin authentication check)"
echo "- CsrfProtection.php (CSRF token validation)"
echo "- RateLimit.php (Rate limiting by IP)"
echo "- IpWhitelist.php (IP whitelist check)"
