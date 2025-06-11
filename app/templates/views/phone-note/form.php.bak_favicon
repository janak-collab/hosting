<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phone Note - Greater Maryland Pain Management</title>
    <link rel="stylesheet" href="/assets/css/form-styles.css">
    <style>
        .provider-section {
            background: #f7fafc;
            border-radius: var(--radius);
            padding: 1.5rem;
            border: 1px solid var(--border-color);
            margin-top: 2rem;
        }
        
        .provider-section h3 {
            color: var(--secondary-color);
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        
        .provider-buttons {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .provider-button {
            padding: 1rem;
            background: var(--card-background);
            border: 2px solid var(--border-color);
            border-radius: var(--radius);
            cursor: pointer;
            transition: var(--transition);
            font-weight: 500;
        }
        
        .provider-button:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }
        
        .phone-preview {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-top: 0.25rem;
        }
        
        .hipaa-warning {
            background: #fef5e7;
            border: 2px solid var(--warning-color);
            border-radius: var(--radius);
            padding: 1rem;
            margin-top: 0.5rem;
            font-size: 0.875rem;
            color: #744210;
            display: none;
            align-items: flex-start;
            gap: 0.75rem;
        }
        
        .appointment-info {
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>📞 Phone Note</h1>
                <p>Create a professional phone message record</p>
            </div>
            
            <div class="form-content">
                <div id="alertContainer"></div>
                
                <div class="info-box">
                    💡 <strong>HIPAA Reminder:</strong> Ensure caller authorization before discussing patient information.
                </div>
                
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <small>Logged in as: <?php echo htmlspecialchars($user); ?></small>
                </div>
                
                <form method="post" id="phoneNoteForm">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Patient Name <span class="required">*</span></label>
                            <input type="text" id="pname" name="pname" class="form-input" required maxlength="100">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Date of Birth <span class="required">*</span></label>
                            <input type="date" id="dob" name="dob" class="form-input" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Phone Number <span class="required">*</span></label>
                            <input type="tel" id="phone" name="phone" class="form-input" required maxlength="10" placeholder="1234567890">
                            <div class="phone-preview" id="phonePreview"></div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Caller Name (if different from patient)</label>
                            <input type="text" id="caller_name" name="caller_name" class="form-input" maxlength="100">
                            <div class="hipaa-warning" id="hipaaWarning">
                                <span>⚠️</span>
                                <div>
                                    <strong>HIPAA Alert:</strong> Third-party caller detected. Verify caller is on patient's HIPAA authorization before proceeding.
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Date Last Seen <span class="required">*</span></label>
                            <input type="date" id="last_seen" name="last_seen" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Next Appointment <span class="required">*</span></label>
                            <input type="date" id="upcoming" name="upcoming" class="form-input" required>
                            <div class="appointment-info" id="appointmentInfo"></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Office Location <span class="required">*</span></label>
                        <select name="location" class="form-select" required>
                            <option value="">Select location</option>
                            <?php foreach ($locations as $location): ?>
                                <option value="<?php echo htmlspecialchars($location); ?>"><?php echo htmlspecialchars($location); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Message Description <span class="required">*</span></label>
                        <textarea id="description" name="description" class="form-textarea" required maxlength="2000" placeholder="Provide detailed information about the patient's concern..."></textarea>
                        <div class="char-count" id="charCount">0 / 2000</div>
                    </div>
                    
                    <div class="provider-section">
                        <h3>Select Message Recipient</h3>
                        <div class="provider-buttons">
                            <?php foreach ($providers as $provider): ?>
                            <button type="button" class="provider-button" data-provider="<?php echo htmlspecialchars($provider['name']); ?>">
                                <?php echo htmlspecialchars($provider['display_name']); ?>
                            </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="footer">
            <p>Greater Maryland Pain Management</p>
            <p><a href="/">Back to Portal</a></p>
        </div>
    </div>
    
    <script src="/assets/js/phone-note-form.js"></script>
</body>
</html>