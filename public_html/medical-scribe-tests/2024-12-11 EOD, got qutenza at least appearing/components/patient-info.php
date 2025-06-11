<div class="medication-card">
    <div class="medication-card-header">
        <h2>Patient Information</h2>
    </div>
    
    <section class="patient-info">
        <div id="error-container" class="error-container"></div>
        
        <div class="form-group">
            <label for="patient_name">Patient Name:</label>
            <input type="text"
                id="patient_name"
                name="patient_name"
                required
                pattern="[a-zA-Z. -]{2,50}"
                title="Please enter a valid name (2-50 characters, letters, spaces, dots and hyphens only)">
        </div>

        <div class="form-group">
            <label for="dob">Date of Birth:</label>
            <input type="date"
                id="dob"
                name="dob"
                required
                max="<?php echo date('Y-m-d'); ?>">
        </div>

        <div class="form-group">
            <label for="dos">Date of Service (DOS):</label>
            <input type="date"
                id="dos"
                name="dos"
                required
                value="<?php echo date('Y-m-d'); ?>">
        </div>
        
        <div class="form-group">
            <label for="provider_name">Provider</label>
            <input type="text"
                id="provider_validation"
                name="provider_validation"
                required
                style="display: none;"
                aria-hidden="true">

            <div class="provider-grid" role="radiogroup" aria-required="true">
                <table class="provider-table">
                    <tr>
                        <?php 
                        try {
                            $pdo = new PDO(
                                "mysql:host=localhost;dbname=jvidyart_timecard",
                                "jvidyart_janak",
                                "himabim1",
                                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                            );
                            
                            $stmt = $pdo->query("SELECT id, Provider FROM Providers ORDER BY Provider");
                            $providers = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            
                            $counter = 0;
                            
                            foreach ($providers as $row):
                                if ($counter % 4 === 0 && $counter !== 0) {
                                    echo '</tr><tr>';
                                }
                                
                                $fullValue = htmlspecialchars($row['Provider']);
                                $nameParts = explode(',', $row['Provider']);
                                $displayName = htmlspecialchars(trim($nameParts[0]));
                                ?>
                                <td>
                                    <label class="radio-option">
                                        <input type="radio"
                                            id="provider_<?php echo $row['id']; ?>"
                                            name="provider_name"
                                            value="<?php echo $fullValue; ?>"
                                            aria-label="Select provider <?php echo $fullValue; ?>">
                                        <?php echo $displayName; ?>
                                    </label>
                                </td>
                                <?php
                                $counter++;
                            endforeach;

                            // Fill remaining cells with empty td elements
                            while ($counter % 4 !== 0) {
                                echo '<td></td>';
                                $counter++;
                            }
                            
                        } catch (PDOException $e) {
                            Logger::error('Database Error: ', ['error' => $e->getMessage()]);
                            echo "<p class='error'>Unable to load provider list. Please try again later.</p>";
                        }
                        ?>
                    </tr>
                </table>
            </div>
            <div class="error-message provider-error" style="display: none;">Please select a provider</div>
        </div>
    </section>
</div>