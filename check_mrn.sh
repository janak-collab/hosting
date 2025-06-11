#!/bin/bash

# Script to check for MRN references and remove them
echo "=== Checking for MRN references in dictation.php ==="
echo

cd ~/public_html

# First, let's see what MRN references exist
echo "1. Searching for MRN references in dictation.php..."
echo "Found the following MRN references:"
grep -n "MRN\|mrn" dictation.php || echo "No MRN references found"

echo
echo "2. Creating backup..."
cp dictation.php dictation.php.mrn_backup.$(date +%Y%m%d_%H%M%S)

echo
echo "3. Removing MRN references..."

# Use sed to remove MRN-related lines
# This will remove:
# - The entire MRN form field div
# - MRN references in the output
# - MRN in JavaScript

# Remove the MRN form field (multi-line removal)
sed -i '/<div class="form-group">/,/<\/div>/{
    /<label for="mrn"/,/<\/div>/d
}' dictation.php

# Remove MRN from dictation output
sed -i '/MRN:/d' dictation.php
sed -i '/formData\.get.*mrn/d' dictation.php
sed -i '/mrn.*formData/d' dictation.php

# Remove MRN from any JavaScript sections
sed -i 's/mrn: formData\.get.*mrn.*,//g' dictation.php
sed -i 's/document\.getElementById.*mrn.*//g' dictation.php

echo
echo "4. Let's check if MRN references are gone..."
if grep -q "MRN\|mrn" dictation.php; then
    echo "⚠️  Some MRN references still found:"
    grep -n "MRN\|mrn" dictation.php
    echo
    echo "5. Attempting more aggressive removal..."
    
    # More aggressive approach - remove any line containing MRN
    sed -i '/[Mm][Rr][Nn]/d' dictation.php
    
    echo "Checking again..."
    if grep -q "MRN\|mrn" dictation.php; then
        echo "❌ MRN references still present. Manual editing may be 
required."
        echo
        echo "Remaining references:"
        grep -n "MRN\|mrn" dictation.php
    else
        echo "✅ All MRN references removed!"
    fi
else
    echo "✅ No MRN references found - already clean!"
fi

echo
echo "6. Showing the patient info section of the form..."
echo "---"
grep -A 20 "Patient Information" dictation.php | head -30

echo
echo "=== Script Complete ==="
echo "Backup saved as: dictation.php.mrn_backup.$(date +%Y%m%d_%H%M%S)"
echo
echo "If MRN is still showing, try:"
echo "1. Clear your browser cache (Ctrl+F5)"
echo "2. Check if there's a different dictation file being used"
echo "3. Run: ls -la ~/public_html/*dict*.php to see all dictation files"
