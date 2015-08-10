<?php

class Action_Standard {

    static $type = 'standard_action';
    
    static $title = 'Standard Action';
    
	static $class_item = 'Action_Standard_Item';
	
    static function Params() {
        // Return any field params
        return array(
            
        );
    } 
    
}

vcff_map_action('Action_Standard');