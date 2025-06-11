<?php

use App\Services\Logger;
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to get providers from database
function getProviders($pdo) {
    try {
        $stmt = $pdo->query("SELECT id, Provider FROM Providers ORDER BY Provider");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        Logger::error('Provider Query Error: ', ['error' => $e->getMessage()]);
        return [];
    }
}
?>

<section class="patient-info">
    <div id="error-container" class="error-container"></div>
    
    <h2>Patient Information</h2>
    
    <form id="radiologyForm" class="medical-form" novalidate>
        <!-- Patient Name Field -->
        <div class="form-group" aria-invalid="false">
            <label for="patient_name">
                Patient Name:<span class="required-indicator">*</span>
            </label>
            <input type="text"
                   id="patient_name"
                   name="patient_name"
                   required
                   pattern="[A-Za-z][A-Za-z\-\. ']{1,49}"
                   title="Please enter a valid name (2-50 characters, letters, spaces, dots and hyphens only)"
                   autocomplete="off">
            <div class="error-message">Please enter a valid patient name</div>
            <div class="help-text">Enter the patient's full legal name</div>
        </div>

        <!-- Date of Birth Field -->
        <div class="form-group" aria-invalid="false">
            <label for="dob">
                Date of Birth:<span class="required-indicator">*</span>
            </label>
            <input type="date"
                   id="dob"
                   name="dob"
                   required
                   max="<?php echo date('Y-m-d'); ?>"
                   autocomplete="off">
            <div class="error-message">Please enter a valid date of birth</div>
        </div>

        <!-- Date of Service Field -->
        <div class="form-group" aria-invalid="false">
            <label for="dos">
                Date of Service (DOS):<span class="required-indicator">*</span>
            </label>
            <input type="date"
                   id="dos"
                   name="dos"
                   required
                   value="<?php echo date('Y-m-d'); ?>"
                   min="<?php echo date('Y-m-d', strtotime('-30 days')); ?>"
                   max="<?php echo date('Y-m-d', strtotime('+30 days')); ?>"
                   autocomplete="off">
            <div class="error-message">Please enter a valid date of service</div>
            <div class="help-text">Select a date within 30 days of today</div>
        </div>

        <!-- Provider Selection Field -->
        <div class="form-group" aria-invalid="false">
            <label for="provider_name">
                Provider:<span class="required-indicator">*</span>
            </label>
            <input type="hidden"
                   id="provider_validation"
                   name="provider_validation"
                   required>
            
            <div class="provider-grid" role="radiogroup" aria-label="Select provider" aria-required="true">
                <table class="provider-table">
                    <?php
                    // Get database connection using the constant from config.php
                    $pdo = getDbConnection();
                    if ($pdo) {
                        $providers = getProviders($pdo);
                        
                        if (!empty($providers)) {
                            echo '<tr>';
                            $counter = 0;
                            
                            foreach ($providers as $provider) {
                                if ($counter % 4 === 0 && $counter !== 0) {
                                    echo '</tr><tr>';
                                }
                                
                                $fullName = htmlspecialchars($provider['Provider']);
                                $lastName = htmlspecialchars(trim(explode(',', $provider['Provider'])[0]));
                                ?>
                                <td>
                                    <label class="radio-option">
                                        <input type="radio"
                                               id="provider_<?php echo $provider['id']; ?>"
                                               name="provider_name"
                                               value="<?php echo $fullName; ?>"
                                               aria-label="Select provider <?php echo $fullName; ?>">
                                        <label for="provider_<?php echo $provider['id']; ?>">
                                            <?php echo $lastName; ?>
                                        </label>
                                    </label>
                                </td>
                                <?php
                                $counter++;
                            }
                            
                            // Fill remaining cells in the last row
                            while ($counter % 4 !== 0) {
                                echo '<td></td>';
                                $counter++;
                            }
                            
                            echo '</tr>';
                        } else {
                            echo '<tr><td colspan="4" class="error-message">No providers available</td></tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4" class="error-message">Unable to load provider list</td></tr>';
                    }
                    ?>
                </table>
            </div>
            <div class="error-message">Please select a provider</div>
        </div>
    </form>
</section>