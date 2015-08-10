<?php

class Event_Alert {
    
    static $type = 'alert';
    
    static $title = 'I would like to display a simple alert';
    
	static $class_item = 'Event_Alert_Item';
	
    static function Params() {
        // Return any field params
        return array(
            
        );
    } 
}

vcff_map_event('Event_Alert');