#!/bin/bash

echo "=== Fixing Dictation Route to Use Endpoint ==="
echo

cd ~/public_html

echo "1. Backing up index.php..."
cp index.php index.php.endpoint_fix.$(date +%Y%m%d_%H%M%S)

echo
echo "2. Current dictation routes:"
grep -n -B2 -A10 "dictation" index.php

echo
echo "3. Fixing the route to use endpoint instead of controller..."

# First, let's see if we have both routes
if grep -q "case \$requestUri === '/dictation':" index.php && grep -q "case strpos(\$requestUri, '/dictation') === 0:" index.php; then
    echo "Found both routes - removing controller route"
    # Remove the complex controller route
    sed -i '/case strpos($requestUri, '\''\/dictation'\'') === 0:/,/^[[:space:]]*break;[[:space:]]*$/d' index.php
fi

# Now make sure we have the simple endpoint route
if ! grep -q "case \$requestUri === '/dictation':" index.php; then
    echo "Adding endpoint route..."
    # Find where to add it (after phone-note or it-support)
    if grep -q "case \$requestUri === '/it-support':" index.php; then
        sed -i "/case \$requestUri === '\/it-support':/,/break;/a\\\n    case \$requestUri === '/dictation':\n        require APP_PATH . 
'/public-endpoints/dictation.php';\n        break;" index.php
    elif grep -q "case \$requestUri === '/phone-note':" index.php; then
        sed -i "/case \$requestUri === '\/phone-note':/,/break;/a\\\n    case \$requestUri === '/dictation':\n        require APP_PATH . 
'/public-endpoints/dictation.php';\n        break;" index.php
    fi
fi

echo
echo "4. Ensuring the endpoint file has the clean form..."
# Make sure the endpoint has the updated form without MRN
if [ -f ~/public_html/dictation.php ]; then
    echo "Copying clean form to endpoint..."
    cp ~/public_html/dictation.php ~/app/public-endpoints/dictation.php
else
    echo "⚠️  Warning: ~/public_html/dictation.php not found"
fi

echo
echo "5. Verifying the fix..."
echo "Current dictation routes after fix:"
grep -n -B2 -A2 "dictation" index.php

echo
echo "6. Checking endpoint file exists and has no MRN..."
if [ -f ~/app/public-endpoints/dictation.php ]; then
    echo "✅ Endpoint file exists"
    if grep -q "MRN\|mrn" ~/app/public-endpoints/dictation.php; then
        echo "❌ MRN found in endpoint!"
    else
        echo "✅ No MRN in endpoint file"
    fi
else
    echo "❌ Endpoint file missing!"
fi

echo
echo "=== Fix Complete ==="
echo
echo "The route should now:"
echo "- Use /app/public-endpoints/dictation.php (like phone-note)"
echo "- NOT use the controller"
echo "- Show the form without MRN"
echo
echo "Test: https://gmpm.us/dictation"
