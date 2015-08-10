<div data-vcff-field-name="<?php echo $machine_code; ?>" class="vcff-field vcff-simple-upload <?php echo $extra_class; ?> <?php echo $css_class; ?>" <?php if ($this->Is_Hidden()): ?>style="display:none;"<?php endif; ?>>
    <?php do_action('vcff_field_pre_label',$this); ?>
    <label><?php echo $field_label; ?><?php if ($this->Is_Required()): ?> <span class="required">*</span><?php endif; ?></label>
    <?php do_action('vcff_field_post_label',$this); ?>
    <?php if ($this->Get_Allowed_Extensions()): ?>
    <div class="allowed allowed-ext"><strong>Allowed extensions:</strong> <?php echo implode(',',$this->Get_Allowed_Extensions()); ?></div>
    <?php endif; ?>
    <?php if ($this->Get_Allowed_Filesize()): ?>
    <div class="allowed allowed-size"><strong>Allowed filesize:</strong> <?php echo $this->Get_Display_Filesize(); ?></div>
    <?php endif; ?>
    <div class="field-alerts" style="<?php if (!$this->Get_Alerts()): ?>display:none;<?php endif; ?>">
        <?php echo $this->Get_Alerts_HTML(); ?>
    </div>
    <div class="uploaded-file" style="<?php if (!$this->Get_Original_Filename()): ?>display:none;<?php endif; ?>">
        <span class="filename"><?php echo $this->Get_Original_Filename(); ?></span><a href="" class="file-cancel">Cancel</a>
    </div>
    <div class="uploading-msg" style="display:none;">
        <span>Your selected file is uploading, please wait...</span>
    </div>
    <input name="<?php echo $machine_code; ?>[original]" type="hidden" value="<?php echo $this->Get_Original_Filename(); ?>" class="form-control field-original">
    <input name="<?php echo $machine_code; ?>[name]" type="hidden" value="<?php echo $this->Get_Actual_Filename(); ?>" class="form-control field-filename">
    <input name="<?php echo $machine_code; ?>[location]" type="hidden" value="<?php echo $this->Get_Actual_Location(); ?>" class="form-control field-location">
    <button type="button" class="btn btn-upload">Select File</button>
    <?php do_action('vcff_field_post_input',$this); ?>
</div>