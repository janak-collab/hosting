#!/bin/bash
# Check .htaccess authentication configuration

echo "==================================="
echo "Checking Authentication Setup"
echo "==================================="
echo ""

# Check .htaccess auth configuration
echo "Current .htaccess authentication configuration:"
echo "----------------------------------------"
grep -A 5 -B 2 "AuthType\|AuthName\|AuthUserFile" public_html/.htaccess 2>/dev/null || echo "No auth configuration found in .htaccess"
echo "----------------------------------------"

# Check if .htpasswds file exists
echo ""
echo "Checking password file location..."
htpasswd_file=$(grep "AuthUserFile" public_html/.htaccess 2>/dev/null | awk '{print $2}' | tr -d '"')
if [ -n "$htpasswd_file" ]; then
    echo "Password file: $htpasswd_file"
    if [ -f "$htpasswd_file" ]; then
        echo "✓ Password file exists"
        echo ""
        echo "Users in password file:"
        # Show usernames only (not passwords)
        cut -d: -f1 "$htpasswd_file" | sed 's/^/  - /'
    else
        echo "✗ Password file not found at: $htpasswd_file"
    fi
else
    echo "No AuthUserFile directive found"
fi

# Check for IP-based restrictions
echo ""
echo "Checking IP restrictions..."
echo "----------------------------------------"
grep -E "RewriteCond.*REMOTE_ADDR|Require ip|Allow from" public_html/.htaccess 2>/dev/null | head -n 10
echo "----------------------------------------"

# Get current IP
echo ""
echo "Your current IP address: $(curl -s ifconfig.me 2>/dev/null || echo 'Unable to determine')"

# Create a test without .htaccess
echo ""
echo "Creating auth bypass test..."
cat > public_html/auth-test.php << 'EOF'
<?php
// Display auth information
echo "<h2>Authentication Test</h2>";
echo "<pre>";
echo "PHP_AUTH_USER: " . ($_SERVER['PHP_AUTH_USER'] ?? 'Not set') . "\n";
echo "PHP_AUTH_PW: " . (isset($_SERVER['PHP_AUTH_PW']) ? '(set)' : 'Not set') . "\n";
echo "REMOTE_USER: " . ($_SERVER['REMOTE_USER'] ?? 'Not set') . "\n";
echo "REMOTE_ADDR: " . $_SERVER['REMOTE_ADDR'] . "\n";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "</pre>";

// Show allowed IPs from config if available
if (file_exists('../app/config/security.php')) {
    $security = include '../app/config/security.php';
    if (isset($security['ip_whitelist'])) {
        echo "<h3>Allowed IPs:</h3><ul>";
        foreach ($security['ip_whitelist'] as $ip => $location) {
            $current = ($_SERVER['REMOTE_ADDR'] == $ip) ? ' (YOU)' : '';
            echo "<li>$ip - $location$current</li>";
        }
        echo "</ul>";
    }
}
?>
EOF

echo ""
echo "Test page created. Visit: https://gmpm.us/auth-test.php"
echo ""
echo "This will show your authentication status and IP address."
echo ""
echo "To remove test file, run: rm public_html/auth-test.php"
echo ""
echo "==================================="
echo "Authentication check complete!"
echo "====================================="
