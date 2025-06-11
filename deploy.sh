#!/bin/bash

# Navigate to your website directory
cd /home/gmpmus/public_html

# Pull the latest changes from GitHub
git pull origin main

# Set proper permissions (adjust as needed)
find . -type f -exec chmod 644 {} \;
find . -type d -exec chmod 755 {} \;

echo "Deployment completed at $(date)"
