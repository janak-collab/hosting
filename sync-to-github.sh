#!/bin/bash

# Set your project root directory (replace if needed)
REPO_DIR="$HOME"

# Change to project root
cd "$REPO_DIR" || { echo "❌ Failed to enter repo root: $REPO_DIR"; exit 1; }

# Optional: check if it's a Git repo
if [ ! -d ".git" ]; then
  echo "❌ This is not a Git repository: $REPO_DIR"
  exit 1
fi

# Stage all changes except ignored ones
git add -A

# Commit with timestamp
git commit -m "Auto-sync on $(date '+%Y-%m-%d %H:%M:%S')" || {
  echo "✅ No changes to commit"
}

# Push to GitHub
git push origin main

