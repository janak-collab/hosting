#!/bin/bash
# GMPM System Cleanup Script
# Run from /home/gmpmus/
# This script identifies and optionally removes backup files, test files, and temporary files

echo "========================================"
echo "GMPM System Cleanup Analysis"
echo "========================================"
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Initialize counters
TOTAL_SIZE=0
BACKUP_COUNT=0
TEST_COUNT=0
TEMP_COUNT=0

# Function to convert bytes to human readable
human_readable() {
    local bytes=$1
    if [ $bytes -lt 1024 ]; then
        echo "${bytes}B"
    elif [ $bytes -lt 1048576 ]; then
        echo "$(($bytes / 1024))KB"
    elif [ $bytes -lt 1073741824 ]; then
        echo "$(($bytes / 1048576))MB"
    else
        echo "$(($bytes / 1073741824))GB"
    fi
}

# Function to get file size safely
get_file_size() {
    if [ -f "$1" ]; then
        stat -f%z "$1" 2>/dev/null || stat -c%s "$1" 2>/dev/null || echo 0
    else
        echo 0
    fi
}

echo -e "${BLUE}🔍 Scanning for cleanup candidates...${NC}"
echo ""

# ==========================================
# 1. BACKUP FILES
# ==========================================
echo -e "${YELLOW}📦 BACKUP FILES:${NC}"
echo "----------------------------------------"

# Find backup files with various extensions
BACKUP_PATTERNS=(
    "*.bak"
    "*.backup"
    "*.backup-*"
    "*_backup_*"
    "*.bak.*"
    "index.php.*"
    "dictation.php.*"
    ".env.backup*"
)

for pattern in "${BACKUP_PATTERNS[@]}"; do
    while IFS= read -r -d '' file; do
        if [ -f "$file" ]; then
            size=$(get_file_size "$file")
            TOTAL_SIZE=$((TOTAL_SIZE + size))
            BACKUP_COUNT=$((BACKUP_COUNT + 1))
            echo "  📄 $file ($(human_readable $size))"
        fi
    done < <(find . -name "$pattern" -type f -print0 2>/dev/null)
done

# Special backup directories
if [ -d "backups/" ]; then
    echo "  📁 backups/ directory:"
    find backups/ -type f 2>/dev/null | while read file; do
        size=$(get_file_size "$file")
        TOTAL_SIZE=$((TOTAL_SIZE + size))
        BACKUP_COUNT=$((BACKUP_COUNT + 1))
        echo "    📄 $file ($(human_readable $size))"
    done
fi

echo ""

# ==========================================
# 2. TEST FILES
# ==========================================
echo -e "${YELLOW}🧪 TEST FILES:${NC}"
echo "----------------------------------------"

TEST_FILES=(
    "test-*.php"
    "test_*.php"
    "test*.sh"
    "debug_*.php"
    "check_*.php"
    "verify_*.php"
    "diagnose_*.php"
    "*test*.php"
)

for pattern in "${TEST_FILES[@]}"; do
    while IFS= read -r -d '' file; do
        if [ -f "$file" ] && [[ ! "$file" =~ vendor/ ]]; then
            size=$(get_file_size "$file")
            TOTAL_SIZE=$((TOTAL_SIZE + size))
            TEST_COUNT=$((TEST_COUNT + 1))
            echo "  🧪 $file ($(human_readable $size))"
        fi
    done < <(find . -name "$pattern" -type f -print0 2>/dev/null)
done

echo ""

# ==========================================
# 3. SETUP/MIGRATION SCRIPTS
# ==========================================
echo -e "${YELLOW}⚙️  SETUP/MIGRATION SCRIPTS:${NC}"
echo "----------------------------------------"

SETUP_SCRIPTS=(
    "*setup*.sh"
    "*migration*.sh"
    "*migrate*.sh"
    "step*.sh"
    "create_*.sh"
    "fix_*.sh"
    "update_*.sh"
    "restructure.sh"
    "deploy.sh"
    "move_files.sh"
    "set_permissions.sh"
    "cleanup*.sh"
)

for pattern in "${SETUP_SCRIPTS[@]}"; do
    while IFS= read -r -d '' file; do
        if [ -f "$file" ]; then
            size=$(get_file_size "$file")
            TOTAL_SIZE=$((TOTAL_SIZE + size))
            TEMP_COUNT=$((TEMP_COUNT + 1))
            echo "  ⚙️  $file ($(human_readable $size))"
        fi
    done < <(find . -maxdepth 1 -name "$pattern" -type f -print0 2>/dev/null)
done

echo ""

# ==========================================
# 4. TEMPORARY/UTILITY FILES
# ==========================================
echo -e "${YELLOW}🗂️  TEMPORARY/UTILITY FILES:${NC}"
echo "----------------------------------------"

TEMP_FILES=(
    "cookies.txt"
    "*.tmp"
    "*.temp"
    "find_*.php"
    "preview_*.php"
    "monitor_*.sh"
    "check-*.sh"
    "*_sample_*.sql"
    "*.md.backup"
    ".mysql_history"
    ".bash_history"
    ".lesshst"
    "sync-to-github.sh"
)

for pattern in "${TEMP_FILES[@]}"; do
    while IFS= read -r -d '' file; do
        if [ -f "$file" ]; then
            size=$(get_file_size "$file")
            TOTAL_SIZE=$((TOTAL_SIZE + size))
            TEMP_COUNT=$((TEMP_COUNT + 1))
            echo "  🗂️  $file ($(human_readable $size))"
        fi
    done < <(find . -maxdepth 2 -name "$pattern" -type f -print0 2>/dev/null)
done

echo ""

# ==========================================
# 5. LOG FILES (OLD)
# ==========================================
echo -e "${YELLOW}📋 OLD LOG FILES:${NC}"
echo "----------------------------------------"

# Check for old log files (older than 30 days)
find . -name "*.log" -type f -mtime +30 2>/dev/null | while read file; do
    size=$(get_file_size "$file")
    TOTAL_SIZE=$((TOTAL_SIZE + size))
    echo "  📋 $file ($(human_readable $size)) - $(stat -f%Sm -t%Y-%m-%d "$file" 2>/dev/null || stat -c%y "$file" 2>/dev/null)"
done

echo ""

# ==========================================
# 6. DUPLICATE/REDUNDANT FILES
# ==========================================
echo -e "${YELLOW}📋 POTENTIAL DUPLICATES:${NC}"
echo "----------------------------------------"

# Check for potential duplicate PHP files
POTENTIAL_DUPLICATES=(
    "public_html/dictation.php vs public_html/debug_dictation.php"
    "public_html/dictation.php vs public_html/dictation_*.php"
)

for dup in "${POTENTIAL_DUPLICATES[@]}"; do
    file1=$(echo $dup | cut -d' ' -f1)
    file2=$(echo $dup | cut -d' ' -f3)
    if [ -f "$file1" ] && ls $file2 2>/dev/null >/dev/null; then
        echo "  📋 $dup"
    fi
done

echo ""

# ==========================================
# SUMMARY
# ==========================================
echo "========================================"
echo -e "${GREEN}📊 CLEANUP SUMMARY${NC}"
echo "========================================"
echo "Backup files found: $BACKUP_COUNT"
echo "Test files found: $TEST_COUNT"
echo "Setup/temp files found: $TEMP_COUNT"
echo "Total space to reclaim: $(human_readable $TOTAL_SIZE)"
echo ""

# ==========================================
# SAFE CLEANUP SCRIPT GENERATOR
# ==========================================
echo -e "${BLUE}🛡️  Generating safe cleanup commands...${NC}"

cat > cleanup_commands.sh << 'CLEANUP_EOF'
#!/bin/bash
# Generated GMPM Cleanup Commands
# Review each section before running

echo "GMPM Safe Cleanup Script"
echo "========================"
echo ""

# BACKUP SECTION - Generally safe to remove
echo "1. Removing backup files..."
find . -name "*.bak" -type f -delete 2>/dev/null
find . -name "*.backup" -type f -delete 2>/dev/null
find . -name "*.backup-*" -type f -delete 2>/dev/null
find . -name "*_backup_*" -type f -delete 2>/dev/null
find . -name "index.php.*" -type f -delete 2>/dev/null
find . -name "dictation.php.*" -type f -delete 2>/dev/null
find . -name ".env.backup*" -type f -delete 2>/dev/null

# TEST FILES SECTION - Safe to remove
echo "2. Removing test files..."
rm -f test-*.php test_*.php debug_*.php check_*.php verify_*.php diagnose_*.php 2>/dev/null
rm -f test*.sh 2>/dev/null

# SETUP SCRIPTS - Keep a few, remove others
echo "3. Removing setup scripts (keeping essential ones)..."
# Keep: backup scripts, essential configs
# Remove: migration, setup, fix scripts
rm -f step*.sh create_*.sh fix_*.sh update_*.sh restructure.sh move_files.sh 2>/dev/null
rm -f *migration*.sh *migrate*.sh *setup*.sh 2>/dev/null

# TEMP FILES - Safe to remove
echo "4. Removing temporary files..."
rm -f cookies.txt *.tmp *.temp find_*.php preview_*.php 2>/dev/null
rm -f monitor_*.sh check-*.sh *_sample_*.sql *.md.backup 2>/dev/null

# HISTORY FILES - Optional (comment out if you want to keep)
echo "5. Clearing history files (optional)..."
# rm -f .mysql_history .bash_history .lesshst 2>/dev/null

# OLD LOGS - Remove logs older than 30 days
echo "6. Removing old log files..."
find . -name "*.log" -type f -mtime +30 -delete 2>/dev/null

# DUPLICATE FILES - Manual review needed
echo "7. Manual review needed for:"
echo "   - public_html/dictation*.php files (keep main dictation.php)"
echo "   - Any remaining test files in subdirectories"

echo ""
echo "Cleanup completed!"
echo "Run 'du -sh .' to see space savings"
CLEANUP_EOF

chmod +x cleanup_commands.sh

echo ""
echo -e "${GREEN}✅ Cleanup analysis complete!${NC}"
echo ""
echo -e "${YELLOW}📋 RECOMMENDATIONS:${NC}"
echo "1. Review the generated 'cleanup_commands.sh' script"
echo "2. Run individual sections or the whole script"
echo "3. Keep one recent backup before cleanup"
echo "4. Test your application after cleanup"
echo ""
echo -e "${BLUE}🚀 TO RUN CLEANUP:${NC}"
echo "   ./cleanup_commands.sh"
echo ""
echo -e "${YELLOW}⚠️  IMPORTANT FILES TO KEEP:${NC}"
echo "   - app/ directory (all application code)"
echo "   - public_html/index.php (main entry point)"
echo "   - public_html/dictation.php (main dictation form)"
echo "   - .env file (configuration)"
echo "   - Recent backup files (just in case)"
