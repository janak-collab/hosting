<?php 
if (function_exists('opcache_reset')) {
    opcache_reset();
}

// Prevent browser caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once 'includes/config.php';
require_once 'includes/functions.php';
?>
<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Medical Scribe</title>
    <link rel="stylesheet" href="css/styles.css?v=<?php echo time(); ?>">
</head>
<body>
    <div class="container">
        <header>
            <h1>Medical Scribe</h1>
        </header>
        
        <main>
            <div id="error-container" role="alert" aria-live="polite"></div>
            
            <form id="radiologyForm" method="POST" class="medical-form">
                <?php 
                // Include form components
                include 'components/patient-info.php';
                include 'components/medications.php';
                include 'components/treatments.php';
                include 'components/treatment-physical-therapy.php';
                include 'components/treatment-braces.php';
                include 'components/diagnostics.php';
                ?>
                
                <div class="form-actions">
                    <button type="submit" class="submit-btn">Generate Orders</button>
                </div>
            </form>
        </main>
        
        <footer>
            <p>&copy; <?php echo date('Y'); ?> Medical Scribe. All rights reserved.</p>
        </footer>
    </div>
    
    <script src="js/form-validation.js?v=<?php echo time(); ?>"></script>
    <script src="js/form-handlers.js?v=<?php echo time(); ?>"></script>
</body>
</html>