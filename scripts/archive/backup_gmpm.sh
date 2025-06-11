#!/bin/bash
# GMPM Automated Backup Script
# Location: ~/scripts/backup_gmpm.sh

# Configuration
BACKUP_DIR="$HOME/backups"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
DB_NAME="gmpmus_gmpm"
DB_USER="gmpmus_gmpmuser"
DAYS_TO_KEEP=7

# Create backup directory if needed
mkdir -p "$BACKUP_DIR"

echo "=== GMPM Backup Starting at $(date) ==="

# 1. Backup database
echo "Backing up database..."
mysqldump -u "$DB_USER" -p"$1" "$DB_NAME" > "$BACKUP_DIR/gmpm_database_${TIMESTAMP}.sql"

if [ $? -eq 0 ]; then
    echo "✓ Database backup successful"
else
    echo "✗ Database backup failed"
    exit 1
fi

# 2. Create complete backup
echo "Creating complete backup..."
cd "$HOME"
tar -czf "$BACKUP_DIR/gmpm_complete_${TIMESTAMP}.tar.gz" \
    --exclude='app/vendor' \
    --exclude='storage/logs/*.log' \
    --exclude='public_html/error_log' \
    public_html app storage .htpasswds \
    "$BACKUP_DIR/gmpm_database_${TIMESTAMP}.sql"

if [ $? -eq 0 ]; then
    echo "✓ Complete backup successful"
    # Remove temporary database file
    rm "$BACKUP_DIR/gmpm_database_${TIMESTAMP}.sql"
else
    echo "✗ Complete backup failed"
    exit 1
fi

# 3. Clean up old backups
echo "Cleaning up old backups (keeping last $DAYS_TO_KEEP days)..."
find "$BACKUP_DIR" -name "gmpm_complete_*.tar.gz" -mtime +$DAYS_TO_KEEP -delete
find "$BACKUP_DIR" -name "gmpm_database_*.sql" -mtime +$DAYS_TO_KEEP -delete

# 4. Show backup stats
BACKUP_SIZE=$(ls -lah "$BACKUP_DIR/gmpm_complete_${TIMESTAMP}.tar.gz" | awk '{print $5}')
echo ""
echo "=== Backup Complete ==="
echo "File: gmpm_complete_${TIMESTAMP}.tar.gz"
echo "Size: $BACKUP_SIZE"
echo "Location: $BACKUP_DIR"
echo ""
echo "Current backups:"
ls -lah "$BACKUP_DIR"/gmpm_complete_*.tar.gz 2>/dev/null | tail -5
