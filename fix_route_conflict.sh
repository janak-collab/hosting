#!/bin/bash

echo "=== Fixing Dictation Route Conflict ==="
echo

cd ~/public_html

echo "1. Backing up index.php..."
cp index.php index.php.route_conflict.$(date +%Y%m%d_%H%M%S)

echo
echo "2. Current problematic routes:"
grep -n "dictation" index.php

echo
echo "3. Removing the conflicting DictationController route..."
# We need to remove the entire case block for the DictationController
# This is the complex route that's causing the 404

# Create a temporary file
cp index.php index.php.tmp

# Remove the problematic controller route section
# This is tricky because it's a multi-line block
sed -i '/case strpos($requestUri, '\''\/dictation'\'') === 
0:/,/^[[:space:]]*break;[[:space:]]*$/d' index.php.tmp

# Now let's verify the simple route is still there
if ! grep -q "case \$requestUri === '/dictation':" index.php.tmp; then
    echo "Simple route was accidentally removed. Re-adding it..."
    # Find where to add it (after phone-note)
    sed -i "/case \$requestUri === '\/phone-note':/,/break;/a\\\n    case 
\$requestUri === '/dictation':\n        require APP_PATH . 
'/public-endpoints/dictation.php';\n        break;" index.php.tmp
fi

# Replace the original file
mv index.php.tmp index.php

echo
echo "4. Verifying the fix..."
echo "Routes after fix:"
grep -B1 -A2 "dictation" index.php

echo
echo "5. Making sure the endpoint file has the correct content..."
# Since we copied from public_html earlier, let's verify it's the right content
if grep -q "MRN" ~/app/public-endpoints/dictation.php; then
    echo "❌ MRN found in endpoint file! Copying clean version..."
    cp ~/public_html/dictation.php ~/app/public-endpoints/dictation.php
else
    echo "✅ Endpoint file is clean (no MRN)"
fi

echo
echo "=== Alternative Manual Fix ==="
echo
echo "If automatic fix didn't work, edit index.php manually:"
echo "1. nano index.php"
echo "2. Find and DELETE this entire section:"
echo "   case strpos(\$requestUri, '/dictation') === 0:"
echo "   ... (all the controller code)"
echo "   break;"
echo
echo "3. KEEP this simple route:"
echo "   case \$requestUri === '/dictation':"
echo "       require APP_PATH . '/public-endpoints/dictation.php';"
echo "       break;"
echo
echo "Test: https://gmpm.us/dictation"
echo "Should now work without 404!"
