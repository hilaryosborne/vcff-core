<?php

class Trigger_Everytime {
    
    static $code = 'everytime';
    
    static $title = 'Use this event everytime';
    
	static $class_item = 'Trigger_Everytime_Item';
	
    static function Params() {
        // Return any field params
        return array();
    } 
}

vcff_map_trigger('Trigger_Everytime');