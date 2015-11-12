<div class="bootstrap vcff_param_url_vars">
    <?php do_action('vcff_param_url_vars_before_rules',$this); ?>
    <div class="url-settings">
        <?php if ($stored_vars && is_array($stored_vars)): ?>
        <?php foreach($stored_vars as $k => $var_item): ?>
        <div class="row url-item">
            <div class="col-rule col-sm-4">
                <select class="fld-rule form-control vcff-nowebkit">
                    <option value="">No method selected</option>
                    <option value="POST"<?php if ($var_item['rule'] == 'POST'): ?> selected="selected"<?php endif; ?>>POST</option>
                    <option value="GET"<?php if ($var_item['rule'] == 'GET'): ?> selected="selected"<?php endif; ?>>GET</option>
                    <option value="REQUEST"<?php if ($var_item['rule'] == 'REQUEST'): ?> selected="selected"<?php endif; ?>>REQUEST</option>
                    <?php do_action('vcff_param_url_vars_rule_types',$this); ?>
                </select>
            </div>
            <div class="col-value col-sm-6">
                <input type="text" value="<?php echo $var_item['value']; ?>" class="fld-value form-control">
            </div>
            <div class="col-links col-sm-2">
                <a href="#" class="dashicons dashicons-plus-alt ln-add"></a> <a href="#" class="dashicons dashicons-dismiss ln-remove"></a>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?php do_action('vcff_param_url_vars_after_rules',$this); ?>
    <script id="url_var_ln_tmpl" type="text/x-handlebars-template">
    <div class="row url-item">
        <div class="col-rule col-sm-4">
            <select class="fld-rule form-control vcff-nowebkit">
                <option value="">No method selected</option>
                <option value="POST">POST</option>
                <option value="GET">GET</option>
                <option value="REQUEST">REQUEST</option>
                <?php do_action('vcff_param_url_vars_rule_types',$this); ?>
        </select>
        </div>
        <div class="col-value col-sm-6">
            <input type="text" value="" class="fld-value form-control">
        </div>
        <div class="col-links col-sm-2">
            <a href="#" class="dashicons dashicons-plus-alt ln-add"></a> <a href="#" class="dashicons dashicons-dismiss ln-remove"></a>
        </div>
    </div>
    </script>
    <input name="<?php echo esc_attr($settings['param_name']); ?>" type="hidden" value="<?php echo esc_attr( $value ); ?>" class="wpb_vc_param_value wpb-hiddeninput">
</div>