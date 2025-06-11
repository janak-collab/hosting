// File: /assets/js/header.js
// GMPM Responsive Header JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileSearchToggle = document.getElementById('mobileSearchToggle');
    const mobileSearch = document.getElementById('mobileSearch');
    const globalSearch = document.getElementById('globalSearch');
    const mobileSearchInput = document.querySelector('.mobile-search-input');
    
    // Toggle icons
    const menuIcon = mobileMenuToggle.querySelector('.menu-icon');
    const closeIcon = mobileMenuToggle.querySelector('.close-icon');
    
    // State
    let isMenuOpen = false;
    let isSearchOpen = false;
    
    // Toggle mobile menu
    function toggleMobileMenu() {
        isMenuOpen = !isMenuOpen;
        
        if (isMenuOpen) {
            mobileMenu.classList.add('active');
            document.body.classList.add('mobile-menu-open');
            menuIcon.style.display = 'none';
            closeIcon.style.display = 'block';
            
            // Close search if open
            if (isSearchOpen) {
                isSearchOpen = false;
                mobileSearch.style.display = 'none';
            }
        } else {
            closeMobileMenu();
        }
    }
    
    // Close mobile menu
    function closeMobileMenu() {
        isMenuOpen = false;
        mobileMenu.classList.remove('active');
        document.body.classList.remove('mobile-menu-open');
        menuIcon.style.display = 'block';
        closeIcon.style.display = 'none';
        mobileSearch.style.display = 'none';
        isSearchOpen = false;
    }
    
    // Toggle mobile search
    function toggleMobileSearch() {
        if (!isMenuOpen) {
            // Open menu if not already open
            isMenuOpen = true;
            mobileMenu.classList.add('active');
            document.body.classList.add('mobile-menu-open');
            menuIcon.style.display = 'none';
            closeIcon.style.display = 'block';
        }
        
        isSearchOpen = !isSearchOpen;
        
        if (isSearchOpen) {
            mobileSearch.style.display = 'block';
            // Focus on search input
            setTimeout(() => {
                mobileSearchInput.focus();
            }, 100);
        } else {
            mobileSearch.style.display = 'none';
        }
    }
    
    // Event listeners
    mobileMenuToggle.addEventListener('click', toggleMobileMenu);
    mobileSearchToggle.addEventListener('click', toggleMobileSearch);
    
    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
        if (isMenuOpen && 
            !mobileMenu.contains(e.target) && 
            !mobileMenuToggle.contains(e.target) && 
            !mobileSearchToggle.contains(e.target)) {
            closeMobileMenu();
        }
    });
    
    // Handle window resize
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (window.innerWidth > 768 && isMenuOpen) {
                closeMobileMenu();
            }
        }, 250);
    });
    
    // Search functionality
    function handleSearch(query) {
        if (!query.trim()) return;
        
        // Determine search type based on query
        if (query.match(/^\d{3}-?\d{3}-?\d{4}$/)) {
            // Phone number search
            window.location.href = 
`/search/patient?phone=${encodeURIComponent(query)}`;
        } else if (query.match(/^\d{2}\/\d{2}\/\d{4}$/)) {
            // Date search
            window.location.href = 
`/search/appointment?date=${encodeURIComponent(query)}`;
        } else if (query.toLowerCase().includes('dr.') || 
query.toLowerCase().includes('doctor')) {
            // Provider search
            window.location.href = 
`/search/provider?name=${encodeURIComponent(query)}`;
        } else {
            // General patient search
            window.location.href = 
`/search/patient?name=${encodeURIComponent(query)}`;
        }
    }
    
    // Search input handlers
    globalSearch.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            handleSearch(this.value);
        }
    });
    
    mobileSearchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            handleSearch(this.value);
            closeMobileMenu();
        }
    });
    
    // Sync search values between desktop and mobile
    globalSearch.addEventListener('input', function() {
        mobileSearchInput.value = this.value;
    });
    
    mobileSearchInput.addEventListener('input', function() {
        globalSearch.value = this.value;
    });
    
    // Add keyboard navigation
    document.addEventListener('keydown', function(e) {
        // Close menu on Escape
        if (e.key === 'Escape' && isMenuOpen) {
            closeMobileMenu();
        }
        
        // Focus search on Ctrl+K or Cmd+K
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            if (window.innerWidth > 768) {
                globalSearch.focus();
            } else {
                toggleMobileSearch();
            }
        }
    });
    
    // Add touch gestures for mobile
    let touchStartX = 0;
    let touchEndX = 0;
    
    document.addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });
    
    document.addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, { passive: true });
    
    function handleSwipe() {
        // Swipe left to close menu
        if (isMenuOpen && touchEndX < touchStartX - 50) {
            closeMobileMenu();
        }
    }
});
