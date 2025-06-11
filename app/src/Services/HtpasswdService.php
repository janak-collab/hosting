<?php
namespace App\Services;

class HtpasswdService {
    private $htpasswdPath;
    
    public function __construct() {
        $this->htpasswdPath = $_ENV['HTPASSWD_PATH'] ?? '/home/gmpmus/.htpasswds/passwd';
    }
    
    /**
     * Add a user to htpasswd file
     */
    public function addUser($username, $password) {
        // Check if user already exists
        if ($this->userExists($username)) {
            return $this->updatePassword($username, $password);
        }
        
        // Generate bcrypt hash (Apache 2.4+ compatible)
        $hash = password_hash($password, PASSWORD_BCRYPT);
        
        // Format: username:$2y$10$hash
        $entry = $username . ':' . $hash . PHP_EOL;
        
        // Append to file
        $result = file_put_contents($this->htpasswdPath, $entry, FILE_APPEND | LOCK_EX);
        
        return $result !== false;
    }
    
    /**
     * Remove user (actually comments out the line)
     */
    public function removeUser($username) {
        $lines = file($this->htpasswdPath, FILE_IGNORE_NEW_LINES);
        $newContent = '';
        $found = false;
        
        foreach ($lines as $line) {
            if (strpos($line, $username . ':') === 0) {
                // Comment out the line
                $newContent .= '# Disabled on ' . date('Y-m-d H:i:s') . ': ' . $line . PHP_EOL;
                $found = true;
            } else {
                $newContent .= $line . PHP_EOL;
            }
        }
        
        if ($found) {
            return file_put_contents($this->htpasswdPath, $newContent, LOCK_EX) !== false;
        }
        
        return false;
    }
    
    /**
     * Update user password
     */
    public function updatePassword($username, $password) {
        $lines = file($this->htpasswdPath, FILE_IGNORE_NEW_LINES);
        $newContent = '';
        $found = false;
        
        // Generate new hash
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $newEntry = $username . ':' . $hash;
        
        foreach ($lines as $line) {
            // Skip empty lines and comments
            if (empty(trim($line)) || strpos(trim($line), '#') === 0) {
                $newContent .= $line . PHP_EOL;
                continue;
            }
            
            if (strpos($line, $username . ':') === 0) {
                // Replace with new entry
                $newContent .= $newEntry . PHP_EOL;
                $found = true;
            } else {
                $newContent .= $line . PHP_EOL;
            }
        }
        
        if ($found) {
            return file_put_contents($this->htpasswdPath, $newContent, LOCK_EX) !== false;
        }
        
        // User not found, add them
        return $this->addUser($username, $password);
    }
    
    /**
     * Check if user exists in htpasswd
     */
    public function userExists($username) {
        if (!file_exists($this->htpasswdPath)) {
            return false;
        }
        
        $lines = file($this->htpasswdPath, FILE_IGNORE_NEW_LINES);
        
        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            
            if (strpos($line, $username . ':') === 0) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Get all users from htpasswd
     */
    public function getAllUsers() {
        if (!file_exists($this->htpasswdPath)) {
            return [];
        }
        
        $users = [];
        $lines = file($this->htpasswdPath, FILE_IGNORE_NEW_LINES);
        
        foreach ($lines as $line) {
            // Skip empty lines and comments
            if (empty(trim($line)) || strpos(trim($line), '#') === 0) {
                continue;
            }
            
            $parts = explode(':', $line, 2);
            if (count($parts) === 2) {
                $users[] = $parts[0];
            }
        }
        
        return $users;
    }
    
    /**
     * Verify password for a user
     */
    public function verifyPassword($username, $password) {
        if (!file_exists($this->htpasswdPath)) {
            return false;
        }
        
        $lines = file($this->htpasswdPath, FILE_IGNORE_NEW_LINES);
        
        foreach ($lines as $line) {
            // Skip comments
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            
            if (strpos($line, $username . ':') === 0) {
                $parts = explode(':', $line, 2);
                if (count($parts) === 2) {
                    $storedHash = $parts[1];
                    
                    // Handle different hash formats
                    if (strpos($storedHash, '$2y$') === 0 || strpos($storedHash, '$2a$') === 0 || strpos($storedHash, '$2b$') === 0) {
                        // Bcrypt hash
                        return password_verify($password, $storedHash);
                    } elseif (strpos($storedHash, '$apr1$') === 0) {
                        // Apache MD5 - would need special handling
                        error_log("Apache MD5 hash detected for user $username - please update password");
                        return false;
                    } else {
                        // Plain crypt() - legacy
                        return crypt($password, $storedHash) === $storedHash;
                    }
                }
            }
        }
        
        return false;
    }
    
    /**
     * Enable a disabled user
     */
    public function enableUser($username) {
        $lines = file($this->htpasswdPath, FILE_IGNORE_NEW_LINES);
        $newContent = '';
        $found = false;
        
        foreach ($lines as $line) {
            // Look for commented out user
            if (preg_match('/^#.*' . preg_quote($username, '/') . ':/', $line)) {
                // Extract the original line
                if (preg_match('/' . preg_quote($username, '/') . ':[^\s]+/', $line, $matches)) {
                    $newContent .= $matches[0] . PHP_EOL;
                    $found = true;
                }
            } else {
                $newContent .= $line . PHP_EOL;
            }
        }
        
        if ($found) {
            return file_put_contents($this->htpasswdPath, $newContent, LOCK_EX) !== false;
        }
        
        return false;
    }
}
