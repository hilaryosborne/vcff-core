<div class="form-group vcff-meta-checkbox <?php echo $extra_class; ?>">
    <div class="col-sm-9 col-sm-offset-3">
        <?php do_action('vcff_settings_field_pre_input',$this); ?>
        <label>
            <input name="<?php echo $machine_code; ?>" type="checkbox" value="<?php echo $checkbox_value; ?>" <?php if ($this->value): ?>checked="checked"<?php endif; ?> class="vcff-meta-field <?php echo $extra_class; ?>" <?php if ($required): ?>required<?php endif; ?>>
            <?php do_action('vcff_settings_field_pre_label',$this); ?>
            <?php echo apply_filters('vcff_settings_field_label',$label,$this); ?> <?php if ($this->Is_Required()): ?><span class="required">*</span><?php endif; ?>
            <?php do_action('vcff_settings_field_post_label',$this); ?>
        </label>
        <div class="field-alerts" style="<?php if (!$this->Get_Alerts()): ?>display:none;<?php endif; ?>">
            <?php echo $this->Get_Alerts_HTML(); ?>
        </div>
        <?php if ($hints_html): ?><p class="hints"><?php echo $hints_html; ?></p><?php endif; ?>
        <?php do_action('vcff_settings_field_post_input',$this); ?>
    </div>
</div>