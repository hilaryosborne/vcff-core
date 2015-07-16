<div id="VCFF_FORM_SETTINGS" class="postbox bootstrap vcff-meta-container">
    <div class="handlediv" title="Click to toggle"><br></div>
    <h3 class="hndle ui-sortable-handle">
        <strong>Form Framework</strong>
    </h3>
    <div class="inside">
        <?php do_action('vcff_meta_container_pre_content',$this); ?>
        <input type="hidden" name="vcff_form_uuid" value="<?php echo $form_instance->Get_UUID(); ?>" >
        <div class="vcff-tabs">
            <ul class="vcff-tab-nav">
                <?php do_action('vcff_meta_container_tabs_content',$this); ?>
            </ul>
        </div>
        <?php do_action('vcff_meta_container_post_content',$this); ?>
    </div>

    <div id="vcff_meta_model" tabindex="-1" role="dialog" class="modal meta-model">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">

                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
</div>

