<?php

class Trigger_Conditional {
    
    static $code = 'conditional';
    
    static $title = 'Use conditions';
    
	static $class_item = 'Trigger_Conditional_Item';
	
    static function Params() {
        // Return any field params
        return array();
    } 
}

vcff_map_trigger('Trigger_Conditional');