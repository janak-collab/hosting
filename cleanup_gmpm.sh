#!/bin/bash
# GMPM Project Cleanup Script
# This script will consolidate and clean up the GMPM project

echo "==================================="
echo "GMPM Project Cleanup & Consolidation"
echo "==================================="
echo ""

# 1. Clean up JavaScript files
echo ""
echo "1. Consolidating JavaScript files..."
cd ~/app/resources/assets/js/

# Remove backup directory
rm -rf ../backup/

# Keep only the consolidated app.js
rm -f phone-note-form.js it-support-form.js ip-manager.js phone-note-print.js modules.js
echo "✓ JavaScript consolidated to app.js"

# 2. Clean up CSS files
echo ""
echo "2. Consolidating CSS files..."
cd ~/app/resources/assets/css/

# Remove duplicates, keep only app.css
rm -f consolidated.css form-styles.css panel-styles.css app.min.css
echo "✓ CSS will be consolidated (app.css to be updated)"

# 3. Remove test and debug files
echo ""
echo "3. Removing test/debug files..."
cd ~/

# Find and remove test files (excluding legitimate test directories)
find . -name "*test*.php" -not -path "./app/tests/*" -type f -delete 2>/dev/null
find . -name "*debug*.php" -type f -delete 2>/dev/null
find . -name "check-*.php" -type f -delete 2>/dev/null
rm -f test-*.php debug-*.php

# Remove specific debug/test files
cd ~/app/scripts/
rm -f test-session.php check-db.php check-users.php check-tickets.php \
      check-password.php check-created-by.php check-session-state.php \
      check-table-structure.php debug-logging.php find-all-error-logs.sh \
      clear-session.php update-created-by.php

echo "✓ Test/debug files removed"

# 4. Clean up error logs
echo ""
echo "4. Cleaning up error logs..."
> ~/error_log
> ~/app/error_log
> ~/public_html/error_log
echo "✓ Error logs cleared"

# 5. Remove duplicate/outdated files
echo ""
echo "5. Removing duplicate files..."
cd ~/app/

# Remove duplicate router-related files
rm -f route-list.php route-list-debug.php debug-routes.php \
      system-report.php dashboard.php clear-route-cache.php \
      analyze-braces.php dashboard-final.php

# Remove migration/setup scripts that are no longer needed
rm -f fix_user_role.php fix_super_admin.php migrate_user_tables.php

echo "✓ Duplicate files removed"

# 6. Consolidate documentation
echo ""
echo "6. Consolidating documentation..."
cd ~/app/docs/

# Keep only essential docs
rm -f admin-login-encryption-note.md logging*.md

echo "✓ Documentation consolidated"

# 7. Clean up secure-admin directory
echo ""
echo "7. Cleaning up secure-admin..."
cd ~/app/secure-admin/
rm -f ip-manager.php.bak2
echo "✓ Secure-admin cleaned"

# 8. Remove empty directories
echo ""
echo "8. Removing empty directories..."
cd ~/
find app -type d -empty -delete 2>/dev/null
echo "✓ Empty directories removed"

# 9. Fix permissions
echo ""
echo "9. Setting correct permissions..."
find ~/app -type f -name "*.php" -exec chmod 644 {} \;
find ~/app -type d -exec chmod 755 {} \;
chmod 755 ~/bin/gmpm
echo "✓ Permissions fixed"

echo ""
echo "==================================="
echo "Cleanup Complete!"
echo "==================================="
echo ""
echo "Summary of changes:"
echo "- JavaScript consolidated to single app.js"
echo "- CSS files cleaned up (app.css needs updating)"
echo "- Test/debug files removed"
echo "- Error logs cleared"
echo "- Documentation consolidated"
echo "- Permissions fixed"
echo ""
echo "Next steps:"
echo "1. Update app.css with consolidated styles"
echo "2. Update references to consolidated files"
echo "3. Test all functionality"
echo "4. Commit changes to git"
