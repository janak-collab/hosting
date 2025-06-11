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
