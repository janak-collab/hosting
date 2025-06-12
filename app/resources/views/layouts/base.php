<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? htmlspecialchars($title) . ' - ' : ''; ?>Greater Maryland Pain Management</title>
    
    <!-- Preload critical assets -->
    <link rel="preload" href="<?php echo asset('css/app.css'); ?>" as="style">
    <link rel="preload" href="<?php echo asset('js/app.js'); ?>" as="script">
    
    <!-- Main stylesheet -->
    <link rel="stylesheet" href="<?php echo asset('css/app.css'); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo asset('images/favicon.ico'); ?>">
    
    <!-- Additional head content -->
    <?php if (isset($head)): ?>
        <?php echo $head; ?>
    <?php endif; ?>
</head>
<body>
    <?php if (isset($bodyClass)): ?>
        <body class="<?php echo htmlspecialchars($bodyClass); ?>">
    <?php endif; ?>
    
    <!-- Main content -->
    <?php echo $content; ?>
    
    <!-- Main JavaScript -->
    <script src="<?php echo asset('js/app.js'); ?>"></script>
    
    <!-- Page-specific scripts -->
    <?php if (isset($scripts)): ?>
        <?php echo $scripts; ?>
    <?php endif; ?>
    
    <!-- Initialize page-specific modules -->
    <?php if (isset($initScript)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php echo $initScript; ?>
        });
    </script>
    <?php endif; ?>
</body>
</html>
