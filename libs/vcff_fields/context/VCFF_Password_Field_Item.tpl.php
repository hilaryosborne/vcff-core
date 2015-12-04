<div data-vcff-field-name="<?php echo $machine_code; ?>" class="vcff-field vcff-password-field <?php echo $extra_class; ?> <?php echo $css_class; ?>" <?php if ($this->Is_Hidden()): ?>style="display:none;"<?php endif; ?>>
    <?php do_action('vcff_field_pre_label',$this); ?>
    <label><?php echo $field_label; ?><?php if ($this->Is_Required()): ?> <span class="required">*</span><?php endif; ?></label>
    <?php do_action('vcff_field_post_label',$this); ?>
	<div class="field-alerts" style="<?php if (!$this->Get_Alerts()): ?>display:none;<?php endif; ?>">
        <?php echo $this->Get_Alerts_HTML(); ?>
    </div>
    <input name="<?php echo $machine_code; ?>" <?php echo $attributes; ?> <?php if ($placeholder): ?> placeholder="<?php echo $placeholder; ?>"<?php endif; ?> type="password" value="<?php echo $this->posted_value ? $this->posted_value : $default_value; ?>"<?php if ($is_disabled == 'yes'): ?> disabled="disabled"<?php endif; ?> class="form-control <?php if ($this->Has_Dependents()): ?>key-change check-change<?php endif; ?> <?php echo $field_extra_class; ?>">
    <?php if ($confirm_password == 'yes'): ?>
    <input name="<?php echo $machine_code; ?>_confirm" type="password" <?php if ($confirm_password_placeholder): ?>placeholder="<?php echo $confirm_password_placeholder; ?>"<?php endif; ?> value="" class="password-again <?php if ($this->Has_Dependents()): ?>check-change<?php endif; ?>">
    <?php endif; ?>
    <?php do_action('vcff_field_post_input',$this); ?>
</div>