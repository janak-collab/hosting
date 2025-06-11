#!/bin/bash

# List of views to update
views=(
    "/home/gmpmus/app/templates/views/phone-note/form.php"
    "/home/gmpmus/app/templates/views/it-support-form.php"
    "/home/gmpmus/app/templates/views/admin/tickets.php"
    "/home/gmpmus/app/templates/views/admin/login.php"
    "/home/gmpmus/app/templates/views/phone-note/list.php"
)

for view in "${views[@]}"; do
    if [ -f "$view" ]; then
        echo "Checking $view..."
        
        # Check if already has new header
        if grep -q "header.php" "$view"; then
            echo "  ✓ Already updated"
            continue
        fi
        
        # Check if has old header
        if grep -q "<header" "$view"; then
            echo "  → Found old header, updating..."
            
            # Backup
            cp "$view" "$view.backup-$(date +%Y%m%d-%H%M%S)"
            
            # Add header styles to head if not present
            if ! grep -q "header-styles.css" "$view"; then
                sed -i '/<\/head>/i\    <link rel="stylesheet" href="/assets/css/header-styles.css">' "$view"
            fi
            
            # Replace old header with new include
            sed -i '/<header/,/<\/header>/c\    <?php require_once APP_PATH . '"'"'/templates/components/header.php'"'"'; ?>' "$view"
            
            # Add header.js before closing body if not present
            if ! grep -q "header.js" "$view"; then
                sed -i '/<\/body>/i\    <script src="/assets/js/header.js"></script>' "$view"
            fi
            
            echo "  ✓ Updated"
        else
            echo "  - No header found"
        fi
    else
        echo "File not found: $view"
    fi
done
