<div class="create-action">
    
    <input type="hidden" name="event_action[id]" value="<?php echo $this->Get_ID(); ?>">
    <input type="hidden" name="event_action[order]" value="<?php echo $this->Get_Order(); ?>">
    
    <div class="action-header">
    <div class="row">
        <div class="col-md-12">
            <h2>Create Form Event</h2>
            <p class="lead">To create a new event fill out the following form. An event typically requires essential information such as the event name, machine code, and event description as well is additional information outlining what actions you would like to happen and under what conditions the event should run.  You can have multiple events running at the same time so create as many as you need for your form's requirements.</p>
        </div>
    </div>
    </div>

    <div class="action-field-group">
        <div class="action-group-header">
            <h4><strong><?php echo __('Describe this event', VCFF_NS); ?></strong></h4><a href="" target="vcff_hint" class="help-lnk"><span class="dashicons dashicons-editor-help"></span> Help</a>
        </div>
        <div class="action-group-contents">
            <div class="row">
                <div class="col-sm-4">
                    <p>Your event will require a label to let people know within the administration panel know the gist of what your event is for. This event will also require a machine code. This is a unique code that no other events within this form should have. For more complicated events or for forms that have a lot of events it is recommended to add a simple event description to let people know in more detail what this event will do.</p>
                </div>
                <div class="col-sm-8">
                    <div class="form-group">
                        <label class="control-label"><?php echo __('Event Label', VCFF_NS); ?> <span class="required">*</span></label>
                        <input name="event_action[name]" placeholder="What is this event called?" type="text" value="<?php echo $this->Get_Name(); ?>" class="form-control event-label">
                        <?php if ($this->Is_Update() && isset($validation_errors['name'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <div class="alert-text"><?php echo __('A name is a required field', VCFF_NS); ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo __('Event Machine Name', VCFF_NS); ?> <span class="required">*</span></label>
                        <input name="event_action[code]" placeholder="sample_event" type="text" value="<?php echo $this->Get_Code(); ?>" class="form-control machine-name">
                        <?php if ($this->Is_Update() && isset($validation_errors['code'])): ?>
                        <div class="alert alert-danger" role="alert">
                            <div class="alert-text"><?php echo __('Please enter a machine code', VCFF_NS); ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label class="control-label"><?php echo __('Event Description', VCFF_NS); ?></label>
                        <textarea name="event_action[description]" placeholder="What does this event do?" style="height:120px;" class="form-control"><?php echo $this->Get_Description(); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="action-field-group">
        <div class="action-group-header">
            <h4><strong><?php echo __('What would you like to happen?', VCFF_NS); ?></strong></h4><a href="" target="vcff_hint" class="help-lnk"><span class="dashicons dashicons-editor-help"></span> Help</a>
        </div>
        <div class="action-group-contents">
            <div class="row">
                <div class="col-sm-4">
                    <p>Choose which action you would like your event to run if triggered.</p>
                </div>
                <div class="col-sm-8 action-events">
                    <div class="form-group">
                        <select name="event_action[selected_event]" class="select-event form-control">
                            <?php foreach ($this->events as $k => $event_instance): ?>
                            <option <?php if ($this->Get_Selected_Event() == $event_instance->type): ?>selected="selected"<?php endif; ?> value="<?php echo $event_instance->type; ?>"><?php echo $event_instance->title; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="action-event-items">
        <?php foreach ($this->events as $k => $event_instance): ?>
        <?php echo $event_instance->Render(); ?>
        <?php endforeach; ?>
    </div>

    <div class="action-field-group">
        <div class="action-group-header">
            <h4><strong><?php echo __('When would you like this action to happen?', VCFF_NS); ?></strong></h4><a href="" target="vcff_hint" class="help-lnk"><span class="dashicons dashicons-editor-help"></span> Help</a>
        </div>
        <div class="action-group-contents">
            <div class="row">
                <div class="col-sm-4">
                    <p>Instructions</p>
                </div>
                <div class="col-sm-8 action-triggers">
                    <div class="form-group">
                        <select name="event_action[selected_trigger]" class="select-trigger form-control">
                            <?php foreach ($this->triggers as $k => $trigger_instance): ?>
                            <option <?php if ($this->Get_Selected_Trigger() == $trigger_instance->code): ?>selected="selected"<?php endif; ?> value="<?php echo $trigger_instance->code; ?>"><?php echo $trigger_instance->title; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="action-event-triggers">
        <?php foreach ($this->triggers as $k => $trigger_instance): ?>
        <?php echo $trigger_instance->Render(); ?>
        <?php endforeach; ?>
    </div>

</div>