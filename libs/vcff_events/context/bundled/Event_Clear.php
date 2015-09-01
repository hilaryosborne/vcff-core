<?php

vcff_map_event(array(
    'type' => 'clear',
    'title' => 'I would like to clear all form values',
    'class' => 'Event_Clear_Item'
));

// Register the vcff admin css
vcff_front_enqueue_script('vcff-event-clear', VCFF_EVENTS_URL . '/assets/public/event_clear.js', array('vcff-core'), '20120608', 'all');