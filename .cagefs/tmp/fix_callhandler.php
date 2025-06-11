<?php
$file = '/home/gmpmus/app/src/Core/Router.php';
$content = file_get_contents($file);

// Replace the entire callHandler method
$newMethod = '    private function callHandler($handler, $params = []) {
        error_log(\'GMPM Router: callHandler called with handler: \' . print_r($handler, true));
        error_log(\'GMPM Router: handler type: \' . gettype($handler));
        
        // String format: Controller@method
        if (is_string($handler)) {
            error_log(\'GMPM Router: Processing string handler: \' . $handler);
            if (strpos($handler, \'@\') === false) {
                error_log(\'GMPM Router: ERROR - No @ symbol in handler: \' . $handler);
                throw new \Exception("Invalid handler format: {$handler}");
            }
            
            list($controllerName, $method) = explode(\'@\', $handler);
            error_log(\'GMPM Router: Controller: \' . $controllerName . \', Method: \' . $method);
            
            // Add namespace if not present
            if (strpos($controllerName, \'\\\\\') === false) {
                $controllerClass = \'\\\\App\\\\Controllers\\\\\' . $controllerName;
            } else {
                $controllerClass = $controllerName;
            }
            
            error_log(\'GMPM Router: Full controller class: \' . $controllerClass);
            
            // Check if controller exists
            if (!class_exists($controllerClass)) {
                error_log(\'GMPM Router: ERROR - Controller class not found: \' . $controllerClass);
                throw new \Exception("Controller not found: {$controllerClass}");
            }
            
            $controller = new $controllerClass();
            
            // Check if method exists
            if (!method_exists($controller, $method)) {
                error_log(\'GMPM Router: ERROR - Method not found: \' . $method . \' in \' . $controllerClass);
                throw new \Exception("Method not found: {$method} in {$controllerClass}");
            }
            
            error_log(\'GMPM Router: Calling method \' . $method . \' on controller \' . $controllerClass);
            return call_user_func_array([$controller, $method], $params);
        }
        
        // Callable (closure or array)
        if (is_callable($handler)) {
            error_log(\'GMPM Router: Processing callable handler\');
            return call_user_func_array($handler, $params);
        }
        
        error_log(\'GMPM Router: ERROR - Invalid handler type\');
        throw new \Exception("Invalid handler type");
    }';

// Find and replace the method
$pattern = '/private function callHandler\([^{]*\{[^}]*\}/s';
$count = 0;
$content = preg_replace($pattern, $newMethod, $content, 1, $count);

if ($count > 0) {
    file_put_contents($file, $content);
    echo "callHandler method replaced successfully\n";
} else {
    echo "Failed to replace callHandler method\n";
}
