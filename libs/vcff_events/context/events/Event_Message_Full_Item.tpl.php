<div data-event-code="<?php echo $this->type; ?>" class="event-item action-field-group">
    <div class="action-group-header">
        <h4><strong><?php echo __('What would you like to happen?', VCFF_NS); ?></strong></h4><a href="" target="vcff_hint" class="help-lnk"><span class="dashicons dashicons-editor-help"></span> Help</a>
    </div>
    <div class="action-group-contents">
    <div class="row">
        <div class="col-sm-4">
            <p>Instructions</p>
        </div>
        <div class="col-sm-8">
            <div class="form-group">
                <?php echo vcff_curly_editor_textarea($this->form_instance,'event_action[events][full_message][message]',$this->Get_Message()); ?>
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