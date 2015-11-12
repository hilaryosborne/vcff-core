<div class="bootstrap vcff_param_validation">
    <h3><?php echo __('Field Input Validation',VCFF_NS); ?><a href="http://vcff.theblockquote.com" target="vcff_help" class="help-link"><span class="dashicons dashicons-editor-help"></span> <?php echo __('Help',VCFF_NS); ?></a></h3>
    <?php do_action('vcff_params_conditions_before_rules',$this); ?>
    <div class="validation-settings">
        <?php if ($stored_rules && is_array($stored_rules)): ?>
        <?php foreach($stored_rules as $k => $rule_item): ?>
        <div class="row validation-item">
            <div class="col-item col-sm-6">
                <select class="item-rule vcff-nowebkit form-control">
                    <option value=""><?php echo __('No rule selected',VCFF_NS); ?></option>
                    <?php foreach($validation_logic as $k => $_rule): ?>
                        <?php if ($rule_item['rule'] == $_rule['machine_code']): ?>
                        <option <?php if ($_rule['value']): ?>data-val-hasvalue="yes"<?php endif; ?> data-val-description="<?php echo $_rule['description']; ?>" value="<?php echo $_rule['machine_code']; ?>" selected="selected"><?php echo $_rule['title']; ?></option>
                        <?php else: ?>
                        <option <?php if ($_rule['value']): ?>data-val-hasvalue="yes"<?php endif; ?> data-val-description="<?php echo $_rule['description']; ?>" value="<?php echo $_rule['machine_code']; ?>"><?php echo $_rule['title']; ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php do_action('vcff_params_conditions_rule_list',$this); ?>
                </select>
            </div>
            <div class="col-value col-sm-3">
                <input type="text" value="<?php echo $rule_item['value']; ?>" class="item-value form-control">
            </div>
            <div class="col-links col-sm-3">
                <a href="#" class="dashicons dashicons-plus-alt item-add"></a> <a href="#" class="dashicons dashicons-dismiss item-remove"></a>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <a href="" class="add-validation">+ Add new validation</a>
    <?php do_action('vcff_params_conditions_after_rules',$this); ?>
    <script id="validation_ln_tmpl" type="text/x-handlebars-template">
    <div class="row validation-item">
        <div class="col-item col-sm-6">
            <select class="item-rule form-control vcff-nowebkit">
                <option value=""><?php echo __('No rule selected',VCFF_NS); ?></option>
                <?php foreach($validation_logic as $k => $_rule): ?>
                    <option <?php if ($_rule['value']): ?>data-val-hasvalue="yes"<?php endif; ?> data-val-description="<?php echo $_rule['description']; ?>" value="<?php echo $_rule['machine_code']; ?>"><?php echo $_rule['title']; ?></option>
                <?php endforeach; ?>
                <?php do_action('vcff_params_conditions_rule_list',$this); ?>
            </select>
        </div>
        <div class="col-value col-sm-3">
            <input type="text" class="item-value form-control">
        </div>
        <div class="col-links col-sm-3">
            <a href="#" class="dashicons dashicons-plus-alt item-add"></a> <a href="#" class="dashicons dashicons-dismiss item-remove"></a>
        </div>
    </div>
    </script>
    <span class="vc_description vc_clearfix">You can configure your field to have either one or many validation rules using the options below. To add multiple or to remove rules, use the buttons to the right of each field rule.</span>
    <input name="<?php echo esc_attr($settings['param_name']); ?>" type="hidden" value="<?php echo esc_attr( $value ); ?>" class="wpb_vc_param_value wpb-hiddeninput">
</div>