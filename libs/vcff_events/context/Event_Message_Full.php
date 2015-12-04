<?php

vcff_map_event(array(
    'type' => 'full_message',
    'title' => 'I would like to display a full message',
    'class' => 'Event_Message_Full_Item'
));

// Register the vcff admin css
vcff_front_enqueue_script('vcff-full-message', VCFF_EVENTS_URL . '/assets/public/event_full_message.js', array('vcff-core'), '20120608', 'all');