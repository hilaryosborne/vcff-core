<div data-event-code="<?php echo $this->type; ?>" class="event-item action-field-group">
    <div class="action-group-header">
        <h4><strong><?php echo __('Redirect to a URL', VCFF_NS); ?></strong></h4><a href="" target="vcff_hint" class="help-lnk"><span class="dashicons dashicons-editor-help"></span> Help</a>
    </div>
    <div class="action-group-contents">
    <div class="row">
        <div class="col-sm-4">
            <p>Instructions</p>
        </div>
        <div class="col-sm-8">
            <div class="form-group">
                <label class="control-label">Redirect URL <span class="required">*</span></label>
                <input name="event_action[events][redirect][url]" type="text" class="form-control" value="<?php echo $this->_Get_Redirect_URL(); ?>">
                <?php if ($this->Is_Update() && isset($validation_errors['url'])): ?>
                <div class="alert alert-danger" role="alert">
                    <div class="alert-text"><?php echo __('Please enter a redirect URL', VCFF_NS); ?></div>
                </div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label class="control-label">Redirect Method <span class="required">*</span></label>
                <?php $show_method = $this->_Get_Redirect_Method(); ?>
                <select name="event_action[events][redirect][method]" class="form-control">
                    <option <?php if ($show_method == 'post'): ?>selected="selected"<?php endif; ?> value="post">POST</option>
                    <option <?php if ($show_method == 'get'): ?>selected="selected"<?php endif; ?> value="get">GET</option>
                </select>
                <?php if ($this->Is_Update() && isset($validation_errors['method'])): ?>
                <div class="alert alert-danger" role="alert">
                    <div class="alert-text"><?php echo __('Please select the method', VCFF_NS); ?></div>
                </div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label class="control-label">Pass the following values</label>
                <?php echo vcff_curly_editor_textarea($this->form_instance,'event_action[events][redirect][query]',$this->_Get_Redirect_Query()); ?>
            </div>
        </div>
    </div>
    </div>
</div>