<?php
// File: /app/templates/components/header.php
// GMPM Responsive Header Component

// Get user info
$username = $_SESSION['username'] ?? $_SERVER['PHP_AUTH_USER'] ?? 'User';
$userRole = $_SESSION['user_role'] ?? 'user';
$userInitial = strtoupper(substr($username, 0, 1));
?>

<!-- Header -->
<header class="header">
    <div class="header-container">
        <div class="header-content">
            <!-- Logo -->
            <a href="/" class="header-logo">
                <div>
                    <div class="logo-text">
                        <span class="logo-text-full">GMPM Portal</span>
                        <span class="logo-text-short">GMPM</span>
                    </div>
                    <div class="logo-subtext">Greater Maryland Pain 
Management</div>
                </div>
            </a>

            <!-- Desktop Search -->
            <div class="header-search">
                <div class="search-container">
                    <svg class="search-icon" fill="none" stroke="currentColor" 
viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" 
stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="search" class="search-input" id="globalSearch" 
placeholder="Search patient, form, or provider...">
                </div>
            </div>

            <!-- Desktop User Section -->
            <div class="header-user">
                <div class="user-welcome">
                    <span class="user-welcome-text">Welcome, <span 
class="user-name"><?php echo htmlspecialchars($username); ?></span></span>
                    <div class="user-avatar"><?php echo $userInitial; ?></div>
                </div>
                <?php if ($userRole === 'admin'): ?>
                    <span class="admin-badge">Admin</span>
                <?php endif; ?>
                <a href="/logout" class="logout-btn">Logout</a>
            </div>

            <!-- Mobile Actions -->
            <div class="header-actions-mobile">
                <button class="mobile-search-toggle" id="mobileSearchToggle">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" 
stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
                <button class="mobile-menu-toggle" id="mobileMenuToggle">
                    <svg class="menu-icon" fill="none" stroke="currentColor" 
viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" 
stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <svg class="close-icon" fill="none" stroke="currentColor" 
viewBox="0 0 24 24" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" 
stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-content">
            <div class="mobile-search" id="mobileSearch" style="display: 
none;">
                <div class="search-container">
                    <svg class="search-icon" fill="none" stroke="currentColor" 
viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" 
stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="search" class="search-input 
mobile-search-input" placeholder="Search patient, form, or provider...">
                </div>
            </div>
            
            <div class="mobile-user-info">
                <div class="user-avatar"><?php echo $userInitial; ?></div>
                <div>
                    <div class="user-name"><?php echo 
htmlspecialchars($username); ?></div>
                    <?php if ($userRole === 'admin'): ?>
                        <span class="admin-badge">Admin</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Mobile Quick Links -->
            <div class="mobile-quick-links">
                <a href="/phone-note" class="mobile-link">📞 Phone 
Note</a>
                <a href="/it-support" class="mobile-link">💻 IT Support</a>
                <a href="/admin" class="mobile-link">🔧 Admin Panel</a>
            </div>
            
            <a href="/logout" class="logout-btn" style="width: 100%; 
text-align: center;">Logout</a>
        </div>
    </div>
</header>
