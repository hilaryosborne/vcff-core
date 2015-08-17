<?php

class VCFF_Settings_Password {

    static $type = 'password';
    
    static $title = 'Password Input';

    static $item_class = 'VCFF_Settings_Password_Item';

    static function Params() {
        // Return any field params
        return array();
    }
}

vcff_map_setting('VCFF_Settings_Password');