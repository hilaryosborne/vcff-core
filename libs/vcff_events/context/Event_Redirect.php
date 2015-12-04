<?php

vcff_map_event(array(
    'type' => 'redirect',
    'title' => 'I would like to redirect to a URL',
    'class' => 'Event_Redirect_Item'
));

// Register the vcff admin css
vcff_front_enqueue_script('vcff-event-redirect', VCFF_EVENTS_URL . '/assets/public/event_redirect.js', array('vcff-core'), '20120608', 'all');