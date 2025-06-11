#!/bin/bash
# Fix Composer Autoload Configuration

echo "Fixing composer autoload configuration..."

cd app/

# Update composer.json to include Core namespace
echo "Updating composer.json..."
if grep -q '"App\\\\Core\\\\"' composer.json; then
    echo "Core namespace already configured"
else
    # Add Core to the autoload
    sed -i '/"App\\\\": "src\/"/a\            "App\\\\Core\\\\": "src\/Core\/",' composer.json
    sed -i '/"App\\\\": "src\/"/a\            "App\\\\Middleware\\\\": "src\/Middleware\/",' composer.json
fi

# Show current composer.json
echo ""
echo "Current composer.json autoload section:"
grep -A 10 '"autoload"' composer.json

# Regenerate autoload
echo ""
echo "Regenerating composer autoload..."
composer dump-autoload

# Test autoload
echo ""
echo "Testing autoload..."
php -r '
require "vendor/autoload.php";
echo "Testing class loading:\n";
$tests = [
    "App\\Core\\Router" => "Router",
    "App\\Controllers\\BaseController" => "BaseController",
    "App\\Controllers\\PortalController" => "PortalController",
    "App\\Services\\Logger" => "Logger",
    "App\\Middleware\\Auth" => "Auth middleware"
];

foreach ($tests as $class => $name) {
    if (class_exists($class)) {
        echo "✓ $name loaded successfully\n";
    } else {
        echo "✗ Failed to load $name ($class)\n";
    }
}
'

echo ""
echo "Autoload configuration complete!"
