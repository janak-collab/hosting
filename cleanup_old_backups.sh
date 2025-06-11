#!/bin/bash
# Keep only last 7 days of backups
find ~/backups/daily -name "*.tar.gz" -mtime +7 -delete
# Keep only last 30 days in archive
find ~/backups/archive -name "*.tar.gz" -mtime +30 -delete
