<?php

namespace App\Core;

class Env
{
    private static $variables = [];
    private static $loaded = false;
    
    public static function load($path = null)
    {
        if (self::$loaded) {
            return;
        }
        
        if ($path === null) {
            // Look for .env in app directory (outside public)
            $path = dirname(APP_PATH) . '/app/.env';
        }
        
        if (!file_exists($path)) {
            throw new \Exception('.env file not found at: ' . $path);
        }
        
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            
            // Parse key=value
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes if present
            if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                $value = substr($value, 1, -1);
            }
            
            self::$variables[$key] = $value;
            
            // Also set as environment variable
            putenv("$key=$value");
        }
        
        self::$loaded = true;
    }
    
    public static function get($key, $default = null)
    {
        if (!self::$loaded) {
            self::load();
        }
        
        return self::$variables[$key] ?? getenv($key) ?: $default;
    }
}
