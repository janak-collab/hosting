<div class="med-card">
    <div class="med-card-header">
        <h3>Physical Therapy</h3>
        <div class="clear-selection">
            <label class="radio-option">
                <input type="radio" name="physical_therapy" value="" checked>
                No Physical Therapy
            </label>
        </div>
    </div>
    <div class="med-card-content">
        <div class="medication-item">
            <label class="radio-option">
                <input type="checkbox" name="treatment[]" value="physical_therapy">
                Physical Therapy Evaluation and Treatment
            </label>
            <div class="treatment-options">
                <!-- Spine Conditions -->
                <div class="dosage-options">
                    <div class="dose-group">
                        <span class="option-label">Spine Conditions:</span>
                        <div class="radio-options">
                            <label class="radio-option">
                                <input type="checkbox" name="pt_conditions[]" value="neck_pain">
                                Neck Pain
                            </label>
                            <label class="radio-option">
                                <input type="checkbox" name="pt_conditions[]" value="cervical_radiculitis">
                                Cervical Radiculitis
                            </label>
                            <label class="radio-option">
                                <input type="checkbox" name="pt_conditions[]" value="back_pain">
                                Back Pain
                            </label>
                            <label class="radio-option">
                                <input type="checkbox" name="pt_conditions[]" value="lumbar_radiculitis">
                                Lumbar Radiculitis
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Hip Section -->
                <div class="dosage-options">
                    <div class="dose-group">
                        <span class="option-label">Hip:</span>
                        <div class="condition-group">
                            <div class="radio-options">
                                <label class="radio-option">
                                    <input type="checkbox" name="pt_conditions[]" value="hip_pain" class="condition-trigger">
                                    Hip Pain
                                </label>
                            </div>
                            <div class="side-options">
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
                </div>

                <!-- Knee Section -->
                <div class="dosage-options">
                    <div class="dose-group">
                        <span class="option-label">Knee:</span>
                        <div class="condition-group">
                            <div class="radio-options">
                                <label class="radio-option">
                                    <input type="checkbox" name="pt_conditions[]" value="knee_pain" class="condition-trigger">
                                    Knee Pain
                                </label>
                            </div>
                            <div class="side-options">
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
                </div>

                <!-- Shoulder Section -->
                <div class="dosage-options">
                    <div class="dose-group">
                        <span class="option-label">Shoulder:</span>
                        <div class="condition-group">
                            <div class="radio-options">
                                <label class="radio-option">
                                    <input type="checkbox" name="pt_conditions[]" value="shoulder_pain" class="condition-trigger">
                                    Shoulder Pain
                                </label>
                            </div>
                            <div class="side-options">
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
    </div>
</div>