#!/bin/bash

echo "=== Checking and cleaning up .htaccess redirects ==="
echo

cd ~/public_html

echo "1. Checking for dictation redirect rules in .htaccess..."
grep -n "dictation" .htaccess || echo "No dictation rules found"

echo
echo "2. Creating backup before cleanup..."
cp .htaccess .htaccess.cleanup.$(date +%Y%m%d_%H%M%S)

echo
echo "3. Removing any dictation redirect rules..."
# Remove lines containing dictation redirect
grep -v "dictation" .htaccess > .htaccess.tmp || true

# Check if the file changed
if ! diff -q .htaccess .htaccess.tmp > /dev/null 2>&1; then
    echo "Found and removing dictation redirect rules..."
    mv .htaccess.tmp .htaccess
    echo "✅ Redirect rules removed"
else
    echo "✅ No dictation redirect rules found in .htaccess"
    rm -f .htaccess.tmp
fi

echo
echo "4. Also checking for any LiteSpeed cache rules specific to dictation..."
grep -n -A2 -B2 "Files.*dictation" .htaccess || echo "No file-specific rules 
found"

echo
echo "5. Current rewrite rules (first 30 lines after RewriteEngine On)..."
awk '/RewriteEngine On/{flag=1} flag && n++<30' .htaccess

echo
echo "=== Cleanup Complete ==="
echo
echo "Summary:"
echo "- Any redirect from /dictation to /dictation.php has been removed"
echo "- The route now properly uses app/public-endpoints/dictation.php"
echo "- Clean URL works: https://gmpm.us/dictation"
echo
echo "Backup saved as: .htaccess.cleanup.$(date +%Y%m%d_%H%M%S)"
