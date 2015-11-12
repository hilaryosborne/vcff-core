<div data-vcff-field-name="<?php echo $machine_code; ?>" class="vcff-field vcff-recaptcha-field <?php echo $extra_class; ?> <?php echo $css_class; ?>" <?php if ($this->Is_Hidden()): ?>style="display:none;"<?php endif; ?>>
    <div class="field-alerts" style="<?php if (!$this->Get_Alerts()): ?>display:none;<?php endif; ?>">
        <?php echo $this->Get_Alerts_HTML(); ?>
    </div>
    <div class="recaptcha-field" data-sitekey="<?php echo $recaptcha_site_key; ?>"></div>
    <?php do_action('vcff_field_post_input',$this); ?>
</div>