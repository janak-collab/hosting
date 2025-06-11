# Manual Log Updates Guide

## Common Replacements

### Error Logging
```php
// Old
error_log("Error: " . $e->getMessage());

// New
Logger::error($e->getMessage(), [
    'exception' => get_class($e),
    'trace' => $e->getTraceAsString()
]);
```

### Info Logging
```php
// Old
error_log("User $username logged in");

// New
Logger::info('User logged in', ['username' => $username]);
```

### Access Logging
```php
// Old
error_log("Access: " . $_SERVER['REMOTE_ADDR'] . " - " . $requestUri);

// New
Logger::access('Page accessed', [
    'uri' => $requestUri,
    'ip' => $_SERVER['REMOTE_ADDR']
]);
```

### Debug Logging
```php
// Old
error_log("Debug: " . print_r($data, true));

// New
Logger::debug('Debug data', ['data' => $data]);
```

## Don't Forget

1. Add `use App\Services\Logger;` at the top of each file
2. Use structured data in the context array instead of string concatenation
3. Choose appropriate log levels (error, warning, info, debug)
4. Use specific channels (access, security, sql) when appropriate
