<div data-trigger-code="<?php echo $this->code; ?>" class="trigger-item trigger-conditional">
                            
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
                    <div class="conditional-rules">
                        <?php if ($current_rules && is_array($current_rules)): ?>
                        <?php $i=0; foreach ($current_rules as $machine_code => $rule_data): ?>
                        <div class="row form-inline conditional-rule">
                            <div class="col-sm-4">
                                <select name="event_action[triggers][conditional][rules][rule_<?php echo $i; ?>][against]" class="item-against form-control">
                                    <option value="">Select form field</option>
                                    <?php if ($rule_data['field_list'] && is_array($rule_data['field_list'])): ?>
                                    <?php foreach($rule_data['field_list'] as $machine_code => $field_details): ?>
                                    <option value="<?php echo $field_details['machine_code'] ?>" <?php if ($field_details['selected']): ?>selected="selected"<?php endif; ?>><?php echo $field_details['field_label']; ?></option>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <select name="event_action[triggers][conditional][rules][rule_<?php echo $i; ?>][check]" class="item-check form-control">
                                <?php if ($rule_data['field_conditions'] && is_array($rule_data['field_conditions'])): ?>
                                    <?php foreach($rule_data['field_conditions'] as $condition_value => $condition_data): ?>
                                    <option value="<?php echo $condition_data['rule_name']; ?>" <?php if ($condition_data['selected']): ?>selected="selected"<?php endif; ?>><?php echo $condition_data['rule_label']; ?></option>
                                    <?php endforeach; ?>
                                    <?php endif; ?>    
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <input name="event_action[triggers][conditional][rules][rule_<?php echo $i; ?>][value]" type="text" value="<?php echo $rule_data['field_value']; ?>" class="item-value form-control">
                            </div>
                            <div class="col-sm-2">
                                <a href="#" class="dashicons dashicons-plus-alt item-add"></a> <a href="#" class="dashicons dashicons-dismiss item-remove"></a>
                            </div>
                        </div>
                        <?php $i++; ?>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

</div>

<script id="trigger_item_conditional_rule" type="text/x-handlebars-template">    
    <div class="row conditional-rule">
        <div class="col-sm-4">
            <select name="event_action[triggers][conditional][rules][rule_{{i}}][against]" class="item-against form-control">
                <option value="">Select form field</option>
                <?php if ($current_fields && is_array($current_fields)): ?>
                <?php foreach($current_fields as $machine_code => $field_details): ?>
                <option value="<?php echo $field_details['machine_code'] ?>"><?php echo $field_details['field_label']; ?></option>
                <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="col-sm-3">
            <select name="event_action[triggers][conditional][rules][rule_{{i}}][check]" class="item-check form-control">

            </select>
        </div>
        <div class="col-sm-3">
            <input name="event_action[triggers][conditional][rules][rule_{{i}}][value]" type="text" class="item-value form-control">
        </div>
        <div class="col-sm-2">
            <a href="#" class="dashicons dashicons-plus-alt item-add"></a> <a href="#" class="dashicons dashicons-dismiss item-remove"></a>
        </div>
    </div>
</script>
<script>var vcff_trigger_conditions_fields = <?php echo $this->_Get_Field_JSON(); ?>;</script>

