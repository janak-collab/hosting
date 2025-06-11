<div class="treatment-group">
    <h3>Physical Therapy</h3>
    <div class="treatment-item">
        <label>
            <input type="checkbox" name="treatment[]" value="physical_therapy">
            Physical Therapy Evaluation and Treatment
        </label>
        <div class="treatment-options" style="display: none;">
            <!-- Spine Conditions -->
            <div class="condition-section">
                <h4>Spine Conditions:</h4>
                <div class="condition-options">
                    <label class="checkbox-option">
                        <input type="checkbox" name="pt_conditions[]" value="neck_pain">
                        Neck Pain
                    </label>
                    <label class="checkbox-option">
                        <input type="checkbox" name="pt_conditions[]" value="cervical_radiculitis">
                        Cervical Radiculitis
                    </label>
                    <label class="checkbox-option">
                        <input type="checkbox" name="pt_conditions[]" value="back_pain">
                        Back Pain
                    </label>
                    <label class="checkbox-option">
                        <input type="checkbox" name="pt_conditions[]" value="lumbar_radiculitis">
                        Lumbar Radiculitis
                    </label>
                </div>
            </div>

            <!-- Hip Section -->
            <div class="condition-section">
                <h4>Hip:</h4>
                <div class="condition-group">
                    <label class="checkbox-option">
                        <input type="checkbox" name="pt_conditions[]" value="hip_pain" class="condition-trigger">
                        Hip Pain
                    </label>
                    <div class="side-options" style="display: none;">
                        <div class="radio-options">
                            <label class="radio-option">
                                <input type="radio" name="hip_side" value="right">
                                Right
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="hip_side" value="left">
                                Left
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="hip_side" value="bilateral">
                                Bilateral
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Knee Section -->
            <div class="condition-section">
                <h4>Knee:</h4>
                <div class="condition-group">
                    <label class="checkbox-option">
                        <input type="checkbox" name="pt_conditions[]" value="knee_pain" class="condition-trigger">
                        Knee Pain
                    </label>
                    <div class="side-options" style="display: none;">
                        <div class="radio-options">
                            <label class="radio-option">
                                <input type="radio" name="knee_side" value="right">
                                Right
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="knee_side" value="left">
                                Left
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="knee_side" value="bilateral">
                                Bilateral
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shoulder Section -->
            <div class="condition-section">
                <h4>Shoulder:</h4>
                <div class="condition-group">
                    <label class="checkbox-option">
                        <input type="checkbox" name="pt_conditions[]" value="shoulder_pain" class="condition-trigger">
                        Shoulder Pain
                    </label>
                    <div class="side-options" style="display: none;">
                        <div class="radio-options">
                            <label class="radio-option">
                                <input type="radio" name="shoulder_side" value="right">
                                Right
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="shoulder_side" value="left">
                                Left
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="shoulder_side" value="bilateral">
                                Bilateral
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>