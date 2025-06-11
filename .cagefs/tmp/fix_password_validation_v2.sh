#!/bin/bash

echo "=== Fixing UserService password validation ==="

# First, let's see the current validatePassword method
echo "Current method:"
grep -n -B2 -A20 "function validatePassword" /home/gmpmus/app/src/Services/UserService.php | head -30

# Create the new validatePassword method
cat > /tmp/new_validatePassword.php << 'INNEREOF'
    public function validatePassword($password) {
        // Check if password is null or empty first
        if (empty($password)) {
            return false;
        }
        
        // At least 12 characters
        if (strlen($password) < 12) {
            return false;
        }
        
        // At least one uppercase
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }
        
        // At least one lowercase
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }
        
        // At least one number
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }
        
        // At least one special character
        if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
            return false;
        }
        
        return true;
    }
INNEREOF

# Backup the current file
cp /home/gmpmus/app/src/Services/UserService.php /home/gmpmus/app/src/Services/UserService.php.backup3

# Use PHP to replace the method properly
php << 'PHP'
<?php
$file = '/home/gmpmus/app/src/Services/UserService.php';
$content = file_get_contents($file);

// More flexible pattern to match the validatePassword method
$pattern = '/public function validatePassword\([^)]*\)\s*\{[^}]*\}/s';
$newMethod = trim(file_get_contents('/tmp/new_validatePassword.php'));

if (preg_match($pattern, $content)) {
    $content = preg_replace($pattern, $newMethod, $content);
    file_put_contents($file, $content);
    echo "✓ validatePassword method updated successfully\n";
} else {
    echo "✗ Could not find validatePassword method with simple pattern\n";
    // Try a different approach - find the method and replace it manually
    $lines = file($file);
    $inMethod = false;
    $braceCount = 0;
    $startLine = -1;
    $endLine = -1;
    
    for ($i = 0; $i < count($lines); $i++) {
        if (strpos($lines[$i], 'public function validatePassword') !== false) {
            $startLine = $i;
            $inMethod = true;
        }
        
        if ($inMethod) {
            $braceCount += substr_count($lines[$i], '{');
            $braceCount -= substr_count($lines[$i], '}');
            
            if ($braceCount == 0 && strpos($lines[$i], '}') !== false) {
                $endLine = $i;
                break;
            }
        }
    }
    
    if ($startLine >= 0 && $endLine >= 0) {
        // Remove old method
        array_splice($lines, $startLine, $endLine - $startLine + 1);
        
        // Insert new method
        $newMethodLines = explode("\n", $newMethod);
        array_splice($lines, $startLine, 0, $newMethodLines);
        
        file_put_contents($file, implode("", $lines));
        echo "✓ validatePassword method replaced manually\n";
    }
}
PHP

# Also update the createUser method to show better error messages
echo -e "\n=== Updating createUser for better error messages ==="
php << 'PHP'
<?php
$file = '/home/gmpmus/app/src/Services/UserService.php';
$content = file_get_contents($file);

// Update the createUser method to throw exception with password validation details
$pattern = '/if \(!\\$this->validatePassword\(\\$data\[\'password\'\]\)\) \{[^}]*\}/';
$replacement = 'if (!$this->validatePassword($data[\'password\'])) {
            throw new Exception(\'Password does not meet requirements: minimum 12 characters, must contain uppercase, lowercase, number, and special character\');
        }';

$content = preg_replace($pattern, $replacement, $content);
file_put_contents($file, $content);
echo "✓ Updated error message for password validation\n";
PHP

echo -e "\n=== Testing password validation ==="
# Test with a valid password
curl -X POST https://gmpm.us/api/users/create-user.php \
  -u "$USER:$PASS" \
  -H "Content-Type: application/json" \
  -d '{
    "username": "pwtest'$(date +%s)'",
    "password": "ValidPass123!@#",
    "full_name": "Password Test User",
    "email": "pwtest'$(date +%s)'@test.com",
    "role": "user"
  }' | jq .
