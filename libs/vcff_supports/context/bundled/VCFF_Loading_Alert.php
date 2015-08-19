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
                    "param_name" => "button_heading",
                    'html_title' => 'Button Configuration',
                    'html_description' => 'You can set this field to accept dynamic values from either POST, GET or REQUEST variables. This is useful if you have forms posting to each other or if you want to refill form fields via a URL link.',
                    'help_url' => 'http://blah',
                ),
                array (
                    "type" => "textfield",
                    "heading" => __ ( "Message text", VCFF_FORM ),
                    "param_name" => "loading_msg",
                    'admin_label' => true
                ),
                array (
                    "type" => "dropdown",
                    "heading" => __ ( "Display message as a...", VCFF_FORM ),
                    "param_name" => "display",
                    'value' => array(
                        __('Select a display mode') => '',
                        __('Hovering/Floating Message') => 'hovering',
                        __('Stationary/Fixed Message') => 'stationary'
                    )
                ),
                array (
                    "type" => "checkbox",
                    "heading" => __ ( "When to show the message", VCFF_FORM ),
                    "param_name" => "usage",
                    'value' => array(
                        __('Checking Conditions') => 'conditions',
                        __('Form Submission') => 'submission',
                        __('Form Error') => 'error',
                    )
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

vcff_map_support('VCFF_Loading_Alert');

vcff_front_enqueue_script('vcff_loading_alert', VCFF_SUPPORTS_URL.'/assets/public/vcff_loading_alert.js',array('vcff-core'));
vcff_front_enqueue_style('vcff_loading_alert', VCFF_SUPPORTS_URL.'/assets/public/vcff_loading_alert.css');