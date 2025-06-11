#!/bin/bash
BACKUP_DIR="backups/header_responsive_20250609_220139"
echo "Rolling back responsive header changes..."

# Restore CSS files
cp $BACKUP_DIR/dashboard.css.bak public_html/assets/css/dashboard.css
cp $BACKUP_DIR/dashboard-index.php.bak app/templates/views/dashboard/index.php

# Remove responsive CSS file
rm -f public_html/assets/css/dashboard-responsive.css

echo "Rollback complete!"
