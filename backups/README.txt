GMPM Backup Information
======================
Created: $(date)

Backup Contents:
- gmpm_backup_*.tar.gz : Website files (public_html, app, storage, .htpasswds)
- gmpm_database_*.sql : MySQL database dump

To Restore:
1. Extract files: tar -xzf gmpm_backup_TIMESTAMP.tar.gz -C ~/
2. Import database: mysql -u username -p database_name < gmpm_database_TIMESTAMP.sql

Important Files Excluded:
- app/vendor/ (can be restored with: cd ~/app && composer install)
- storage/logs/*.log
- public_html/error_log

Backups Created on Mon Jun  9 03:07:23 UTC 2025:
=========================
-rw-rw-r-- 1 gmpmus gmpmus 9.6M Jun  9 03:02 /home/gmpmus/backups/gmpm_backup_20250609_030212.tar.gz
-rw-rw-r-- 1 gmpmus gmpmus 9.3M Jun  9 03:03 /home/gmpmus/backups/gmpm_backup_20250609_030316.tar.gz
-rw-rw-r-- 1 gmpmus gmpmus 9.3M Jun  9 03:07 /home/gmpmus/backups/gmpm_complete_20250609_030714.tar.gz
-rw-rw-r-- 1 gmpmus gmpmus    0 Jun  9 03:05 /home/gmpmus/backups/gmpm_database_20250609_030547.sql
-rw-rw-r-- 1 gmpmus gmpmus  41K Jun  9 03:06 /home/gmpmus/backups/gmpm_database_20250609_030615.sql
