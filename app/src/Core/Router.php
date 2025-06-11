<?php
namespace App\Core;

class Router {
    private $routes = [];
    private $middlewares = [];
    
    public function get($path, $handler, $middlewares = []) {
        $this->addRoute('GET', $path, $handler, $middlewares);
    }
    
    public function post($path, $handler, $middlewares = []) {
        $this->addRoute('POST', $path, $handler, $middlewares);
    }
    
    public function put($path, $handler, $middlewares = []) {
        $this->addRoute('PUT', $path, $handler, $middlewares);
    }
    
    public function delete($path, $handler, $middlewares = []) {
        $this->addRoute('DELETE', $path, $handler, $middlewares);
    }
    
    private function addRoute($method, $path, $handler, $middlewares) {
        $pattern = preg_replace('/\{([^}]+)\}/', '(?P<$1>[^/]+)', $path);
        $pattern = '#^' . $pattern . '$#';
        
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'pattern' => $pattern,
            'handler' => $handler,
            'middlewares' => $middlewares
        ];
    }
    
    public function dispatch($method, $uri) {
    // Debug output
    error_log("Router::dispatch called - Method: $method, URI: $uri");
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $uri, $matches)) {
                // Extract parameters
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                // Run middlewares
                foreach ($route['middlewares'] as $middleware) {
                    if (!$this->runMiddleware($middleware)) {
                        return;
                    }
                }
                
                // Execute handler
                $this->executeHandler($route['handler'], $params);
                exit; // Stop execution after handling route
            }
        }
        
        // No route found
        http_response_code(404);
        if (file_exists(RESOURCE_PATH . '/views/errors/404.php')) {
            include RESOURCE_PATH . '/views/errors/404.php';
        } else {
            echo '404 - Not Found';
        }
    }
    
    private function runMiddleware($middleware) {
        if (is_string($middleware) && class_exists($middleware)) {
            $instance = new $middleware();
            if (method_exists($instance, 'handle')) {
                return $instance->handle();
            }
        }
        return true;
    }
    
    private function executeHandler($handler, $params) {
        if (is_string($handler)) {
            if (strpos($handler, '@') !== false) {
                list($controller, $method) = explode('@', $handler);
                $controllerClass = "App\\Controllers\\{$controller}";
                
                if (class_exists($controllerClass)) {
                    $instance = new $controllerClass();
                    if (method_exists($instance, $method)) {
                        call_user_func_array([$instance, $method], $params);
                    } else {
                        throw new \Exception("Method {$method} not found in {$controllerClass}");
                    }
                } else {
                    throw new \Exception("Controller {$controllerClass} not found");
                }
            }
        } elseif (is_callable($handler)) {
            call_user_func_array($handler, $params);
        }
    }
}
