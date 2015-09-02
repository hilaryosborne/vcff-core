<?php

vcff_map_field(array(
    'type' => 'vcff_date_field',
    'title' => 'Date Field',
    'class' => 'VCFF_Date_Field_Item',
    'filter_logic' => array(
        array(
            'machine_code' => 'SANITZE_STRING',
            'title' => 'Sanitize String',
            'callback' => false,
            'description' => 'Remove script tags and encode HTML entities',
            'gump_code' => 'sanitize_string',
        ),
    ),
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
    'validation_logic' => array(
        array(
            'machine_code' => 'REQUIRED',
            'title' => 'Required Field',
            'callback' => false,
            'description' => 'Insures the specified key value exists and is not empty',
            'value' => false,
            'gump_code' => 'required',
        ),
    ),
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
                "param_name" => "date_config",
                'html_title' => 'Date Settings',
                'html_description' => 'You can set this field to accept dynamic values from either POST, GET or REQUEST variables. This is useful if you have forms posting to each other or if you want to refill form fields via a URL link.',
            ),
            array (
                "type" => "dropdown",
                "heading" => __ ( "Display Mode", VCFF_FORM ),
                "param_name" => "display_mode",
                "value" => array(
                    'Please select a display mode' => '',
                    'Select Boxes' => 'select_el',
                    'HTML5 Date Input' => 'html5_el'
                )
            ),
            array (
                "type" => "textfield",
                "heading" => __ ( "Min date", VCFF_FORM ),
                "param_name" => "min_date",
                "description" => 'Must be in ISO 8601 formate YYYY-MM-DD (ie 1979-12-31)'
            ),
            array (
                "type" => "textfield",
                "heading" => __ ( "Max date", VCFF_FORM ),
                "param_name" => "max_date",
                "description" => 'Must be in ISO 8601 formate YYYY-MM-DD (ie 1979-12-31)'
            ),
            array (
                "type" => "textfield",
                "heading" => __ ( "Output Format", VCFF_FORM ),
                "param_name" => "output_format",
                "value" => 'Y-m-d',
                "description" => 'ISO 8601 = Y-m-d, m/d/y for US dates and d-m-y for European'
            ),
            array (
                "type" => "vcff_heading",
                "heading" => false,
                "param_name" => "advanced_label",
                'html_title' => 'Advanced Settings',
                'group' => 'Adv. Settings',
                'html_description' => 'Fields may have a set of advanced configurable options which allow you to better configure a field to behave differently within the form. Examples of these may be to add a placeholder text field or to add a default value. More advanced fields may have significantly more advanced settings. These are generally optional.',
            ),
            array (
                "type" => "textfield",
                "heading" => __ ( "Placeholder", VCFF_FORM ),
                "param_name" => "placeholder",
                'group' => 'Adv. Settings',
            ),
            array (
                "type" => "textfield",
                "heading" => __ ( "Default Value", VCFF_FORM ),
                "param_name" => "default_value",
                'group' => 'Adv. Settings',
            ),
            array (
                "type" => "vcff_heading",
                "heading" => false,
                "param_name" => "element_label",
                'html_title' => 'Element Settings',
                'group' => 'Adv. Settings',
                'html_description' => 'Use the following options to configure the field element. These options will help you add information such as element attributes or add a custom CSS class.',
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
                "type" => "vcff_heading",
                "heading" => false,
                "param_name" => "dynamic_pop_label",
                'html_title' => 'Dynamic Population',
                'group' => 'Adv. Settings',
                'html_description' => 'You can set this field to accept dynamic values from either POST, GET or REQUEST variables. This is useful if you have forms posting to each other or if you want to refill form fields via a URL link.',
            ),
            array (
                "type" => "vcff_url_vars",
                "heading" => false,
                "param_name" => "dynamically_populate",
                'group' => 'Adv. Settings',
            ),
            array (
                'type' => 'vcff_filters',
                'heading' => false,
                'param_name' => 'filter',
                'group' => 'Adv. Logic',
                'filter_rules' => array(
                    'sanitize_string',
                    'sanitize_email',
                    'basic_tags',
                    'base64_encode'
                )
            ),
            array (
                'type' => 'checkbox',
                'heading' => false,
                'param_name' => 'use_adv_filter',
                'group' => 'Adv. Logic',
                'value' => array('Use advanced GUMP filter rules' => 'yes')
            ),
            array (
                'type' => 'textfield',
                'heading' => 'Advanced Filter Rules',
                'description' => 'You may use any combination of the GUMP filter rules ie trim|sanitize_string. For further information visit the <a href="https://github.com/Wixel/GUMP" target="GUMP">GUMP documentation</a>.',
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
                'value' => array('Use advanced GUMP validation rules' => 'yes')
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
            // VC CSS EDITOR
            array(
                'type' => 'css_editor',
                'heading' => __('CSS',VCFF_FORM),
                'param_name' => 'css',
                'group' => __('Design Options',VCFF_FORM),
            ),
        )
    )
));
