<div>
    <h3>Create A New Submission Event</h3>
    <?php if (isset($event_item_instance) && $event_item_instance->id): ?>
    <input type="hidden" name="event_item_id" value="<?php echo $event_item_instance->id; ?>">
    <?php endif; ?>
    <div class="field-group">
        <label>What is this submission event called? <span class="required">*</span></label>
        <input name="event_form_name" type="text" value="<?php if (isset($event_item_instance)): ?><?php echo $event_item_instance->name; ?><?php endif; ?>">
    </div>
    <div class="field-group">
        <label>A brief summary about this event? <span class="required">*</span></label>
        <textarea name="event_form_description"><?php if (isset($event_item_instance)): ?><?php echo $event_item_instance->description; ?><?php endif; ?></textarea>
    </div>
    <div class="field-group">
        <label>What would you like to happen? <span class="required">*</span></label>
        <select name="event_form_type">
            <?php $events = $this->_Get_Event_Type_List(); ?>
            <?php foreach ($events as $event_type => $event_data): ?>
            <option <?php if (isset($event_item_instance) && $event_item_instance->type == $event_type): ?>selected="selected"<?php endif; ?> value="<?php echo $event_type; ?>"><?php echo $event_data['title']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="events-create-options">
        <?php $event_forms = $this->_Get_Event_Type_Instances($event_item_instance); ?>
        <?php foreach ($event_forms as $event_type => $event_instance): ?>
        <div data-event-type="<?php echo $event_type; ?>" class="event-type-form" style="display:none;">
            <?php echo $event_instance->Render(); ?>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="field-group">
        <label>When should this event be performed?</label>
        <select name="event_form_trigger">
            <option value="everytime" <?php if (isset($event_item_instance) && $event_item_instance->trigger == 'everytime'): ?>selected="selected"<?php endif; ?>>Use this event everytime</option>
            <option value="conditional" <?php if (isset($event_item_instance) && $event_item_instance->trigger == 'conditional'): ?>selected="selected"<?php endif; ?>>Use conditions for this event</option>
            <option value="programmed" <?php if (isset($event_item_instance) && $event_item_instance->trigger == 'programmed'): ?>selected="selected"<?php endif; ?>>Trigger via scripting</option>
        </select>
    </div>
    <div class="field-group">
        <?php if (isset($event_item_instance) && $event_item_instance->id): ?>
        <button type="button" class="btn-update">Update Event</button>
        <?php else: ?>
        <button type="button" class="btn-add">Add Event</button>
        <?php endif; ?>
        <button type="button" class="btn-cancel">Cancel</button>
    </div>
</div>