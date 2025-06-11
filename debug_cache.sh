#!/bin/bash

echo "=== Debugging Dictation Cache Issue ==="
echo

cd ~/public_html

echo "1. Checking file timestamps..."
ls -la dictation.php*
echo

echo "2. Adding cache-busting timestamp to dictation.php..."
# Add a comment with timestamp to force cache refresh
sed -i '1s/^/<!-- Cache bust: '$(date +%s)' -->\n/' dictation.php

echo "3. Checking if .htaccess has caching rules..."
grep -i "cache\|expire" .htaccess || echo "No cache rules found in .htaccess"
echo

echo "4. Creating a test version with visible changes..."
cp dictation.php dictation_test.php

# Add a visible test message at the top of the form
sed -i '/<div class="form-header">/a\            <div class="alert alert-info" 
style="margin: 1rem;">TEST VERSION - NO MRN - Updated: '$(date)'</div>' 
dictation_test.php

echo "5. Checking LiteSpeed cache..."
if [ -d ".litespeed" ]; then
    echo "LiteSpeed cache directory found. Clearing..."
    rm -rf .litespeed/*
else
    echo "No LiteSpeed cache directory found"
fi

echo
echo "6. Let's check what debug_dictation.php contains..."
echo "First 20 lines of debug_dictation.php:"
head -20 debug_dictation.php
echo

echo "7. Creating a completely fresh dictation file..."
cat > dictation_fresh.php << 'EOF'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Dictation (Fresh) - GMPM</title>
    <link rel="stylesheet" href="/assets/css/form-styles.css">
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>📝 Medical Dictation - FRESH VERSION</h1>
                <p>This is a test version without MRN field</p>
            </div>
            <div class="form-content">
                <div class="alert alert-success">
                    ✅ This is the fresh version - NO MRN FIELD
                    <br>Generated: <?php echo date('Y-m-d H:i:s'); ?>
                </div>
                
                <form>
                    <div class="form-group">
                        <label class="form-label">Patient Name</label>
                        <input type="text" class="form-input" placeholder="No 
MRN field here!">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Date of Service</label>
                        <input type="date" class="form-input">
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
EOF

chmod 644 dictation_fresh.php
chmod 644 dictation_test.php

echo
echo "=== Tests to run: ==="
echo "1. Try the test version: https://gmpm.us/dictation_test.php"
echo "   - Should show 'TEST VERSION - NO MRN' message at top"
echo
echo "2. Try the fresh version: https://gmpm.us/dictation_fresh.php"
echo "   - Should show completely different page with success message"
echo
echo "3. Force reload original with timestamp: 
https://gmpm.us/dictation.php?nocache=$(date +%s)"
echo
echo "4. Check server cache headers:"
curl -I https://gmpm.us/dictation.php 2>/dev/null | grep -i 
"cache\|expires\|modified" || echo "No cache headers found"

echo
echo "=== If you still see MRN field: ==="
echo "This indicates server-level caching (CloudFlare, LiteSpeed, etc.)"
echo "Try:"
echo "1. Check hosting control panel for cache settings"
echo "2. Add cache bypass rules to .htaccess"
echo "3. Contact hosting support to clear server cache"
