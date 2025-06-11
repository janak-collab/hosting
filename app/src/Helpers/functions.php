<?php
/**
 * Global helper functions
 */

if (!function_exists('env')) {
    function env($key, $default = null) {
        return $_ENV[$key] ?? $default;
    }
}

if (!function_exists('config')) {
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
}

if (!function_exists('sanitize')) {
    function sanitize($input) {
        if (is_array($input)) {
            return array_map('sanitize', $input);
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('asset')) {
    function asset($path) {
        return rtrim(config('app.url'), '/') . '/assets/' . ltrim($path, '/');
    }
}

if (!function_exists('url')) {
    function url($path = '') {
        return rtrim(config('app.url'), '/') . '/' . ltrim($path, '/');
    }
}

if (!function_exists('dd')) {
    function dd(...$vars) {
        foreach ($vars as $var) {
            echo '<pre>';
            var_dump($var);
            echo '</pre>';
        }
        die;
    }
}

if (!function_exists('logger')) {
    function logger($channel = 'app') {
        return \App\Services\Logger::channel($channel);
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field() {
        return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
    }
}

/**
 * Render a view with optional layout
 */
function view($view, $data = [], $layout = 'layouts/base') {
    extract($data);
    
    // Start output buffering for content
    ob_start();
    require RESOURCE_PATH . '/views/' . $view . '.php';
    $content = ob_get_clean();
    
    // If layout is specified, wrap content
    if ($layout) {
        ob_start();
        require RESOURCE_PATH . '/views/' . $layout . '.php';
        return ob_get_clean();
    }
    
    return $content;
}

/**
 * Render a view and echo it
 */
function render($view, $data = [], $layout = 'layouts/base') {
    echo view($view, $data, $layout);
}

/**
 * Include a partial view
 */
function partial($partial, $data = []) {
    extract($data);
    require RESOURCE_PATH . '/views/partials/' . $partial . '.php';
}

/**
 * Old value helper for forms
 */
function old($field, $default = '') {
    return $_SESSION['old_input'][$field] ?? $_POST[$field] ?? $default;
}

/**
 * CSRF field helper
 */
function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . ($_SESSION['csrf_token'] ?? '') . '">';
}

/**
 * Method field helper
 */
function method_field($method) {
    return '<input type="hidden" name="_method" value="' . strtoupper($method) . '">';
}
