<?php

class VCFF_Meta_Select {

    static $meta_type = 'select';
    
    static $meta_title = 'Select Dropdown';
    
    static $item_class = 'VCFF_Meta_Select_Item';
    
    static function Meta_Params() {
        // Return any field params
        return array(
            'js' => 'yup',
            'validation' => array(
                'required' => true
            )
        );
    }
}