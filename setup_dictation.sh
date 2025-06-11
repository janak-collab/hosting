#!/bin/bash

# GMPM Dictation System Setup Script
# This script sets up the complete dictation system with privacy features

echo "=== GMPM Dictation System Setup ==="
echo "This script will create all necessary files for the dictation system"
echo ""

# Set the base directory
BASE_DIR="$HOME/app"
cd "$BASE_DIR" || exit 1

echo "Working in: $BASE_DIR"
echo ""

# Create necessary directories
echo "Creating directories..."
mkdir -p src/Controllers
mkdir -p src/Models  
mkdir -p templates/views/dictation
mkdir -p public-endpoints

# Create the Dictation Model
echo "Creating Dictation Model..."
cat > src/Models/Dictation.php << 'PHP_EOF'
<?php
namespace App\Models;

use App\Database\Connection;
use PDO;

class Dictation
{
    private $db;
    
    public function __construct()
    {
        $this->db = Connection::getInstance()->getConnection();
        $this->createTablesIfNotExist();
    }
    
    private function createTablesIfNotExist()
    {
        // Create dictation_procedures table
        $sql = "CREATE TABLE IF NOT EXISTS dictation_procedures (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            template TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE 
CURRENT_TIMESTAMP,
            INDEX idx_name (name)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $this->db->exec($sql);
        
        // Create audit log table
        $sql = "CREATE TABLE IF NOT EXISTS dictation_audit_log (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_name VARCHAR(100) NOT NULL,
            user_ip VARCHAR(45) NOT NULL,
            action VARCHAR(50) NOT NULL,
            procedure_id INT NULL,
            procedure_name VARCHAR(255) NULL,
            timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
            user_agent TEXT,
            session_id VARCHAR(128),
            INDEX idx_user_timestamp (user_name, timestamp),
            INDEX idx_action (action),
            INDEX idx_timestamp (timestamp)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $this->db->exec($sql);
    }
    
    public function getProcedures()
    {
        $stmt = $this->db->query("SELECT * FROM dictation_procedures ORDER BY 
name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getProcedureById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM dictation_procedures WHERE id 
= :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function logAccess($action, $procedureId = null, $procedureName = 
null)
    {
        $sql = "INSERT INTO dictation_audit_log (user_name, user_ip, action, 
procedure_id, procedure_name, user_agent, session_id)
                VALUES (:user_name, :user_ip, :action, :procedure_id, 
:procedure_name, :user_agent, :session_id)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'user_name' => $_SERVER['PHP_AUTH_USER'] ?? 'Anonymous',
            'user_ip' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
            'action' => $action,
            'procedure_id' => $procedureId,
            'procedure_name' => $procedureName,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
            'session_id' => session_id()
        ]);
    }
}
PHP_EOF

# Create the Dictation Controller
echo "Creating Dictation Controller..."
cat > src/Controllers/DictationController.php << 'PHP_EOF'
<?php
namespace App\Controllers;

use App\Models\Dictation;

class DictationController
{
    private $dictationModel;
    
    public function __construct(Dictation $dictationModel)
    {
        $this->dictationModel = $dictationModel;
    }
    
    public function index()
    {
        // Set no-cache headers for privacy
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        header('X-Frame-Options: SAMEORIGIN');
        header('X-Content-Type-Options: nosniff');
        
        // Log access
        $this->dictationModel->logAccess('view_dictation_system');
        
        // Load the view
        require_once __DIR__ . '/../../templates/views/dictation/index.php';
    }
}
PHP_EOF

# Create the Dictation View (Part 1 - PHP and HTML head)
echo "Creating Dictation View..."
cat > templates/views/dictation/index.php << 'PHP_EOF'
<?php
// Set no-cache headers
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

// Get procedures from database
$procedures = $dictationModel->getProcedures();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, 
must-revalidate">
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
        }
        
        .procedure-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
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
        
        .dictation-form {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--text-primary);
        }
        
        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--border-color);
            border-radius: 4px;
            font-size: 1rem;
        }
        
        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        .form-textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--border-color);
            border-radius: 4px;
            font-size: 1rem;
            min-height: 100px;
            resize: vertical;
        }
        
        .generated-dictation {
            background: #f8f9fa;
            border: 2px solid var(--border-color);
            border-radius: 4px;
            padding: 1.5rem;
            margin-top: 2rem;
            font-family: monospace;
            white-space: pre-wrap;
            max-height: 500px;
            overflow-y: auto;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .btn-primary {
            background: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-hover);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background: #218838;
        }
        
        /* Privacy Settings */
        .privacy-settings {
            background: #e3f2fd;
            border: 1px solid #90caf9;
            border-radius: 4px;
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .privacy-settings h3 {
            margin-top: 0;
            color: #1976d2;
            font-size: 1rem;
        }
        
        .privacy-option {
            margin: 0.5rem 0;
        }
        
        .privacy-option input[type="checkbox"] {
            margin-right: 0.5rem;
        }
        
        .session-timer {
            position: fixed;
            top: 1rem;
            right: 1rem;
            background: #fff3cd;
            border: 1px solid #ffeeba;
            border-radius: 4px;
            padding: 0.5rem 1rem;
            display: none;
            z-index: 1000;
        }
        
        .session-timer.warning {
            background: #f8d7da;
            border-color: #f5c6cb;
        }
        
        /* Secure Print Styles */
        @media print {
            body {
                position: relative;
            }
            
            .watermark {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%) rotate(-45deg);
                font-size: 120px;
                color: rgba(0, 0, 0, 0.1);
                z-index: -1;
                white-space: nowrap;
            }
            
            .confidential-header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                text-align: center;
                background: red;
                color: white;
                font-weight: bold;
                padding: 5px;
                font-size: 14px;
            }
            
            .confidential-footer {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                text-align: center;
                background: red;
                color: white;
                font-weight: bold;
                padding: 5px;
                font-size: 12px;
            }
            
            .no-print {
                display: none !important;
            }
            
            .generated-dictation {
                max-height: none !important;
                page-break-inside: avoid;
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
                <!-- Session Timer -->
                <div class="session-timer no-print" id="sessionTimer">
                    <span id="timerText">Session timeout in: 15:00</span>
                </div>
                
                <!-- Privacy Settings -->
                <div class="privacy-settings no-print">
                    <h3>🔒 Privacy Settings</h3>
                    <div class="privacy-option">
                        <label>
                            <input type="checkbox" id="autoClearOnPrint" 
checked>
                            Auto-clear form after printing
                        </label>
                    </div>
                    <div class="privacy-option">
                        <label>
                            <input type="checkbox" id="autoClearOnCopy" 
checked>
                            Auto-clear form after copying to clipboard
                        </label>
                    </div>
                    <div class="privacy-option">
                        <label>
                            <input type="checkbox" id="sessionTimeout" checked>
                            Enable 15-minute inactivity timeout
                        </label>
                    </div>
                    <div class="privacy-option">
                        <label>
                            <input type="checkbox" id="addWatermark" checked>
                            Add CONFIDENTIAL watermark when printing
                        </label>
                    </div>
                </div>
                
                <!-- Procedure Selection -->
                <div id="procedureSelection">
                    <h2>Select a Procedure</h2>
                    <div id="procedureList">
                        <?php foreach ($procedures as $procedure): ?>
                        <div class="procedure-card" 
onclick="selectProcedure(<?php echo $procedure['id']; ?>, '<?php echo 
htmlspecialchars($procedure['name'], ENT_QUOTES); ?>')">
                            <div class="procedure-title"><?php echo 
htmlspecialchars($procedure['name']); ?></div>
                            <div class="procedure-description">
                                <?php echo 
htmlspecialchars(substr($procedure['template'], 0, 100)); ?>...
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Dictation Form -->
                <div id="dictationForm" style="display: none;">
                    <h2>Patient Information for: <span 
id="selectedProcedure"></span></h2>
                    <form id="patientForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Patient Name</label>
                                <input type="text" name="patient_name" 
class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label>Date of Birth</label>
                                <input type="date" name="dob" 
class="form-input" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>MRN</label>
                                <input type="text" name="mrn" 
class="form-input" required>
                            </div>
                            <div class="form-group">
                                <label>Procedure Date</label>
                                <input type="date" name="procedure_date" 
class="form-input" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Additional Notes</label>
                            <textarea name="additional_notes" 
class="form-textarea"></textarea>
                        </div>
                        
                        <div class="action-buttons">
                            <button type="button" class="btn btn-primary" 
onclick="generateDictation()">Generate Dictation</button>
                            <button type="button" class="btn btn-secondary" 
onclick="cancelForm()">Cancel</button>
                        </div>
                    </form>
                </div>
                
                <!-- Generated Dictation Output -->
                <div id="dictationOutput" style="display: none;">
                    <h2>Generated Dictation</h2>
                    <div class="generated-dictation" id="generatedText"></div>
                    <div class="action-buttons no-print">
                        <button class="btn btn-primary" 
onclick="printDictation()">🖨️ Print</button>
                        <button class="btn btn-success" 
onclick="copyToClipboard()">📋 Copy to Clipboard</button>
                        <button class="btn btn-secondary" 
onclick="clearAndReset()">🔄 New Dictation</button>
                    </div>
                    
                    <!-- Print-only elements -->
                    <div class="watermark" style="display: 
none;">CONFIDENTIAL</div>
                    <div class="confidential-header" style="display: 
none;">CONFIDENTIAL PATIENT INFORMATION</div>
                    <div class="confidential-footer" style="display: none;">
                        This document contains confidential patient 
information. Handle according to HIPAA guidelines.
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>Greater Maryland Pain Management</p>
            <p><a href="/">Back to Portal</a></p>
        </div>
    </div>
    
    <script>
        // Store procedures data
        const procedures = <?php echo json_encode($procedures); ?>;
        let selectedProcedureId = null;
        let selectedTemplate = '';
        
        // Privacy settings
        let sessionTimeoutId = null;
        let activityTimeoutId = null;
        let timeRemaining = 900; // 15 minutes in seconds
        let timerInterval = null;
        
        // Initialize privacy features
        document.addEventListener('DOMContentLoaded', function() {
            // Start session timeout if enabled
            if (document.getElementById('sessionTimeout').checked) {
                startSessionTimeout();
            }
            
            // Monitor activity
            ['mousedown', 'keypress', 'scroll', 'touchstart'].forEach(event => 
{
                document.addEventListener(event, resetActivityTimeout, true);
            });
            
            // Monitor privacy settings changes
            
document.getElementById('sessionTimeout').addEventListener('change', function() 
{
                if (this.checked) {
                    startSessionTimeout();
                } else {
                    stopSessionTimeout();
                }
            });
            
            // Clear on tab close
            window.addEventListener('beforeunload', function(e) {
                if (document.querySelector('#dictationForm').style.display !== 
'none' ||
                    document.querySelector('#dictationOutput').style.display 
!== 'none') {
                    clearFormData();
                    // Log session end
                    navigator.sendBeacon('/api/dictation/log', JSON.stringify({
                        action: 'session_end',
                        user: '<?php echo $_SERVER['PHP_AUTH_USER'] ?? 
'Anonymous'; ?>'
                    }));
                }
            });
            
            // Clear form on page visibility change (switching tabs)
            document.addEventListener('visibilitychange', function() {
                if (document.hidden && 
document.getElementById('sessionTimeout').checked) {
                    // Pause timer when tab is hidden
                    clearInterval(timerInterval);
                } else if (!document.hidden && 
document.getElementById('sessionTimeout').checked) {
                    // Resume timer when tab is visible
                    startTimer();
                }
            });
        });
        
        // Session timeout functions
        function startSessionTimeout() {
            resetActivityTimeout();
            startTimer();
            document.getElementById('sessionTimer').style.display = 'block';
        }
        
        function stopSessionTimeout() {
            clearTimeout(activityTimeoutId);
            clearInterval(timerInterval);
            document.getElementById('sessionTimer').style.display = 'none';
            timeRemaining = 900;
        }
        
        function resetActivityTimeout() {
            clearTimeout(activityTimeoutId);
            timeRemaining = 900; // Reset to 15 minutes
            updateTimerDisplay();
            
            if (document.getElementById('sessionTimeout').checked) {
                activityTimeoutId = setTimeout(function() {
                    showTimeoutWarning();
                }, 840000); // 14 minutes - warning
            }
        }
        
        function startTimer() {
            clearInterval(timerInterval);
            timerInterval = setInterval(function() {
                timeRemaining--;
                updateTimerDisplay();
                
                if (timeRemaining === 60) {
                    
document.getElementById('sessionTimer').classList.add('warning');
                }
                
                if (timeRemaining <= 0) {
                    clearAndReset();
                    alert('Session timed out due to inactivity. All patient 
data has been cleared for security.');
                    stopSessionTimeout();
                }
            }, 1000);
        }
        
        function updateTimerDisplay() {
            const minutes = Math.floor(timeRemaining / 60);
            const seconds = timeRemaining % 60;
            document.getElementById('timerText').textContent = 
                `Session timeout in: 
${minutes}:${seconds.toString().padStart(2, '0')}`;
        }
        
        function showTimeoutWarning() {
            if (confirm('Your session will timeout in 1 minute due to 
inactivity. Click OK to continue working.')) {
                resetActivityTimeout();
            }
        }
        
        // Clear all form data
        function clearFormData() {
            document.querySelectorAll('input[type="text"], input[type="date"], 
textarea').forEach(input => {
                input.value = '';
            });
            document.getElementById('generatedText').textContent = '';
        }
        
        // Enhanced clear and reset function
        function clearAndReset() {
            clearFormData();
            document.getElementById('procedureSelection').style.display = 
'block';
            document.getElementById('dictationForm').style.display = 'none';
            document.getElementById('dictationOutput').style.display = 'none';
            selectedProcedureId = null;
            selectedTemplate = '';
            resetActivityTimeout();
        }
        
        // Select procedure function
        function selectProcedure(procedureId, procedureName) {
            selectedProcedureId = procedureId;
            const procedure = procedures.find(p => p.id === procedureId);
            selectedTemplate = procedure.template;
            
            document.getElementById('procedureSelection').style.display = 
'none';
            document.getElementById('dictationForm').style.display = 'block';
            document.getElementById('selectedProcedure').textContent = 
procedureName;
            
            // Focus on first input
            document.querySelector('#dictationForm input').focus();
            
            // Reset activity timeout
            resetActivityTimeout();
            
            // Log procedure selection
            fetch('/api/dictation/log', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    action: 'select_procedure',
                    procedureId: procedureId,
                    procedureName: procedureName
                })
            });
        }
        
        // Generate dictation
        function generateDictation() {
            const formData = new 
FormData(document.getElementById('patientForm'));
            let dictation = selectedTemplate;
            
            // Replace all placeholders
            for (let [key, value] of formData.entries()) {
                const placeholder = `{{${key}}}`;
                dictation = dictation.replace(new RegExp(placeholder, 'g'), 
value || '[Not provided]');
            }
            
            // Add timestamp
            const now = new Date();
            dictation = `Generated: ${now.toLocaleString()}\n\n${dictation}`;
            
            // Display the dictation
            document.getElementById('generatedText').textContent = dictation;
            document.getElementById('dictationForm').style.display = 'none';
            document.getElementById('dictationOutput').style.display = 'block';
            
            // Reset activity timeout
            resetActivityTimeout();
            
            // Log generation
            fetch('/api/dictation/log', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    action: 'generate_dictation',
                    procedureId: selectedProcedureId
                })
            });
        }
        
        // Enhanced print function
        function printDictation() {
            // Add watermark if enabled
            const watermark = document.querySelector('.watermark');
            const header = document.querySelector('.confidential-header');
            const footer = document.querySelector('.confidential-footer');
            
            if (document.getElementById('addWatermark').checked) {
                watermark.style.display = 'block';
                header.style.display = 'block';
                footer.style.display = 'block';
            }
            
            // Trigger print
            window.print();
            
            // Hide watermark elements after print
            setTimeout(() => {
                watermark.style.display = 'none';
                header.style.display = 'none';
                footer.style.display = 'none';
                
                // Auto-clear if enabled
                if (document.getElementById('autoClearOnPrint').checked) {
                    clearAndReset();
                    alert('Patient data has been cleared for security.');
                }
            }, 100);
            
            // Log print action
            fetch('/api/dictation/log', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    action: 'print_dictation',
                    procedureId: selectedProcedureId
                })
            });
        }
        
        // Enhanced copy function
        function copyToClipboard() {
            const dictationText = 
document.getElementById('generatedText').textContent;
            
            // Add confidentiality notice to clipboard
            const confidentialText = 
document.getElementById('addWatermark').checked 
                ? `[CONFIDENTIAL PATIENT 
INFORMATION]\n\n${dictationText}\n\n[This document contains confidential 
patient information. Handle according to HIPAA guidelines.]`
                : dictationText;
            
            navigator.clipboard.writeText(confidentialText).then(() => {
                alert('Dictation copied to clipboard!');
                
                // Auto-clear if enabled
                if (document.getElementById('autoClearOnCopy').checked) {
                    clearAndReset();
                    alert('Patient data has been cleared for security.');
                }
            }).catch(err => {
                console.error('Failed to copy: ', err);
                alert('Failed to copy to clipboard. Please try again.');
            });
            
            // Log copy action
            fetch('/api/dictation/log', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    action: 'copy_dictation',
                    procedureId: selectedProcedureId
                })
            });
        }
        
        // Cancel function
        function cancelForm() {
            if (confirm('Are you sure you want to cancel? All entered data will 
be lost.')) {
                clearAndReset();
            }
        }
    </script>
</body>
</html>
PHP_EOF

# Create the public endpoint
echo "Creating public endpoint..."
cat > public-endpoints/dictation.php << 'PHP_EOF'
<?php
// Set up paths
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(dirname(__DIR__)));
}
if (!defined('APP_PATH')) {
    define('APP_PATH', ROOT_PATH . '/app');
}

// Set security headers immediately
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');

require_once APP_PATH . '/vendor/autoload.php';
require_once APP_PATH . '/src/bootstrap.php';

use App\Controllers\DictationController;
use App\Models\Dictation;

// Create instances
$dictationModel = new Dictation();
$controller = new DictationController($dictationModel);

// Handle the request
$controller->index();
PHP_EOF

# Create API endpoint for logging
echo "Creating API logging endpoint..."
mkdir -p ../public_html/api/dictation
cat > ../public_html/api/dictation/log.php << 'PHP_EOF'
<?php
// API endpoint for audit logging
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['action'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

// Log to database (simplified version - in production, use the model)
try {
    require_once __DIR__ . '/../../../app/vendor/autoload.php';
    require_once __DIR__ . '/../../../app/src/bootstrap.php';
    
    $dictationModel = new \App\Models\Dictation();
    $dictationModel->logAccess(
        $input['action'],
        $input['procedureId'] ?? null,
        $input['procedureName'] ?? null
    );
    
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Logging failed']);
}
PHP_EOF

# Create sample procedures
echo ""
echo "Creating sample procedures SQL..."
cat > ~/dictation_sample_procedures.sql << 'SQL_EOF'
-- Sample procedures for testing
INSERT INTO dictation_procedures (name, template) VALUES 
('Lumbar Epidural Steroid Injection', 'PROCEDURE: Lumbar Epidural Steroid 
Injection

PATIENT: {{patient_name}}
DOB: {{dob}}
MRN: {{mrn}}
DATE OF PROCEDURE: {{procedure_date}}

INDICATION: Low back pain with radiculopathy

PROCEDURE: After informed consent was obtained, the patient was placed in the 
prone position. The lumbar area was prepped and draped in a sterile fashion. 
Using fluoroscopic guidance, the epidural space was identified at the L4-L5 
level. A 22-gauge spinal needle was advanced using a loss of resistance 
technique. After negative aspiration, contrast was injected confirming epidural 
spread. Subsequently, 80mg of methylprednisolone and 2ml of 0.25% bupivacaine 
were injected.

The patient tolerated the procedure well without complications.

ADDITIONAL NOTES: {{additional_notes}}'),

('Cervical Facet Joint Injection', 'PROCEDURE: Cervical Facet Joint Injection

PATIENT: {{patient_name}}
DOB: {{dob}}
MRN: {{mrn}}
DATE OF PROCEDURE: {{procedure_date}}

INDICATION: Cervical facet arthropathy

PROCEDURE: After informed consent was obtained, the patient was placed in the 
prone position. The cervical area was prepped and draped in a sterile fashion. 
Using fluoroscopic guidance, the C5-C6 facet joint was identified. A 25-gauge 
needle was advanced into the joint space. After confirmation with contrast, 
0.5ml of 0.25% bupivacaine and 20mg of methylprednisolone were injected.

The patient tolerated the procedure well without complications.

ADDITIONAL NOTES: {{additional_notes}}'),

('Knee Joint Injection', 'PROCEDURE: Knee Joint Injection

PATIENT: {{patient_name}}
DOB: {{dob}}
MRN: {{mrn}}
DATE OF PROCEDURE: {{procedure_date}}

INDICATION: Knee osteoarthritis

PROCEDURE: After informed consent was obtained, the patient was placed in the 
supine position with the knee slightly flexed. The knee was prepped and draped 
in a sterile fashion. Using a superolateral approach, a 22-gauge needle was 
advanced into the joint space. After negative aspiration for blood, 40mg of 
methylprednisolone and 4ml of 0.5% bupivacaine were injected.

The patient tolerated the procedure well without complications.

ADDITIONAL NOTES: {{additional_notes}}');
SQL_EOF

echo ""
echo "=== Setup Complete! ==="
echo ""
echo "Next steps:"
echo "1. Import sample procedures:"
echo "   mysql -u your_username -p your_database < 
~/dictation_sample_procedures.sql"
echo ""
echo "2. Update index.php routing (add to the switch statement):"
echo "   case \$requestUri === '/dictation':"
echo "       require APP_PATH . '/public-endpoints/dictation.php';"
echo "       break;"
echo ""
echo "3. Test the system:"
echo "   curl -I https://gmpm.us/dictation"
echo ""
echo "Files created:"
echo "- $BASE_DIR/src/Models/Dictation.php"
echo "- $BASE_DIR/src/Controllers/DictationController.php"
echo "- $BASE_DIR/templates/views/dictation/index.php"
echo "- $BASE_DIR/public-endpoints/dictation.php"
echo "- ~/public_html/api/dictation/log.php"
echo "- ~/dictation_sample_procedures.sql"
