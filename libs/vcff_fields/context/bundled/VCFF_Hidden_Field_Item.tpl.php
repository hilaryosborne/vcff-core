<input name="<?php echo $machine_code; ?>" <?php echo $attributes; ?> type="hidden" value="<?php echo $this->posted_value ? $this->posted_value : $default_value; ?>" class="form-control <?php if ($this->Has_Dependents()): ?>check-change<?php endif; ?> <?php echo $field_extra_class; ?>">
<?php do_action('vcff_field_post_input',$this); ?>