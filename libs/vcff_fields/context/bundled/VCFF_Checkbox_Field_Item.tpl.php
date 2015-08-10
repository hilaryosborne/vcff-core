<div data-vcff-field-name="<?php echo $machine_code; ?>" class="vcff-field vcff-checkbox-field <?php echo $extra_class; ?> <?php echo $css_class; ?>" <?php if ($this->Is_Hidden()): ?>style="display:none;"<?php endif; ?>>
    <?php do_action('vcff_field_pre_label',$this); ?>
    <label>
        <input name="<?php echo $machine_code; ?>" <?php echo $attributes; ?> type="checkbox" value="yes" <?php if ($is_disabled == 'yes'): ?> disabled="disabled"<?php endif; ?> class="<?php if ($this->Has_Dependents()): ?>check-change<?php endif; ?> <?php echo $field_extra_class; ?>" <?php if ($this->posted_value): ?>checked="checked"<?php endif; ?>>
        <?php echo $field_label; ?>
        <?php do_action('vcff_field_post_input',$this); ?>
    </label>
    <?php do_action('vcff_field_post_label',$this); ?>
    <div class="field-alerts" style="<?php if (!$this->Get_Alerts()): ?>display:none;<?php endif; ?>">
        <?php echo $this->Get_Alerts_HTML(); ?>
    </div>
</div>