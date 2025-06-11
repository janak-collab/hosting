#!/bin/bash
echo "Searching for error_log() usage in all PHP files..."
echo "=================================================="
echo ""

echo "In app directory:"
grep -r "error_log(" /home/gmpmus/app/ --include="*.php" -n | grep -v "/vendor/" | grep -v "/storage/"

echo ""
echo "In public_html directory:"
grep -r "error_log(" /home/gmpmus/public_html/ --include="*.php" -n

echo ""
echo "Summary:"
echo "App directory: $(grep -r "error_log(" /home/gmpmus/app/ --include="*.php" | grep -v "/vendor/" | grep -v "/storage/" | wc -l) occurrences"
echo "Public directory: $(grep -r "error_log(" /home/gmpmus/public_html/ --include="*.php" | wc -l) occurrences"
