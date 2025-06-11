#!/bin/bash
# Preview error_log changes

echo "==================================="
echo "Preview error_log Changes"
echo "==================================="
echo ""

# Count total occurrences
total_files=$(grep -r "error_log(" --include="*.php" app/ public_html/ 2>/dev/null | grep -v "/vendor/" | grep -v ".bak" | cut -d: -f1 | sort -u | 
wc -l)
total_occurrences=$(grep -r "error_log(" --include="*.php" app/ public_html/ 2>/dev/null | grep -v "/vendor/" | grep -v ".bak" | wc -l)

echo "Found error_log() in $total_files files with $total_occurrences total occurrences"
echo ""

# Show different patterns of error_log usage
echo "1. Analyzing different error_log patterns..."
echo ""

echo "=== Simple string logging ==="
grep -h "error_log(['\"]" --include="*.php" -r app/ public_html/ 2>/dev/null | grep -v "/vendor/" | head -3
echo ""

echo "=== With variable concatenation ==="
grep -h "error_log(.*\. \$" --include="*.php" -r app/ public_html/ 2>/dev/null | grep -v "/vendor/" | head -3
echo ""

echo "=== With getMessage() ==="
grep -h "error_log(.*getMessage()" --include="*.php" -r app/ public_html/ 2>/dev/null | grep -v "/vendor/" | head -3
echo ""

echo "==================================="
echo "2. Files that will be updated:"
echo "==================================="
grep -r "error_log(" --include="*.php" app/ public_html/ 2>/dev/null | grep -v "/vendor/" | grep -v ".bak" | cut -d: -f1 | sort -u | while read 
file; do
    count=$(grep -c "error_log(" "$file")
    echo "$file ($count occurrences)"
done

echo ""
echo "==================================="
echo "3. Example conversions:"
echo "==================================="
echo ""
echo "Before: error_log('Something happened');"
echo "After:  Logger::error('Something happened');"
echo ""
echo "Before: error_log('Error: ' . \$e->getMessage());"
echo "After:  Logger::error('Error', ['error' => \$e->getMessage()]);"
echo ""
echo "Before: error_log('User ' . \$username . ' failed login');"
echo "After:  Logger::error('User failed login', ['username' => \$username]);"
echo ""
echo "Before: error_log('Debug: ' . print_r(\$data, true));"
echo "After:  Logger::debug('Debug data', ['data' => \$data]);"
echo ""
