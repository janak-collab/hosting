#!/bin/bash

# Navigate to your project root (adjust path if needed)
cd ~ || {
  echo "❌ Failed to enter project directory"
  exit 1
}

echo "🔄 Starting sync to GitHub..."

# Stage all changes, including new and deleted files
git add -A

# Only commit if something actually changed
if ! git diff --cached --quiet; then
  TIMESTAMP=$(date '+%Y-%m-%d %H:%M:%S')
  git commit -m "Auto-sync on $TIMESTAMP"
  git push origin main && echo "✅ Push successful!"
else
  echo "🟢 Nothing new to commit — working tree is clean."
fi

