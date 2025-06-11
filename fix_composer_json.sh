#!/bin/bash
# Fix composer.json syntax error

echo "Fixing composer.json syntax..."

cd app/

# Create correct composer.json
cat > composer.json << 'EOF'
{
    "name": "gmpm/website",
    "description": "Greater Maryland Pain Management Website",
    "type": "project",
    "require": {
        "php": ">=7.4",
        "vlucas/phpdotenv": "^5.4",
        "phpmailer/phpmailer": "^6.6",
        "monolog/monolog": "^2.8",
        "ext-pdo": "*",
        "ext-mbstring": "*"
    },
    "autoload": {
        "psr-4": {
            "App\\": "src/",
            "App\\Core\\": "src/Core/",
            "App\\Middleware\\": "src/Middleware/"
        },
        "files": ["src/Helpers/functions.php"]
    }
}
EOF

echo "Composer.json fixed!"

# Regenerate autoload
echo ""
echo "Regenerating autoload..."
composer dump-autoload

echo ""
echo "Autoload regenerated successfully!"
