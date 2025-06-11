#!/bin/bash

echo "=== Removing Dashboard Controller Code ==="
echo

cd ~/public_html

echo "1. Creating backup of index.php..."
cp index.php index.php.dashboard_removal.$(date +%Y%m%d_%H%M%S)

echo
echo "2. Looking for Dashboard Controller code..."
grep -n "Dashboard" index.php

echo
echo "3. Removing Dashboard Controller code..."
# Remove the Dashboard Controller lines
sed -i '/\/\/ Load Dashboard Controller/,/break;/d' index.php

echo
echo "4. Verifying removal..."
echo "Checking if Dashboard code is gone:"
if grep -q "DashboardController" index.php; then
    echo "⚠️  Dashboard code still found:"
    grep -n "Dashboard" index.php
else
    echo "✅ Dashboard Controller code successfully removed"
fi

echo
echo "5. Showing the surrounding code..."
echo "Current routing structure:"
grep -B5 -A5 "case.*'/" index.php | head -40

echo
echo "=== Complete ==="
echo "Backup saved as: index.php.dashboard_removal.$(date +%Y%m%d_%H%M%S)"
