#!/bin/bash

echo "==================================="
echo "Creating update_logging.sh Script"
echo "==================================="

# Create the proper update_logging.sh script
cat > update_logging.sh << 'SCRIPT_END'
#!/bin/bash

echo "==================================="
echo "Updating Codebase to Use Logger"
echo "==================================="

# Create the Logger service file
echo "Creating Logger service..."
mkdir -p app/src/Services

# First, let's create the Logger.php file directly
cat > app/src/Services/Logger.php << 'EOF'
<?php
namespace App\Services;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Formatter\LineFormatter;

class Logger
{
    private static $instances = [];
    
    /**
     * Get a logger instance for a specific channel
     * 
     * @param string $channel
     * @return MonologLogger
     */
    public static function channel($channel = 'app')
    {
        if (!isset(self::$instances[$channel])) {
            self::$instances[$channel] = self::createLogger($channel);
        }
        
        return self::$instances[$channel];
    }
    
    /**
     * Create a new logger instance
     * 
     * @param string $channel
     * @return MonologLogger
     */
    private static function createLogger($channel)
    {
        $logger = new MonologLogger($channel);
        
        // Define log path
        $logPath = STORAGE_PATH . '/logs/';
        
        // Create log directory if it doesn't exist
        if (!is_dir($logPath)) {
            mkdir($logPath, 0755, true);
        }
        
        // Set log level based on environment
        $logLevel = env('APP_DEBUG', false) ? MonologLogger::DEBUG : MonologLogger::INFO;
        
        // Create formatter
        $formatter = new LineFormatter(
            "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n",
            "Y-m-d H:i:s",
            true,
            true
        );
        
        // Add handlers based on channel
        switch ($channel) {
            case 'access':
                // Access logs - daily rotation
                $handler = new RotatingFileHandler(
                    $logPath . 'access.log',
                    30, // Keep 30 days
                    MonologLogger::INFO
                );
                break;
                
            case 'security':
                // Security logs - keep longer
                $handler = new RotatingFileHandler(
                    $logPath . 'security.log',
                    90, // Keep 90 days
                    MonologLogger::WARNING
                );
                break;
                
            case 'sql':
                // SQL logs - only in debug mode
                if (env('APP_DEBUG', false)) {
                    $handler = new StreamHandler(
                        $logPath . 'sql.log',
                        MonologLogger::DEBUG
                    );
                } else {
                    // In production, don't log SQL queries
                    $handler = new StreamHandler(
                        'php://memory',
                        MonologLogger::ERROR
                    );
                }
                break;
                
            case 'error':
                // Error logs - always log
                $handler = new RotatingFileHandler(
                    $logPath . 'error.log',
                    60, // Keep 60 days
                    MonologLogger::ERROR
                );
                break;
                
            default:
                // Default app logs
                $handler = new RotatingFileHandler(
                    $logPath . $channel . '.log',
                    30,
                    $logLevel
                );
        }
        
        $handler->setFormatter($formatter);
        $logger->pushHandler($handler);
        
        // Add processor to include useful information
        $logger->pushProcessor(function ($record) {
            $record['extra']['ip'] = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $record['extra']['user'] = $_SESSION['user_id'] ?? $_SERVER['PHP_AUTH_USER'] ?? 'guest';
            $record['extra']['request_id'] = self::getRequestId();
            
            return $record;
        });
        
        return $logger;
    }
    
    /**
     * Get or generate a unique request ID
     * 
     * @return string
     */
    private static function getRequestId()
    {
        static $requestId = null;
        
        if ($requestId === null) {
            $requestId = uniqid('req_', true);
        }
        
        return $requestId;
    }
    
    /**
     * Log to a specific channel
     * 
     * @param string $channel
     * @param string $level
     * @param string $message
     * @param array $context
     */
    public static function log($channel, $level, $message, array $context = [])
    {
        $logger = self::channel($channel);
        $logger->log($level, $message, $context);
    }
    
    // Convenience methods for common channels
    
    /**
     * Log an error
     */
    public static function error($message, array $context = [])
    {
        self::channel('error')->error($message, $context);
    }
    
    /**
     * Log a warning
     */
    public static function warning($message, array $context = [])
    {
        self::channel('app')->warning($message, $context);
    }
    
    /**
     * Log info
     */
    public static function info($message, array $context = [])
    {
        self::channel('app')->info($message, $context);
    }
    
    /**
     * Log debug info
     */
    public static function debug($message, array $context = [])
    {
        self::channel('app')->debug($message, $context);
    }
    
    /**
     * Log access
     */
    public static function access($message, array $context = [])
    {
        self::channel('access')->info($message, $context);
    }
    
    /**
     * Log security event
     */
    public static function security($message, array $context = [])
    {
        self::channel('security')->warning($message, $context);
    }
    
    /**
     * Log SQL query (only in debug mode)
     */
    public static function sql($query, array $bindings = [], $time = null)
    {
        if (env('APP_DEBUG', false)) {
            $context = [
                'bindings' => $bindings,
                'time' => $time
            ];
            self::channel('sql')->debug($query, $context);
        }
    }
}
EOF

echo "✓ Logger service created"

# Create log directories
echo "Creating log directories..."
mkdir -p app/storage/logs
touch app/storage/logs/.gitkeep
chmod -R 775 app/storage/logs
echo "✓ Log directories created"

# Create sample usage file
echo "Creating sample usage file..."
mkdir -p app/docs
cat > app/docs/logging-usage.md << 'EOF'
# GMPM Logging Usage Guide

## Basic Usage

```php
use App\Services\Logger;

// Log errors
Logger::error('Database connection failed', [
    'host' => $host,
    'error' => $e->getMessage()
]);

// Log warnings
Logger::warning('Rate limit approaching', [
    'user' => $userId,
    'requests' => $requestCount
]);

// Log info
Logger::info('User logged in', [
    'user' => $username,
    'ip' => $_SERVER['REMOTE_ADDR']
]);

// Log debug (only in debug mode)
Logger::debug('API request', [
    'endpoint' => $endpoint,
    'params' => $params
]);
```

## Channel-Specific Logging

```php
// Access logs
Logger::access('Page viewed', [
    'url' => $_SERVER['REQUEST_URI'],
    'method' => $_SERVER['REQUEST_METHOD']
]);

// Security logs
Logger::security('Failed login attempt', [
    'username' => $username,
    'ip' => $_SERVER['REMOTE_ADDR']
]);

// SQL logs (only in debug mode)
Logger::sql('SELECT * FROM users WHERE id = ?', [123], 0.0023);
```

## In Controllers

```php
namespace App\Controllers;

use App\Services\Logger;

class ExampleController extends BaseController
{
    public function index()
    {
        Logger::access('Example page accessed');
        
        try {
            // Your code here
        } catch (\Exception $e) {
            Logger::error('Example error', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
```

## Log Files

Logs are stored in `app/storage/logs/`:
- `app.log` - General application logs
- `access.log` - Access logs (rotated daily)
- `error.log` - Error logs (kept for 60 days)
- `security.log` - Security events (kept for 90 days)
- `sql.log` - SQL queries (debug mode only)

## Configuration

Set in `.env`:
```
APP_DEBUG=false  # Set to true for debug logging
```
EOF

echo "✓ Logging documentation created"

# Update STORAGE_PATH in bootstrap if not defined
if ! grep -q "define('STORAGE_PATH'" app/src/bootstrap.php; then
    echo "Adding STORAGE_PATH to bootstrap.php..."
    sed -i "/define('APP_PATH'/a define('STORAGE_PATH', dirname(__DIR__) . '/storage');" app/src/bootstrap.php
    echo "✓ Added STORAGE_PATH definition"
fi

echo ""
echo "==================================="
echo "Logging Implementation Complete!"
echo "==================================="
echo ""
echo "Logger service created at: app/src/Services/Logger.php"
echo "Log directory created at: app/storage/logs/"
echo "Documentation created at: app/docs/logging-usage.md"
echo ""
echo "Next steps:"
echo "1. Test logging: Visit https://gmpm.us/test-logging.php"
echo "2. Update error_log calls in your code to use Logger"
echo "3. Monitor logs: tail -f app/storage/logs/*.log"
SCRIPT_END

# Make the script executable
chmod +x update_logging.sh

echo "✓ Created update_logging.sh"
echo ""
echo "Now run: ./update_logging.sh"
