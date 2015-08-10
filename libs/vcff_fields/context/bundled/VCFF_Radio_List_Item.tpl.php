<div data-vcff-field-name="<?php echo $machine_code; ?>" class="vcff-field vcff-radiolist-field <?php echo $extra_class; ?> <?php echo $css_class; ?>" <?php if ($this->Is_Hidden()): ?>style="display:none;"<?php endif; ?>>
    <?php do_action('vcff_field_pre_label',$this); ?>
    <label class="field-label"><?php echo $field_label; ?><?php if ($this->Is_Required()): ?> <span class="required">*</span><?php endif; ?></label>
    <?php do_action('vcff_field_post_label',$this); ?>
	<div class="field-alerts" style="<?php if (!$this->Get_Alerts()): ?>display:none;<?php endif; ?>">
        <?php echo $this->Get_Alerts_HTML(); ?>
    </div>
    <?php if ($options_list && is_array($options_list)): ?>
    <?php foreach ($options_list as $field_item_value => $field_item_label): ?>
    <div class="radio">
        <label>
            <input type="radio" name="<?php echo $machine_code; ?>" value="<?php echo $field_item_value; ?>" <?php if ($this->posted_value == $field_item_value): ?>checked="checked"<?php endif; ?> <?php echo $attributes; ?> <?php if ($is_disabled == 'yes'): ?> disabled="disabled"<?php endif; ?> class="<?php if ($this->Has_Dependents()): ?>check-change<?php endif; ?> <?php echo $field_extra_class; ?>">
            <?php echo $field_item_label; ?>
        </label>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
    <?php do_action('vcff_field_post_input',$this); ?>
</div>