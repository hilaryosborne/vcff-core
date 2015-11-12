<div id="vcff_page_<?php echo $meta_page['id']; ?>" class="vcff-meta-page">
    <?php do_action('vcff_meta_page_pre_content',$this); ?>
    <div class="meta-page-groups">
        <?php do_action('vcff_meta_page_groups',$this); ?>
    </div>
    <?php do_action('vcff_meta_page_post_content',$this); ?>
</div>