<?php

class Trigger_Scripted {
    
    static $code = 'scripted';
    
    static $title = 'This event will be scripted';
    
	static $class_item = 'Trigger_Scripted_Item';
	
    static function Params() {
        // Return any field params
        return array();
    } 
}

vcff_map_trigger('Trigger_Scripted');