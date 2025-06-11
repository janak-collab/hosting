<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dictation - <?php echo htmlspecialchars($dictation['patient']['name']); ?></title>
    <link rel="stylesheet" href="/assets/css/app.css">
    <style>
        .dictation-header {
            background: var(--background-color);
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .dictation-content {
            background: white;
            border: 2px solid var(--border-color);
            border-radius: var(--radius);
            padding: 2rem;
            margin-bottom: 2rem;
            line-height: 1.8;
        }
        
        .dictation-content p {
            margin-bottom: 1rem;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .info-item {
            padding: 0.5rem 0;
        }
        
        .info-label {
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }
        
        .info-value {
            color: var(--text-primary);
            margin-top: 0.25rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-bottom: 2rem;
        }
        
        .billing-info {
            background: #f7fafc;
            border-radius: var(--radius);
            padding: 1rem;
            margin-top: 1rem;
        }
        
        .billing-table {
            width: 100%;
            margin-top: 0.5rem;
        }
        
        .billing-table th,
        .billing-table td {
            padding: 0.5rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        
        .billing-table th {
            font-weight: 600;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }
        
        @media print {
            .no-print {
                display: none !important;
            }
            
            .dictation-content {
                border: none;
                padding: 0;
            }
            
            body {
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1><?php echo htmlspecialchars($procedure['name']); ?></h1>
                <p>Dictation Report</p>
            </div>
            
            <div class="form-content">
                <!-- Action Buttons -->
                <div class="action-buttons no-print">
                    <a href="/dictation" class="btn btn-secondary">← Back to Procedures</a>
                    <a href="/dictation/recent" class="btn btn-secondary">Recent Dictations</a>
                    <button onclick="window.print()" class="btn btn-secondary">🖨️ Print</button>
                    <a href="/dictation/pdf/<?php echo htmlspecialchars($filename); ?>" 
                       class="btn btn-primary" 
                       target="_blank">📄 Download PDF</a>
                </div>
                
                <!-- Patient Information -->
                <div class="dictation-header">
                    <h3>Patient Information</h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-label">Patient Name</div>
                            <div class="info-value"><?php echo htmlspecialchars($dictation['patient']['name']); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Date of Birth</div>
                            <div class="info-value"><?php echo date('m/d/Y', strtotime($dictation['patient']['dob'])); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">MRN</div>
                            <div class="info-value"><?php echo htmlspecialchars($dictation['patient']['mrn'] ?: 'N/A'); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Date of Service</div>
                            <div class="info-value"><?php echo date('m/d/Y', strtotime($dictation['patient']['dos'])); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Location</div>
                            <div class="info-value"><?php echo htmlspecialchars($dictation['metadata']['location']); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Provider</div>
                            <div class="info-value"><?php echo htmlspecialchars($provider['full_name']); ?>, <?php echo htmlspecialchars($provider['title']); ?></div>
                        </div>
                    </div>
                </div>
                
                <!-- Dictation Content -->
                <div class="dictation-content">
                    <?php echo $formattedDictation; ?>
                </div>
                
                <!-- Billing Information -->
                <?php if (!empty($dictation['billing']) && !empty($dictation['billing']['codes'])): ?>
                    <div class="billing-info">
                        <h3>Billing Information</h3>
                        <table class="billing-table">
                            <thead>
                                <tr>
                                    <th>CPT Code</th>
                                    <th>Description</th>
                                    <th>Units</th>
                                    <th>Laterality</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dictation['billing']['codes'] as $code): ?>
                                    <?php 
                                    // Find the matching billing code info
                                    $codeInfo = null;
                                    foreach ($billingCodes as $bc) {
                                        if ($bc['cpt_code'] == $code) {
                                            $codeInfo = $bc;
                                            break;
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($code); ?></td>
                                        <td><?php echo $codeInfo ? htmlspecialchars($codeInfo['description']) : 'N/A'; ?></td>
                                        <td><?php echo htmlspecialchars($dictation['billing']['units'][$code] ?? '1'); ?></td>
                                        <td><?php echo htmlspecialchars($dictation['billing']['laterality'][$code] ?? 'N/A'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                
                <!-- Metadata -->
                <div class="info-box" style="margin-top: 2rem;">
                    <p><strong>Created:</strong> <?php echo date('m/d/Y g:i A', strtotime($dictation['metadata']['created_at'])); ?></p>
                    <p><strong>File:</strong> <?php echo htmlspecialchars($filename); ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Add keyboard shortcut for printing
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
    </script>
</body>
</html>
