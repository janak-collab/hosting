#!/bin/bash

# Favicon HTML to insert
FAVICON_HTML='    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />\
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />\
    <link rel="shortcut icon" href="/favicon.ico" />\
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />\
    <link rel="manifest" href="/site.webmanifest" />\
    <meta name="theme-color" content="#f26522">'

# Find all PHP files with <head> tags
FILES=$(find /home/gmpmus/app/templates -name "*.php" -type f -exec grep -l "<head>" {} \;)

# Counter
COUNT=0

# Process each file
for FILE in $FILES; do
    # Check if favicon links already exist
    if grep -q "favicon.ico" "$FILE"; then
        echo "⏭️  Skipping $FILE (favicon already exists)"
        continue
    fi
    
    # Backup the file
    cp "$FILE" "$FILE.bak_favicon"
    
    # Add favicon HTML after </title> tag
    sed -i "/<\/title>/a\\
$FAVICON_HTML" "$FILE"
    
    echo "✅ Updated $FILE"
    ((COUNT++))
done

echo "🎉 Complete! Updated $COUNT files"
echo "📁 Backup files created with .bak_favicon extension"

