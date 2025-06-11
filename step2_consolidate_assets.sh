#!/bin/bash
# GMPM Step 2: Consolidate Assets Script
# Run from /home/gmpmus/

echo "============================================"
echo "GMPM Step 2: Consolidate Assets"
echo "============================================"
echo ""

# Check if we're in the right directory
if [ ! -d "app" ] || [ ! -d "public_html" ]; then
    echo "Error: Must run from /home/gmpmus/ directory"
    exit 1
fi

echo "1. Consolidating CSS files..."
echo "------------------------------"

# Create consolidated CSS file
cat > app/resources/assets/css/consolidated.css << 'EOF'
/* ============================================
   GMPM Consolidated CSS
   Generated: $(date)
   ============================================ */

EOF

# Combine CSS files in order
if [ -f "app/resources/assets/css/form-styles.css" ]; then
    echo "Adding form-styles.css..."
    echo -e "\n/* ===== Form Styles ===== */\n" >> app/resources/assets/css/consolidated.css
    cat app/resources/assets/css/form-styles.css >> app/resources/assets/css/consolidated.css
fi

if [ -f "app/resources/assets/css/panel-styles.css" ]; then
    echo "Adding panel-styles.css..."
    echo -e "\n/* ===== Panel Styles ===== */\n" >> app/resources/assets/css/consolidated.css
    cat app/resources/assets/css/panel-styles.css >> app/resources/assets/css/consolidated.css
fi

# Add component styles from inline CSS (if any additional styles needed)
echo -e "\n/* ===== Additional Component Styles ===== */\n" >> app/resources/assets/css/consolidated.css

echo "✓ CSS consolidation complete"

echo ""
echo "2. Consolidating JavaScript files..."
echo "------------------------------"

# Create main app.js file
cat > app/resources/assets/js/app.js << 'EOF'
// ============================================
// GMPM Application JavaScript
// Generated: $(date)
// ============================================

// Namespace for GMPM application
window.GMPM = window.GMPM || {};

// ============================================
// Utility Functions
// ============================================
GMPM.Utils = {
    // Show alert message
    showAlert: function(container, type, message, duration = 5000) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        
        const icons = {
            'error': '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path 
fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 
1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>',
            'success': '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path 
fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 
001.414 0l4-4z" clip-rule="evenodd"/></svg>',
            'info': '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path 
fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 
00-1-1H9z" clip-rule="evenodd"/></svg>'
        };
        
        alertDiv.innerHTML = `${icons[type] || ''} ${message}`;
        
        container.innerHTML = '';
        container.appendChild(alertDiv);
        
        if (duration > 0) {
            setTimeout(() => {
                alertDiv.remove();
            }, duration);
        }
    }
};

// Initialize modules based on page
document.addEventListener('DOMContentLoaded', function() {
    console.log('GMPM Application Initialized');
});
EOF

echo "✓ Created main app.js file"

# Create module loader
cat > app/resources/assets/js/modules.js << 'EOF'
// Module loader for individual components
// This will load modules as needed based on page

document.addEventListener('DOMContentLoaded', function() {
    // Detect which module to load based on page elements
    if (document.getElementById('phoneNoteForm')) {
        loadScript('/assets/js/phone-note-form.js');
    }
    
    if (document.getElementById('supportForm')) {
        loadScript('/assets/js/it-support-form.js');
    }
    
    if (document.getElementById('ipForm')) {
        loadScript('/assets/js/ip-manager.js');
    }
    
    if (document.querySelector('.print-container')) {
        loadScript('/assets/js/phone-note-print.js');
    }
});

function loadScript(src) {
    const script = document.createElement('script');
    script.src = src;
    document.body.appendChild(script);
}
EOF

echo "✓ Created module loader"

echo ""
echo "3. Copying assets to public directory..."
echo "------------------------------"

# Copy consolidated CSS to public
cp app/resources/assets/css/consolidated.css public_html/assets/css/app.css
cp app/resources/assets/css/form-styles.css public_html/assets/css/
cp app/resources/assets/css/panel-styles.css public_html/assets/css/

# Copy JS files to public
cp app/resources/assets/js/app.js public_html/assets/js/
cp app/resources/assets/js/modules.js public_html/assets/js/
cp app/resources/assets/js/*.js public_html/assets/js/

echo "✓ Assets copied to public directory"

echo ""
echo "4. Creating asset manifest..."
echo "------------------------------"

# Create simple asset manifest
cat > public_html/assets/manifest.json << EOF
{
    "css/app.css": "css/app.css?v=$(date +%s)",
    "js/app.js": "js/app.js?v=$(date +%s)",
    "js/modules.js": "js/modules.js?v=$(date +%s)"
}
EOF

echo "✓ Asset manifest created"

echo ""
echo "============================================"
echo "✓ Step 2 completed successfully!"
echo ""
echo "Summary:"
echo "- CSS files consolidated into app.css"
echo "- JavaScript structure created with modular loading"
echo "- Assets copied to public directory"
echo "- Asset manifest created for cache busting"
echo ""
echo "Next steps:"
echo "1. Test the consolidated CSS by visiting any page"
echo "2. Update view files to use new asset paths"
echo "3. Consider minification for production"
echo "============================================"
