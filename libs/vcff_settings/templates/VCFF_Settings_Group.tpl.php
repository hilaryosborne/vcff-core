<div class="vcff-settings-group form-horizontal <?php if (isset($group['extra_class'])): ?><?php echo $group['extra_class']; ?><?php endif; ?>">
    <div class="settings-group-header clearfix">
        <h4><?php echo apply_filters('vcff_settings_group_title',$group['title'],$this,$group); ?></h4><?php if (isset($group['help_url'])): ?><a href="<?php echo $group['help_url']; ?>" target="vcff_hint" class="help-lnk"><span class="dashicons dashicons-editor-help"></span> Help</a><?php endif; ?>
    </div>
    <?php do_action('vcff_settings_group_before_contents',$this,$group); ?>
    <div class="row row-content">
        <div class="col-fields col-sm-9 settings-group-fields">
            <?php do_action('vcff_settings_group_before_fields',$this,$group); ?>
            <?php // It is important to remember that fields are populated via ajax and are not directly rendered here ?>
            <?php do_action('vcff_settings_group_fields',$this,$group); ?>
            <?php do_action('vcff_settings_group_after_fields',$this,$group); ?>
        </div>
        <div class="col-details col-sm-3">
            <?php do_action('vcff_settings_group_before_hint',$this,$group); ?>
            <div class="instructions">
                <?php echo apply_filters('vcff_settings_group_hint_html',$group['hint_html'],$this,$group); ?>    
            </div>
            <?php do_action('vcff_settings_group_after_hint',$this,$group); ?>
        </div>
    </div>
    <?php do_action('vcff_settings_group_after_contents',$this,$group); ?>
</div>

