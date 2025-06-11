#!/bin/bash
# GMPM Zero-Byte Files Cleanup Script

echo "=============================================="
echo "GMPM Zero-Byte Files Cleanup"
echo "=============================================="
echo ""

echo "🔍 Scanning for 0-byte files in root directory..."
echo ""

# Find all 0-byte files in current directory (non-recursive)
ZERO_BYTE_FILES=$(find . -maxdepth 1 -type f -size 0 2>/dev/null)

if [ -z "$ZERO_BYTE_FILES" ]; then
    echo "✅ No 0-byte files found in root directory"
    exit 0
fi

echo "📋 Found the following 0-byte files:"
echo "$ZERO_BYTE_FILES" | sed 's/^/   • /'
echo ""

# Files that should NEVER be deleted (even if 0 bytes)
PROTECTED_FILES=(
    "./.gitignore"
    "./.htaccess"
    "./.env"
    "./robots.txt"
    "./favicon.ico"
    "./sitemap.xml"
    "./.gitkeep"
    "./.keep"
)

# Files that are safe to delete if they're 0 bytes
SAFE_TO_DELETE=(
    "./-H"
    "./-d" 
    "./-o"
    "./-u"
    "./,"
    "./Action required"
    "./Failed to load procedures"
    "./Failed to log audit entry"
    "./Invalid security token"
    "./Provider ID required"
    "./.spamassassinboxenable"
    "./.spamassassinenable"
    "./false,"
    "./load_procedures,"
    "./true,"
    "./view_form,"
    "./[metadata]"
    "./[PHP_AUTH_USER]"
    "./["
)

echo "🔍 Analyzing files for safety..."
echo ""

# Categorize files
SAFE_FILES=""
PROTECTED_FOUND=""
UNKNOWN_FILES=""

for file in $ZERO_BYTE_FILES; do
    # Check if it's in protected list
    protected=false
    for protected_file in "${PROTECTED_FILES[@]}"; do
        if [ "$file" = "$protected_file" ]; then
            protected=true
            PROTECTED_FOUND="$PROTECTED_FOUND $file"
            break
        fi
    done
    
    if [ "$protected" = false ]; then
        # Check if it's in safe list
        safe=false
        for safe_file in "${SAFE_TO_DELETE[@]}"; do
            if [ "$file" = "$safe_file" ]; then
                safe=true
                SAFE_FILES="$SAFE_FILES $file"
                break
            fi
        done
        
        if [ "$safe" = false ]; then
            UNKNOWN_FILES="$UNKNOWN_FILES $file"
        fi
    fi
done

# Show categorization
if [ -n "$PROTECTED_FOUND" ]; then
    echo "🔒 PROTECTED files (will NOT be deleted):"
    for file in $PROTECTED_FOUND; do
        echo "   ✓ $file (important system file)"
    done
    echo ""
fi

if [ -n "$SAFE_FILES" ]; then
    echo "🗑️  SAFE TO DELETE (appear to be command fragments/accidents):"
    for file in $SAFE_FILES; do
        echo "   • $file"
    done
    echo ""
fi

if [ -n "$UNKNOWN_FILES" ]; then
    echo "❓ UNKNOWN files (need manual review):"
    for file in $UNKNOWN_FILES; do
        echo "   ? $file"
        # Show file details
        ls -la "$file" 2>/dev/null | sed 's/^/     /'
    done
    echo ""
fi

# Ask for confirmation if there are files to delete
if [ -n "$SAFE_FILES" ]; then
    echo "🤔 Do you want to delete the SAFE TO DELETE files? (y/N): "
    read -n 1 -r
    echo ""
    
    if [[ $REPLY =~ ^[Yy]$ ]]; then
        echo ""
        echo "🗑️  Deleting safe files..."
        for file in $SAFE_FILES; do
            if [ -f "$file" ]; then
                echo "   Removing: $file"
                rm -f "$file"
            fi
        done
        echo "✅ Safe files deleted!"
    else
        echo "❌ Deletion cancelled"
    fi
fi

if [ -n "$UNKNOWN_FILES" ]; then
    echo ""
    echo "⚠️  MANUAL REVIEW NEEDED for unknown files:"
    echo "   Run: ls -la [filename] to inspect each file"
    echo "   If you're sure a file is safe to delete: rm -f ./filename"
fi

echo ""
echo "📋 Final scan..."
REMAINING=$(find . -maxdepth 1 -type f -size 0 | wc -l)
echo "   Remaining 0-byte files: $REMAINING"

if [ "$REMAINING" -eq 0 ]; then
    echo "🎉 All 0-byte files have been cleaned up!"
else
    echo "ℹ️  Some 0-byte files remain (likely protected or need manual review)"
fi

echo ""
echo "=============================================="
