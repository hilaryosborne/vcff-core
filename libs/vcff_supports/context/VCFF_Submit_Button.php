<?php

class VCFF_Submit_Button {
    
    static $type = 'vcff_submit_btn';
    
    static $title = 'Submit Button';

    static $item_class = 'VCFF_Submit_Button_Item';

    static function VC_Params() {
        // Return any visual composer params
        return array(
            'params' =>  array(
                array (
                    "type" => "vcff_machine",
                    "heading" => __ ( "Machine Code", VCFF_FORM ),
                    "param_name" => "machine_code",
                ), 
                array (
                    "type" => "textfield",
                    "heading" => __ ( "Button Label", VCFF_FORM ),
                    "param_name" => "btn_label",
                    'admin_label' => true,
                ),
                array (
                    "type" => "textfield",
                    "heading" => __ ( "Button Value", VCFF_FORM ),
                    "param_name" => "btn_value",
                ),
                array (
                    'type' => 'textfield',
                    'heading' => __ ( 'Extra Class', VCFF_FORM ),
                    'param_name' => 'extra_class',
                ),
                array (
                    'type' => 'textfield',
                    'heading' => __ ( 'Element Extra Class', VCFF_FORM ),
                    'param_name' => 'el_extra_class',
                ),
                array(
                    'type' => 'css_editor',
                    'heading' => __('CSS',VCFF_FORM),
                    'param_name' => 'css',
                    'group' => __('Design Options',VCFF_FORM),
                ),
                // FIELD CONDITIONAL PARAMETERS
                array (
                    'type' => 'vcff_conditional',
                    'heading' => false,
                    'param_name' => 'conditions',
                    'group' => 'Adv. Logic'
                )
            )
        );
    }
    
    static function Params() {
        // Return any field params
        return array();
    }
}