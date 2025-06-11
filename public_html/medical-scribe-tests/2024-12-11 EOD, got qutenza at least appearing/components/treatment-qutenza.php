<div class="medication-card">
    <div class="medication-card-header">
        <h2>Treatments</h2>
    </div>
    
    <section class="medications-section">
        <div class="category-header">
            <h3>Qutenza</h3>
            <div class="medication-item">
                <label class="radio-option">
                    <input type="radio" name="treatment[]" value="" checked>
                    <span>No Qutenza</span>
                </label>
            </div>
        </div>

        <div class="medications-list">
            <div class="medication-item">
                <label class="radio-option">
                    <input type="radio" name="treatment[]" value="Qutenza" data-medication="qutenza">
                    <span>Qutenza</span>
                </label>

                <div class="medication-details" id="details-qutenza">
                    <div class="option-group">
                        <span class="option-label">Side:</span>
                        <div class="options-wrapper">
                            <label class="radio-option">
                                <input type="radio" name="qutenza_side" value="right" disabled>
                                <span>Right</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="qutenza_side" value="left" disabled>
                                <span>Left</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="qutenza_side" value="bilateral" disabled>
                                <span>Bilateral</span>
                            </label>
                        </div>
                    </div>

                    <div class="option-group">
                        <span class="option-label">Treatment Area:</span>
                        <div class="options-wrapper">
                            <label class="radio-option">
                                <input type="radio" name="qutenza_area" value="top" disabled>
                                <span>Top of Feet</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="qutenza_area" value="bottom" disabled>
                                <span>Bottom of Feet</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="qutenza_area" value="both" disabled>
                                <span>Top and Bottom of Feet</span>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="qutenza_area" value="flank" disabled>
                                <span>Flank</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>