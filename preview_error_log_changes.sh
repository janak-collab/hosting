#!/bin/bash
# Update error_log() calls to use Logger

echo "==================================="
echo "Update error_log to Logger Script"
echo "==================================="
echo ""

# First, let's analyze what we're dealing with
echo "1. Analyzing error_log usage..."
echo ""

# Count occurrences
total_files=$(grep -r "error_log(" --include="*.php" app/ public_html/ 2>/dev/null | grep -v "/vendor/" | grep -v ".bak" | cut -d: -f1 | sort -u | 
wc -l)
total_occurrences=$(grep -r "error_log(" --include="*.php" app/ public_html/ 2>/dev/null | grep -v "/vendor/" | grep -v ".bak" | wc -l)

echo "Found error_log() in $total_files files with $total_occurrences total occurrences"
echo ""

# Show examples of what we'll be updating
echo "2. Examples of error_log calls to update:"
grep -r "error_log(" --include="*.php" app/ public_html/ 2>/dev/null | grep -v "/vendor/" | grep -v ".bak" | head -5
echo ""

# Create backup directory
backup_dir="backups/error_log_update_$(date +%Y%m%d_%H%M%S)"
mkdir -p "$backup_dir"
echo "Created backup directory: $backup_dir"
echo ""

# Ask for confirmation
read -p "Do you want to proceed with updating error_log to Logger? (y/n) " -n 1 -r
echo ""

if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Aborted."
    exit 0
fi

echo ""
echo "3. Processing files..."
echo ""

# Get list of files to update
files_to_update=$(grep -r "error_log(" --include="*.php" app/ public_html/ 2>/dev/null | grep -v "/vendor/" | grep -v ".bak" | cut -d: -f1 | sort 
-u)

# Counter
updated_count=0

# Process each file
while IFS= read -r file; do
    echo "Processing: $file"
    
    # Create backup
    backup_file="$backup_dir/$(echo "$file" | tr '/' '_').bak"
    cp "$file" "$backup_file"
    
    # Create temporary file
    temp_file=$(mktemp)
    
    # Check if file already has use statement for Logger
    has_use_logger=$(grep -c "use App\\\\Services\\\\Logger;" "$file")
    
    # Check if file has namespace
    has_namespace=$(grep -c "^namespace " "$file")
    
    # Process the file with PHP to handle complex replacements
    php -r '
    $content = file_get_contents($argv[1]);
    $has_use_logger = $argv[2];
    $has_namespace = $argv[3];
    
    // Replace error_log patterns
    $patterns = [
        // Simple error_log with string
        "/error_log\s*\(\s*[\"\'](.*?)[\"\']\s*\)/s" => "Logger::error(\"$1\")",
        
        // error_log with concatenation - basic pattern
        "/error_log\s*\(\s*[\"\'](.*?)[\"\']\s*\.\s*\\\$(\w+)\s*\)/s" => "Logger::error(\"$1\", [\"value\" => \$$2])",
        
        // error_log with getMessage()
        "/error_log\s*\(\s*[\"\'](.*?)[\"\']\s*\.\s*\\\$(\w+)->getMessage\(\)\s*\)/s" => "Logger::error(\"$1\", [\"error\" => \$$2->getMessage()])",
        
        // error_log with complex concatenation
        "/error_log\s*\(\s*[\"\'](.*?)[\"\'].*?\.\s*(.*?)\s*\);/s" => "Logger::error(\"$1\", [\"details\" => $2]);",
    ];
    
    // First pass - simple replacements
    foreach ($patterns as $pattern => $replacement) {
        $content = preg_replace($pattern, $replacement, $content);
    }
    
    // Handle more complex patterns manually
    // error_log("Some text: " . $var . " more text");
    $content = preg_replace_callback(
        "/error_log\s*\(\s*([^;]+)\s*\);/s",
        function($matches) {
            $original = $matches[0];
            $inner = $matches[1];
            
            // If already converted, skip
            if (strpos($original, "Logger::") !== false) {
                return $original;
            }
            
            // Try to parse the concatenation
            if (strpos($inner, " . ") !== false) {
                // Extract the main message
                if (preg_match("/^[\"\'](.*?)[\"\']/", $inner, $msgMatch)) {
                    $message = $msgMatch[1];
                    
                    // Extract variables
                    preg_match_all("/\\\$(\w+)(?:->(\w+)\(\))?/", $inner, $varMatches);
                    
                    if (!empty($varMatches[1])) {
                        $context = [];
                        foreach ($varMatches[1] as $i => $var) {
                            $accessor = $varMatches[2][$i];
                            if ($accessor) {
                                $context[] = "\"$var\" => \$$var->$accessor()";
                            } else {
                                $context[] = "\"$var\" => \$$var";
                            }
                        }
                        return "Logger::error(\"$message\", [" . implode(", ", $context) . "]);";
                    }
                }
            }
            
            // Fallback - just convert simple calls
            if (preg_match("/^[\"\'](.*?)[\"\']\s*$/", $inner, $match)) {
                return "Logger::error(\"" . $match[1] . "\");";
            }
            
            // If we cannot parse it, leave it for manual review
            return "// TODO: Manual review needed\n        // " . $original;
        },
        $content
    );
    
    // Add use statement if needed
    if ($has_use_logger == "0" && $has_namespace == "1") {
        // Add after namespace
        $content = preg_replace(
            "/(namespace\s+[^;]+;)/", 
            "$1\n\nuse App\Services\Logger;", 
            $content, 
            1
        );
    } elseif ($has_use_logger == "0" && $has_namespace == "0") {
        // Add after <?php
        $content = preg_replace(
            "/(<\?php\s*\n)/", 
            "$1\nuse App\Services\Logger;\n", 
            $content, 
            1
        );
    }
    
    echo $content;
    ' "$file" "$has_use_logger" "$has_namespace" > "$temp_file"
    
    # Check if the conversion was successful
    if [ -s "$temp_file" ]; then
        # Move temp file to original
        mv "$temp_file" "$file"
        echo "  ✓ Updated successfully"
        ((updated_count++))
    else
        echo "  ✗ Failed to update (check backup)"
        rm -f "$temp_file"
    fi
    
done <<< "$files_to_update"

echo ""
echo "==================================="
echo "Update Complete!"
echo "==================================="
echo ""
echo "Summary:"
echo "- Files updated: $updated_count"
echo "- Backups saved in: $backup_dir"
echo ""
echo "Next steps:"
echo "1. Review the changes:"
echo "   grep -r 'Logger::error' app/ public_html/ | head -10"
echo ""
echo "2. Look for TODO comments (manual review needed):"
echo "   grep -r 'TODO: Manual review' app/ public_html/"
echo ""
echo "3. Test the application to ensure everything works"
echo ""
echo "4. If there are issues, restore from backup:"
echo "   for f in $backup_dir/*.bak; do"
echo '       original=$(echo "$f" | sed "s|$backup_dir/||" | sed "s|_|/|g" | sed "s|.bak||")'
echo '       cp "$f" "$original"'
echo "   done"
echo ""
