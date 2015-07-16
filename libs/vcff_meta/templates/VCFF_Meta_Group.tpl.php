<div class="vcff-meta-group form-horizontal <?php if (isset($meta_group['extra_class'])): ?><?php echo $meta_group['extra_class']; ?><?php endif; ?>">
    <div class="meta-group-header clearfix">
        <h4><?php echo apply_filters('vcff_meta_group_title',$meta_group['title'],$this,$meta_group); ?></h4><?php if (isset($meta_group['help_url'])): ?><a href="<?php echo $meta_group['help_url']; ?>" target="vcff_hint" class="help-lnk"><span class="dashicons dashicons-editor-help"></span> Help</a><?php endif; ?>
        
    </div>
    <?php do_action('vcff_meta_group_before_contents',$this,$meta_group); ?>
    <div class="row row-content">
        <div class="col-fields col-sm-9 meta-group-fields">
            <?php do_action('vcff_meta_group_before_fields',$this,$meta_group); ?>
            <?php // It is important to remember that fields are populated via ajax and are not directly rendered here ?>
            <?php do_action('vcff_meta_group_fields',$this,$meta_group); ?>
            <?php do_action('vcff_meta_group_after_fields',$this,$meta_group); ?>
        </div>
        <div class="col-details col-sm-3">
            <?php do_action('vcff_meta_group_before_hint',$this,$meta_group); ?>
            <div class="instructions">
                <?php echo apply_filters('vcff_meta_group_hint_html',$meta_group['hint_html'],$this,$meta_group); ?>    
            </div>
            <?php do_action('vcff_meta_group_after_hint',$this,$meta_group); ?>
        </div>
    </div>
    <?php do_action('vcff_meta_group_after_contents',$this,$meta_group); ?>
</div>