# GMPM Logging System Documentation

## Overview
The GMPM application uses a centralized logging system based on Monolog with daily rotating log files.

## Log Channels

### 1. App Log (`app-YYYY-MM-DD.log`)
- General application events
- Info, warning, and debug messages
- User actions and system events

### 2. Error Log (`error-YYYY-MM-DD.log`)
- All error-level messages
- Exceptions and critical issues
- Stack traces when available

### 3. Security Log (`security-YYYY-MM-DD.log`)
- Authentication attempts
- Access violations
- Security-related events

### 4. Access Log (`access-YYYY-MM-DD.log`)
- Page visits
- API calls
- User navigation tracking

### 5. SQL Log (`sql.log`)
- Database queries (debug mode only)
- Query execution times
- Useful for performance optimization

## Usage Examples

```php
use App\Services\Logger;

// Simple logging
Logger::info('User logged in', ['user_id' => $userId]);
Logger::error('Failed to process payment', ['error' => $e->getMessage()]);

// Channel-specific logging
Logger::channel('security')->warning('Failed login attempt', ['username' => $username]);
Logger::channel('sql')->debug($query, ['bindings' => $bindings, 'time' => $executionTime]);
