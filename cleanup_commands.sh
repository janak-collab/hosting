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
