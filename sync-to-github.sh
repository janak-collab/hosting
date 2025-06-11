#!/bin/bash

# Use the user's home directory as Git repo root
REPO_ROOT="$HOME"

# Path to symlinked directory (e.g., public_html)
TARGET_SYMLINK="$REPO_ROOT/public_html"

# Resolve real path of the symlink
REAL_TARGET=$(readlink -f "$TARGET_SYMLINK")

# Move to Git repo root
cd "$REPO_ROOT" || {
  echo "❌ Failed to enter repo root: $REPO_ROOT"
  exit 1
}

echo "🔄 Syncing Git repository from: $REAL_TARGET"

# Stage all changes
git add -A

# Commit only if there are staged changes
if ! git diff --cached --quiet; then
  TIMESTAMP=$(date '+%Y-%m-%d %H:%M:%S')
  git commit -m "Auto-sync on $TIMESTAMP"
  git push origin main && echo "✅ Push successful!"
else
  echo "🟢 Nothing new to commit — working tree is clean."
fi

