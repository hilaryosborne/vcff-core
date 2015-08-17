<?php

class VCFF_Settings_Select {

    static $type = 'select';
    
    static $title = 'Select Dropdown';
    
    static $item_class = 'VCFF_Settings_Select_Item';
    
    static function Params() {
        // Return any field params
        return array(
            'validation' => array(
                'required' => true
            )
        );
    }
}

vcff_map_setting('VCFF_Settings_Select');