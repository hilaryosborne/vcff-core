<?php

class VCFF_Loading_Alert {

    static $type = 'vcff_loading_alert';
    
    static $title = 'Loading Message';

    static $item_class = 'VCFF_Loading_Alert_Item';

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
                    "heading" => __ ( "Loading Message", VCFF_FORM ),
                    "param_name" => "loading_msg",
                    'admin_label' => true
                ),
                array (
                    "type" => "dropdown",
                    "heading" => __ ( "Display as", VCFF_FORM ),
                    "param_name" => "display",
                    'value' => array(
                        __('Hovering') => 'hovering',
                        __('Stationary') => 'stationary'
                    )
                ),
                array (
                    "type" => "checkbox",
                    "heading" => __ ( "Use during", VCFF_FORM ),
                    "param_name" => "usage",
                    'value' => array(
                        __('Checking Conditions') => 'conditions',
                        __('Form Submission') => 'submission',
                        __('Form Error') => 'error',
                    )
                ),
                array (
                    'type' => 'textfield',
                    'heading' => __ ( 'Extra Class', VCFF_FORM ),
                    'param_name' => 'extra_class',
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

vcff_map_support('VCFF_Loading_Alert');

vcff_front_enqueue_script('vcff_loading_alert', VCFF_SUPPORTS_URL.'/assets/public/vcff_loading_alert.js',array('vcff-core'));
vcff_front_enqueue_style('vcff_loading_alert', VCFF_SUPPORTS_URL.'/assets/public/vcff_loading_alert.css');