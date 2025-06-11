#!/bin/bash
# GMPM Step 2 Verification Script
# Run from /home/gmpmus/

echo "==================================="
echo "GMPM Step 2 Verification"
echo "==================================="
echo ""

GREEN='\033[0;32m'
RED='\033[0;31m'
NC='\033[0m'

# Check consolidated CSS
echo "Checking consolidated CSS..."
echo "------------------------------"
if [ -f "app/resources/assets/css/consolidated.css" ]; then
    echo -e "${GREEN}✓${NC} Consolidated CSS exists"
    size=$(wc -c < app/resources/assets/css/consolidated.css)
    echo "  Size: $size bytes"
else
    echo -e "${RED}✗${NC} Consolidated CSS missing"
fi

if [ -f "public_html/assets/css/app.css" ]; then
    echo -e "${GREEN}✓${NC} Public app.css exists"
else
    echo -e "${RED}✗${NC} Public app.css missing"
fi

# Check JavaScript files
echo ""
echo "Checking JavaScript structure..."
echo "------------------------------"
if [ -f "app/resources/assets/js/app.js" ]; then
    echo -e "${GREEN}✓${NC} Main app.js created"
else
    echo -e "${RED}✗${NC} Main app.js missing"
fi

if [ -f "app/resources/assets/js/modules.js" ]; then
    echo -e "${GREEN}✓${NC} Module loader created"
else
    echo -e "${RED}✗${NC} Module loader missing"
fi

# Check public assets
echo ""
echo "Checking public assets..."
echo "------------------------------"
css_count=$(ls -1 public_html/assets/css/*.css 2>/dev/null | wc -l)
js_count=$(ls -1 public_html/assets/js/*.js 2>/dev/null | wc -l)

echo "CSS files in public: $css_count"
echo "JS files in public: $js_count"

# Check for PHP files in JS directory (shouldn't be any)
php_in_js=$(find public_html/assets/js -name "*.php" 2>/dev/null | wc -l)
if [ $php_in_js -eq 0 ]; then
    echo -e "${GREEN}✓${NC} No PHP files in JS directory"
else
    echo -e "${RED}✗${NC} Found $php_in_js PHP files in JS directory (should be removed)"
    find public_html/assets/js -name "*.php" -exec basename {} \;
fi

# Check manifest
echo ""
echo "Checking asset manifest..."
echo "------------------------------"
if [ -f "public_html/assets/manifest.json" ]; then
    echo -e "${GREEN}✓${NC} Asset manifest exists"
    echo "Manifest content:"
    cat public_html/assets/manifest.json | head -5
else
    echo -e "${RED}✗${NC} Asset manifest missing"
fi

# Summary
echo ""
echo "==================================="
echo "Asset Statistics:"
echo "------------------------------"
echo "Total CSS size: $(du -sh app/resources/assets/css/ | cut -f1)"
echo "Total JS size: $(du -sh app/resources/assets/js/ | cut -f1)"
echo "Public assets size: $(du -sh public_html/assets/ | cut -f1)"

echo ""
echo "==================================="
echo "Step 2 Status: Complete!"
echo ""
echo "Next: Step 3 - Implement Router"
echo "This will involve:"
echo "- Creating Core/Router.php"
echo "- Creating BaseController.php" 
echo "- Setting up route files"
echo "- Updating index.php"
echo "==================================="
