<?php

class VCFF_Alert_Panel {
    
    static $type = 'vcff_alert_panel';
    
    static $title = 'Alert Panels';
    
    static $item_class = 'VCFF_Alert_Panel_Item';

    static function VC_Params() {
        // Return any visual composer params
        return array(
            'params' =>  array(
                // CORE FIELD SETTINGS
                array (
                    "type" => "vcff_heading",
                    "heading" => false,
                    "param_name" => "support_heading",
                    'html_title' => 'VCFF Supports',
                    'html_description' => 'You can set this field to accept dynamic values from either POST, GET or REQUEST variables. This is useful if you have forms posting to each other or if you want to refill form fields via a URL link.',
                    'help_url' => 'http://blah',
                ),
                array (
                    "type" => "vcff_machine",
                    "heading" => __ ( "Machine Code", VCFF_FORM ),
                    "param_name" => "machine_code",
                ), 
                array (
                    "type" => "vcff_heading",
                    "heading" => false,
                    "param_name" => "el_heading",
                    'html_title' => 'Element Configuration',
                    'html_description' => 'You can set this field to accept dynamic values from either POST, GET or REQUEST variables. This is useful if you have forms posting to each other or if you want to refill form fields via a URL link.',
                    'help_url' => 'http://blah',
                ),
                array (
                    'type' => 'textfield',
                    'heading' => __ ( 'Extra Class', VCFF_FORM ),
                    'param_name' => 'extra_class',
                ),
                // FIELD CONDITIONAL PARAMETERS
                array (
                    'type' => 'vcff_conditional',
                    'heading' => false,
                    'param_name' => 'conditions',
                    'group' => 'Adv. Logic'
                ),
                array(
                    'type' => 'css_editor',
                    'heading' => __('CSS',VCFF_FORM),
                    'param_name' => 'css',
                    'group' => __('Design Options',VCFF_FORM),
                ),
            )
        );
    }
    
    static function Params() {
        // Return any field params
        return array();
    }
}

vcff_map_support('VCFF_Alert_Panel');