<div data-event-code="<?php echo $this->type; ?>" class="event-item action-field-group">
    <div class="action-group-header">
        <h4><strong><?php echo __('Display An Alert', VCFF_NS); ?></strong></h4><a href="" target="vcff_hint" class="help-lnk"><span class="dashicons dashicons-editor-help"></span> Help</a>
    </div>
    <div class="action-group-contents">
    <div class="row">
        <div class="col-sm-4">
            <p>Instructions</p>
        </div>
        <div class="col-sm-8">
            <div class="form-group">
                <select name="event_action[events][alert][type]" class="form-control">
                    <option <?php if ($this->Get_Type() == 'success'): ?>selected="selected"<?php endif; ?> value="success">Display a success alert</option>
                    <option <?php if ($this->Get_Type() == 'info'): ?>selected="selected"<?php endif; ?> value="info">Display a information alert</option>
                    <option <?php if ($this->Get_Type() == 'warning'): ?>selected="selected"<?php endif; ?> value="warning">Display a warning alert</option>
                    <option <?php if ($this->Get_Type() == 'danger'): ?>selected="selected"<?php endif; ?> value="danger">Display a danger alert</option>
                </select>
                <?php if ($this->Is_Update() && isset($validation_errors['type'])): ?>
                <div class="alert alert-danger" role="alert">
                    <div class="alert-text"><?php echo __('Please select the type of message', VCFF_NS); ?></div>
                </div>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <?php echo vcff_curly_editor_textarea($this->form_instance,'event_action[events][alert][message]',$this->Get_Message()); ?>
                <?php if ($this->Is_Update() && isset($validation_errors['message'])): ?>
                <div class="alert alert-danger" role="alert">
                    <div class="alert-text"><?php echo __('Please enter a message to display', VCFF_NS); ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    </div>
</div>

