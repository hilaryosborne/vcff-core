<?php

class VCFF_Meta_Textfield {

    static $meta_type = 'textfield';
    
    static $meta_title = 'Single Line Text Input';
    
    static $item_class = 'VCFF_Meta_Textfield_Item';
    
    static function Meta_Params() {
        // Return any field params
        return array();
    }
}

vcff_map_meta_field('VCFF_Meta_Textfield');