<?php

class Event_Redirect {
    
    static $type = 'redirect';
    
    static $title = 'I would like to redirect to a URL';
    
	static $class_item = 'Event_Redirect_Item';
	
    static function Params() {
        // Return any field params
        return array();
    } 
}

vcff_map_event('Event_Redirect');

// Register the vcff admin css
vcff_front_enqueue_script('vcff-event-redirect', VCFF_EVENTS_URL . '/assets/public/event_redirect.js', array('vcff-core'), '20120608', 'all');