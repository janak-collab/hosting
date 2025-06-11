#!/bin/bash

# Update CSS files with GMPM corporate colors
echo "Updating CSS files with GMPM corporate colors..."

# First, let's see what CSS files we have
echo "CSS files found:"
find /home/gmpmus/public_html/assets/css -name "*.css" -type f

# Backup CSS files
echo -e "\nCreating backups..."
cp /home/gmpmus/public_html/assets/css/form-styles.css /home/gmpmus/public_html/assets/css/form-styles.css.bak
cp /home/gmpmus/public_html/assets/css/panel-styles.css /home/gmpmus/public_html/assets/css/panel-styles.css.bak

# Update the root CSS variables in form-styles.css
echo -e "\nUpdating form-styles.css color variables..."
sed -i '/:root {/,/^}/ s/--primary-color: #[a-fA-F0-9]\{6\};/--primary-color: #f26522;/g' /home/gmpmus/public_html/assets/css/form-styles.css
sed -i '/:root {/,/^}/ s/--primary-hover: #[a-fA-F0-9]\{6\};/--primary-hover: #d9581f;/g' /home/gmpmus/public_html/assets/css/form-styles.css
sed -i '/:root {/,/^}/ s/--secondary-color: #[a-fA-F0-9]\{6\};/--secondary-color: #003049;/g' /home/gmpmus/public_html/assets/css/form-styles.css
sed -i '/:root {/,/^}/ s/--text-primary: #[a-fA-F0-9]\{6\};/--text-primary: #58595b;/g' /home/gmpmus/public_html/assets/css/form-styles.css

echo "Done! Corporate colors have been applied."
