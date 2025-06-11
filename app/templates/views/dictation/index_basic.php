<?php
// Set no-cache headers
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Get procedures from database
// $procedures is passed from the controller
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Dictation Generator - GMPM</title>
    <link rel="stylesheet" href="/assets/css/form-styles.css">
    <style>
        .procedure-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.2s;
            border: 2px solid var(--border-color);
        }
        
        .procedure-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            border-color: var(--primary-color);
        }
        
        .procedure-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .procedure-description {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }
        
        #dictationForm {
            display: none;
            background: white;
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
        }
        
        #dictationOutput {
            display: none;
            background: white;
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
        }
        
        .generated-dictation {
            background: #f8f9fa;
            border: 2px solid var(--border-color);
            border-radius: 4px;
            padding: 1.5rem;
            margin: 1rem 0;
            font-family: monospace;
            white-space: pre-wrap;
            max-height: 500px;
            overflow-y: auto;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        @media (max-width: 640px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-card">
            <div class="form-header">
                <h1>Dictation Generator</h1>
                <p>Select a procedure and fill in patient information</p>
            </div>
            
            <div class="form-content">
                <!-- Procedure Selection -->
                <div id="procedureSelection">
                    <h2>Select a Procedure</h2>
                    <?php if (empty($procedures)): ?>
                        <p>No procedures found. Please add procedures to the database.</p>
                    <?php else: ?>
                        <?php foreach ($procedures as $procedure): ?>
                            <?php 
                                $procId = $procedure['id'];
                                $procName = $procedure['name'];
                                $procDesc = substr($procedure['template'], 0, 100);
                            ?>
                            <div class="procedure-card" data-id="<?php echo htmlspecialchars($procId); ?>" data-name="<?php echo htmlspecialchars($procName); ?>">
                                <div class="procedure-title"><?php echo htmlspecialchars($procName); ?></div>
                                <div class="procedure-description"><?php echo htmlspecialchars($procDesc); ?>...</div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <!-- Patient Form -->
                <div id="dictationForm">
                    <h2>Patient Information for: <span id="selectedProcedure"></span></h2>
                    <form id="patientForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Patient Name</label>
                                <input type="text" name="patient_name" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="dob" class="form-input" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">MRN</label>
                                <input type="text" name="mrn" class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Procedure Date</label>
                                <input type="date" name="procedure_date" class="form-input" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Additional Notes</label>
                            <textarea name="additional_notes" class="form-textarea"></textarea>
                        </div>
                        
                        <div class="form-actions">
                            <button type="button" class="btn btn-primary" onclick="generateDictation()">Generate Dictation</button>
                            <button type="button" class="btn btn-secondary" onclick="cancelForm()">Cancel</button>
                        </div>
                    </form>
                </div>
                
                <!-- Output -->
                <div id="dictationOutput">
                    <h2>Generated Dictation</h2>
                    <div class="generated-dictation" id="generatedText"></div>
                    <div class="form-actions">
                        <button class="btn btn-primary" onclick="window.print()">🖨️ Print</button>
                        <button class="btn btn-success" onclick="copyToClipboard()">📋 Copy</button>
                        <button class="btn btn-secondary" onclick="resetForm()">🔄 New Dictation</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Store procedures data properly
        const procedures = <?php echo json_encode($procedures, JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
        let selectedProcedureId = null;
        let selectedTemplate = '';
        
        console.log('Loaded procedures:', procedures);
        
        // Add click handlers to procedure cards
        document.querySelectorAll('.procedure-card').forEach(card => {
            card.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                selectProcedure(id, name);
            });
        });
        
        function selectProcedure(id, name) {
            console.log('Selecting procedure:', id, name);
            
            selectedProcedureId = id;
            const procedure = procedures.find(p => p.id == id);
            
            if (!procedure) {
                alert('Error: Procedure not found');
                return;
            }
            
            selectedTemplate = procedure.template;
            
            document.getElementById('procedureSelection').style.display = 'none';
            document.getElementById('dictationForm').style.display = 'block';
            document.getElementById('selectedProcedure').textContent = name;
        }
        
        function generateDictation() {
            const formData = new FormData(document.getElementById('patientForm'));
            let dictation = selectedTemplate;
            
            // Replace placeholders
            for (let [key, value] of formData.entries()) {
                const placeholder = new RegExp('{{' + key + '}}', 'g');
                dictation = dictation.replace(placeholder, value || '[Not provided]');
            }
            
            // Add timestamp
            dictation = 'Generated: ' + new Date().toLocaleString() + '\n\n' + dictation;
            
            document.getElementById('generatedText').textContent = dictation;
            document.getElementById('dictationForm').style.display = 'none';
            document.getElementById('dictationOutput').style.display = 'block';
        }
        
        function copyToClipboard() {
            const text = document.getElementById('generatedText').textContent;
            navigator.clipboard.writeText(text).then(() => {
                alert('Dictation copied to clipboard!');
            }).catch(err => {
                console.error('Failed to copy:', err);
            });
        }
        
        function cancelForm() {
            document.getElementById('dictationForm').style.display = 'none';
            document.getElementById('procedureSelection').style.display = 'block';
            document.getElementById('patientForm').reset();
        }
        
        function resetForm() {
            document.getElementById('dictationOutput').style.display = 'none';
            document.getElementById('procedureSelection').style.display = 'block';
            document.getElementById('patientForm').reset();
            selectedProcedureId = null;
            selectedTemplate = '';
        }
    </script>
</body>
</html>
