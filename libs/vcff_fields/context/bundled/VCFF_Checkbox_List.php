<?php

vcff_map_field(array(
    'type' => 'vcff_list_checkbox',
    'title' => 'Checkbox List',
    'class' => 'VCFF_Checkbox_List_Item',
    'filter_logic' => array(),
    'conditional_logic' => array(
        array(
            'machine_code' => 'IS',
            'title' => 'Is',
            'callback' => 'IS',
            'description' => 'Checks that a field value does match a string',
            'value' => true
        ),
        array(
            'machine_code' => 'IS_NOT',
            'title' => 'Is Not',
            'callback' => 'IS_NOT',
            'description' => 'Checks that a field value does not match a string',
            'value' => true
        ),
        array(
            'machine_code' => 'IS_EMPTY',
            'title' => 'Is Empty',
            'callback' => 'IS_EMPTY',
            'description' => 'Checks if the field value is empty',
            'value' => false
        ),
        array(
            'machine_code' => 'GREATER_THAN',
            'title' => 'Greater Than',
            'callback' => 'GREATER_THAN',
            'description' => 'Checks if the field value is greater than a value',
            'value' => true
        ),
        array(
            'machine_code' => 'LESS_THAN',
            'title' => 'Less Than',
            'callback' => 'LESS_THAN',
            'description' => 'Checks if the field value is less than a value',
            'value' => true
        )
    ),
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
                "type" => "vcff_heading",
                "heading" => false,
                "param_name" => "options_heading",
                'html_title' => 'Field Options',
                'html_description' => 'You can set this field to accept dynamic values from either POST, GET or REQUEST variables. This is useful if you have forms posting to each other or if you want to refill form fields via a URL link.',
            ),
            array (
                'type' => 'textarea_raw_html',
                'heading' => __ ( 'Select Options', VCFF_FORM ),
                'param_name' => 'options',
                'description' => __ ( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', VCFF_FORM ) 
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
                'type' => 'vcff_validation',
                'heading' => false,
                'param_name' => 'validation',
                'group' => 'Adv. Logic',
                'validation_rules' => array(
                    'required',
                    'max_len',
                    'exact_len',
                    'alpha',
                )
            ),
            array (
                'type' => 'checkbox',
                'heading' => false,
                'param_name' => 'use_adv_validation',
                'group' => 'Adv. Logic',
                'value' => array('Use advanced validation rules' => 'yes')
            ),
            array (
                'type' => 'textfield',
                'heading' => 'Advanced Validation Rules',
                'description' => 'You may use any combination of the GUMP validation rules ie required|exact_len,15. For further information visit the <a href="https://github.com/Wixel/GUMP" target="GUMP">GUMP documentation</a>.',
                'param_name' => 'adv_validation',
                'group' => 'Adv. Logic',
                'dependency' => array(
                    'element' => 'use_adv_validation',
                    'value' => 'yes',
                )
            ),
            array (
                'type' => 'vcff_filters',
                'heading' => false,
                'param_name' => 'filter',
                'group' => 'Adv. Logic',
                'filter_rules' => array(
                    'sanitize_string',
                    'sanitize_email',
                    'basic_tags'
                )
            ),
            array (
                'type' => 'checkbox',
                'heading' => false,
                'param_name' => 'use_adv_filter',
                'group' => 'Adv. Logic',
                'value' => array('Use advanced filter rules' => 'yes')
            ),
            array (
                'type' => 'textfield',
                'heading' => 'Advanced Filter Rules',
                'description' => 'You may use any combination of the GUMP validation rules ie required|exact_len,15. For further information visit the <a href="https://github.com/Wixel/GUMP" target="GUMP">GUMP documentation</a>.',
                'param_name' => 'adv_filter',
                'group' => 'Adv. Logic',
                'dependency' => array(
                    'element' => 'use_adv_filter',
                    'value' => 'yes',
                )
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
