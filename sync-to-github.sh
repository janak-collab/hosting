#!/bin/bash

# 🏁 Automatically sync project to GitHub

# Use dynamic project root (instead of assuming $HOME)
REPO_DIR="$(git rev-parse --show-toplevel 2>/dev/null)"

if [ -z "$REPO_DIR" ]; then
  echo "❌ Not inside a Git repository."
  exit 1
fi

# Move to repo root
cd "$REPO_DIR" || {
  echo "❌ Failed to cd into Git repo at: $REPO_DIR"
  exit 1
}

# Stage all changes, respecting .gitignore
git add -A

# Commit with timestamp
git commit -m "Auto-sync on $(date '+%Y-%m-%d %H:%M:%S')" || {
  echo "✅ No changes to commit"
}

# Push to GitHub
git push origin main

