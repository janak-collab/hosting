#!/bin/bash
echo "=== GMPM Authentication Diagnostic ==="
echo "Date: $(date)"
echo ""

# Get current IP
echo "1. Your current IP address:"
MY_IP=$(echo $SSH_CONNECTION | awk '{print $1}')
echo "   SSH IP: $MY_IP"
echo "   External IP: $(curl -s ifconfig.me 2>/dev/null || echo 'Could not determine')"
echo ""

# Check if IP is whitelisted
echo "2. Checking if your IP is whitelisted..."
if grep -q "${MY_IP//./\\.}" ~/public_html/.htaccess; then
    echo "   ✓ Your IP ($MY_IP) is whitelisted"
else
    echo "   ❌ Your IP ($MY_IP) is NOT whitelisted"
fi
echo ""

# Check index.php
echo "3. Checking index.php..."
if [ -f ~/public_html/index.php ]; then
    echo "   ✓ index.php exists"
    if head -20 ~/public_html/index.php | grep -q "Location.*login"; then
        echo "   ❌ Found login redirect in index.php"
    else
        echo "   ✓ No login redirect in index.php header"
    fi
fi
echo ""

# Check for 401 handler
echo "4. Checking for 401 error handling..."
grep -n "401\|WWW-Authenticate" ~/public_html/.htaccess | head -5
echo ""

# Test HTTP response
echo "5. Testing HTTP response..."
response=$(curl -s -o /dev/null -w "%{http_code}" https://gmpm.us/)
echo "   HTTP Response Code: $response"
echo ""

echo "=== End Diagnostic ==="
