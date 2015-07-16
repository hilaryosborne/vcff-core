<div class="bootstrap vcff_param_filters">
    <h3><?php echo __('Field Input Filters', VCFF_NS); ?><a href="http://vcff.theblockquote.com" target="vcff_help" class="help-link"><span class="dashicons dashicons-editor-help"></span> <?php echo __('Help',VCFF_NS); ?></a></h3>
    <?php do_action('vcff_params_filters_before_rules',$this); ?>
    <div class="filter-settings">
        <?php if ($stored_rules && is_array($stored_rules)): ?>
        <?php foreach($stored_rules as $k => $rule_item): ?>
        <div class="row filter-item">
            <div class="col-rule col-sm-10">
                <select class="ln-rule form-control vcff-nowebkit">
                    <option value="">No filter selected</option>
                    <?php $filters = $this->Get_Filters();  ?>
                    <?php foreach($filters as $rule_code => $rule_data): ?>
                        <?php if (!in_array($rule_code, $settings['filter_rules'])) { continue; } ?>
                        <?php if ($rule_item['rule'] == $rule_code): ?>
                        <option value="<?php echo $rule_code; ?>" selected="selected"><?php echo $rule_data['label']; ?></option>
                        <?php else: ?>
                        <option value="<?php echo $rule_code; ?>"><?php echo $rule_data['label']; ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php do_action('vcff_params_filters_rule_list',$this); ?>
                </select>
            </div>
            <div class="col-links col-sm-2">
                <a href="#" class="dashicons dashicons-plus-alt ln-add"></a> <a href="#" class="dashicons dashicons-dismiss ln-remove"></a>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?php do_action('vcff_params_filters_after_rules',$this); ?>
    <span class="vc_description vc_clearfix">Field filtering allows you to use a set of predefined filters to either sanitise or convert data submitted via the form into a safer and/or a desired format.</span>
    <script id="filter_ln_tmpl" type="text/x-handlebars-template">
    <div class="row filter-item">
        <div class="col-rule col-sm-10">
            <select class="ln-rule form-control vcff-nowebkit">
                <option value="">No filter selected</option>
                <?php $filters = $this->Get_Filters(); ?>
                <?php foreach($filters as $rule_code => $rule_data): ?>
                    <?php if (!in_array($rule_code, $settings['filter_rules'])) { continue; } ?>
                    <option data-filter-description="" value="<?php echo $rule_code; ?>"><?php echo $rule_data['label']; ?></option>
                <?php endforeach; ?>
                <?php do_action('vcff_params_filters_rule_list',$this); ?>
            </select>
        </div>
        <div class="col-links col-sm-2">
            <a href="#" class="dashicons dashicons-plus-alt ln-add"></a> <a href="#" class="dashicons dashicons-dismiss ln-remove"></a>
        </div>
    </div>
    </script>
    <input name="<?php echo esc_attr($settings['param_name']); ?>" type="hidden" value="<?php echo esc_attr( $value ); ?>" class="wpb_vc_param_value wpb-hiddeninput">
</div>