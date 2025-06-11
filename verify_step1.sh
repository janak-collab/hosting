#!/bin/bash
# GMPM Step 1 Verification Script
# Run from /home/gmpmus/

echo "==================================="
echo "GMPM Step 1 Verification"
echo "==================================="
echo ""

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Check function
check_exists() {
    if [ -e "$1" ]; then
        echo -e "${GREEN}✓${NC} $2"
        return 0
    else
        echo -e "${RED}✗${NC} $2"
        return 1
    fi
}

# Check directory function
check_dir() {
    check_exists "$1" "$2 exists"
}

# Check file function
check_file() {
    check_exists "$1" "$2 exists"
}

# Track errors
ERRORS=0

echo "Checking directory structure..."
echo "------------------------------"

# Check main directories
check_dir "app/config" "app/config directory" || ((ERRORS++))
check_dir "app/database/migrations" "app/database/migrations directory" || ((ERRORS++))
check_dir "app/database/seeds" "app/database/seeds directory" || ((ERRORS++))
check_dir "app/resources/views/phone-note" "Phone note views directory" || ((ERRORS++))
check_dir "app/resources/views/it-support" "IT support views directory" || ((ERRORS++))
check_dir "app/resources/views/admin" "Admin views directory" || ((ERRORS++))
check_dir "app/resources/views/errors" "Error views directory" || ((ERRORS++))
check_dir "app/resources/views/layouts" "Layouts directory" || ((ERRORS++))
check_dir "app/resources/assets/css" "CSS assets directory" || ((ERRORS++))
check_dir "app/resources/assets/js" "JS assets directory" || ((ERRORS++))
check_dir "app/routes" "Routes directory" || ((ERRORS++))
check_dir "app/src/Controllers" "Controllers directory" || ((ERRORS++))
check_dir "app/src/Models" "Models directory" || ((ERRORS++))
check_dir "app/src/Services" "Services directory" || ((ERRORS++))
check_dir "app/src/Middleware" "Middleware directory" || ((ERRORS++))
check_dir "app/src/Core" "Core directory" || ((ERRORS++))
check_dir "app/storage/logs" "Logs directory" || ((ERRORS++))
check_dir "app/storage/cache" "Cache directory" || ((ERRORS++))
check_dir "app/storage/sessions" "Sessions directory" || ((ERRORS++))
check_dir "app/storage/uploads" "Uploads directory" || ((ERRORS++))

echo ""
echo "Checking configuration files..."
echo "------------------------------"

# Check config files
check_file "app/config/app.php" "App configuration" || ((ERRORS++))
check_file "app/config/database.php" "Database configuration" || ((ERRORS++))
check_file "app/config/security.php" "Security configuration" || ((ERRORS++))
check_file "app/config/providers.php" "Providers configuration" || ((ERRORS++))

echo ""
echo "Checking permissions..."
echo "------------------------------"

# Check permissions
if [ -d "app/storage" ]; then
    STORAGE_PERMS=$(stat -c "%a" app/storage 2>/dev/null || stat -f "%OLp" app/storage 2>/dev/null)
    if [ "$STORAGE_PERMS" = "775" ]; then
        echo -e "${GREEN}✓${NC} app/storage has correct permissions (775)"
    else
        echo -e "${RED}✗${NC} app/storage has incorrect permissions (current: $STORAGE_PERMS, expected: 775)"
        ((ERRORS++))
    fi
fi

if [ -f "app/.env" ]; then
    ENV_PERMS=$(stat -c "%a" app/.env 2>/dev/null || stat -f "%OLp" app/.env 2>/dev/null)
    if [ "$ENV_PERMS" = "600" ]; then
        echo -e "${GREEN}✓${NC} .env has correct permissions (600)"
    else
        echo -e "${RED}✗${NC} .env has incorrect permissions (current: $ENV_PERMS, expected: 600)"
        ((ERRORS++))
    fi
fi

echo ""
echo "Checking for backup..."
echo "------------------------------"

# Check if backup exists
BACKUP_COUNT=$(ls -1 gmpm_backup_*.tar.gz 2>/dev/null | wc -l)
if [ $BACKUP_COUNT -gt 0 ]; then
    echo -e "${GREEN}✓${NC} Backup file found"
    ls -lh gmpm_backup_*.tar.gz | tail -1
else
    echo -e "${RED}✗${NC} No backup file found"
    ((ERRORS++))
fi

echo ""
echo "Checking storage .htaccess..."
echo "------------------------------"

check_file "app/storage/.htaccess" "Storage .htaccess security file" || ((ERRORS++))

echo ""
echo "==================================="
if [ $ERRORS -eq 0 ]; then
    echo -e "${GREEN}✓ Step 1 completed successfully!${NC}"
    echo ""
    echo "Next steps:"
    echo "1. Review the structure with: tree app/ -d -L 3"
    echo "2. Update composer.json autoload paths"
    echo "3. Run: cd app && composer dump-autoload"
else
    echo -e "${RED}✗ Step 1 has $ERRORS errors that need to be fixed${NC}"
    echo ""
    echo "Please run the missing scripts or manually create missing items"
fi
echo "==================================="
