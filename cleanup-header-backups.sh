#!/bin/bash
echo "Finding header backup files..."
find /home/gmpmus/app/templates/views -name "*.backup-header-*" -type f -print

read -p "Delete all header backup files? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    find /home/gmpmus/app/templates/views -name "*.backup-header-*" -type f -delete
    echo "Backup files deleted."
else
    echo "Cleanup cancelled."
fi
