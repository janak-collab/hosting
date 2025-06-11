#!/bin/bash
cd ~

if git diff --quiet && git diff --cached --quiet; then
  echo "No changes to sync."
  exit 0
fi

git add .
git commit -m "Auto-sync on $(date '+%Y-%m-%d %H:%M:%S')"
git push origin main

