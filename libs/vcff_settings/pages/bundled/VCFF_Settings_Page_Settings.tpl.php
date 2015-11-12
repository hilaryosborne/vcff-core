<div class="bootstrap vcff-admin-panel">

    <?php do_action('vcff_settings_form_pre_header',$this); ?>
    <div class="row">
        <div class="col-md-12">
            <h2>General Settings</h2>
        </div>
    </div>
    <?php do_action('vcff_settings_form_post_header',$this); ?>
    
    <div class="row">
    <form id="VCFF_SETTINGS" method="post" action="" autocomplete="false" class="form-horizontal">
        <!-- AUTOFILL DISABLE WORKAROUND -->
        <input type="text" name="prevent_autofill" id="prevent_autofill" value="" style="display:none;" />
        <input type="password" name="password_fake" id="password_fake" value="" style="display:none;" />
        <!-- END OF AUTOFILL DISABLE WORKAROUND -->

        <div class="col-md-9">
            <div class="form-alerts">
                <?php echo $this->Get_Alerts_HTML(); ?>
            </div>
            <?php do_action('vcff_settings_form_pre_content',$this); ?>
            <div class="postbox vcff-settings-container">
                <div class="inside">
                    <div class="vcff-tabs">
                        <ul class="vcff-tab-nav">
                            <?php do_action('vcff_settings_form_navigation',$this); ?>
                        </ul>
                    </div>
                    <?php do_action('vcff_settings_form_content',$this); ?>
                </div>
            </div>
            <?php do_action('vcff_settings_form_post_content',$this); ?>
        </div>

        <div class="col-md-3">
            <div class="postbox" style="margin-right:55px;">
                <h3 class="hndle ui-sortable-handle"><span>About VCFF</span></h3>
                <div class="inside">
                    <p class="misc-plugin-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus eu enim porta, placerat nisi id, tincidunt libero. Ut hendrerit dui erat. Mauris aliquet, urna sed consequat vehicula, felis nisl suscipit massa</p>
                    <div class="misc-plugin-info">
                    Version
                    </div>
                    <div class="major-publishing-actions">
                        <button type="submit" class="btn btn-primary" data-loading-text="Updating...">Update Settings</button>
                    </div>
                </div>
            </div>
        </div>
        
        <input type="hidden" value="yes" name="vcff_settings_update">
        
    </form>
    </div>
    
    <?php do_action('vcff_settings_form_footer',$this); ?>
    
</div>