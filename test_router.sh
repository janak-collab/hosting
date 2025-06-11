#!/bin/bash
# Test Router Implementation

echo "==================================="
echo "Testing Router Implementation"
echo "==================================="
echo ""

# Function to test URL
test_url() {
    local url=$1
    local expected=$2
    echo -n "Testing $url... "
    
    # Use curl with basic auth
    response=$(curl -s -o /dev/null -w "%{http_code}" -u "username:password" "$url")
    
    if [ "$response" == "$expected" ]; then
        echo "✓ (HTTP $response)"
    else
        echo "✗ (Expected $expected, got $response)"
    fi
}

# Check if files exist
echo "Checking files..."
echo -n "Router.php... "
[ -f "app/src/Core/Router.php" ] && echo "✓" || echo "✗"

echo -n "BaseController.php... "
[ -f "app/src/Controllers/BaseController.php" ] && echo "✓" || echo "✗"

echo -n "web.php routes... "
[ -f "app/routes/web.php" ] && echo "✓" || echo "✗"

echo -n "api.php routes... "
[ -f "app/routes/api.php" ] && echo "✓" || echo "✗"

echo -n "New index.php... "
[ -f "public_html/index.php" ] && echo "✓" || echo "✗"

echo ""

# Test PHP syntax
echo "Checking PHP syntax..."
php -l app/src/Core/Router.php 2>&1 | grep -q "No syntax errors" && echo "Router.php: ✓" || echo "Router.php: ✗"
php -l app/src/Controllers/BaseController.php 2>&1 | grep -q "No syntax errors" && echo "BaseController.php: ✓" || echo "BaseController.php: 
✗"
php -l public_html/index.php 2>&1 | grep -q "No syntax errors" && echo "index.php: ✓" || echo "index.php: ✗"

echo ""

# Test URLs (if server is accessible)
echo "Testing routes (replace with your auth credentials)..."
echo "Note: Replace 'username:password' with actual HTTP Basic Auth credentials"
echo ""

# Test basic routes
test_url "https://gmpm.us/" "200"
test_url "https://gmpm.us/status" "200"
test_url "https://gmpm.us/phone-note" "200"
test_url "https://gmpm.us/it-support" "200"
test_url "https://gmpm.us/admin/login" "200"
test_url "https://gmpm.us/nonexistent" "404"

echo ""

# Check error logs
echo "Recent errors in error_log:"
if [ -f "public_html/error_log" ]; then
    tail -n 10 public_html/error_log | grep -E "(Fatal|Parse|Exception)" || echo "No critical errors found"
else
    echo "No error_log file found"
fi

echo ""
echo "==================================="
echo "Router test complete!"
echo "==================================="
echo ""
echo "If all tests pass, proceed to Step 4: Implement Logging"
echo "If tests fail, check:"
echo "1. PHP syntax in the generated files"
echo "2. Namespace declarations"
echo "3. File permissions"
echo "4. Error logs for detailed information"
