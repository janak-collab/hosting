#!/bin/bash
# Test Router with Authentication

echo "==================================="
echo "Testing Router with Authentication"
echo "==================================="
echo ""

# Prompt for credentials
echo "Enter HTTP Basic Auth credentials for testing:"
read -p "Username: " username
read -sp "Password: " password
echo ""
echo ""

# Function to test URL with auth
test_url_auth() {
    local url=$1
    local expected=$2
    echo -n "Testing $url... "
    
    # Use curl with basic auth
    response=$(curl -s -o /dev/null -w "%{http_code}" -u "$username:$password" "$url")
    
    if [ "$response" == "$expected" ]; then
        echo "✓ (HTTP $response)"
    else
        echo "✗ (Expected $expected, got $response)"
    fi
}

# Function to get content
get_content() {
    local url=$1
    echo "Getting content from $url..."
    curl -s -u "$username:$password" "$url" | head -n 20
    echo ""
}

# Test routes with authentication
echo "Testing routes with authentication..."
test_url_auth "https://gmpm.us/" "200"
test_url_auth "https://gmpm.us/status" "200"
test_url_auth "https://gmpm.us/phone-note" "200"
test_url_auth "https://gmpm.us/it-support" "200"
test_url_auth "https://gmpm.us/admin/login" "200"
test_url_auth "https://gmpm.us/nonexistent" "404"
test_url_auth "https://gmpm.us/api/public/status" "200"

echo ""

# Check if router is working by examining content
echo "Checking page content..."
echo "----------------------------------------"
get_content "https://gmpm.us/"

echo "----------------------------------------"
echo "Checking status page..."
curl -s -u "$username:$password" "https://gmpm.us/api/public/status" | json_pp 2>/dev/null || curl -s -u "$username:$password" 
"https://gmpm.us/api/public/status"

echo ""
echo "----------------------------------------"

# Check PHP errors directly
echo "Checking for PHP errors via direct access..."
ssh_output=$(ssh localhost "cd /home/gmpmus/public_html && php -r 'require \"index.php\";' 2>&1" 2>/dev/null)
if [ $? -eq 0 ]; then
    echo "No PHP fatal errors detected"
else
    echo "PHP errors found:"
    echo "$ssh_output"
fi

echo ""
echo "==================================="
echo "Test complete!"
echo "==================================="
