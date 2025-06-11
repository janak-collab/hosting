#!/bin/bash
# GMPM Dotfile Cleanup Script
# Run this from your project root directory

echo "=============================================="
echo "GMPM Dotfiles Cleanup Script"
echo "=============================================="
echo ""

# Check if we're in a git repository
if [ ! -d ".git" ]; then
    echo "❌ Error: Not in a Git repository root"
    echo "Please run this script from your project root directory"
    exit 1
fi

# Create backup first
echo "📦 Creating backup before cleanup..."
BACKUP_DIR="backups/dotfile_cleanup_$(date +%Y%m%d_%H%M%S)"
mkdir -p "$BACKUP_DIR"

# Backup current .gitignore
if [ -f ".gitignore" ]; then
    cp .gitignore "$BACKUP_DIR/gitignore.bak"
    echo "✓ Backed up current .gitignore"
fi

# List current dotfiles
echo ""
echo "📋 Current dotfiles found:"
find . -maxdepth 1 -name ".*" -type f | sort

echo ""
echo "🔍 Analyzing dotfiles..."

# Categorize files
KEEP_FILES=(".htpasswds" ".gitconfig" ".gitignore")
SENSITIVE_FILES=(".env.backup.*" ".bash_history" ".mysql_history" ".lesshst" ".lastlogin")
SERVER_FILES=(".imunify_patch_id" ".myimunify_id" ".wp-toolkit-identifier" ".spamassassinboxenable" ".spamassassinenable" ".bashrc" ".bash_profile" ".bash_logout")

echo ""
echo "📝 File categorization:"
echo "🟢 KEEPING (needed for app):"
for file in "${KEEP_FILES[@]}"; do
    if [ -e "$file" ]; then
        echo "   ✓ $file"
    fi
done

echo ""
echo "🔴 WILL BE GITIGNORED (sensitive):"
for pattern in "${SENSITIVE_FILES[@]}"; do
    # Use find to handle patterns with wildcards
    if [ "$pattern" = ".env.backup.*" ]; then
        files=$(find . -maxdepth 1 -name ".env.backup.*" -type f 2>/dev/null)
        if [ -n "$files" ]; then
            echo "$files" | sed 's/^/   ⚠️  /'
        fi
    else
        if [ -e "$pattern" ]; then
            echo "   ⚠️  $pattern"
        fi
    fi
done

echo ""
echo "🟡 WILL BE GITIGNORED (server-specific):"
for file in "${SERVER_FILES[@]}"; do
    if [ -e "$file" ]; then
        echo "   📁 $file"
    fi
done

echo ""
read -p "🤔 Do you want to proceed with the cleanup? (y/N): " -n 1 -r
echo ""

if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "❌ Cleanup cancelled"
    exit 0
fi

echo ""
echo "🧹 Starting cleanup..."

# Update .gitignore
echo "📝 Updating .gitignore..."
cat > .gitignore << 'EOF'
# GMPM Project .gitignore

# Sensitive files and credentials
.env
*.env
.env.backup.*
.git-credentials
*.pem
*.key

# Personal/sensitive dotfiles
.bash_history
.mysql_history
.lesshst
.lastlogin

# Server-specific configuration files
.bashrc
.bash_profile
.bash_logout
.imunify_patch_id
.myimunify_id
.wp-toolkit-identifier
.spamassassin*

# Application logs and caches
*.log
*.bak
*.swp
storage/logs/*
storage/cache/*
storage/sessions/*

# Dependencies and build artifacts
vendor/
node_modules/
tmp/
temp_restore/

# Server/hosting-specific directories
.cache/
.cagefs/
.caldav/
.cl.selector/
.config/
.cpanel/
.gnupg/
.local/
.sitepad/
.softaculous/
.trash/
mail/
ssl/

# SSH keys (keep .gitconfig but ignore sensitive keys)
.ssh/
*.pub
yes
yes.pub

# Symlinked paths and aliases
www/
gmpm-new/
access-logs/

# Keep .htpasswds (needed for HTTP Basic Auth)
!.htpasswds/
!.htpasswds/**

# Keep .gitconfig (useful for development)
!.gitconfig

# Application-specific ignores
public_html/error_log
app/storage/logs/*.log
backups/
cookies.txt

# IDE and editor files
.vscode/
.idea/
*.sublime-*

# OS generated files
.DS_Store
.DS_Store?
._*
.Spotlight-V100
.Trashes
ehthumbs.db
Thumbs.db
