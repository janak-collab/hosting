#!/bin/bash

echo "=== Debugging 404 Error for /dictation ==="
echo

cd ~/public_html

echo "1. Check if app/public-endpoints/dictation.php exists..."
if [ -f ~/app/public-endpoints/dictation.php ]; then
    echo "✅ File exists at: ~/app/public-endpoints/dictation.php"
    ls -la ~/app/public-endpoints/dictation.php
else
    echo "❌ File NOT FOUND at: ~/app/public-endpoints/dictation.php"
fi

echo
echo "2. Check the route in index.php..."
echo "Looking for dictation route:"
grep -B2 -A2 "dictation" index.php || echo "No dictation route found!"

echo
echo "3. Check all routes in index.php..."
echo "All routes found:"
grep -E "case.*'/" index.php

echo
echo "4. Let's see how other endpoints are structured..."
echo "Checking phone-note endpoint:"
grep -B1 -A2 "phone-note" index.php

echo
echo "5. Check if APP_PATH is defined correctly..."
grep "APP_PATH" index.php | head -5

echo
echo "6. Creating the endpoint file if missing..."
if [ ! -f ~/app/public-endpoints/dictation.php ]; then
    echo "Creating ~/app/public-endpoints/dictation.php..."
    
    # Create the endpoint that includes the public file
    cat > ~/app/public-endpoints/dictation.php << 'EOF'
<?php
// Dictation endpoint
// Include the actual dictation form from public_html

// Check if the file exists in public_html
$dictation_file = dirname(dirname(dirname(__DIR__))) . 
'/public_html/dictation.php';

if (file_exists($dictation_file)) {
    require_once $dictation_file;
} else {
    // Fallback - show error
    die('Dictation form not found at: ' . $dictation_file);
}
EOF
    
    echo "✅ Created endpoint file"
else
    echo "Endpoint file already exists"
fi

echo
echo "7. Let's check .htaccess for any blocking rules..."
echo "Checking if .htaccess is blocking the route:"
grep -E "RewriteRule.*\[F\]" .htaccess | grep -v "^#" | head -10

echo
echo "8. Test with a simple endpoint..."
cat > ~/app/public-endpoints/dictation_test.php << 'EOF'
<?php
echo "<h1>Dictation endpoint is working!</h1>";
echo "<p>Time: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>This file is located at: " . __FILE__ . "</p>";
EOF

echo
echo "=== Solutions to try: ==="
echo
echo "1. Test if routing works at all:"
echo "   https://gmpm.us/dictation_test"
echo "   (Should show 'Dictation endpoint is working!')"
echo
echo "2. Check actual dictation route:"
echo "   https://gmpm.us/dictation"
echo
echo "3. If still 404, add route manually to index.php:"
echo "   nano index.php"
echo "   Find the other routes and add:"
echo "   case \$requestUri === '/dictation':"
echo "       require APP_PATH . '/public-endpoints/dictation.php';"
echo "       break;"
