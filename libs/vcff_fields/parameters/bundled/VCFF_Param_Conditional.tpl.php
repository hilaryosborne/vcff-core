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
                <label class="col-sm-6">
                    <select class="conditional-result form-control vcff-nowebkit">
                        <option value="show" <?php if (is_array($_decoded) && $_decoded['result'] == 'show'): ?>selected="selected"<?php endif; ?>>Show this element if...</option>
                        <option value="hide" <?php if (is_array($_decoded) && $_decoded['result'] == 'hide'): ?>selected="selected"<?php endif; ?>>Hide this element if...</option>
                    </select>
                </label>
                <label class="col-sm-6">
                    <select class="conditional-match form-control vcff-nowebkit">
                        <option value="all" <?php if (is_array($_decoded) && $_decoded['match'] == 'all'): ?>selected="selected"<?php endif; ?>>...all of the rules match</option>
                        <option value="any" <?php if (is_array($_decoded) && $_decoded['match'] == 'any'): ?>selected="selected"<?php endif; ?>>...any of the rules match</option>
                    </select>
                </label>
            </div>
        </div>
        <?php do_action('vcff_params_conditions_after_config',$this); ?>
    </div>
    
    <div class="container-rules">
        <?php do_action('vcff_params_conditions_before_rules',$this); ?>
        <p><strong>Using the following conditions</strong></p>
        <div class="conditional-items">
            
        </div>
        <a href="" class="add-condition">+ Add new condition</a>
        <script id="conditional_ln_tmpl" type="text/x-handlebars-template">
        <div class="row conditions-item">
            <div class="col-element col-sm-4">
            <select class="item-element form-control vcff-nowebkit">
                <option value="">Select form element</option>
                <?php do_action('vcff_params_conditions_field_list',$this); ?>
            </select>
            </div>
            <div class="col-condition col-sm-3">
                <select style="display:none;" class="item-rules form-control vcff-nowebkit">
                
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
            var vcff_conditions_els = <?php echo json_encode($this->_Els()); ?>;
        </script>
        <?php do_action('vcff_params_conditions_after_rules',$this); ?>
    </div>
    <input name="<?php echo esc_attr($settings['param_name']); ?>" type="hidden" value="<?php echo esc_attr( $value ); ?>" class="wpb_vc_param_value wpb-hiddeninput">
</div>