<section class="medications">
    <h2>Medications</h2>

    <!-- Anti-inflammatories Card -->
    <div class="med-card">
        <div class="med-card-header">
            <h3>Anti-inflammatories</h3>
            <div class="clear-selection">
                <label class="radio-option">
                    <input type="radio" name="NSAID" value="" checked>
                    No Anti-inflammatory
                </label>
            </div>
        </div>
        <div class="med-card-content">
            <?php foreach (ALLOWED_MEDICATIONS['nsaids'] as $nsaid): ?>
                <div class="medication-item">
                    <label class="radio-option">
                        <input type="radio" name="NSAID" value="<?php echo htmlspecialchars($nsaid); ?>">
                        <?php echo htmlspecialchars($nsaid); ?>
                    </label>
                    
                    <?php if ($nsaid === 'nabumetone'): ?>
                        <div class="dosage-options">
                            <div class="dose-group">
                                <span class="option-label">Dose:</span>
                                <label class="radio-option">
                                    <input type="radio" name="nabumetone_dose" value="250mg">
                                    250mg
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="nabumetone_dose" value="500mg">
                                    500mg
                                </label>
                            </div>
                            
                            <div class="frequency-group">
                                <span class="option-label">Frequency:</span>
                                <label class="radio-option">
                                    <input type="radio" name="nabumetone_frequency" value="qday">
                                    Once daily
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="nabumetone_frequency" value="bid">
                                    Twice daily
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($nsaid === 'celecoxib/Celebrex'): ?>
                        <div class="dosage-options">
                            <div class="dose-group">
                                <span class="option-label">Dose:</span>
                                <label class="radio-option">
                                    <input type="radio" name="celebrex_dose" value="100mg">
                                    100mg
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="celebrex_dose" value="200mg">
                                    200mg
                                </label>
                            </div>
                            
                            <div class="frequency-group">
                                <span class="option-label">Frequency:</span>
                                <label class="radio-option">
                                    <input type="radio" name="celebrex_frequency" value="qday">
                                    Once daily
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($nsaid === 'meloxicam'): ?>
                        <div class="dosage-options">
                            <div class="dose-group">
                                <span class="option-label">Dose:</span>
                                <label class="radio-option">
                                    <input type="radio" name="meloxicam_dose" value="7.5mg">
                                    7.5mg
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="meloxicam_dose" value="15mg">
                                    15mg
                                </label>
                            </div>
                            
                            <div class="frequency-group">
                                <span class="option-label">Frequency:</span>
                                <label class="radio-option">
                                    <input type="radio" name="meloxicam_frequency" value="qday">
                                    Once daily
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Muscle Relaxers Card -->
    <div class="med-card">
        <div class="med-card-header">
            <h3>Muscle Relaxers</h3>
            <div class="clear-selection">
                <label class="radio-option">
                    <input type="radio" name="mrelaxer" value="" checked>
                    No Muscle Relaxer
                </label>
            </div>
        </div>
        <div class="med-card-content">
            <?php foreach (ALLOWED_MEDICATIONS['muscle_relaxers'] as $relaxer): ?>
                <div class="medication-item">
                    <label class="radio-option">
                        <input type="radio" name="mrelaxer" value="<?php echo htmlspecialchars($relaxer); ?>">
                        <?php echo htmlspecialchars($relaxer); ?>
                    </label>
                    
                    <?php if ($relaxer === 'cyclobenzaprine/Flexeril'): ?>
                        <div class="dosage-options">
                            <div class="dose-group">
                                <span class="option-label">Dose:</span>
                                <label class="radio-option">
                                    <input type="radio" name="flexeril_dose" value="5mg">
                                    5mg
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="flexeril_dose" value="10mg">
                                    10mg
                                </label>
                            </div>
                            
                            <div class="frequency-group">
                                <span class="option-label">Frequency:</span>
                                <label class="radio-option">
                                    <input type="radio" name="flexeril_frequency" value="qday">
                                    Once daily
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="flexeril_frequency" value="bid">
                                    Twice daily
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($relaxer === 'metaxalone/Skelaxin'): ?>
                        <div class="dosage-options">
                            <div class="dose-group">
                                <span class="option-label">Dose:</span>
                                <label class="radio-option">
                                    <input type="radio" name="metaxalone_dose" value="400mg">
                                    400mg
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="metaxalone_dose" value="800mg">
                                    800mg
                                </label>
                            </div>
                            
                            <div class="frequency-group">
                                <span class="option-label">Frequency:</span>
                                <label class="radio-option">
                                    <input type="radio" name="metaxalone_frequency" value="qday">
                                    Once daily
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="metaxalone_frequency" value="bid">
                                    Twice daily
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($relaxer === 'methocarbamol/Robaxin'): ?>
                        <div class="dosage-options">
                            <div class="dose-group">
                                <span class="option-label">Dose:</span>
                                <label class="radio-option">
                                    <input type="radio" name="methocarbamol_dose" value="500mg">
                                    500mg
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="methocarbamol_dose" value="750mg">
                                    750mg
                                </label>
                            </div>
                            
                            <div class="frequency-group">
                                <span class="option-label">Frequency:</span>
                                <label class="radio-option">
                                    <input type="radio" name="methocarbamol_frequency" value="qday">
                                    Once daily
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="methocarbamol_frequency" value="bid">
                                    Twice daily
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($relaxer === 'baclofen'): ?>
                        <div class="dosage-options">
                            <div class="dose-group">
                                <span class="option-label">Dose:</span>
                                <label class="radio-option">
                                    <input type="radio" name="baclofen_dose" value="5mg">
                                    5mg
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="baclofen_dose" value="10mg">
                                    10mg
                                </label>
                            </div>
                            
                            <div class="frequency-group">
                                <span class="option-label">Frequency:</span>
                                <label class="radio-option">
                                    <input type="radio" name="baclofen_frequency" value="qday">
                                    Once daily
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="baclofen_frequency" value="bid">
                                    Twice daily
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="baclofen_frequency" value="tid">
                                    Three times daily
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Nerve Agents Card -->
    <div class="med-card">
        <div class="med-card-header">
            <h3>Nerve Agents</h3>
            <div class="clear-selection">
                <label class="radio-option">
                    <input type="radio" name="nerve_agent" value="" checked>
                    No Nerve Agent
                </label>
            </div>
        </div>
        <div class="med-card-content">
            <!-- Cymbalta -->
            <div class="medication-item">
                <label class="radio-option">
                    <input type="radio" name="nerve_agent" value="duloxetine/Cymbalta">
                    duloxetine/Cymbalta
                </label>
                <div class="dosage-options">
                    <div class="dose-group">
                        <span class="option-label">Dose:</span>
                        <label class="radio-option">
                            <input type="radio" name="cymbalta_dose" value="20mg">
                            20mg
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="cymbalta_dose" value="30mg">
                            30mg
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="cymbalta_dose" value="40mg">
                            40mg
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="cymbalta_dose" value="60mg">
                            60mg
                        </label>
                    </div>
                    
                    <div class="frequency-group">
                        <span class="option-label">Frequency:</span>
                        <label class="radio-option">
                            <input type="radio" name="cymbalta_frequency" value="qday">
                            Once daily
                        </label>
                    </div>
                </div>
            </div>

        <!-- Lyrica -->
        <div class="medication-item">
            <label class="radio-option">
                <input type="radio" name="nerve_agent" value="pregabalin/Lyrica">
                pregabalin/Lyrica
            </label>
            <div class="dosage-options">
                <div class="dose-group">
                    <span class="option-label">Dose:</span>
                    <label class="radio-option">
                        <input type="radio" name="lyrica_dose" value="25mg">
                        25mg
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="lyrica_dose" value="50mg">
                        50mg
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="lyrica_dose" value="75mg">
                        75mg
                    </label>
                </div>
                
                <div class="frequency-group">
                    <span class="option-label">Frequency:</span>
                    <label class="radio-option">
                        <input type="radio" name="lyrica_frequency" value="qday">
                        Once daily
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="lyrica_frequency" value="bid">
                        Twice daily
                    </label>
                </div>
            </div>
        </div>

        <!-- Gabapentin -->
        <div class="medication-item">
            <label class="radio-option">
                <input type="radio" name="nerve_agent" value="Gabapentin">
                Gabapentin
            </label>
            <div class="dosage-options">
                <div class="dose-group">
                    <span class="option-label">Dose:</span>
                    <label class="radio-option">
                        <input type="radio" name="gabapentin_dose" value="100mg">
                        100mg
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="gabapentin_dose" value="300mg">
                        300mg
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="gabapentin_dose" value="600mg">
                        600mg
                    </label>
                </div>
                
                <div class="frequency-group">
                    <span class="option-label">Frequency:</span>
                    <label class="radio-option">
                        <input type="radio" name="gabapentin_frequency" value="qhs">
                        At bedtime
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="gabapentin_frequency" value="bid">
                        Twice daily
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="gabapentin_frequency" value="tid">
                        Three times daily
                    </label>
                </div>
            </div>
        </div>

        <!-- Gabapentin Titration -->
        <div class="medication-item">
            <label class="radio-option">
                <input type="radio" name="nerve_agent" value="Gabapentin titration">
                Gabapentin Titration
            </label>
            <div class="dosage-options">
                <div class="dose-group">
                    <span class="option-label">Duration:</span>
                    <label class="radio-option">
                        <input type="radio" name="gabapentin_titration" value="1 week">
                        1 week
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="gabapentin_titration" value="2 weeks">
                        2 weeks
                    </label>
                    <label class="radio-option">
                        <input type="radio" name="gabapentin_titration" value="4 weeks">
                        4 weeks
                    </label>
                </div>
            </div>
        </div>
    </div>










</section>