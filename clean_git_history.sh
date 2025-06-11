#!/bin/bash
echo "=== Cleaning Git History ==="

# Backup first
echo "Creating backup..."
tar -czf ~/git_backup_$(date +%Y%m%d_%H%M%S).tar.gz .git

# Remove sensitive directories from all history
echo "Removing sensitive files from history..."
git filter-branch --force --index-filter \
  'git rm -r --cached --ignore-unmatch .ssh .htpasswds' \
  --prune-empty --tag-name-filter cat -- --all

# Cleanup
echo "Cleaning up..."
git for-each-ref --format="delete %(refname)" refs/original | git update-ref --stdin
git reflog expire --expire=now --all
git gc --prune=now --aggressive

echo "=== Done! Now run: git push origin --force --all ==="
