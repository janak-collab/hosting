<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Handle both POST and GET requests
$data = $_SERVER["REQUEST_METHOD"] == "POST" ? $_POST : $_GET;
$hasData = !empty($data);

// Define imaging fields
$imaging_fields = [
    'shoulder_xray', 'shoulder_mri', 
    'hip_xray', 'hip_mri',
    'SIJ_xray', 'SIJ_mri', 
    'knee_xray', 'knee_mri',
    'cervical_spine', 'thoracic_spine', 'lumbar_spine'
];

// Helper function to format provider name
function formatProviderName($fullProviderString) {
    $parts = explode('NPI', $fullProviderString);
    return trim($parts[0]);
}
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
            <a href="index.php" class="submit-btn" style="text-decoration: none; margin-bottom: var(--spacing-lg);">
                ← Back to Form
            </a>

            <?php if ($hasData): ?>
                <div class="results-section medical-form">
                    <!-- Patient Information -->
                    <section class="summary-section">
                        <h2>Patient Information</h2>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($data['patient_name'] ?? ''); ?></p>
                        <p><strong>DOB:</strong> <?php echo htmlspecialchars(formatDate($data['dob'] ?? '')); ?></p>
                        <p><strong>DOS:</strong> <?php echo htmlspecialchars(formatDate($data['dos'] ?? '')); ?></p>
                        <p><strong>Provider:</strong> <?php echo htmlspecialchars(formatProviderName($data['provider_name'] ?? '')); ?></p>
                    </section>

                    <!-- Medications -->
                    <?php 
                    $hasAnyMedication = false;
                    if (
                        (isset($data['NSAID']) && $data['NSAID'] !== '') || 
                        (isset($data['mrelaxer']) && $data['mrelaxer'] !== '') || 
                        (isset($data['nerve_agent']) && $data['nerve_agent'] !== '')
                    ): 
                        $hasAnyMedication = true;
                    endif;
                    ?>

                    <?php if ($hasAnyMedication): ?>
                        <section class="summary-section">
                            <h2>Prescribed Medications</h2>
                            
                            <!-- Anti-inflammatory Medications -->
        <!-- Anti-inflammatory Medications -->
        <?php if (isset($data['NSAID']) && $data['NSAID'] !== ''): ?>
            <div class="treatment-item">
                <p><strong>Anti-inflammatory Medications</strong></p>
                <div class="treatment-details">
                    <p><strong><?php echo htmlspecialchars($data['NSAID']); ?></strong></p>
                    <?php
                    $nsaid_parts = explode('/', $data['NSAID']);
                    $nsaid_brand = !empty($nsaid_parts[1]) ? trim($nsaid_parts[1]) : $nsaid_parts[0];
                    $nsaid_name = strtolower(preg_replace('/[\(\)]/', '', $nsaid_brand));
                    
                    $nsaid_map = MEDICATION_NAME_MAPPINGS['nsaid'];
                    $field_name = $nsaid_map[$nsaid_name] ?? $nsaid_name;
                    $medication_details = MEDICATION_DETAILS[$field_name] ?? null;
                    ?>
                    
                    <ul>
                        <li>
                            <strong>Dosage Information:</strong>
                            <ul>
                                <?php if (!empty($data[$field_name . '_dose'])): ?>
                                    <li>Dose: <?php echo htmlspecialchars($data[$field_name . '_dose']); ?></li>
                                <?php endif; ?>
                                
                                <?php if (!empty($data[$field_name . '_frequency'])): ?>
                                    <li>Frequency: <?php echo FREQUENCY_LABELS[$data[$field_name . '_frequency']] ?? htmlspecialchars($data[$field_name . '_frequency']); ?></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                        
                        <?php if ($medication_details): ?>
                            <?php if (!empty($medication_details['warnings'])): ?>
                                <li>
                                    <strong>Important Warnings:</strong>
                                    <ul>
                                        <li class="important"><?php echo htmlspecialchars($medication_details['warnings']); ?></li>
                                    </ul>
                                </li>
                            <?php endif; ?>
                            
                            <?php if (!empty($medication_details['instructions'])): ?>
                                <li>
                                    <strong>Instructions:</strong>
                                    <ul>
                                        <li><?php echo htmlspecialchars($medication_details['instructions']); ?></li>
                                    </ul>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

                            <!-- Muscle Relaxers -->
        <?php if (isset($data['mrelaxer']) && $data['mrelaxer'] !== ''): ?>
            <div class="treatment-item">
                <p><strong>Muscle Relaxers</strong></p>
                <div class="treatment-details">
                    <p><strong><?php echo htmlspecialchars($data['mrelaxer']); ?></strong></p>
                    <?php 
                    $relaxer_parts = explode('/', $data['mrelaxer']);
                    $relaxer_brand = !empty($relaxer_parts[1]) ? trim($relaxer_parts[1]) : $relaxer_parts[0];
                    $relaxer_name = strtolower(preg_replace('/[\(\)]/', '', $relaxer_brand));
                    
                    $relaxer_map = MEDICATION_NAME_MAPPINGS['muscle_relaxer'];
                    $field_name = $relaxer_map[$relaxer_name] ?? $relaxer_name;
                    $medication_details = MEDICATION_DETAILS[$field_name] ?? null;
                    ?>
                    
                    <ul>
                        <li>
                            <strong>Dosage Information:</strong>
                            <ul>
                                <?php if (!empty($data[$field_name . '_dose'])): ?>
                                    <li>Dose: <?php echo htmlspecialchars($data[$field_name . '_dose']); ?></li>
                                <?php endif; ?>
                                
                                <?php if (!empty($data[$field_name . '_frequency'])): ?>
                                    <li>Frequency: <?php echo FREQUENCY_LABELS[$data[$field_name . '_frequency']] ?? htmlspecialchars($data[$field_name . '_frequency']); ?></li>
                                <?php endif; ?>
                            </ul>
                        </li>
                        
                        <?php if ($medication_details): ?>
                            <?php if (!empty($medication_details['warnings'])): ?>
                                <li>
                                    <strong>Important Warnings:</strong>
                                    <ul>
                                        <li class="important"><?php echo htmlspecialchars($medication_details['warnings']); ?></li>
                                    </ul>
                                </li>
                            <?php endif; ?>
                            
                            <?php if (!empty($medication_details['instructions'])): ?>
                                <li>
                                    <strong>Instructions:</strong>
                                    <ul>
                                        <li><?php echo htmlspecialchars($medication_details['instructions']); ?></li>
                                    </ul>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

                            <!-- Nerve Agents -->
        <!-- Nerve Agents -->
        <?php if (isset($data['nerve_agent']) && $data['nerve_agent'] !== ''): ?>
            <div class="treatment-item">
                <p><strong>Nerve Agents</strong></p>
                <div class="treatment-details">
                    <p><strong><?php echo htmlspecialchars($data['nerve_agent']); ?></strong></p>
                    <?php 
                    if ($data['nerve_agent'] === 'Gabapentin titration') {
                        if (!empty($data['gabapentin_titration'])): ?>
                            <ul>
                                <li>
                                    <strong>Treatment Duration:</strong>
                                    <ul>
                                        <li>Duration: <?php echo htmlspecialchars($data['gabapentin_titration']); ?></li>
                                    </ul>
                                </li>
                                <?php 
                                $medication_details = MEDICATION_DETAILS['Gabapentin titration'];
                                if (!empty($medication_details['warnings'])): ?>
                                    <li>
                                        <strong>Important Warnings:</strong>
                                        <ul>
                                            <li class="important"><?php echo htmlspecialchars($medication_details['warnings']); ?></li>
                                        </ul>
                                    </li>
                                <?php endif;
                                if (!empty($medication_details['instructions'])): ?>
                                    <li>
                                        <strong>Instructions:</strong>
                                        <ul>
                                            <li><?php echo htmlspecialchars($medication_details['instructions']); ?></li>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        <?php endif;
                    } else {
                        $nerve_map = MEDICATION_NAME_MAPPINGS['nerve_agent'];
                        $nerve_parts = explode('/', $data['nerve_agent']);
                        $nerve_brand = !empty($nerve_parts[1]) ? trim($nerve_parts[1]) : $nerve_parts[0];
                        $nerve_name = strtolower(preg_replace('/\(|\)/', '', $nerve_brand));
                        $field_name = $nerve_map[$nerve_name] ?? $nerve_name;
                        $medication_details = MEDICATION_DETAILS[$field_name] ?? null;
                        ?>
                        <ul>
                            <li>
                                <strong>Dosage Information:</strong>
                                <ul>
                                    <?php if (!empty($data[$field_name . '_dose'])): ?>
                                        <li>Dose: <?php echo htmlspecialchars($data[$field_name . '_dose']); ?></li>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($data[$field_name . '_frequency'])): ?>
                                        <li>Frequency: 
                                            <?php 
                                            $freq = $data[$field_name . '_frequency'];
                                            switch($freq) {
                                                case 'qday':
                                                    echo 'Once daily';
                                                    break;
                                                case 'bid':
                                                    echo 'Twice daily';
                                                    break;
                                                case 'tid':
                                                    echo 'Three times daily';
                                                    break;
                                                case 'qhs':
                                                    echo 'At bedtime';
                                                    break;
                                                default:
                                                    echo htmlspecialchars($freq);
                                            }
                                            ?>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                            
                            <?php if ($medication_details): ?>
                                <?php if (!empty($medication_details['warnings'])): ?>
                                    <li>
                                        <strong>Important Warnings:</strong>
                                        <ul>
                                            <li class="important"><?php echo htmlspecialchars($medication_details['warnings']); ?></li>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if (!empty($medication_details['instructions'])): ?>
                                    <li>
                                        <strong>Instructions:</strong>
                                        <ul>
                                            <li><?php echo htmlspecialchars($medication_details['instructions']); ?></li>
                                        </ul>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>
                        </ul>
                    <?php } ?>
                </div>
            </div>
        <?php endif; ?>
                        </section>
                    <?php endif; ?>

                    <!-- Treatments -->
                    <?php if (!empty($data['treatment'])): ?>
                        <section class="summary-section">
                            <h2>Ordered Treatments</h2>
                            <?php foreach ($data['treatment'] as $treatment): ?>
                                <div class="treatment-item">
                                    <p><strong><?php echo htmlspecialchars($treatment); ?></strong></p>
<?php if ($treatment === 'Qutenza'): ?>
    <div class="treatment-details">
        <p><strong>Your Qutenza Treatment Instructions:</strong></p>
        <ul>
            <li>
                <strong>Immediately:</strong>
                <ul>
                    <li class="important">Please let us know immediately if you are allergic to chili peppers</li>
                </ul>
            </li>
            <li>
                <strong>Day of your treatment:</strong>
                <ul>
                    <li>Please wear loose fitting clothing the day of your treatment</li>
                    <?php 
                    if (!empty($data['qutenza_area'])) {
                        $area = strtolower($data['qutenza_area']);
                        // Check if area includes feet/foot and specifically check for top, bottom, or both
                        if ($area === 'top' || $area === 'bottom' || $area === 'both') {
                            if (!empty($data['qutenza_side'])) {
                                if ($data['qutenza_side'] === 'bilateral') {
                                    echo '<li>Please wear loose fitted pant legs</li>
                                        <li>Please wear easily removeable footwear (socks and shoes)</li>
                                        <li>Please wash both feet thoroughly before the treatment</li>';
                                } else {
                                    echo '<li>Please wash your ' . htmlspecialchars($data['qutenza_side']) . ' foot thoroughly before the treatment</li>';
                                }
                            }
                        }
                    }
                    ?>
                </ul>
            </li>
            <li>
                <strong>Treatment Protocol:</strong>
                <ul>
                    <li>Series of 3-4 treatments every 3 months</li>
                    <li>45 minute treatment sessions</li>
                    <li>No topical medications on treatment day</li>
                </ul>
            </li>
        </ul>
        <?php if (!empty($data['qutenza_side'])): ?>
            <p><strong>Side:</strong> <?php echo ucfirst(htmlspecialchars($data['qutenza_side'])); ?></p>
        <?php endif; ?>
        <?php if (!empty($data['qutenza_area'])): ?>
            <p><strong>Treatment Area:</strong> 
                <?php 
                $area = $data['qutenza_area'];
                switch($area) {
                    case 'top':
                        echo 'Top of Feet';
                        break;
                    case 'bottom':
                        echo 'Bottom of Feet';
                        break;
                    case 'both':
                        echo 'Top and Bottom of Feet';
                        break;
                    case 'other':
                        echo htmlspecialchars($data['qutenza_other_specify'] ?? '');
                        break;
                    default:
                        echo htmlspecialchars($area);
                }
                ?>
            </p>
        <?php endif; ?>
    </div>
<?php endif; ?>                                </div>
                            <?php endforeach; ?>
                        </section>
                    <?php endif; ?>

                    <!-- Diagnostic Tests -->
                    <?php 
                    $xrays = array();
                    $mris = array();
                    foreach ($imaging_fields as $field) {
                        if (!empty($data[$field])) {
                            if (strpos($field, 'xray') !== false) {
                                $xrays[] = $data[$field];
                            } else if (strpos($field, 'mri') !== false || strpos($field, 'spine') !== false) {
                                $mris[] = $data[$field];
                            }
                        }
                    }

                    if (!empty($xrays) || !empty($mris)): ?>
                        <section class="summary-section">
                            <h2>Diagnostic Tests</h2>
                            <?php if (!empty($xrays)): ?>
                                <div class="treatment-item">
                                    <p><strong>X-Ray Orders</strong></p>
                                    <div class="treatment-details">
                                        <ul>
                                            <?php foreach ($xrays as $xray): ?>
                                                <li><?php 
                                                    $text = strtolower($xray);
                                                    $text = str_replace(['xr ', 'XR '], '', $text);
                                                    $parts = explode(' ', $text);
                                                    
                                                    if (count($parts) >= 2) {
                                                        // Special case for SIJ
                                                        $location = (strtolower($parts[0]) === 'sij') ? 'SIJ' : ucfirst($parts[0]);
                                                        $side = strtolower(end($parts));
                                                        echo htmlspecialchars("$location, $side");
                                                    } else {
                                                        echo htmlspecialchars(ucfirst($text));
                                                    }
                                                ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($mris)): ?>
                                <div class="treatment-item">
                                    <p><strong>MRI Orders</strong></p>
                                    <div class="treatment-details">
                                        <ul>
                                            <?php foreach ($mris as $mri): ?>
                                                <li><?php 
                                                    $text = strtolower($mri);
                                                    $text = str_replace(['mri ', 'MRI '], '', $text);
                                                    
                                                    if (strpos($text, 'spine') !== false) {
                                                        $parts = explode(' ', $text);
                                                        $spineType = ucfirst($parts[0]);
                                                        echo htmlspecialchars("$spineType Spine");
                                                    } else {
                                                        $parts = explode(' ', $text);
                                                        // Special case for SIJ
                                                        $location = (strtolower($parts[0]) === 'sij') ? 'SIJ' : ucfirst($parts[0]);
                                                        $side = strtolower(end($parts));
                                                        echo htmlspecialchars("$location, $side");
                                                    }
                                                ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </section>
                    <?php endif; ?>
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