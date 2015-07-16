<?php

class Event_Email {
    
    static $type = 'send_email';
    
    static $title = 'I would like to send a email notification';
    
	static $class_item = 'Event_Email_Item';
	
    static function Params() {
        // Return any field params
        return array();
    } 
}