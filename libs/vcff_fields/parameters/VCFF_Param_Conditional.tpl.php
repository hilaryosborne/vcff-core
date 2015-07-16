<div class="bootstrap vcff_param_conditions">
    <h3>Field Conditions<a href="" class="help-link"><span class="dashicons dashicons-editor-help"></span> <?php echo __('Help',VCFF_NS); ?></a></h3>
    <?php do_action('vcff_params_conditions_before_description',$this); ?>
    <span class="vc_description vc_clearfix">Conditional rules allow you to show or hide certain parts of your form depending on the values of form fields. An example would be to have a company field which is shown if a the person filling out a form were to tick a “I represent a company” checkbox. Conditional rules can apply to both form fields and form pages.</span>
    <?php do_action('vcff_params_conditions_after_description',$this); ?>
    
    <div class="container-config">
        <?php do_action('vcff_params_conditions_before_config',$this); ?>
        <p><strong>I would like to...</strong></p>
        <div class="conditional-settings">
            <div class="row">
                <?php $decoded_value = json_decode(base64_decode($this->value),true); ?>
                <label class="col-sm-6">
                    <select class="conditional-display form-control vcff-nowebkit">
                        <option value="show" <?php if (is_array($decoded_value) && $decoded_value['visibility'] == 'show'): ?>selected="selected"<?php endif; ?>>Show this field if...</option>
                        <option value="hide" <?php if (is_array($decoded_value) && $decoded_value['visibility'] == 'hide'): ?>selected="selected"<?php endif; ?>>Hide this field if...</option>
                    </select>
                </label>
                <label class="col-sm-6">
                    <select class="conditional-distribution form-control vcff-nowebkit">
                        <option value="all" <?php if (is_array($decoded_value) && $decoded_value['target'] == 'all'): ?>selected="selected"<?php endif; ?>>...all of the rules match</option>
                        <option value="any" <?php if (is_array($decoded_value) && $decoded_value['target'] == 'any'): ?>selected="selected"<?php endif; ?>>...any of the rules match</option>
                    </select>
                </label>
            </div>
        </div>
        <?php do_action('vcff_params_conditions_after_config',$this); ?>
    </div>
    
    <div class="container-rules">
        <?php do_action('vcff_params_conditions_before_rules',$this); ?>
        <p><strong>Using the following conditions</strong></p>
        <div class="conditional-lines">
            <?php if ($current_rules && is_array($current_rules)): ?>
            <?php foreach ($current_rules as $machine_code => $rule_data): ?>
            <div class="row conditional-ln">
                <div class="col-field col-sm-4">
                    <select class="ln-fieldname form-control vcff-nowebkit">
                        <option value="">Select form field</option>
                        <?php if ($rule_data['field_list'] && is_array($rule_data['field_list'])): ?>
                        <?php foreach($rule_data['field_list'] as $machine_code => $field_details): ?>
                        <option value="<?php echo $field_details['machine_code'] ?>" <?php if ($field_details['selected']): ?>selected="selected"<?php endif; ?>><?php echo $field_details['field_label']; ?></option>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        <?php do_action('vcff_params_conditions_field_list',$this); ?>
                    </select>
                </div>
                <div class="col-operator col-sm-3">
                    <select class="ln-fieldcheck form-control vcff-nowebkit">
                        <?php if ($rule_data['field_conditions'] && is_array($rule_data['field_conditions'])): ?>
                        <?php foreach($rule_data['field_conditions'] as $condition_value => $condition_data): ?>
                        <option value="<?php echo $condition_data['rule_name']; ?>" <?php if ($condition_data['selected']): ?>selected="selected"<?php endif; ?>><?php echo $condition_data['rule_label']; ?></option>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-value col-sm-3">
                    <input type="text" value="<?php echo $rule_data['field_value']; ?>" class="ln-value">
                </div>
                <div class="col-links col-sm-2">
                    <a href="#" class="dashicons dashicons-plus-alt ln-add"></a> <a href="#" class="dashicons dashicons-dismiss ln-remove"></a>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <script id="conditional_ln_tmpl" type="text/x-handlebars-template">
        <div class="row conditional-ln">
            <div class="col-field col-sm-4">
            <select class="ln-fieldname form-control vcff-nowebkit">
                <option value="">Select form field</option>
                <?php if ($current_fields && is_array($current_fields)): ?>
                <?php foreach($current_fields as $machine_code => $field_details): ?>
                <option value="<?php echo $field_details['machine_code'] ?>"><?php echo $field_details['field_label']; ?></option>
                <?php endforeach; ?>
                <?php endif; ?>
                <?php do_action('vcff_params_conditions_field_list',$this); ?>
            </select>
            </div>
            <div class="col-operator col-sm-3">
                <select class="ln-fieldcheck form-control vcff-nowebkit">
                </select>
            </div>
            <div class="col-value col-sm-3">
                <input type="text" class="form-control ln-value">
            </div>
            <div class="col-links col-sm-2">
                <a href="#" class="dashicons dashicons-plus-alt ln-add"></a> <a href="#" class="dashicons dashicons-dismiss ln-remove"></a>
            </div>
        </div>
        </script>
        <script>var vcff_conditions_fields = <?php echo $this->_Get_Field_JSON(); ?>;</script>
        <?php do_action('vcff_params_conditions_after_rules',$this); ?>
    </div>
    <input name="<?php echo esc_attr($settings['param_name']); ?>" type="hidden" value="<?php echo esc_attr( $value ); ?>" class="wpb_vc_param_value wpb-hiddeninput">
</div>