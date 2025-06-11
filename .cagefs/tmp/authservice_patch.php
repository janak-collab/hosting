    public function requireRole($minRole = 'user') {
        // Start session if not started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is authenticated
        if (!$this->isAuthenticated()) {
            throw new Exception('Authentication required', 401);
        }
        
        // Skip CSRF check for API requests
        $isApiRequest = strpos($_SERVER['REQUEST_URI'] ?? '', '/api/') !== false;
        
        // Check CSRF for non-API requests
        if (!$isApiRequest && $_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || 
                !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                throw new Exception('Invalid CSRF token', 403);
            }
        }
        
        // Check role hierarchy
        $roleHierarchy = ['user' => 1, 'admin' => 2, 'super_admin' => 3];
        $userRole = $_SESSION['user_role'] ?? 'user';
        
        if ($roleHierarchy[$userRole] < $roleHierarchy[$minRole]) {
            throw new Exception('Insufficient permissions', 403);
        }
        
        return true;
    }
