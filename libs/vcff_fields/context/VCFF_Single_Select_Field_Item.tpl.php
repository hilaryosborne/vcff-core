<div data-vcff-field-name="<?php echo $machine_code; ?>" class="vcff-field vcff-select-field <?php echo $extra_class; ?> <?php echo $css_class; ?>" <?php if ($this->Is_Hidden()): ?>style="display:none;"<?php endif; ?>>  
    <?php do_action('vcff_field_pre_label',$this); ?>
    <label><?php echo $field_label; ?><?php if ($this->Is_Required()): ?> <span class="required">*</span><?php endif; ?></label>
    <?php do_action('vcff_field_post_label',$this); ?>
	<div class="field-alerts" style="<?php if (!$this->Get_Alerts()): ?>display:none;<?php endif; ?>">
        <?php echo $this->Get_Alerts_HTML(); ?>
    </div>
    <?php if ($options_list && is_array($options_list)): ?>
        <?php $selected_value = $this->posted_value ? $this->posted_value : $default_value; ?>
        <select name="<?php echo $machine_code; ?>" <?php echo $attributes; ?> <?php if ($placeholder): ?> placeholder="<?php echo $placeholder; ?>"<?php endif; ?> <?php if ($is_disabled == 'yes'): ?> disabled="disabled"<?php endif; ?> class="form-control <?php if ($this->Has_Dependents()): ?>check-change<?php endif; ?> <?php echo $field_extra_class; ?>" style="display:block;width:100%;">
        <?php foreach ($options_list as $field_item_value => $field_item_label): ?>
            <option value="<?php echo $field_item_value; ?>" <?php if ($field_item_value == $this->posted_value): ?>selected="selected"<?php endif; ?>><?php echo $field_item_label; ?></option>
        <?php endforeach; ?>
        </select>
    <?php else: ?>
    <p>There are no checklist items</p>
    <?php endif; ?>
    <?php do_action('vcff_field_post_input',$this); ?>
</div>