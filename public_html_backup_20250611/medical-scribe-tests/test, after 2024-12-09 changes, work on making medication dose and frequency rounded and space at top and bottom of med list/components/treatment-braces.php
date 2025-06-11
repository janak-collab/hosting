<div class="treatment-group">
    <h3>Braces</h3>
    <div class="treatment-item">
        <label>
            <input type="checkbox" name="treatment[]" value="braces">
            Brace Treatment
        </label>
        <div class="treatment-options" style="display: none;">
            <!-- Back Brace Section -->
            <div class="brace-section">
                <h4>Back Brace:</h4>
                <div class="brace-options">
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
            <div class="brace-section">
                <h4>Knee Brace:</h4>
                <div class="brace-group">
                    <label class="checkbox-option">
                        <input type="checkbox" name="knee_brace" value="1" class="brace-trigger">
                        Knee Brace
                    </label>
                    <div class="side-options" style="display: none;">
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