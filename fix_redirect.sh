#!/bin/bash

echo "=== Fixing dictation redirect issue ==="
echo

cd ~/public_html

echo "1. Removing the non-PHP file..."
rm -f dictation

echo "2. Creating proper .htaccess redirect rule..."
# Add redirect rule to .htaccess
cp .htaccess .htaccess.backup.dictation

# Check if redirect already exists
if ! grep -q "RewriteRule.*\^dictation\$.*dictation\.php" .htaccess; then
    echo "Adding redirect rule to .htaccess..."
    
    # Find the RewriteEngine On line and add our rule after it
    # Using a different approach to ensure it works
    awk '/RewriteEngine On/ && !done {print; print "    \n    # Redirect 
/dictation to /dictation.php"; print "    RewriteCond %{REQUEST_URI} 
^/dictation$ [NC]"; print "    RewriteRule ^dictation$ /dictation.php 
[L,R=301]"; done=1; next} 1' .htaccess > .htaccess.new
    
    mv .htaccess.new .htaccess
else
    echo "Redirect rule already exists in .htaccess"
fi

echo
echo "3. Alternative approach - creating dictation/index.php..."
# Create a directory with index.php
mkdir -p dictation
cat > dictation/index.php << 'EOF'
<?php
// Redirect to dictation.php
header('Location: /dictation.php', true, 301);
exit;
?>
EOF

chmod 755 dictation
chmod 644 dictation/index.php

echo
echo "4. Checking if it's being handled by index.php router..."
if [ -f index.php ]; then
    echo "Checking index.php for dictation routes..."
    grep -n -B2 -A2 "dictation" index.php || echo "No dictation route found"
fi

echo
echo "5. Let's check the actual rewrite rules..."
echo "Current RewriteRules in .htaccess:"
grep -A1 "RewriteRule" .htaccess | head -20

echo
echo "=== DONE ==="
echo
echo "The redirect should now work via:"
echo "1. .htaccess redirect rule (best option)"
echo "2. dictation/index.php fallback"
echo
echo "Test: https://gmpm.us/dictation"
echo "Should redirect to: https://gmpm.us/dictation.php"
echo
echo "If still not working, the issue might be in index.php routing."
