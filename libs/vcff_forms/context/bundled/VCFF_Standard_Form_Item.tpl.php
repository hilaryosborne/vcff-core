<form <?php if ($form_attributes): ?><?php echo $form_attributes ?><?php endif ?> method="post" action="" enctype="multipart/form-data" autocomplete="off" class="<?php if ($this->Get_Meta_Field_Value('use_ajax') == 'yes'): ?>do-ajax-submit<?php endif; ?> vcff-form <?php if ($extra_class): ?><?php echo $extra_class; ?><?php endif ?>">
    <?php do_action('vcff_form_render_pre_content',$this); ?>
    <div class="form-alerts">
        <?php echo $this->Get_Alerts_HTML(); ?>
    </div>
    <?php echo $form_content; ?>
    <?php do_action('vcff_form_render_post_content',$this); ?>
    <input type="hidden" name="vcff_form" value="true">
    <input type="hidden" name="vcff_key" value="<?php echo $this->Issue_Security_Key(); ?>">
    <input type="hidden" name="vcff_form_id" value="<?php echo $this->form_id; ?>">
    <input type="hidden" name="vcff_form_uuid" value="<?php echo $this->form_uuid; ?>">
    <input type="hidden" name="vcff_post_id" value="<?php echo $this->post_id; ?>">
</form>