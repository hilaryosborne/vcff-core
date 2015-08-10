<?php

class Event_Message_Full {
    
    static $type = 'full_message';
    
    static $title = 'I would like to display a full message';
    
	static $class_item = 'Event_Message_Full_Item';
	
    static function Params() {
        // Return any field params
        return array();
    } 
}

vcff_map_event('Event_Message_Full');

// Register the vcff admin css
vcff_front_enqueue_script('vcff-full-message', VCFF_EVENTS_URL . '/assets/public/event_full_message.js', array('vcff-core'), '20120608', 'all');