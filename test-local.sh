#!/bin/bash
# GMPM Local Testing Script

echo "Testing GMPM endpoints locally..."
echo ""

# Use the domain name with --resolve to force local resolution
test_endpoint() {
    local path=$1
    local desc=$2
    echo "Testing: $desc ($path)"
    
    # Force resolution to localhost
    response=$(curl -s -o /dev/null -w "%{http_code}" \
        --resolve gmpm.us:80:127.0.0.1 \
        --resolve gmpm.us:443:127.0.0.1 \
        "http://gmpm.us$path")
    
    echo "Status: $response"
    echo ""
}

# Test endpoints
test_endpoint "/health-check.php" "Health Check"
test_endpoint "/status.php" "Status Page"
test_endpoint "/" "Home Page"
test_endpoint "/api/public/status.php" "API Status"

echo "Done!"
