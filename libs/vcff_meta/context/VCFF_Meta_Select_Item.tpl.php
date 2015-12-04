<div class="form-group vcff-meta-textfield <?php echo $extra_class; ?>">
    <label class="col-sm-3 control-label">
        <?php do_action('vcff_meta_field_pre_label',$this); ?>
        <?php echo apply_filters('vcff_meta_field_label',$label,$this); ?> <?php if ($this->Is_Required()): ?><span class="required">*</span><?php endif; ?>
        <?php do_action('vcff_meta_field_post_label',$this); ?>
    </label>
    <div class="col-sm-9">
        <?php do_action('vcff_meta_field_pre_input',$this); ?>
        <select name="<?php echo $machine_code; ?>" class="form-control vcff-meta-field <?php echo $field_extra_class; ?>" <?php if ($required): ?>required<?php endif; ?>>
            <?php if ($values && is_array($values)): ?>
            <?php foreach($values as $k => $value): ?>
            <option value="<?php echo $k; ?>"<?php if($this->value == $k): ?> selected="selected"<?php endif; ?>><?php echo $value; ?></option>
            <?php endforeach; ?>
            <?php endif; ?>
        </select>
        <div class="field-alerts" style="<?php if (!$this->Get_Alerts()): ?>display:none;<?php endif; ?>">
            <?php echo $this->Get_Alerts_HTML(); ?>
        </div>
        <?php if ($hints_html): ?><p class="hints"><?php echo $hints_html; ?></p><?php endif; ?>
        <?php do_action('vcff_meta_field_post_input',$this); ?>
    </div>
</div>