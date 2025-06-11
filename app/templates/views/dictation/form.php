<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dictation System - Greater Maryland Pain Management</title>
    <link rel="stylesheet" href="/assets/css/app.css">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <style>
        /* Batch settings banner */
        .batch-banner {
            background: #e8f5e9;
            border: 2px solid #4caf50;
            border-radius: var(--radius);
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: none;
        }
        
        .batch-banner.active {
            display: block;
        }
        
        .batch-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .batch-details {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }
        
        .batch-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
        }
        
        /* Status footer */
        .status-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--secondary-color);
            color: white;
            padding: 0.75rem 1rem;
            display: none;
            z-index: 1000;
        }
        
        .status-footer.active {
            display: block;
        }
        
        .status-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            font-size: 0.875rem;
        }
        
        .status-left, .status-right {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }
        
        .status-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .timer {
            font-weight: 600;
            font-family: monospace;
            font-size: 1rem;
        }
        
        .timer.warning {
            color: #ffeb3b;
            animation: pulse 2s infinite;
        }
        
        .timer.critical {
            color: #ff5252;
            animation: flash 1s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        @keyframes flash {
            0%, 50%, 100% { opacity: 1; }
            25%, 75% { opacity: 0.5; }
        }
        
        /* Patient section styling */
        .patient-section {
            opacity: 0.5;
            pointer-events: none;
            transition: opacity 0.3s;
        }
        
        .patient-section.active {
            opacity: 1;
            pointer-events: auto;
        }
        
        /* Procedure grid */
        .procedure-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .procedure-card {
            border: 2px solid var(--border-color);
            border-radius: var(--radius);
            padding: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            background: var(--card-background);
        }
        
        .procedure-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .procedure-card.selected {
            border-color: var(--primary-color);
            background: rgba(242, 101, 34, 0.05);
        }
        
        .procedure-card.favorite {
            border-top: 4px solid #ffc107;
        }
        
        .procedure-name {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .procedure-category {
            font-size: 0.875rem;
            color: var(--text-secondary);
        }
        
        .procedure-codes {
            margin-top: 0.5rem;
            font-size: 0.75rem;
            color: var(--info-color);
        }
        
        /* Preview area */
        .preview-section {
            background: #f5f5f5;
            border: 1px solid var(--border-color);
            border-radius: var(--radius);
            padding: 1.5rem;
            margin-top: 2rem;
            display: none;
        }
        
        .preview-section.active {
            display: block;
        }
        
        .preview-content {
            font-family: 'Courier New', monospace;
            white-space: pre-wrap;
            line-height: 1.6;
            background: white;
            padding: 1rem;
            border-radius: calc(var(--radius) * 0.5);
            max-height: 400px;
            overflow-y: auto;
        }
        
        /* Timeout modal */
        .timeout-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }
        
        .timeout-modal.active {
            display: flex;
        }
        
        .timeout-content {
            background: white;
            padding: 2rem;
            border-radius: var(--radius);
            max-width: 400px;
            text-align: center;
        }
        
        .timeout-timer {
            font-size: 3rem;
            font-weight: bold;
            color: var(--error-color);
            margin: 1rem 0;
        }
        
        /* Ensure footer spacing */
        body {
            padding-bottom: 60px;
        }
        
        /* Category filter tabs */
        .category-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
            overflow-x: auto;
            padding-bottom: 0.5rem;
        }
        
        .category-tab {
            padding: 0.5rem 1rem;
            background: var(--background-color);
            border: 1px solid var(--border-color);
            border-radius: 2rem;
            cursor: pointer;
            white-space: nowrap;
            transition: all 0.2s;
        }
        
        .category-tab:hover {
            background: white;
            border-color: var(--primary-color);
        }
        
        .category-tab.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .print-counter {
            background: var(--success-color);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>📝 Dictation System</h1>
                <p>Create procedure dictations for patients</p>
            </div>
            
            <div class="form-content">
                <div id="alertContainer"></div>
                
                <!-- Batch Settings Banner -->
                <div class="batch-banner" id="batchBanner">
                    <div class="batch-info">
                        <div class="batch-details">
                            <div class="batch-item">
                                <span>👨‍⚕️</span>
                                <span id="selectedProvider">Provider</span>
                            </div>
                            <div class="batch-item">
                                <span>📍</span>
                                <span id="selectedLocation">Location</span>
                            </div>
                            <div class="batch-item">
                                <span>📅</span>
                                <span id="selectedDate">Date</span>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary" id="changeSettingsBtn">Change Settings</button>
                    </div>
                </div>
                
                <!-- Batch Setup Section -->
                <div id="batchSetup">
                    <h3>Batch Settings</h3>
                    <p class="text-secondary">Set these once for all dictations in this session</p>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="provider" class="form-label">
                                Provider <span class="required">*</span>
                            </label>
                            <select id="provider" class="form-select" required>
                                <option value="">Select Provider</option>
                                <?php foreach ($providers as $provider): ?>
                                <option value="<?php echo $provider['id']; ?>">
                                    <?php echo htmlspecialchars($provider['name']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="location" class="form-label">
                                Location <span class="required">*</span>
                            </label>
                            <select id="location" class="form-select" required>
                                <option value="">Select Location</option>
                                <?php foreach ($locations as $location): ?>
                                <option value="<?php echo htmlspecialchars($location); ?>">
                                    <?php echo htmlspecialchars($location); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="procedure_date" class="form-label">
                                Procedure Date <span class="required">*</span>
                            </label>
                            <input type="date" id="procedure_date" class="form-input" required>
                        </div>
                    </div>
                    
                    <button type="button" class="btn btn-primary" id="startBatchBtn">
                        Start Batch Dictations
                    </button>
                </div>
                
                <!-- Patient Section -->
                <div id="patientSection" class="patient-section">
                    <h3>Patient Information</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="patient_name" class="form-label">
                                Patient Name <span class="required">*</span>
                            </label>
                            <input type="text" id="patient_name" class="form-input" 
                                   placeholder="Enter patient name" autocomplete="off">
                        </div>
                        
                        <div class="form-group">
                            <label for="patient_dob" class="form-label">
                                Date of Birth <span class="required">*</span>
                            </label>
                            <input type="date" id="patient_dob" class="form-input">
                        </div>
                    </div>
                </div>
                
                <!-- Procedure Selection -->
                <div id="procedureSection" class="patient-section">
                    <h3>Select Procedure</h3>
                    
                    <!-- Category Filter Tabs -->
                    <div class="category-tabs" id="categoryTabs">
                        <button type="button" class="category-tab active" data-category="all">All</button>
                        <button type="button" class="category-tab" data-category="favorites">⭐ Favorites</button>
                    </div>
                    
                    <!-- Procedures Grid -->
                    <div class="procedure-grid" id="procedureList">
                        <!-- Populated by JavaScript -->
                    </div>
                    
                    <!-- Billing Codes Display -->
                    <div id="billingCodes" class="procedure-codes" style="display: none; margin-top: 1rem;"></div>
                </div>
                
                <!-- Preview Section -->
                <div id="previewSection" class="preview-section">
                    <h3>Dictation Preview</h3>
                    <div id="previewContent" class="preview-content"></div>
                    
                    <div class="form-actions" style="margin-top: 1.5rem;">
                        <button type="button" class="btn btn-secondary" onclick="window.print()">
                            🖨️ Print Preview
                        </button>
                        <button type="button" class="btn btn-primary" id="printBtn">
                            🖨️ Print Dictation
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>Greater Maryland Pain Management</p>
            <p><a href="/">Back to Portal</a></p>
        </div>
    </div>
    
    <!-- Status Footer -->
    <div class="status-footer" id="statusFooter">
        <div class="status-content">
            <div class="status-left">
                <div class="status-item">
                    <span>📋</span>
                    <span>Dictations: <span class="print-counter" id="printCounter">0</span></span>
                </div>
                <div class="status-item">
                    <span>📍</span>
                    <span id="footerLocation">Location</span>
                </div>
                <div class="status-item">
                    <span>👨‍⚕️</span>
                    <span id="footerProvider">Provider</span>
                </div>
                <div class="status-item">
                    <span>📅</span>
                    <span id="footerDate">Date</span>
                </div>
            </div>
            <div class="status-right">
                <div class="status-item">
                    <span>⏱️</span>
                    <span>Session expires in: <span class="timer" id="sessionTimer">15:00</span></span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Timeout Modal -->
    <div class="timeout-modal" id="timeoutModal">
        <div class="timeout-content">
            <h2>⚠️ Session Expiring</h2>
            <p>Your session will expire in:</p>
            <div class="timeout-timer" id="timeoutTimer">60</div>
            <p>Move your mouse or press any key to continue working.</p>
            <button type="button" class="btn btn-primary" onclick="window.resetIdleTimer()">
                Continue Working
            </button>
        </div>
    </div>
    
    <script src="/assets/js/dictation-form.js"></script>
</body>
</html>
