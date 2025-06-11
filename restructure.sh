#!/bin/bash
# GMPM Directory Restructure Script
# Run this from /home/gmpmus/

echo "==================================="
echo "GMPM Directory Restructure Script"
echo "==================================="
echo ""

# Create backup first
echo "Creating backup..."
tar -czf gmpm_backup_$(date +%Y%m%d_%H%M%S).tar.gz app/ public_html/ --exclude='*/vendor/*' --exclude='*/node_modules/*'

# Create new directory structure
echo "Creating new directory structure..."

# App directories
mkdir -p app/config
mkdir -p app/database/migrations
mkdir -p app/database/seeds
mkdir -p app/resources/views/{phone-note,it-support,admin,errors,layouts,components}
mkdir -p app/resources/assets/{css,js,images,fonts}
mkdir -p app/resources/lang
mkdir -p app/routes
mkdir -p app/src/{Controllers,Models,Services,Middleware,Traits,Core}
mkdir -p app/storage/{logs,cache,uploads,sessions}
mkdir -p app/tests
mkdir -p backups

# Public directories
mkdir -p public_html/assets/{css,js,images,fonts}

echo "Directory structure created successfully!"
