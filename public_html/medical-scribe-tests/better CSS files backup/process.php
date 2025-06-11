<?php

use App\Services\Logger;
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include required files
$rootPath = $_SERVER['DOCUMENT_ROOT'] . '/private_area/medical-scribe';
//require_once $rootPath . '/includes/config.php';
//require_once $rootPath . '/includes/functions.php';
//require_once $rootPath . '/includes/validation.php';
//require_once $rootPath . '/includes/security.php';

// Initialize database connection
$pdo = getDbConnection();
if (!$pdo) {
    $_SESSION['form_errors'] = ['System error occurred. Please try again later.'];
    header('Location: index.php');
    exit;
}

// Initialize variables
$errors = [];
$data = $_SERVER["REQUEST_METHOD"] == "POST" ? $_POST : $_GET;
$hasData = !empty($data);

// Check request size
if ($_SERVER['CONTENT_LENGTH'] > MAX_FORM_SIZE) {
    $_SESSION['form_errors'] = ['Form submission too large'];
    header('Location: index.php');
    exit;
}

// Validate submission method
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $_SESSION['form_errors'] = ['Invalid request method'];
    header('Location: index.php');
    exit;
}

// Validate CSRF token
if (!validateCsrfToken()) {
    $_SESSION['form_errors'] = ['Security token validation failed'];
    header('Location: index.php');
    exit;
}

try {
    // Begin transaction
    $pdo->beginTransaction();

    // Store form data in session in case of validation failure
    $_SESSION['form_data'] = $_POST;

    // Validate required fields
    $requiredFields = [
        'patient_name' => 'Patient name',
        'dob' => 'Date of birth',
        'dos' => 'Date of service',
        'provider_name' => 'Provider'
    ];

    foreach ($requiredFields as $field => $label) {
        if (empty($_POST[$field])) {
            $errors[] = "$label is required";
        }
    }

    // Validate patient name format
    if (!empty($_POST['patient_name'])) {
        if (!preg_match(VALIDATION_RULES['patient_name']['pattern'], $_POST['patient_name'])) {
            $errors[] = VALIDATION_RULES['patient_name']['message'];
        }
    }

    // Validate dates
    if (!empty($_POST['dob'])) {
        if (!validateDate($_POST['dob'])) {
            $errors[] = "Invalid date of birth";
        }
    }

    if (!empty($_POST['dos'])) {
        if (!validateDate($_POST['dos'])) {
            $errors[] = "Invalid date of service";
        }
        
        $today = new DateTime();
        $dos = new DateTime($_POST['dos']);
        $daysBeforeLimit = (new DateTime())->modify('-' . VALIDATION_RULES['dos']['days_before'] . ' days');
        $daysAfterLimit = (new DateTime())->modify('+' . VALIDATION_RULES['dos']['days_after'] . ' days');
        
        if ($dos > $daysAfterLimit || $dos < $daysBeforeLimit) {
            $errors[] = VALIDATION_RULES['dos']['message'];
        }
    }

    // Validate provider
    if (!empty($_POST['provider_name'])) {
        if (!in_array($_POST['provider_name'], ALLOWED_PROVIDERS)) {
            $errors[] = "Invalid provider selected";
        }
    }

    // Validate medications if any are selected
    if (hasAnyMedication($_POST)) {
        $medicationErrors = validateMedications($_POST);
        $errors = array_merge($errors, $medicationErrors);
    }

    // If there are validation errors, redirect back to form
    if (!empty($errors)) {
        throw new Exception(implode(', ', $errors));
    }

    // Process form data if no errors
    $orderId = insertOrder($pdo, $data);
    if (!$orderId) {
        throw new Exception('Failed to create order');
    }

    // Commit transaction
    $pdo->commit();

    // Clear session data
    unset($_SESSION['form_data'], $_SESSION['form_errors']);

} catch (Exception $e) {
    // Rollback transaction
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    // Log error
    Logger::error('Order Processing Error: ', ['error' => $e->getMessage()]);
    
    // Store error and redirect
    $_SESSION['form_errors'] = [$e->getMessage()];
    header('Location: index.php');
    exit;
}

// Define imaging fields for output
$imaging_fields = [
    'shoulder_xray', 'shoulder_mri', 
    'hip_xray', 'hip_mri',
    'SIJ_xray', 'SIJ_mri', 
    'knee_xray', 'knee_mri',
    'cervical_spine', 'thoracic_spine', 'lumbar_spine'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Medical Orders Summary</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Medical Orders Summary</h1>
        </header>

        <main>
            <a href="index.php" class="submit-btn back-to-form">← Back to Form</a>

            <?php if ($hasData): ?>
                <div class="results-section medical-form">
                    <!-- Patient Information -->
                    <section class="summary-section">
                        <h2>Patient Information</h2>
                        <div class="treatment-details patient-info-details">
                            <strong>Name:</strong>
                            <div><?php echo htmlspecialchars($data['patient_name'] ?? ''); ?></div>
                            
                            <strong>DOB:</strong>
                            <div><?php echo htmlspecialchars(formatDate($data['dob'] ?? '')); ?></div>
                            
                            <strong>DOS:</strong>
                            <div><?php echo htmlspecialchars(formatDate($data['dos'] ?? '')); ?></div>
                            
                            <strong>Provider:</strong>
                            <div><?php echo htmlspecialchars(formatProviderName($data['provider_name'] ?? '')); ?></div>
                        </div>
                    </section>

                    <?php if (hasAnyMedication($data)): ?>
                        <!-- Medications Section -->
                        <section class="summary-section">
                            <h2>Prescribed Medications</h2>
                            <?php foreach (MEDICATION_CATEGORIES_INFO as $categoryKey => $categoryInfo): 
                                $fieldName = $categoryInfo['form_field'];
                                if (!empty($data[$fieldName])):
                                    $medicationName = $data[$fieldName];
                                    $details = getMedicationFullDetails($data, $medicationName);
                                    if ($details):
                            ?>
                                <div class="medication-section">
                                    <h3><?php echo htmlspecialchars($categoryInfo['display_name']); ?></h3>
                                    <div class="treatment-details">
                                        <p class="medication-name">
                                            <strong><?php echo htmlspecialchars($details['name']); ?></strong>
                                        </p>
                                        
                                        <?php if ($medicationName === 'Gabapentin titration'): ?>
                                            <p class="dosage-details">
                                                Duration: <?php echo htmlspecialchars($details['duration'] ?? ''); ?>
                                            </p>
                                        <?php else: ?>
                                            <p class="dosage-details">
                                                <?php if (!empty($details['dose']) && !empty($details['frequency'])): ?>
                                                    <?php echo htmlspecialchars($details['dose']); ?> 
                                                    <?php echo htmlspecialchars($details['frequency']); ?>
                                                <?php endif; ?>
                                            </p>
                                        <?php endif; ?>

                                        <?php if (!empty($details['instructions'])): ?>
                                            <div class="medication-instructions">
                                                <h4>Instructions:</h4>
                                                <p><?php echo htmlspecialchars($details['instructions']); ?></p>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($details['warnings'])): ?>
                                            <div class="medication-warnings">
                                                <h4>Warnings:</h4>
                                                <p><?php echo htmlspecialchars($details['warnings']); ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php 
                                    endif;
                                endif; 
                            endforeach; 
                            ?>
                        </section>
                    <?php endif; ?>

                    <!-- Imaging Section -->
                    <?php 
                    $hasImaging = false;
                    foreach ($imaging_fields as $field) {
                        if (!empty($data[$field])) {
                            $hasImaging = true;
                            break;
                        }
                    }
                    if ($hasImaging): 
                    ?>
                    <section class="summary-section">
                        <h2>Imaging Orders</h2>
                        <div class="treatment-details">
                            <div class="imaging-container">
                                <?php
                                // Process body part imaging
                                $bodyParts = [
                                    'shoulder' => 'Shoulder',
                                    'hip' => 'Hip',
                                    'SIJ' => 'SIJ',
                                    'knee' => 'Knee'
                                ];
                                
                                foreach ($bodyParts as $part => $displayName) {
                                    $xrayField = "{$part}_xray";
                                    $mriField = "{$part}_mri";
                                    
                                    if (!empty($data[$xrayField]) || !empty($data[$mriField])) {
                                        echo "<div class='imaging-item'>";
                                        echo "<strong>$displayName:</strong>";
                                        if (!empty($data[$xrayField])) {
                                            echo "<div>" . htmlspecialchars($data[$xrayField]) . "</div>";
                                        }
                                        if (!empty($data[$mriField])) {
                                            echo "<div>" . htmlspecialchars($data[$mriField]) . "</div>";
                                        }
                                        echo "</div>";
                                    }
                                }
                                
                                // Process spine imaging
                                $spineTypes = ['cervical', 'thoracic', 'lumbar'];
                                foreach ($spineTypes as $type) {
                                    $field = "{$type}_spine";
                                    if (!empty($data[$field])) {
                                        echo "<div class='imaging-item'>";
                                        echo "<strong>" . ucfirst($type) . " Spine:</strong>";
                                        echo "<div>" . htmlspecialchars($data[$field]) . "</div>";
                                        echo "</div>";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </section>
                    <?php endif; ?>

                    <!-- Display success message -->
                    <div class="success-message">
                        Order successfully created. Order ID: <?php echo htmlspecialchars($orderId); ?>
                    </div>

                </div>
            <?php else: ?>
                <div class="error-message">No order data submitted.</div>
            <?php endif; ?>
        </main>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> Medical Scribe. All rights reserved.</p>
        </footer>
    </div>
</body>
</html>