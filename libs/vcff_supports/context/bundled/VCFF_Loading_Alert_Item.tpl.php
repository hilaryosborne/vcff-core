<div data-vcff-support-name="<?php echo $machine_code; ?>" class="form-loading-alert ignore-visibility <?php echo $extra_class; ?> <?php echo $css_class; ?> <?php echo 'for-'.$display; ?><?php if (in_array('conditions',$usage_list)): ?> for-conditions<?php endif; ?><?php if (in_array('validation',$usage_list)): ?> for-validation<?php endif; ?><?php if (in_array('submission',$usage_list)): ?> for-submission<?php endif; ?><?php if (in_array('error',$usage_list)): ?> for-error<?php endif; ?>"> 
    <div class="inner-wrap">
        <?php echo $loading_msg; ?>
    </div>
</div>

