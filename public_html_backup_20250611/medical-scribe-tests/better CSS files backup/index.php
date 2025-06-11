<?php 
if (function_exists('opcache_reset')) {
   opcache_reset();
}

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
           <form id="radiologyForm" method="POST" action="process.php" class="medical-form" novalidate>
               <?php 
               // Add error reporting
               error_reporting(E_ALL);
               ini_set('display_errors', 1);
               
               $basePath = '/home1/jvidyart/public_html/janakvidyarthi.com/private_area/medical-scribe';
               include $basePath . '/components/patient-info.php';
               include $basePath . '/components/treatment-medications.php';
               ?>
               
               <div class="form-actions">
                   <button type="button" class="btn btn-secondary" onclick="window.history.back();">Back</button>
                   <button type="submit" class="btn btn-primary" id="submitButton">Generate Orders</button>
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

</body>
</html>