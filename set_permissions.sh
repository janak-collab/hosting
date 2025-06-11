#!/bin/bash
# GMPM Set Permissions Script
# Run from /home/gmpmus/

echo "Setting permissions..."

# Set base app directory permissions
chmod -R 755 app/

# Set storage directory permissions (needs to be writable)
chmod -R 775 app/storage/

# Set specific permissions for sensitive files
if [ -f "app/.env" ]; then
    chmod 600 app/.env
    echo "Set .env permissions to 600"
fi

# Ensure storage subdirectories are writable
for dir in logs cache sessions uploads; do
    if [ -d "app/storage/$dir" ]; then
        chmod 775 "app/storage/$dir"
        echo "Set app/storage/$dir to 775"
    fi
done

# Set ownership (adjust 'www-data' to your web server user if different)
# Uncomment and modify the following line if needed:
# chown -R gmpmus:gmpmus app/

echo "Permissions set successfully!"
echo ""
echo "Current permissions:"
ls -la app/
echo ""
echo "Storage permissions:"
ls -la app/storage/
