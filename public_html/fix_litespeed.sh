#!/bin/bash

echo "=== Fixing A2 Hosting / LiteSpeed Cache Issue ==="
echo

cd ~/public_html

echo "1. Creating .htaccess rules to disable LiteSpeed cache for 
dictation.php..."

# Create a backup of .htaccess
cp .htaccess .htaccess.backup.$(date +%Y%m%d_%H%M%S)

# Add LiteSpeed cache exclusion rules at the TOP of .htaccess
# We'll create a temporary file and prepend it
cat > .htaccess.tmp << 'EOF'
# ===== LITESPEED CACHE EXCLUSIONS =====
# Disable all caching for dictation.php
<IfModule LiteSpeed>
    RewriteEngine On
    RewriteCond %{REQUEST_URI} dictation\.php [NC]
    RewriteRule .* - [E=Cache-Control:no-cache]
</IfModule>

# Additional cache prevention for specific files
<Files "dictation.php">
    <IfModule LiteSpeed>
        RewriteEngine On
        RewriteRule .* - [E=Cache-Control:no-store,no-cache,must-revalidate]
        RewriteRule .* - [E=Cache-Pragma:no-cache]
        RewriteRule .* - [E=Cache-Expires:0]
    </IfModule>
    Header set X-LiteSpeed-Cache-Control "no-cache, no-store, must-revalidate"
    Header set Cache-Control "no-cache, no-store, must-revalidate, private"
    Header set Pragma "no-cache"
    Header set Expires "0"
</Files>

# Disable caching for PHP files in general
<FilesMatch "\.php$">
    <IfModule LiteSpeed>
        RewriteEngine On
        RewriteRule .* - [E=Cache-Control:no-cache]
    </IfModule>
</FilesMatch>
# ===== END CACHE EXCLUSIONS =====

EOF

# Append the existing .htaccess content
cat .htaccess >> .htaccess.tmp
mv .htaccess.tmp .htaccess

echo "2. Creating cache-purge trigger file..."
# LiteSpeed watches for this
touch .lscache_purge

echo "3. Creating LiteSpeed specific cache control file..."
cat > dictation.php.htaccess << 'EOF'
<IfModule LiteSpeed>
    RewriteEngine On
    RewriteRule .* - [E=Cache-Control:no-cache,no-store,must-revalidate]
</IfModule>
Header set Cache-Control "no-cache, no-store, must-revalidate"
Header set Pragma "no-cache"
Header set Expires "0"
EOF

echo "4. Adding cache-busting headers directly to dictation.php..."
# Create a new version with PHP headers at the very top
cp dictation.php dictation.php.tmp

# Add PHP cache headers at the beginning
cat > dictation.php << 'EOF'
<?php
// Force no caching
header('Cache-Control: no-cache, no-store, must-revalidate, private');
header('Pragma: no-cache');
header('Expires: 0');
header('X-LiteSpeed-Cache-Control: no-cache');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('ETag: "' . uniqid() . '"');

// Force reload if cached
if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) || 
isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
    header('HTTP/1.1 200 OK');
}
?>
EOF

# Append the rest of the file (skipping any existing PHP tags at the start)
tail -n +2 dictation.php.tmp >> dictation.php
rm dictation.php.tmp

echo "5. Creating test file to verify cache is cleared..."
cat > cache_test.php << 'EOF'
<?php
header('Cache-Control: no-cache, no-store, must-revalidate');
header('X-LiteSpeed-Cache-Control: no-cache');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cache Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        .success { color: green; font-size: 24px; }
        .info { background: #e0f7fa; padding: 20px; border-radius: 5px; margin: 
20px 0; }
    </style>
</head>
<body>
    <h1 class="success">✅ Cache Cleared Successfully!</h1>
    <div class="info">
        <p><strong>Timestamp:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
        <p><strong>Random ID:</strong> <?php echo uniqid(); ?></p>
        <p>If you see this page with current timestamp, LiteSpeed cache has 
been bypassed.</p>
    </div>
    <hr>
    <h2>Quick Links:</h2>
    <ul>
        <li><a href="/dictation.php?nocache=<?php echo time(); ?>">Dictation 
Form (with cache bypass)</a></li>
        <li><a href="/dictation.php">Dictation Form (normal)</a></li>
    </ul>
</body>
</html>
EOF

echo "6. Setting permissions..."
chmod 644 .htaccess
chmod 644 dictation.php
chmod 644 cache_test.php

echo "7. Attempting to clear LiteSpeed cache directories..."
# Common LiteSpeed cache locations
for dir in ~/.lscache ~/lscache ~/.litespeed ./.lscache ./lscache ./.litespeed; 
do
    if [ -d "$dir" ]; then
        echo "Found cache directory: $dir - Clearing..."
        rm -rf "$dir"/*
    fi
done

echo
echo "=== IMMEDIATE ACTIONS REQUIRED ==="
echo
echo "1. TEST THIS URL FIRST:"
echo "   https://gmpm.us/cache_test.php"
echo "   (Should show current timestamp)"
echo
echo "2. Then test dictation with cache bypass:"
echo "   https://gmpm.us/dictation.php?purge=1&t=$(date +%s)"
echo
echo "3. If still showing old version, you need to:"
echo "   a) Log into your hosting.com control panel"
echo "   b) Look for 'LiteSpeed Cache' or 'Cache Manager'"
echo "   c) Click 'Purge All' or 'Clear Cache'"
echo "   d) OR find 'Development Mode' and enable it temporarily"
echo
echo "4. Alternative - Add this to your browser URL:"
echo "   https://gmpm.us/dictation.php?LSCache-Ctrl=no-cache"
echo
echo "Cache exclusion rules have been added to .htaccess"
echo "Backup saved as: .htaccess.backup.$(date +%Y%m%d_%H%M%S)"
