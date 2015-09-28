<div data-vcff-container="<?php echo $this->machine_code; ?>" class="vcff-container vcff-active-container vcff-container-form <?php echo $css_class; ?>" <?php if ($this->Is_Hidden()): ?>style="display:none;"<?php endif; ?>>
    <?php if ($label): ?>
    <h3><?php echo $label; ?></h3>
    <?php endif; ?>
    <div class="container-alerts">
        <?php echo $this->Get_Alerts_HTML(); ?>
    </div>
    <?php echo do_shortcode( $content ); ?>
</div>