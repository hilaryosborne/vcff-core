<?php

class Event_Clear {
    
    static $type = 'clear';
    
    static $title = 'I would like to clear all form values';
    
	static $class_item = 'Event_Clear_Item';
	
    static function Params() {
        // Return any field params
        return array(
            
        );
    } 
}

vcff_map_event('Event_Clear');

// Register the vcff admin css
vcff_front_enqueue_script('vcff-event-clear', VCFF_EVENTS_URL . '/assets/public/event_clear.js', array('vcff-core'), '20120608', 'all');