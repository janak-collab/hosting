#!/bin/bash

# List of views that need the responsive header
views=(
    "/home/gmpmus/app/templates/views/phone-note/form.php"
    "/home/gmpmus/app/templates/views/it-support-form.php"
    "/home/gmpmus/app/templates/views/admin/tickets.php"
    "/home/gmpmus/app/templates/views/admin/login.php"
    "/home/gmpmus/app/templates/views/phone-note/list.php"
    "/home/gmpmus/app/templates/views/forms/index.php"
)

for view in "${views[@]}"; do
    if [ -f "$view" ]; then
        echo "Processing $view..."
        
        # Check if already has new header
        if grep -q "header.php" "$view"; then
            echo "  ✓ Already has responsive header"
            continue
        fi
        
        # Backup
        cp "$view" "$view.backup-header-$(date +%Y%m%d-%H%M%S)"
        
        # Add header styles to head if not present
        if ! grep -q "header-styles.css" "$view"; then
            echo "  → Adding header CSS..."
            sed -i '/<\/head>/i\    <link rel="stylesheet" href="/assets/css/header-styles.css">' "$view"
        fi
        
        # Add header include after <body> tag
        echo "  → Adding header component..."
        sed -i '/<body[^>]*>/a\    <?php require_once APP_PATH . '"'"'/templates/components/header.php'"'"'; ?>\n' "$view"
        
        # Add header.js before closing body if not present
        if ! grep -q "header.js" "$view"; then
            echo "  → Adding header JS..."
            sed -i '/<\/body>/i\    <script src="/assets/js/header.js"></script>' "$view"
        fi
        
        echo "  ✓ Completed"
    else
        echo "File not found: $view"
    fi
done

echo -e "\nDone! Check the updates with:"
echo "grep -n 'header.php' ${views[0]}"
