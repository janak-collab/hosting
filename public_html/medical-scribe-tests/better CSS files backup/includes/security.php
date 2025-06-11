<?php
/**
 * Security configuration and helper functions
 */

// Security constants
define('CSRF_TOKEN_NAME', 'csrf_token');
define('CSRF_TIMEOUT', 3600); // 1 hour in seconds

/**
 * Initialize CSRF protection
 * 
 * @return void
 */
function initializeCsrf() {
    if (!isset($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
}

/**
 * Generates and returns CSRF token input field
 * 
 * @return string HTML input field containing CSRF token
 */
function generateCsrfTokenField() {
    initializeCsrf();
    return sprintf(
        '<input type="hidden" name="%s" value="%s">',
        CSRF_TOKEN_NAME,
        $_SESSION[CSRF_TOKEN_NAME]
    );
}

/**
 * Validates CSRF token
 * 
 * @return bool True if token is valid, false otherwise
 */
function validateCsrfToken() {
    if (!isset($_SESSION[CSRF_TOKEN_NAME]) || 
        !isset($_POST[CSRF_TOKEN_NAME]) || 
        !hash_equals($_SESSION[CSRF_TOKEN_NAME], $_POST[CSRF_TOKEN_NAME])) {
        return false;
    }
    return true;
}

/**
 * Enhanced input sanitization
 * 
 * @param mixed $data Input data to sanitize
 * @return mixed Sanitized data
 */
function secureSanitizeInput($data) {
    if (is_array($data)) {
        return array_map('secureSanitizeInput', $data);
    }
    
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    return $data;
}

/**
 * Validates and sanitizes all post data
 * 
 * @param array $data POST data to validate
 * @return array Sanitized data and any errors
 */
function validateAndSanitizeInput($data) {
    $sanitizedData = [];
    $errors = [];
    
    foreach ($data as $key => $value) {
        // Skip CSRF token from sanitization
        if ($key === CSRF_TOKEN_NAME) {
            $sanitizedData[$key] = $value;
            continue;
        }
        
        // Sanitize input
        $sanitizedValue = secureSanitizeInput($value);
        
        // Validate based on field type
        switch ($key) {
            case 'patient_name':
                if (!preg_match("/^[A-Za-z][A-Za-z\-\. ']{1,49}$/", $sanitizedValue)) {
                    $errors[] = 'Invalid patient name format';
                }
                break;
                
            case 'dob':
            case 'dos':
                if (!validateDate($sanitizedValue)) {
                    $errors[] = "Invalid date format for {$key}";
                }
                break;
                
            case 'provider_name':
                if (!empty($sanitizedValue) && !in_array($sanitizedValue, ALLOWED_PROVIDERS)) {
                    $errors[] = 'Invalid provider selected';
                }
                break;
        }
        
        $sanitizedData[$key] = $sanitizedValue;
    }
    
    return [
        'data' => $sanitizedData,
        'errors' => $errors
    ];
}

/**
 * Validates date format
 * 
 * @param string $date Date string to validate
 * @param string $format Expected date format
 * @return bool True if date is valid
 */
function validateDate($date, $format = 'Y-m-d') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

/**
 * Enhanced error logging
 * 
 * @param string $message Error message
 * @param array $context Additional context data
 * @return void
 */
function logSecurityError($message, array $context = []) {
    $logEntry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'message' => $message,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
        'context' => $context
    ];
    
    error_log(json_encode($logEntry));
}

// Initialize session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize CSRF protection
initializeCsrf();