<div class="medication-card">
    <div class="medication-card-header">
        <h2>Medications</h2>
    </div>
    
    <section class="medications-section">
        <div id="medications-error-container"></div>
        
        <?php foreach (MEDICATION_CATEGORIES_INFO as $category_key => $category_info): ?>
            <div class="category-header">
                <h3><?php echo htmlspecialchars($category_info['display_name']); ?></h3>
                <div class="medication-item">
                    <label class="radio-option">
                        <input type="radio" 
                               name="<?php echo htmlspecialchars($category_info['form_field']); ?>" 
                               value="" 
                               checked>
                        <span>No <?php echo htmlspecialchars($category_info['display_name']); ?></span>
                    </label>
                </div>
            </div>

            <div class="medications-list">
                <?php foreach (MEDICATION_DETAILS as $med_key => $medication): ?>
                    <?php if ($medication['category'] === $category_key): ?>
                        <div class="medication-item">
                            <label class="radio-option">
                                <input type="radio" 
                                       name="<?php echo htmlspecialchars($category_info['form_field']); ?>" 
                                       value="<?php echo htmlspecialchars($med_key); ?>"
                                       data-medication="<?php echo htmlspecialchars($med_key); ?>">
                                <span><?php echo htmlspecialchars($medication['display_name']); ?></span>
                            </label>
                            
                            <div class="medication-details" id="details-<?php echo htmlspecialchars($med_key); ?>">
                                <?php if (isset($medication['doses'])): ?>
                                    <div class="option-group">
                                        <span class="option-label">Dose:</span>
                                        <div class="options-wrapper">
                                            <?php foreach ($medication['doses'] as $dose): ?>
                                                <label class="radio-option">
                                                    <input type="radio" 
                                                           name="<?php echo htmlspecialchars($med_key); ?>_dose" 
                                                           value="<?php echo htmlspecialchars($dose); ?>"
                                                           disabled>
                                                    <span><?php echo htmlspecialchars($dose); ?></span>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                        
                                <?php if (isset($medication['frequencies'])): ?>
                                    <div class="option-group">
                                        <span class="option-label">Frequency:</span>
                                        <div class="options-wrapper">
                                            <?php foreach ($medication['frequencies'] as $frequency): ?>
                                                <label class="radio-option">
                                                    <input type="radio" 
                                                           name="<?php echo htmlspecialchars($med_key); ?>_frequency" 
                                                           value="<?php echo htmlspecialchars($frequency); ?>"
                                                           disabled>
                                                    <span><?php echo htmlspecialchars($frequency); ?></span>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const medicationItems = document.querySelectorAll('.medication-item');
    
    medicationItems.forEach(item => {
        const mainRadio = item.querySelector('input[type="radio"]');
        const details = item.querySelector('.medication-details');
        
        if (mainRadio && details) {
            mainRadio.addEventListener('change', function() {
                const categoryInputs = document.querySelectorAll(`input[name="${this.name}"]`);
                
                categoryInputs.forEach(input => {
                    const currentItem = input.closest('.medication-item');
                    const currentDetails = currentItem?.querySelector('.medication-details');
                    const detailInputs = currentDetails?.querySelectorAll('input[type="radio"]');
                    
                    if (currentDetails && detailInputs) {
                        if (input.checked && input.value !== '') {
                            currentDetails.style.display = 'block';
                            detailInputs.forEach(input => input.disabled = false);
                        } else {
                            currentDetails.style.display = 'none';
                            detailInputs.forEach(input => {
                                input.disabled = true;
                                input.checked = false;
                            });
                        }
                    }
                });
            });
        }
    });
});
</script>