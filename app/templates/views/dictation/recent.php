<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recent Dictations - GMPM</title>
    <link rel="stylesheet" href="/assets/css/app.css">
    <style>
        .search-section {
            background: var(--background-color);
            border-radius: var(--radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .search-grid {
            display: grid;
            grid-template-columns: 1fr 200px 200px auto;
            gap: 1rem;
            align-items: end;
        }
        
        .dictations-table {
            width: 100%;
            background: var(--card-background);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }
        
        .dictations-table th {
            background: var(--secondary-color);
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }
        
        .dictations-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .dictations-table tr:hover td {
            background: var(--background-color);
        }
        
        .action-links {
            display: flex;
            gap: 0.5rem;
        }
        
        .action-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }
        
        .action-link:hover {
            text-decoration: underline;
        }
        
        .pdf-badge {
            display: inline-block;
            background: var(--success-color);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: var(--radius);
            font-size: 0.75rem;
            margin-left: 0.5rem;
        }
        
        .no-results {
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary);
        }
        
        @media (max-width: 768px) {
            .search-grid {
                grid-template-columns: 1fr;
            }
            
            .dictations-table {
                font-size: 0.875rem;
            }
            
            .dictations-table th,
            .dictations-table td {
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>📋 Recent Dictations</h1>
                <p><?php echo htmlspecialchars($provider['full_name']); ?></p>
            </div>
            
            <div class="form-content">
                <div class="form-actions" style="margin-bottom: 2rem;">
                    <a href="/dictation" class="btn btn-primary">+ New Dictation</a>
                    <a href="/" class="btn btn-secondary">← Back to Portal</a>
                </div>
                
                <!-- Search Section -->
                <div class="search-section">
                    <h3>Search Dictations</h3>
                    <form id="searchForm" method="GET">
                        <div class="search-grid">
                            <input type="text" 
                                   id="searchTerm"
                                   class="form-input" 
                                   placeholder="Search patient name or procedure...">
                            
                            <input type="date" 
                                   id="dateFrom"
                                   class="form-input" 
                                   placeholder="From date">
                            
                            <input type="date" 
                                   id="dateTo"
                                   class="form-input" 
                                   placeholder="To date">
                            
                            <button type="submit" class="btn btn-secondary">Search</button>
                        </div>
                    </form>
                </div>
                
                <!-- Results Table -->
                <?php if (empty($recentDictations)): ?>
                    <div class="no-results">
                        <p>No dictations found.</p>
                        <a href="/dictation" class="btn btn-primary" style="margin-top: 1rem;">Create Your First Dictation</a>
                    </div>
                <?php else: ?>
                    <table class="dictations-table">
                        <thead>
                            <tr>
                                <th>Date/Time</th>
                                <th>Patient</th>
                                <th>DOS</th>
                                <th>Procedure</th>
                                <th>Location</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="resultsBody">
                            <?php foreach ($recentDictations as $dictation): ?>
                                <tr>
                                    <td><?php echo date('m/d/y g:i A', strtotime($dictation['created_at'])); ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($dictation['patient_name']); ?>
                                        <?php if ($dictation['has_pdf']): ?>
                                            <span class="pdf-badge">PDF</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('m/d/Y', strtotime($dictation['dos'])); ?></td>
                                    <td><?php echo htmlspecialchars($dictation['procedure_name']); ?></td>
                                    <td><?php echo htmlspecialchars($dictation['location']); ?></td>
                                    <td>
                                        <div class="action-links">
                                            <a href="/dictation/view/<?php echo htmlspecialchars($dictation['filepath']); ?>" 
                                               class="action-link">View</a>
                                            <?php if ($dictation['has_pdf']): ?>
                                                <a href="/dictation/pdf/<?php echo htmlspecialchars($dictation['filepath']); ?>" 
                                                   class="action-link" 
                                                   target="_blank">PDF</a>
                                            <?php else: ?>
                                                <a href="/dictation/pdf/<?php echo htmlspecialchars($dictation['filepath']); ?>" 
                                                   class="action-link">Generate PDF</a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        // Search functionality
        document.getElementById('searchForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const searchTerm = document.getElementById('searchTerm').value;
            const dateFrom = document.getElementById('dateFrom').value;
            const dateTo = document.getElementById('dateTo').value;
            
            const params = new URLSearchParams();
            if (searchTerm) params.append('q', searchTerm);
            if (dateFrom) params.append('from', dateFrom);
            if (dateTo) params.append('to', dateTo);
            
            try {
                const response = await fetch('/dictation/search?' + params.toString());
                const result = await response.json();
                
                if (result.success) {
                    updateResults(result.results);
                }
            } catch (error) {
                console.error('Search error:', error);
            }
        });
        
        function updateResults(results) {
            const tbody = document.getElementById('resultsBody');
            
            if (results.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="no-results">No results found</td></tr>';
                return;
            }
            
            tbody.innerHTML = results.map(d => `
                <tr>
                    <td>${formatDate(d.created_at)}</td>
                    <td>
                        ${escapeHtml(d.patient_name)}
                        ${d.has_pdf ? '<span class="pdf-badge">PDF</span>' : ''}
                    </td>
                    <td>${formatDate(d.dos, true)}</td>
                    <td>${escapeHtml(d.procedure_name)}</td>
                    <td>${escapeHtml(d.location)}</td>
                    <td>
                        <div class="action-links">
                            <a href="/dictation/view/${escapeHtml(d.filepath)}" class="action-link">View</a>
                            ${d.has_pdf 
                                ? `<a href="/dictation/pdf/${escapeHtml(d.filepath)}" class="action-link" target="_blank">PDF</a>`
                                : `<a href="/dictation/pdf/${escapeHtml(d.filepath)}" class="action-link">Generate PDF</a>`
                            }
                        </div>
                    </td>
                </tr>
            `).join('');
        }
        
        function formatDate(dateStr, dateOnly = false) {
            const date = new Date(dateStr);
            if (dateOnly) {
                return date.toLocaleDateString('en-US');
            }
            return date.toLocaleDateString('en-US') + ' ' + date.toLocaleTimeString('en-US', {
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>
