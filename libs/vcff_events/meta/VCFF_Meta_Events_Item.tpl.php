<div id="VCFF_Meta_Submission_Events" class="vcff-row bootstrap vcff-meta-fieldset vcff-meta-submission-events">
    
    <div class="event-alerts" style="display:none;"></div>
    
    <div class="alert alert-loading alert-warning">
        <h4>Refreshing the event list</h4>
        <p>Change this and that and try again. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Cras mattis consectetur purus sit amet fermentum.</p>
    </div>  
    
    <?php do_action('events_meta_before_nav',$this); ?>
    <div class="events-overview">
        <div class="events-actions">
            <span class="form-inline">
                <select class="bulk-type form-control">
                    <option value="">Bulk Actions</option>
                    <option value="delete">Delete Events</option>
                    <?php do_action('events_meta_nav_bulk_actions',$this); ?>
                </select>
                <button class="btn bulk-btn btn-default">Update</button>
            </span>
            <a href="" class="btn btn-primary create-event" style="float:right;">Create New Event</a>
            <?php do_action('events_meta_nav_buttons',$this); ?>
        </div>
        <div class="event-list">

        </div>
    </div>
    <?php do_action('events_meta_after_nav',$this); ?>

</div>