<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Support Request - Greater Maryland Pain Management</title>
    <link rel="stylesheet" href="/assets/css/form-styles.css">
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>🛠️ IT Support Request</h1>
                <p>Submit a ticket for technical assistance</p>
            </div>
            
            <div class="form-content">
                <div id="alertContainer"></div>
                
                <div class="info-box">
                    💡 <strong>Tip:</strong> For urgent issues preventing you from working, select "Critical" priority and call IT at 410-555-1234 after submitting.
                </div>
                
                <form id="supportForm" method="POST" action="/api/it-support/submit">
                    <input type="hidden" name="csrf_token" id="csrfToken" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name" class="form-label">
                                Your Name <span class="required">*</span>
                            </label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                class="form-input"
                                required
                                maxlength="100"
                            >
                            <div class="form-error" id="nameError"></div>
                        </div>
                        
                        <div class="form-group">
                            <label for="location" class="form-label">
                                Office Location <span class="required">*</span>
                            </label>
                            <select id="location" name="location" class="form-select" required>
                                <option value="">Select location</option>
                                <?php foreach ($locations as $loc): ?>
                                    <option value="<?php echo $loc; ?>"><?php echo $loc; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-error" id="locationError"></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            Issue Category <span class="required">*</span>
                        </label>
                        <div class="category-grid">
                            <?php foreach ($categories as $value => $label): ?>
                            <div class="category-option">
                                <input 
                                    type="radio" 
                                    id="cat_<?php echo $value; ?>" 
                                    name="category" 
                                    value="<?php echo $value; ?>"
                                    required
                                >
                                <label for="cat_<?php echo $value; ?>" class="category-label">
                                    <?php echo $label; ?>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="form-error" id="categoryError"></div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            Priority Level <span class="required">*</span>
                        </label>
                        <div class="priority-grid">
                            <?php foreach ($priorities as $value => $info): ?>
                            <div class="priority-option">
                                <input 
                                    type="radio" 
                                    id="priority_<?php echo $value; ?>" 
                                    name="priority" 
                                    value="<?php echo $value; ?>"
                                    <?php echo $value === 'normal' ? 'checked' : ''; ?>
                                >
                                <label for="priority_<?php echo $value; ?>" class="priority-label <?php echo $info['class']; ?>">
                                    <strong><?php echo $info['label']; ?></strong><br>
                                    <small><?php echo $info['desc']; ?></small>
                                </label>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="form-group full-width">
                        <label for="description" class="form-label">
                            Issue Description <span class="required">*</span>
                        </label>
                        <textarea 
                            id="description" 
                            name="description" 
                            class="form-textarea"
                            maxlength="2000"
                            placeholder="Please describe your issue in detail. Include any error messages, when the problem started, and what you were trying to do."
                            required
                        ></textarea>
                        <div class="form-error" id="descriptionError"></div>
                        <div class="char-count" id="charCount">0 / 2000</div>
                    </div>
                    
                    <div class="form-actions">
                        <a href="/admin/tickets" class="btn btn-secondary">View Tickets</a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <span id="btnText">Submit Ticket</span>
                            <span id="btnSpinner" class="spinner" style="display: none;"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="footer">
            <p>Greater Maryland Pain Management</p>
            <p><a href="/">Back to Portal</a></p>
        </div>
    </div>
    
    <script src="/assets/js/it-support-form.js"></script>
</body>
</html>
