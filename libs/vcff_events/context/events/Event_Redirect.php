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