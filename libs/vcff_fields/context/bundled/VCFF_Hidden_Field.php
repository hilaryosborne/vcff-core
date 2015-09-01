<?php

vcff_map_field(array(
    'type' => 'vcff_hidden_field',
    'title' => 'Hidden Field',
    'class' => 'VCFF_Hidden_Field_Item',
    'filter_logic' => array(),
    'conditional_logic' => array(),
    'validation_logic' => array(),
    'vc_map' => array(
        'params' => array(
            array (
                "type" => "vcff_heading",
                "heading" => false,
                "param_name" => "field_heading",
                'html_title' => 'VCFF Fields',
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
                "param_name" => "label_heading",
                'html_title' => 'Field Labels',
                'html_description' => 'You can set this field to accept dynamic values from either POST, GET or REQUEST variables. This is useful if you have forms posting to each other or if you want to refill form fields via a URL link.',
            ),
            array (
                "type" => "textfield",
                "heading" => __ ( "Label (Data Entry)", VCFF_FORM ),
                "param_name" => "field_label",
                'value' => __('Enter a field label..'),
                'admin_label' => true,
            ),
            array (
                "type" => "textfield",
                "heading" => __ ( "Label (Data Viewing)", VCFF_FORM ),
                "param_name" => "view_label",
            ),
            array (
                "type" => "textfield",
                "heading" => __ ( "Default Value", VCFF_FORM ),
                "param_name" => "default_value",
                'group' => 'Adv. Settings',
            ),
            array (
                "type" => "textfield",
                "heading" => __ ( "Additional Attributes", VCFF_FORM ),
                "param_name" => "attributes",
                'group' => 'Adv. Settings',
            ),
            array (
                'type' => 'textfield',
                'heading' => __ ( 'Extra Class', VCFF_FORM ),
                'param_name' => 'extra_class',
                'group' => 'Adv. Settings',
            ),
            array (
                "type" => "vcff_url_vars",
                "heading" => false,
                "param_name" => "dynamically_populate",
                'group' => 'Adv. Settings',
            ),
            array (
                'type' => 'vcff_conditional',
                'heading' => false,
                'param_name' => 'conditions',
                'group' => 'Adv. Logic'
            ),
        )
    )
));
