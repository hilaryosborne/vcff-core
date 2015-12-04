<div data-trigger-code="<?php echo $this->type; ?>" class="trigger-item trigger-conditional">
                            
<div class="action-field-group">
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
                    <select name="event_action[triggers][conditional][submission_status]" class="select-trigger form-control">
                        <option value="validation_fail" <?php if ($this->_Get_Submission_Status() == 'validation_fail'): ?>selected="selected"<?php endif; ?>>When the form has passed validation</option>
                        <option value="validation_success" <?php if ($this->_Get_Submission_Status() == 'validation_success'): ?>selected="selected"<?php endif; ?>>When the form has failed validation</option>
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

        <div class="row">
            <div class="col-sm-4">
                <p>Instructions</p>
            </div>
            <div class="col-sm-8">
                <div class="form-group">
                    <select name="event_action[triggers][conditional][criteria]" class="conditions-use form-control">
                        <option value="all" <?php if ($this->_Get_Criteria() == 'all'): ?>selected="selected"<?php endif; ?>>All of the conditions match</option>
                        <option value="any" <?php if ($this->_Get_Criteria() == 'any'): ?>selected="selected"<?php endif; ?>>Any of the conditions match</option>
                    </select>
                    <?php if ($this->Is_Update() && isset($validation_errors['criteria'])): ?>
                    <div class="alert alert-danger" role="alert">
                        <div class="alert-text"><?php echo __('Please select a criteria', VCFF_NS); ?></div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <div class="conditional-items">

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

</div>

<script id="trigger_item_conditional_rule" type="text/x-handlebars-template">    
<div data-item-i="{{i}}" class="row conditions-item">
    <div class="col-element col-sm-4">
        <select name="event_action[triggers][conditional][rules][rule_{{i}}][machine_code]" class="item-element form-control vcff-nowebkit">
            <option value="">Select form element</option>
            <?php do_action('vcff_params_conditions_field_list',$this); ?>
        </select>
    </div>
    <div class="col-condition col-sm-3">
        <select name="event_action[triggers][conditional][rules][rule_{{i}}][code]" style="display:none;" class="item-rules form-control vcff-nowebkit">

        </select>
    </div>
    <div class="col-value col-sm-3">

    </div>
    <div class="col-links col-sm-2">
        <a href="#" class="dashicons dashicons-plus-alt item-add"></a> <a href="#" class="dashicons dashicons-dismiss item-remove"></a>
    </div>
</div>
</script>
<script>
    var vcff_trigger_conditions_fields = <?php echo json_encode($this->_Els()); ?>;
    var vcff_trigger_conditions_val = <?php echo is_array($this->value) ? json_encode($this->value) : json_encode(array()); ?>;
</script>

