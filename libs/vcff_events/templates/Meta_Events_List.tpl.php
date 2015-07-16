<div class="events-existing">      
    <?php $form_actions = $form_instance->events; ?>
    <?php if ($form_actions && is_array($form_actions) && count($form_actions) > 0): ?>
    <?php do_action('vcff_event_list_pre_table',$this); ?>
    <table width="100%" class="table table-striped">
        <thead>
            <tr>
                <th class="col-select"><input id="x_1" type="checkbox"></th>
                <th class="col-order"></th>
                <?php do_action('vcff_report_entries_thead',$this); ?>
                <th class="col-name">Event Name</th>
                <th class="col-event">Event Type</th>
                <th class="col-delete"></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th class="col-select"><input id="x_2" type="checkbox"></th>
                <th class="col-order"></th>
                <?php do_action('vcff_report_entries_tfoot',$this); ?>
                <th class="col-name">Event Name</th>
                <th class="col-event">Event Type</th>
                <th class="col-delete"></th>
            </tr>
        </tfoot>
        <tbody>
            
            <?php $i=0; foreach ($form_actions as $k => $action_instance):  ?>
            <tr data-action-id="<?php echo $action_instance->id; ?>" class="event-row <?php if ($i % 2 == 0): ?>even<?php else: ?>odd<?php endif; ?>">
                <td class="col-select"><input type="checkbox" value="" class="event-toggle"></td>
                <td class="col-order"><a href="" class="move-link dashicons-arrow-up move-up"></a><a href="" class="move-link dashicons-arrow-down move-down"></a></td>
                <?php do_action('vcff_report_entries_tbody',$this); ?>
                <td class="col-name">
                    <a href="" class="edit-action"><?php echo $action_instance->Get_Name(); ?></a>
                    <?php echo $action_instance->Get_Description();  ?>
                </td>
                <?php $event_instance = $action_instance->Get_Selected_Event_Instance(); ?>
                <td class="col-event"><?php if (is_object($event_instance)): ?><?php echo $event_instance->Get_Name(); ?><?php else: ?><span>-</span><?php endif; ?></td>
                <td class="col-delete"><a href="" class="dashicon dashicons-trash delete-action"></a></td>
            </tr>
            <?php $i++; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php do_action('vcff_event_list_post_table',$this); ?>
    <?php else: ?>
    <div class="alert alert-warning" role="alert">
        <h4>No events currently configured</h4>
        <p>You can start creating events by clicking the "Create New Event" button at the top of this section.</p>
    </div>
    <?php endif; ?>
</div>