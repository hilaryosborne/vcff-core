<div data-trigger-code="<?php echo $this->code; ?>" class="trigger-item action-field-group">
    <div class="action-group-header">
        <h4><strong><?php echo __('What Conditions Settings?', VCFF_NS); ?></strong></h4><a href="" target="vcff_hint" class="help-lnk"><span class="dashicons dashicons-editor-help"></span> Help</a>
    </div>
    <div class="action-group-contents">
        <div class="row">
            <div class="col-sm-4">
                <p>Instructions</p>
            </div>
            <div class="col-sm-8">
                <div class="form-group">
                    <select name="event_action[triggers][everytime][submission_status]" class="select-trigger form-control">
                        <option value="submission" <?php if ($this->_Get_Submission_Status() == 'submission'): ?>selected="selected"<?php endif; ?>>When the form has submitted</option>
                        <option value="submission_success" <?php if ($this->_Get_Submission_Status() == 'submission_success'): ?>selected="selected"<?php endif; ?>>When the form has successfully submitted</option>
                        <option value="submission_failed" <?php if ($this->_Get_Submission_Status() == 'submission_failed'): ?>selected="selected"<?php endif; ?>>When the form has unsuccessfully submitted</option>
                    </select>
                    <?php if ($this->Is_Update() && isset($validation_errors['submission_status'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <div class="alert-text"><?php echo __('Please select a submission status', VCFF_NS); ?></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>