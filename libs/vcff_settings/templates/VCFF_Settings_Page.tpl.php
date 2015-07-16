<div id="vcff_page_<?php echo $page['id']; ?>" class="vcff-settings-page">
    <?php do_action('vcff_settings_pre_groups_tmpl',$this); ?>
    <div class="settings-page-groups">
        <?php do_action('vcff_settings_groups_tmpl',$this); ?>
    </div>
    <?php do_action('vcff_settings_post_groups_tmpl',$this); ?>
</div>