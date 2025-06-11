<section class="qutenza-options">
    <h2>Qutenza</h2>

    <div class="treatment-controls">
        <button type="button" id="toggleQutenza" class="btn btn-primary">Add Qutenza</button>
    </div>

    <div class="medication-card" id="qutenzaContent">
        <div class="medication-card-header">
            <h3>Qutenza Treatment</h3>
            <div class="clear-selection">
                <label class="radio-option">
                    <input type="radio" name="qutenza_area" value="" checked>
                    <span class="radio-label">No Qutenza Treatment</span>
                </label>
            </div>
        </div>

        <div class="medication-card-content">
            <div class="treatment-options-container">
                <!-- Top of Feet -->
                <div class="medication-item">
                    <label class="radio-option">
                        <input type="radio" name="qutenza_area" value="top">
                        <span class="radio-label">Top of Feet</span>
                    </label>
                    
                    <div class="dosage-options" style="display: none;">
                        <div class="side-group">
                            <span class="option-label">Side:</span>
                            <div class="radio-group">
                                <label class="radio-pill">
                                    <input type="radio" name="qutenza_side_top" value="right" disabled>
                                    <span class="radio-pill-label">Right</span>
                                </label>
                                <label class="radio-pill">
                                    <input type="radio" name="qutenza_side_top" value="left" disabled>
                                    <span class="radio-pill-label">Left</span>
                                </label>
                                <label class="radio-pill">
                                    <input type="radio" name="qutenza_side_top" value="bilateral" disabled>
                                    <span class="radio-pill-label">Bilateral</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bottom of Feet -->
                <div class="medication-item">
                    <label class="radio-option">
                        <input type="radio" name="qutenza_area" value="bottom">
                        <span class="radio-label">Bottom of Feet</span>
                    </label>
                    
                    <div class="dosage-options" style="display: none;">
                        <div class="side-group">
                            <span class="option-label">Side:</span>
                            <div class="radio-group">
                                <label class="radio-pill">
                                    <input type="radio" name="qutenza_side_bottom" value="right" disabled>
                                    <span class="radio-pill-label">Right</span>
                                </label>
                                <label class="radio-pill">
                                    <input type="radio" name="qutenza_side_bottom" value="left" disabled>
                                    <span class="radio-pill-label">Left</span>
                                </label>
                                <label class="radio-pill">
                                    <input type="radio" name="qutenza_side_bottom" value="bilateral" disabled>
                                    <span class="radio-pill-label">Bilateral</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top and Bottom of Feet -->
                <div class="medication-item">
                    <label class="radio-option">
                        <input type="radio" name="qutenza_area" value="both">
                        <span class="radio-label">Top and Bottom of Feet</span>
                    </label>
                    
                    <div class="dosage-options" style="display: none;">
                        <div class="side-group">
                            <span class="option-label">Side:</span>
                            <div class="radio-group">
                                <label class="radio-pill">
                                    <input type="radio" name="qutenza_side_both" value="right" disabled>
                                    <span class="radio-pill-label">Right</span>
                                </label>
                                <label class="radio-pill">
                                    <input type="radio" name="qutenza_side_both" value="left" disabled>
                                    <span class="radio-pill-label">Left</span>
                                </label>
                                <label class="radio-pill">
                                    <input type="radio" name="qutenza_side_both" value="bilateral" disabled>
                                    <span class="radio-pill-label">Bilateral</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Flank -->
                <div class="medication-item">
                    <label class="radio-option">
                        <input type="radio" name="qutenza_area" value="flank">
                        <span class="radio-label">Flank</span>
                    </label>
                    
                    <div class="dosage-options" style="display: none;">
                        <div class="side-group">
                            <span class="option-label">Side:</span>
                            <div class="radio-group">
                                <label class="radio-pill">
                                    <input type="radio" name="qutenza_side_flank" value="right" disabled>
                                    <span class="radio-pill-label">Right</span>
                                </label>
                                <label class="radio-pill">
                                    <input type="radio" name="qutenza_side_flank" value="left" disabled>
                                    <span class="radio-pill-label">Left</span>
                                </label>
                                <label class="radio-pill">
                                    <input type="radio" name="qutenza_side_flank" value="bilateral" disabled>
                                    <span class="radio-pill-label">Bilateral</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>