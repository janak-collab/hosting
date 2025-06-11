<?php error_reporting(0); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work/Disability Note - GMPM</title>
    <link rel="stylesheet" href="/assets/css/form-styles.css">
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>📄 Work/Disability Note</h1>
                <p>Generate work excuse or disability documentation</p>
            </div>
            
            <div class="form-content">
                <form id="workNoteForm" method="POST" action="/api/forms/submit">
                    <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? $_SESSION['csrf_token'] : ''; ?>">
                    <input type="hidden" name="form_type" value="work-note">
                    <input type="hidden" name="form_category" value="referrals">
                    
                    <!-- Patient Information -->
                    <h3>Patient Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="patient_name" class="form-label">
                                Patient Name <span class="required">*</span>
                            </label>
                            <input type="text" id="patient_name" name="patient_name" 
                                   class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="dob" class="form-label">
                                Date of Birth <span class="required">*</span>
                            </label>
                            <input type="date" id="dob" name="dob" 
                                   class="form-input" required>
                        </div>
                    </div>
                    
                    <!-- Work Status -->
                    <h3>Work Status</h3>
                    <div class="form-group">
                        <label class="form-label">
                            Patient may return to work <span class="required">*</span>
                        </label>
                        <div class="radio-group">
                            <label>
                                <input type="radio" name="work_status" value="full_duty" required>
                                Full duty
                            </label>
                            <label>
                                <input type="radio" name="work_status" value="light_duty">
                                Light duty with restrictions
                            </label>
                            <label>
                                <input type="radio" name="work_status" value="off_work">
                                Off work completely
                            </label>
                        </div>
                    </div>
                    
                    <!-- Date Range -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="start_date" class="form-label">
                                Effective Date <span class="required">*</span>
                            </label>
                            <input type="date" id="start_date" name="start_date" 
                                   class="form-input" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="end_date" class="form-label">
                                Through Date
                            </label>
                            <input type="date" id="end_date" name="end_date" 
                                   class="form-input">
                        </div>
                    </div>
                    
                    <!-- Restrictions -->
                    <div class="form-group" id="restrictionsGroup" style="display: none;">
                        <label for="restrictions" class="form-label">
                            Work Restrictions
                        </label>
                        <textarea id="restrictions" name="restrictions" 
                                  class="form-textarea" rows="4"
                                  placeholder="List specific work restrictions..."></textarea>
                    </div>
                    
                    <!-- Additional Notes -->
                    <div class="form-group">
                        <label for="notes" class="form-label">
                            Additional Notes
                        </label>
                        <textarea id="notes" name="notes" 
                                  class="form-textarea" rows="3"></textarea>
                    </div>
                    
                    <!-- Provider -->
                    <div class="form-group">
                        <label for="provider" class="form-label">
                            Provider <span class="required">*</span>
                        </label>
                        <select id="provider" name="provider" class="form-select" required>
                            <option value="">Select Provider</option>
                            <option value="Dr. Smith">Dr. Smith</option>
                            <option value="Dr. Johnson">Dr. Johnson</option>
                            <option value="Dr. Williams">Dr. Williams</option>
                        </select>
                    </div>
                    
                    <div class="form-actions">
                        <a href="/forms/referrals" class="btn btn-secondary">
                            ← Back to Referrals
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Generate Work Note
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Show/hide restrictions based on work status
        document.querySelectorAll('input[name="work_status"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const restrictionsGroup = document.getElementById('restrictionsGroup');
                if (this.value === 'light_duty') {
                    restrictionsGroup.style.display = 'block';
                } else {
                    restrictionsGroup.style.display = 'none';
                }
            });
        });
        
        // Form submission
        document.getElementById('workNoteForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('/api/forms/submit', {
                    method: 'POST',
                    body: formData
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert('Work note generated successfully!');
                    // Redirect or show preview
                    window.location.href = '/forms/referrals';
                } else {
                    alert('Error: ' + result.message);
                }
            } catch (error) {
                console.error('Submission error:', error);
                alert('An error occurred. Please try again.');
            }
        });
    </script>
</body>
</html>
