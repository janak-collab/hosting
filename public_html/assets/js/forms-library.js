// Forms Library JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('formSearch');
    const searchResults = document.getElementById('searchResults');
    const searchResultsList = document.getElementById('searchResultsList');
    const formsGrid = document.querySelector('.forms-grid');
    
    if (searchInput) {
        searchInput.addEventListener('input', debounce(handleSearch, 300));
    }
    
    // Search handler
    async function handleSearch(e) {
        const query = e.target.value.trim();
        
        if (query.length < 2) {
            searchResults.style.display = 'none';
            formsGrid.style.display = 'block';
            return;
        }
        
        try {
            const response = await fetch(`/api/forms/search?q=${encodeURIComponent(query)}`);
            const data = await response.json();
            
            displaySearchResults(data.results);
        } catch (error) {
            console.error('Search error:', error);
        }
    }
    
    // Display search results
    function displaySearchResults(results) {
        if (results.length === 0) {
            searchResultsList.innerHTML = `
                <div class="no-results">
                    <p>No forms found matching your search.</p>
                </div>
            `;
        } else {
            searchResultsList.innerHTML = results.map(result => `
                <a href="${result.url}" class="form-card">
                    <div class="form-icon">${result.icon}</div>
                    <div class="form-info">
                        <h3>${highlightMatch(result.title, searchInput.value)}</h3>
                        <p>${result.category}</p>
                    </div>
                    <div class="form-arrow">→</div>
                </a>
            `).join('');
        }
        
        formsGrid.style.display = 'none';
        searchResults.style.display = 'block';
    }
    
    // Highlight search matches
    function highlightMatch(text, query) {
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<mark>$1</mark>');
    }
    
    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + K to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchInput.focus();
        }
        
        // Escape to clear search
        if (e.key === 'Escape' && document.activeElement === searchInput) {
            searchInput.value = '';
            searchInput.blur();
            searchResults.style.display = 'none';
            formsGrid.style.display = 'block';
        }
    });
    
    // Add form card animations
    const formCards = document.querySelectorAll('.form-card');
    formCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Auto-focus search on page load if URL has search parameter
    const urlParams = new URLSearchParams(window.location.search);
    const searchQuery = urlParams.get('search');
    if (searchQuery && searchInput) {
        searchInput.value = searchQuery;
        searchInput.dispatchEvent(new Event('input'));
    }
    
    // Track form clicks for analytics (optional)
    document.addEventListener('click', function(e) {
        const formCard = e.target.closest('.form-card');
        if (formCard) {
            const formName = formCard.querySelector('h3')?.textContent;
            console.log('Form clicked:', formName);
            // You can add analytics tracking here
        }
    });
});
