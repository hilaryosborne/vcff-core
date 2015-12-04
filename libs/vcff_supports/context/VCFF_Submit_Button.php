<?php

vcff_map_support(array(
    'type' => 'vcff_submit_btn',
    'title' => 'Submit Button',
    'class' => 'VCFF_Submit_Button_Item',
    'filter_logic' => array(),
    'conditional_logic' => array(),
    'validation_logic' => array(),
    'vc_map' => array(
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
            array (
                'type' => 'textfield',
                'heading' => __ ( 'Element Extra Class', VCFF_FORM ),
                'param_name' => 'el_extra_class',
            ),
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
    )
));
