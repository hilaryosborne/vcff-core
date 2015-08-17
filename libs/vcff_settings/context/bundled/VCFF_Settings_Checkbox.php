<?php

class VCFF_Settings_Checkbox {

    static $type = 'checkbox';
    
    static $title = 'Checkbox';
    
    static $item_class = 'VCFF_Settings_Checkbox_Item';
    
    static function Params() {
        // Return any field params
        return array();
    }
}

vcff_map_setting('VCFF_Settings_Checkbox');