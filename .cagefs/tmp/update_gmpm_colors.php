<?php
// Update CSS files with GMPM corporate colors

$cssFile = '/home/gmpmus/public_html/assets/css/form-styles.css';
$content = file_get_contents($cssFile);

// Define replacements
$replacements = [
    // Primary orange
    '--primary-color: #f26522;' => '--primary-color: #f26522; /* GMPM Orange */',
    '--primary-hover: #d4541d;' => '--primary-hover: #d9581f; /* Darker GMPM Orange */',
    
    // Secondary blue  
    '--secondary-color: #2c3e50;' => '--secondary-color: #003049; /* GMPM Blue */',
    
    // Text colors using gray
    '--text-primary: #1a202c;' => '--text-primary: #58595b; /* GMPM Gray */',
    '--text-secondary: #4a5568;' => '--text-secondary: rgba(88, 89, 91, 0.75); /* GMPM Gray 75% */',
];

// Apply replacements
foreach ($replacements as $old => $new) {
    $content = str_replace($old, $new, $content);
}

// Save the updated file
file_put_contents($cssFile, $content);

echo "CSS colors updated successfully!\n";
echo "Orange: #f26522 (RGB 242,101,34)\n";
echo "Gray: #58595b (RGB 88,89,91)\n";
echo "Blue: #003049 (RGB 0,48,73)\n";
