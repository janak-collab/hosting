<div class="med-card">
    <div class="med-card-header">
        <h3>Braces</h3>
        <div class="clear-selection">
            <label class="radio-option">
                <input type="radio" name="braces" value="" checked>
                No Braces
            </label>
        </div>
    </div>
    <div class="med-card-content">
        <div class="medication-item">
            <label class="radio-option">
                <input type="checkbox" name="treatment[]" value="braces">
                Brace Treatment
            </label>
            <div class="dosage-options">
                <!-- Back Brace Section -->
                <div class="dose-group">
                    <span class="option-label">Back Brace:</span>
                    <div class="radio-options">
                        <label class="radio-option">
                            <input type="radio" name="back_brace_type" value="standard">
                            Standard Back Brace
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="back_brace_type" value="with_extenders">
                            Back Brace with Extenders
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="back_brace_type" value="none" checked>
                            No Back Brace
                        </label>
                    </div>
                </div>

                <!-- Knee Brace Section -->
                <div class="dose-group">
                    <span class="option-label">Knee Brace:</span>
                    <div class="condition-group">
                        <div class="radio-options">
                            <label class="radio-option">
                                <input type="checkbox" name="knee_brace" value="1" class="condition-trigger">
                                Knee Brace
                            </label>
                        </div>
                        <div class="side-options">
                            <div class="radio-options">
                                <label class="radio-option">
                                    <input type="radio" name="knee_brace_side" value="right">
                                    Right
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="knee_brace_side" value="left">
                                    Left
                                </label>
                                <label class="radio-option">
                                    <input type="radio" name="knee_brace_side" value="bilateral">
                                    Bilateral
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>