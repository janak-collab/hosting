<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forms Library - GMPM</title>
    <link rel="stylesheet" href="/assets/css/form-styles.css">
    <link rel="stylesheet" href="/assets/css/forms-library.css">
    <link rel="stylesheet" href="/assets/css/header-styles.css">
</head>
<body>
    <?php require_once APP_PATH . '/templates/components/header.php'; ?>

    <!-- Header -->
    
    <div class="container">
        <!-- Category Tabs -->
        <div class="category-tabs">
            <a href="/forms" class="tab <?php echo !$selectedCategory ? 'active' : ''; ?>">
                All Forms
            </a>
            <?php foreach ($categories as $key => $category): ?>
                <a href="/forms/<?php echo $key; ?>" 
                   class="tab <?php echo $selectedCategory === $key ? 'active' : ''; ?>">
                    <span><?php echo $category['icon']; ?></span>
                    <?php echo $category['title']; ?>
                </a>
            <?php endforeach; ?>
        </div>
        
        <!-- Forms Grid -->
        <div class="forms-grid">
            <?php if (!$selectedCategory): ?>
                <!-- Show all categories -->
                <?php foreach ($categories as $catKey => $category): ?>
                    <div class="category-section">
                        <h2 class="category-title">
                            <span><?php echo $category['icon']; ?></span>
                            <?php echo $category['title']; ?>
                        </h2>
                        
                        <div class="forms-list">
                            <?php foreach ($category['forms'] as $formKey => $formName): ?>
                                <a href="/forms/<?php echo $catKey; ?>/<?php echo $formKey; ?>" 
                                   class="form-card">
                                    <div class="form-icon">📄</div>
                                    <div class="form-info">
                                        <h3><?php echo $formName; ?></h3>
                                        <p>Click to fill out this form</p>
                                    </div>
                                    <div class="form-arrow">→</div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Show specific category -->
                <div class="category-section">
                    <h2 class="category-title">
                        <span><?php echo $categories[$selectedCategory]['icon']; ?></span>
                        <?php echo $categories[$selectedCategory]['title']; ?>
                    </h2>
                    
                    <div class="forms-list">
                        <?php foreach ($categories[$selectedCategory]['forms'] as $formKey => $formName): ?>
                            <a href="/forms/<?php echo $selectedCategory; ?>/<?php echo $formKey; ?>" 
                               class="form-card">
                                <div class="form-icon">📄</div>
                                <div class="form-info">
                                    <h3><?php echo $formName; ?></h3>
                                    <p>Click to fill out this form</p>
                                </div>
                                <div class="form-arrow">→</div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Search Results (hidden by default) -->
        <div id="searchResults" class="search-results" style="display: none;">
            <h2>Search Results</h2>
            <div id="searchResultsList" class="forms-list"></div>
        </div>
    </div>
    
    <script src="/assets/js/forms-library.js"></script>
    <script src="/assets/js/header.js"></script>
</body>
</html>
