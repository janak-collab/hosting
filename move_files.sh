#!/bin/bash
# GMPM Move Files Script
# Run from /home/gmpmus/

echo "Moving files to new structure..."

# Move view templates if they exist
if [ -d "app/templates/views" ]; then
    echo "Moving view templates..."
    
    # Phone note views
    if [ -f "app/templates/views/phone-note/form.php" ]; then
        cp -v app/templates/views/phone-note/form.php app/resources/views/phone-note/
    fi
    if [ -f "app/templates/views/phone-note/list.php" ]; then
        cp -v app/templates/views/phone-note/list.php app/resources/views/phone-note/
    fi
    if [ -f "app/templates/views/phone-note/print.php" ]; then
        cp -v app/templates/views/phone-note/print.php app/resources/views/phone-note/
    fi
    
    # IT support views
    if [ -f "app/templates/views/it-support-form.php" ]; then
        cp -v app/templates/views/it-support-form.php app/resources/views/it-support/form.php
    fi
    
    # Admin views
    if [ -f "app/templates/views/admin/login.php" ]; then
        cp -v app/templates/views/admin/login.php app/resources/views/admin/
    fi
    if [ -f "app/templates/views/admin/tickets.php" ]; then
        cp -v app/templates/views/admin/tickets.php app/resources/views/admin/
    fi
fi

# Move error pages
echo "Moving error pages..."
for error in 400 401 403 404 500 502 503; do
    if [ -f "public_html/errors/${error}.html" ]; then
        # Convert HTML to PHP and update paths
        echo "Converting ${error}.html to PHP..."
        sed 's|/assets/css/form-styles.css|<?php echo asset("css/form-styles.css"); ?>|g' \
            "public_html/errors/${error}.html" > "app/resources/views/errors/${error}.php"
    fi
done

# Copy CSS files (don't move yet, just copy)
echo "Copying CSS files..."
if [ -f "public_html/assets/css/form-styles.css" ]; then
    cp -v public_html/assets/css/form-styles.css app/resources/assets/css/
fi
if [ -f "public_html/assets/css/panel-styles.css" ]; then
    cp -v public_html/assets/css/panel-styles.css app/resources/assets/css/
fi

# Copy JS files (don't move yet, just copy)
echo "Copying JavaScript files..."
for jsfile in phone-note-form.js it-support-form.js ip-manager.js phone-note-print.js; do
    if [ -f "public_html/assets/js/${jsfile}" ]; then
        cp -v "public_html/assets/js/${jsfile}" "app/resources/assets/js/"
    fi
done

# Create .gitkeep files for empty directories
echo "Creating .gitkeep files..."
find app -type d -empty -exec touch {}/.gitkeep \;

# Create storage .htaccess for security
echo "Creating storage .htaccess..."
cat > app/storage/.htaccess << 'EOF'
Order deny,allow
Deny from all
EOF

echo "File moving complete!"
echo ""
echo "Next steps:"
echo "1. Verify files were copied correctly"
echo "2. Create configuration files in app/config/"
echo "3. Update file paths in PHP files"
