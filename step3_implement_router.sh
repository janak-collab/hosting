#!/bin/bash
# GMPM Step 3: Implement Router
# Run this from /home/gmpmus/

echo "==================================="
echo "Step 3: Implement Router"
echo "==================================="
echo ""

# Create Core directory if not exists
mkdir -p app/src/Core
mkdir -p app/src/Middleware
mkdir -p app/routes

# Create Router.php
echo "Creating Router class..."
cat > app/src/Core/Router.php << 'EOF'
<?php
namespace App\Core;

class Router {
    private $routes = [];
    private $middlewareGroups = [];
    private $currentMiddleware = [];
    
    /**
     * Add a GET route
     */
    public function get($path, $handler) {
        return $this->addRoute('GET', $path, $handler);
    }
    
    /**
     * Add a POST route
     */
    public function post($path, $handler) {
        return $this->addRoute('POST', $path, $handler);
    }
    
    /**
     * Add a PUT route
     */
    public function put($path, $handler) {
        return $this->addRoute('PUT', $path, $handler);
    }
    
    /**
     * Add a DELETE route
     */
    public function delete($path, $handler) {
        return $this->addRoute('DELETE', $path, $handler);
    }
    
    /**
     * Add a route with any method
     */
    public function any($path, $handler) {
        return $this->addRoute('ANY', $path, $handler);
    }
    
    /**
     * Add a route
     */
    private function addRoute($method, $path, $handler) {
        $route = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler,
            'middleware' => $this->currentMiddleware
        ];
        
        $this->routes[] = $route;
        
        // Return route for chaining
        return new class($route, $this) {
            private $route;
            private $router;
            
            public function __construct(&$route, $router) {
                $this->route = &$route;
                $this->router = $router;
            }
            
            public function middleware($middleware) {
                if (is_array($middleware)) {
                    $this->route['middleware'] = array_merge($this->route['middleware'], $middleware);
                } else {
                    $this->route['middleware'][] = $middleware;
                }
                return $this;
            }
            
            public function name($name) {
                $this->route['name'] = $name;
                return $this;
            }
        };
    }
    
    /**
     * Group routes with shared attributes
     */
    public function group($attributes, $callback) {
        $previousMiddleware = $this->currentMiddleware;
        
        // Apply group middleware
        if (isset($attributes['middleware'])) {
            $middleware = is_array($attributes['middleware']) ? $attributes['middleware'] : [$attributes['middleware']];
            $this->currentMiddleware = array_merge($this->currentMiddleware, $middleware);
        }
        
        // Execute the group callback
        $callback($this);
        
        // Restore previous middleware
        $this->currentMiddleware = $previousMiddleware;
    }
    
    /**
     * Define middleware group
     */
    public function middlewareGroup($name, $middleware) {
        $this->middlewareGroups[$name] = $middleware;
    }
    
    /**
     * Dispatch the request
     */
    public function dispatch($method, $uri) {
        // Normalize URI
        $uri = rtrim($uri, '/') ?: '/';
        
        // Find matching route
        foreach ($this->routes as $route) {
            if ($this->matchRoute($route, $method, $uri, $params)) {
                return $this->handleRoute($route, $params);
            }
        }
        
        // No route found
        $this->handleNotFound();
    }
    
    /**
     * Check if route matches request
     */
    private function matchRoute($route, $method, $uri, &$params) {
        // Check method
        if ($route['method'] !== 'ANY' && $route['method'] !== $method) {
            return false;
        }
        
        // Convert route pattern to regex
        $pattern = $route['path'];
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $pattern);
        $pattern = '#^' . $pattern . '$#';
        
        // Check if URI matches
        if (preg_match($pattern, $uri, $matches)) {
            // Extract parameters
            $params = [];
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $params[$key] = $value;
                }
            }
            return true;
        }
        
        return false;
    }
    
    /**
     * Handle the matched route
     */
    private function handleRoute($route, $params) {
        // Run middleware
        $middleware = $this->resolveMiddleware($route['middleware']);
        
        $next = function() use ($route, $params) {
            return $this->callHandler($route['handler'], $params);
        };
        
        // Execute middleware chain
        foreach (array_reverse($middleware) as $mw) {
            $next = function() use ($mw, $next, $params) {
                return $this->callMiddleware($mw, $params, $next);
            };
        }
        
        return $next();
    }
    
    /**
     * Resolve middleware names to classes
     */
    private function resolveMiddleware($middleware) {
        $resolved = [];
        
        foreach ($middleware as $mw) {
            if (isset($this->middlewareGroups[$mw])) {
                $resolved = array_merge($resolved, $this->middlewareGroups[$mw]);
            } else {
                $resolved[] = $mw;
            }
        }
        
        return $resolved;
    }
    
    /**
     * Call middleware
     */
    private function callMiddleware($middleware, $params, $next) {
        // Resolve middleware class
        if (is_string($middleware)) {
            $middleware = $this->resolveMiddlewareClass($middleware);
        }
        
        // Call handle method
        return $middleware->handle($params, $next);
    }
    
    /**
     * Resolve middleware class from string
     */
    private function resolveMiddlewareClass($middleware) {
        $aliases = [
            'auth' => \App\Middleware\Auth::class,
            'admin' => \App\Middleware\AdminAuth::class,
            'csrf' => \App\Middleware\CsrfProtection::class,
            'throttle' => \App\Middleware\RateLimit::class,
            'ip' => \App\Middleware\IpWhitelist::class,
        ];
        
        $class = $aliases[$middleware] ?? $middleware;
        
        if (!class_exists($class)) {
            throw new \Exception("Middleware class not found: $class");
        }
        
        return new $class();
    }
    
    /**
     * Call the route handler
     */
    private function callHandler($handler, $params) {
        // String format: Controller@method
        if (is_string($handler) && strpos($handler, '@') !== false) {
            [$controller, $method] = explode('@', $handler);
            $controller = "\\App\\Controllers\\$controller";
            
            if (!class_exists($controller)) {
                throw new \Exception("Controller not found: $controller");
            }
            
            $instance = new $controller();
            
            if (!method_exists($instance, $method)) {
                throw new \Exception("Method not found: $controller::$method");
            }
            
            return call_user_func_array([$instance, $method], $params);
        }
        
        // Closure
        if (is_callable($handler)) {
            return call_user_func_array($handler, $params);
        }
        
        throw new \Exception("Invalid route handler");
    }
    
    /**
     * Handle 404
     */
    private function handleNotFound() {
        http_response_code(404);
        
        if (file_exists(RESOURCE_PATH . '/views/errors/404.php')) {
            include RESOURCE_PATH . '/views/errors/404.php';
        } else {
            echo "404 - Not Found";
        }
        
        exit;
    }
}
EOF

# Create BaseController.php
echo "Creating BaseController..."
cat > app/src/Controllers/BaseController.php << 'EOF'
<?php
namespace App\Controllers;

abstract class BaseController {
    /**
     * Render a view
     */
    protected function view($view, $data = []) {
        extract($data);
        
        $viewPath = RESOURCE_PATH . '/views/' . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View not found: $view");
        }
        
        ob_start();
        include $viewPath;
        return ob_get_clean();
    }
    
    /**
     * Return JSON response
     */
    protected function json($data, $status = 200) {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }
    
    /**
     * Redirect to URL
     */
    protected function redirect($url, $status = 302) {
        header("Location: $url", true, $status);
        exit;
    }
    
    /**
     * Get request input
     */
    protected function input($key = null, $default = null) {
        $data = array_merge($_GET, $_POST);
        
        if ($key === null) {
            return $data;
        }
        
        return $data[$key] ?? $default;
    }
    
    /**
     * Get uploaded file
     */
    protected function file($key) {
        return $_FILES[$key] ?? null;
    }
    
    /**
     * Check if request is AJAX
     */
    protected function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
    
    /**
     * Get authenticated user
     */
    protected function user() {
        return $_SESSION['user'] ?? null;
    }
    
    /**
     * Check if user is authenticated
     */
    protected function isAuthenticated() {
        return isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
    }
    
    /**
     * Check if user is admin
     */
    protected function isAdmin() {
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }
    
    /**
     * Abort with error
     */
    protected function abort($code = 404, $message = '') {
        http_response_code($code);
        
        $viewPath = RESOURCE_PATH . "/views/errors/$code.php";
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            echo "$code - $message";
        }
        
        exit;
    }
    
    /**
     * Validate CSRF token
     */
    protected function validateCsrf() {
        $token = $this->input('csrf_token');
        
        if (!$token || !isset($_SESSION['csrf_token'])) {
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Generate CSRF token
     */
    protected function generateCsrf() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }
}
EOF

# Update helpers file
echo "Updating helpers..."
cat > app/src/Helpers/functions.php << 'EOF'
<?php
/**
 * Global helper functions
 */

/**
 * Get environment variable
 */
function env($key, $default = null) {
    return $_ENV[$key] ?? $default;
}

/**
 * Get configuration value
 */
function config($key, $default = null) {
    $parts = explode('.', $key);
    $file = $parts[0];
    $configKey = $parts[1] ?? null;
    
    $configFile = CONFIG_PATH . "/{$file}.php";
    if (!file_exists($configFile)) {
        return $default;
    }
    
    $config = require $configFile;
    
    if ($configKey) {
        return $config[$configKey] ?? $default;
    }
    
    return $config;
}

/**
 * Generate URL
 */
function url($path = '') {
    $baseUrl = config('app.url', 'https://gmpm.us');
    return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
}

/**
 * Generate asset URL
 */
function asset($path) {
    return url('assets/' . ltrim($path, '/'));
}

/**
 * Sanitize input
 */
function sanitize($input) {
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Get CSRF token
 */
function csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Generate CSRF field
 */
function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

/**
 * Old input value
 */
function old($key, $default = '') {
    return $_SESSION['old_input'][$key] ?? $default;
}

/**
 * Redirect with message
 */
function redirect_with($url, $message, $type = 'info') {
    $_SESSION['flash_message'] = $message;
    $_SESSION['flash_type'] = $type;
    header("Location: $url");
    exit;
}

/**
 * Get flash message
 */
function flash($key = 'message') {
    $message = $_SESSION["flash_$key"] ?? null;
    unset($_SESSION["flash_$key"]);
    return $message;
}
EOF

# Create web.php routes
echo "Creating web routes..."
cat > app/routes/web.php << 'EOF'
<?php
/**
 * Web Routes
 */

return function($router) {
    // Public routes (no auth required)
    $router->get('/', 'PortalController@index')->name('home');
    $router->get('/status', 'StatusController@index')->name('status');
    
    // Phone Note routes
    $router->group(['middleware' => 'auth'], function($router) {
        $router->get('/phone-note', 'PhoneNoteController@showForm')->name('phone-note.form');
        $router->get('/phone-notes', 'PhoneNoteController@index')->name('phone-notes.index');
    });
    
    // IT Support routes
    $router->group(['middleware' => 'auth'], function($router) {
        $router->get('/it-support', 'ITSupportController@showForm')->name('it-support.form');
        $router->get('/view-tickets', 'ITSupportController@viewTickets')->name('tickets.view');
    });
    
    // Admin routes
    $router->get('/admin/login', 'AdminController@loginForm')->name('admin.login');
    $router->post('/admin/login', 'AdminController@login');
    $router->get('/admin/logout', 'AdminController@logout')->name('admin.logout');
    
    $router->group(['middleware' => ['auth', 'admin']], function($router) {
        // Admin dashboard
        $router->get('/admin', 'AdminController@dashboard')->name('admin.dashboard');
        $router->get('/admin/tickets', 'AdminController@tickets')->name('admin.tickets');
        $router->post('/admin/tickets/update', 'AdminController@updateTicket')->name('admin.tickets.update');
        
        // Phone notes admin
        $router->get('/admin/phone-notes', 'AdminController@phoneNotes')->name('admin.phone-notes');
        $router->get('/admin/phone-notes/view/{id}', 'AdminController@viewPhoneNote')->name('admin.phone-note.view');
        $router->get('/admin/phone-notes/print/{id}', 'AdminController@printPhoneNote')->name('admin.phone-note.print');
        
        // IP Manager
        $router->get('/admin/ip-manager', 'AdminController@ipManager')->name('admin.ip-manager');
        $router->post('/admin/ip-manager', 'AdminController@updateIps');
    });
    
    // Error pages
    $router->get('/error/{code}', 'ErrorController@show')->name('error');
};
EOF

# Create api.php routes
echo "Creating API routes..."
cat > app/routes/api.php << 'EOF'
<?php
/**
 * API Routes
 */

return function($router) {
    // API routes with CSRF protection
    $router->group(['middleware' => ['csrf']], function($router) {
        // Phone notes API
        $router->post('/api/phone-notes/submit', 'PhoneNoteController@submit')->name('api.phone-notes.submit');
        $router->post('/api/phone-notes/status/{id}', 'PhoneNoteController@updateStatus')->name('api.phone-notes.status');
        
        // IT Support API
        $router->post('/api/it-support/submit', 'ITSupportController@submit')->name('api.it-support.submit');
        $router->get('/api/tickets/{id}', 'ITSupportController@getTicket')->name('api.tickets.get');
        $router->post('/api/tickets/{id}/comment', 'ITSupportController@addComment')->name('api.tickets.comment');
    });
    
    // Public API endpoints (no auth)
    $router->get('/api/public/status', 'StatusController@apiStatus')->name('api.public.status');
    $router->get('/api/public/summary', 'StatusController@apiSummary')->name('api.public.summary');
};
EOF

# Create PortalController
echo "Creating PortalController..."
cat > app/src/Controllers/PortalController.php << 'EOF'
<?php
namespace App\Controllers;

class PortalController extends BaseController {
    public function index() {
        $user = $_SERVER['PHP_AUTH_USER'] ?? 'User';
        
        return $this->view('portal.index', [
            'user' => $user
        ]);
    }
}
EOF

# Create AdminController
echo "Creating AdminController..."
cat > app/src/Controllers/AdminController.php << 'EOF'
<?php
namespace App\Controllers;

use App\Models\ITTicket;
use App\Models\PhoneNote;
use App\Services\AuthService;

class AdminController extends BaseController {
    private $authService;
    private $ticketModel;
    private $phoneNoteModel;
    
    public function __construct() {
        $this->authService = new AuthService();
        $this->ticketModel = new ITTicket();
        $this->phoneNoteModel = new PhoneNote();
    }
    
    public function loginForm() {
        if ($this->isAdmin()) {
            return $this->redirect('/admin');
        }
        
        return $this->view('admin.login', [
            'csrf_token' => $this->generateCsrf()
        ]);
    }
    
    public function login() {
        if (!$this->validateCsrf()) {
            return $this->redirect('/admin/login')->with('error', 'Invalid security token');
        }
        
        $username = $this->input('username');
        $password = $this->input('password');
        
        if ($this->authService->authenticate($username, $password)) {
            return $this->redirect('/admin');
        }
        
        return $this->redirect('/admin/login')->with('error', 'Invalid credentials');
    }
    
    public function logout() {
        $this->authService->logout();
        return $this->redirect('/admin/login');
    }
    
    public function dashboard() {
        $stats = [
            'tickets' => $this->ticketModel->getStats(),
            'phone_notes' => $this->phoneNoteModel->getStats()
        ];
        
        return $this->view('admin.dashboard', compact('stats'));
    }
    
    public function tickets() {
        $status = $this->input('status', 'all');
        $tickets = $this->ticketModel->getAllWithStatus($status);
        $stats = $this->ticketModel->getStats();
        
        return $this->view('admin.tickets', compact('tickets', 'stats', 'status'));
    }
    
    public function updateTicket() {
        if (!$this->validateCsrf()) {
            return $this->json(['error' => 'Invalid token'], 403);
        }
        
        $ticketId = $this->input('ticket_id');
        $status = $this->input('status');
        
        if ($this->ticketModel->updateStatus($ticketId, $status)) {
            return $this->redirect('/admin/tickets?updated=1');
        }
        
        return $this->redirect('/admin/tickets')->with('error', 'Failed to update ticket');
    }
    
    public function phoneNotes() {
        $page = $this->input('page', 1);
        $search = $this->input('search', '');
        $filter = $this->input('filter', 'all');
        
        $notes = $this->phoneNoteModel->getNotes($page, $search, $filter);
        $totalPages = $this->phoneNoteModel->getTotalPages($search, $filter);
        
        return $this->view('admin.phone-notes', compact('notes', 'totalPages', 'page'));
    }
    
    public function viewPhoneNote($id) {
        $note = $this->phoneNoteModel->getById($id);
        
        if (!$note) {
            return $this->abort(404, 'Phone note not found');
        }
        
        return $this->view('admin.phone-note-view', compact('note'));
    }
    
    public function printPhoneNote($id) {
        $note = $this->phoneNoteModel->getById($id);
        
        if (!$note) {
            return $this->abort(404, 'Phone note not found');
        }
        
        return $this->view('phone-note.print', compact('note'));
    }
    
    public function ipManager() {
        // IP Manager logic would go here
        return $this->view('admin.ip-manager');
    }
}
EOF

# Create StatusController
echo "Creating StatusController..."
cat > app/src/Controllers/StatusController.php << 'EOF'
<?php
namespace App\Controllers;

class StatusController extends BaseController {
    public function index() {
        $checks = [
            'PHP Version' => phpversion(),
            'Session Active' => session_status() === PHP_SESSION_ACTIVE ? 'Yes' : 'No',
            'App Directory' => is_dir(APP_PATH) ? 'Found' : 'Missing',
            'Vendor Directory' => is_dir(APP_PATH . '/vendor') ? 'Found' : 'Missing',
            'Storage Directory' => is_dir(STORAGE_PATH) ? 'Found' : 'Missing',
            'Environment File' => file_exists(APP_PATH . '/.env') ? 'Found' : 'Missing',
            'Your IP' => $_SERVER['REMOTE_ADDR'],
            'Authenticated' => isset($_SERVER['PHP_AUTH_USER']) ? 'Yes (as ' . $_SERVER['PHP_AUTH_USER'] . ')' : 'No'
        ];
        
        return $this->view('status', compact('checks'));
    }
    
    public function apiStatus() {
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
    
    public function apiSummary() {
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
        
        echo "System is operational!\n";
        exit;
    }
}
EOF

# Update ITSupportController to extend BaseController
echo "Updating ITSupportController..."
sed -i '1,10 s/class ITSupportController {/class ITSupportController extends BaseController {/' app/src/Controllers/ITSupportController.php

# Update PhoneNoteController to extend BaseController
echo "Updating PhoneNoteController..."
sed -i '1,10 s/class PhoneNoteController/class PhoneNoteController extends BaseController/' app/src/Controllers/PhoneNoteController.php

# Create new index.php
echo "Creating new index.php..."
cp public_html/index.php public_html/index.php.backup
cat > public_html/index.php << 'EOF'
<?php
// GMPM Application Entry Point with Router

// Define base paths
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('PUBLIC_PATH', __DIR__);

// Security check
if (file_exists(__DIR__ . '/.env')) {
    die('Security Error: .env file should not be in public directory!');
}

// Check vendor directory
if (!file_exists(APP_PATH . '/vendor/autoload.php')) {
    die('Error: Vendor directory not found. Please run composer install.');
}

// Load bootstrap
require_once APP_PATH . '/src/bootstrap.php';

// Initialize router
$router = new \App\Core\Router();

// Load web routes
$webRoutes = require APP_PATH . '/routes/web.php';
$webRoutes($router);

// Load API routes
$apiRoutes = require APP_PATH . '/routes/api.php';
$apiRoutes($router);

// Get request details
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Log access for security monitoring
\App\Services\Logger::channel('access')->info("Access", [
    'ip' => $_SERVER['REMOTE_ADDR'],
    'method' => $method,
    'uri' => $uri,
    'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
]);

try {
    // Dispatch the request
    $router->dispatch($method, $uri);
} catch (\Exception $e) {
    // Log the error
    \App\Services\Logger::channel('app')->error("Router exception", [
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
    
    // Show error page
    http_response_code(500);
    if (file_exists(RESOURCE_PATH . '/views/errors/500.php')) {
        include RESOURCE_PATH . '/views/errors/500.php';
    } else {
        echo "500 - Internal Server Error";
    }
}
EOF

# Create portal index view
echo "Creating portal view..."
mkdir -p app/resources/views/portal
cat > app/resources/views/portal/index.php << 'EOF'
<!DOCTYPE html>
<html>
<head>
    <title>GMPM Portal</title>
    <link rel="stylesheet" href="<?php echo asset('css/app.min.css'); ?>">
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>Greater Maryland Pain Management Portal</h1>
            </div>
            <div class="form-content">
                <p>Welcome, <?php echo htmlspecialchars($user); ?>!</p>
                <div style="display: grid; gap: 1rem; margin-top: 2rem;">
                    <a href="<?php echo url('/phone-note'); ?>" class="btn btn-primary">📞 Phone Note Form</a>
                    <a href="<?php echo url('/it-support'); ?>" class="btn btn-secondary">💻 IT Support Request</a>
                    <a href="<?php echo url('/view-tickets'); ?>" class="btn btn-secondary">📋 View Tickets</a>
                    <a href="<?php echo url('/admin'); ?>" class="btn btn-secondary">🔧 Admin Area</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
EOF

# Create status view
echo "Creating status view..."
cat > app/resources/views/status.php << 'EOF'
<!DOCTYPE html>
<html>
<head>
    <title>GMPM System Status</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; max-width: 600px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .good { color: green; }
        .bad { color: red; }
    </style>
</head>
<body>
    <h1>GMPM System Status</h1>
    <table>
        <?php foreach($checks as $check => $status): ?>
        <tr>
            <th><?php echo $check; ?></th>
            <td class="<?php echo strpos($status, 'Missing') === false ? 'good' : 'bad'; ?>">
                <?php echo $status; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <p><a href="<?php echo url('/'); ?>">Back to Main Site</a></p>
</body>
</html>
EOF

# Update composer autoload
echo "Updating composer autoload..."
cd app/
composer dump-autoload

echo ""
echo "==================================="
echo "Step 3 Complete!"
echo "==================================="
echo ""
echo "Router system has been implemented!"
echo ""
echo "Next steps:"
echo "1. Test the new routing system"
echo "2. Update remaining controllers if needed"
echo "3. Create any missing middleware classes"
echo "4. Proceed to Step 4: Implement Logging"
echo ""
echo "Test URLs:"
echo "- https://gmpm.us/ (should show portal)"
echo "- https://gmpm.us/status (should show status page)"
echo "- https://gmpm.us/phone-note (phone note form)"
echo "- https://gmpm.us/it-support (IT support form)"
echo ""
echo "Backup created: public_html/index.php.backup"
