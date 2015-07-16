<div class="bootstrap vcff_param_heading">
    <h3><?php echo $settings['html_title']; ?><?php if (isset($settings['help_url'])): ?><a href="<?php echo $settings['help_url']; ?>" class="help-link"><span class="dashicons dashicons-editor-help"></span> <?php echo __('Help',VCFF_NS); ?></a><?php endif; ?></h3>
    <?php do_action('vcff_param_url_vars_before_description',$this); ?>
    <span class="vc_description vc_clearfix"><?php echo $settings['html_description']; ?></span>
    <?php do_action('vcff_param_url_vars_after_description',$this); ?>
</div>