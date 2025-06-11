// User Management JavaScript
document.addEventListener('DOMContentLoaded', function() {
    let currentPage = 1;
    let totalPages = 1;
    let searchTerm = '';
    let filterRole = 'all';
    
    // Initialize DataTable-like functionality
    initializeUserTable();
    
    // Load users on page load
    loadUsers();
    
    // Initialize tooltips
    initTooltips();
    
    function initializeUserTable() {
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    searchTerm = this.value;
                    currentPage = 1;
                    loadUsers();
                }, 300);
            });
        }
        
        // Role filter
        const roleFilter = document.getElementById('roleFilter');
        if (roleFilter) {
            roleFilter.addEventListener('change', function() {
                filterRole = this.value;
                currentPage = 1;
                loadUsers();
            });
        }
        
        // Pagination
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('page-btn')) {
                e.preventDefault();
                const page = parseInt(e.target.dataset.page);
                if (page && page !== currentPage) {
                    currentPage = page;
                    loadUsers();
                }
            }
        });
    }
    
    function loadUsers() {
        const tbody = document.getElementById('usersTableBody');
        if (!tbody) return;
        
        // Show loading state
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </td>
            </tr>
        `;
        
        // Build query parameters
        const params = new URLSearchParams({
            page: currentPage,
            search: searchTerm,
            role: filterRole
        });
        
        // Fetch users
        fetch(`/api/users/list.php?${params}`, {
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayUsers(data.data);
                updatePagination(data.pagination || {});
            } else {
                showError('Failed to load users: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error loading users:', error);
            showError('Failed to load users. Please try again.');
        });
    }
    
    function displayUsers(users) {
        const tbody = document.getElementById('usersTableBody');
        if (!tbody) return;
        
        if (!users || users.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-muted">
                        No users found
                    </td>
                </tr>
            `;
            return;
        }
        
        tbody.innerHTML = users.map(user => `
            <tr>
                <td>${escapeHtml(user.id)}</td>
                <td>
                    <strong>${escapeHtml(user.username)}</strong>
                    ${user.is_active == 0 ? '<span class="badge badge-secondary ml-2">Inactive</span>' : ''}
                    ${user.locked_until ? '<span class="badge badge-warning ml-2">Locked</span>' : ''}
                </td>
                <td>${escapeHtml(user.full_name || '-')}</td>
                <td>${escapeHtml(user.email || '-')}</td>
                <td>
                    <span class="badge badge-${getRoleBadgeClass(user.role)}">
                        ${escapeHtml(user.role)}
                    </span>
                </td>
                <td>
                    <small class="text-muted">
                        ${formatDate(user.created_at)}
                    </small>
                </td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <a href="/admin/users/edit/${user.id}" 
                           class="btn btn-outline-primary" 
                           title="Edit user">
                            <i class="fas fa-edit"></i>
                        </a>
                        ${user.locked_until ? `
                            <button type="button" 
                                    class="btn btn-outline-warning unlock-btn" 
                                    data-id="${user.id}"
                                    title="Unlock user">
                                <i class="fas fa-unlock"></i>
                            </button>
                        ` : ''}
                        ${user.username !== 'jvidyarthi' ? `
                            <button type="button" 
                                    class="btn btn-outline-danger delete-btn" 
                                    data-id="${user.id}"
                                    data-username="${escapeHtml(user.username)}"
                                    title="Delete user">
                                <i class="fas fa-trash"></i>
                            </button>
                        ` : ''}
                    </div>
                </td>
            </tr>
        `).join('');
        
        // Attach event handlers
        attachActionHandlers();
    }
    
    function attachActionHandlers() {
        // Delete buttons
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.dataset.id;
                const username = this.dataset.username;
                
                if (confirm(`Are you sure you want to delete user "${username}"?\n\nThis action cannot be undone.`)) {
                    deleteUser(userId);
                }
            });
        });
        
        // Unlock buttons
        document.querySelectorAll('.unlock-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const userId = this.dataset.id;
                unlockUser(userId);
            });
        });
    }
    
    function deleteUser(userId) {
        fetch(`/api/users/delete-user.php?id=${userId}`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess('User deleted successfully');
                loadUsers();
            } else {
                showError('Failed to delete user: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error deleting user:', error);
            showError('Failed to delete user. Please try again.');
        });
    }
    
    function unlockUser(userId) {
        fetch(`/api/users/unlock-user.php?id=${userId}`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess('User unlocked successfully');
                loadUsers();
            } else {
                showError('Failed to unlock user: ' + (data.error || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error unlocking user:', error);
            showError('Failed to unlock user. Please try again.');
        });
    }
    
    function updatePagination(pagination) {
        const container = document.getElementById('paginationContainer');
        if (!container) return;
        
        totalPages = pagination.total_pages || 1;
        currentPage = pagination.current_page || 1;
        
        if (totalPages <= 1) {
            container.innerHTML = '';
            return;
        }
        
        let html = '<nav><ul class="pagination justify-content-center">';
        
        // Previous button
        html += `
            <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                <a class="page-link page-btn" href="#" data-page="${currentPage - 1}">
                    Previous
                </a>
            </li>
        `;
        
        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                html += `
                    <li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link page-btn" href="#" data-page="${i}">${i}</a>
                    </li>
                `;
            } else if (i === currentPage - 3 || i === currentPage + 3) {
                html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }
        
        // Next button
        html += `
            <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                <a class="page-link page-btn" href="#" data-page="${currentPage + 1}">
                    Next
                </a>
            </li>
        `;
        
        html += '</ul></nav>';
        container.innerHTML = html;
    }
    
    // Utility functions
    function getRoleBadgeClass(role) {
        const classes = {
            'super_admin': 'danger',
            'admin': 'warning',
            'user': 'secondary'
        };
        return classes[role] || 'secondary';
    }
    
    function formatDate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
    }
    
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    function showSuccess(message) {
        showAlert(message, 'success');
    }
    
    function showError(message) {
        showAlert(message, 'danger');
    }
    
    function showAlert(message, type) {
        const alertContainer = document.getElementById('alertContainer');
        if (!alertContainer) return;
        
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.innerHTML = `
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        `;
        
        alertContainer.appendChild(alert);
        
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            alert.remove();
        }, 5000);
    }
    
    function initTooltips() {
        // Initialize Bootstrap tooltips if available
        if (typeof $ !== 'undefined' && $.fn.tooltip) {
            $('[data-toggle="tooltip"]').tooltip();
        }
    }
});
