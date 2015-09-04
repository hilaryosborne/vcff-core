<?php

vcff_map_field(array(
    'type' => 'vcff_field_single_file',
    'title' => 'Single File Upload',
    'class' => 'VCFF_Single_File_Field_Item',
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

    ),
    'validation_logic' => array(
        array(
            'machine_code' => 'MAX_UPLOAD_SIZE',
            'title' => 'Maximum Filesize',
            'callback' => '_MAX_UPLOAD_SIZE',
            'description' => 'Insures the specified key value exists and is not empty',
            'value' => true,
            'gump_code' => false,
        ),
        array(
            'machine_code' => 'ALLOWED_EXTENSIONS',
            'title' => 'Allowed Extensions',
            'callback' => '_ALLOWED_EXTENSIONS',
            'description' => 'Insures the specified key value exists and is not empty',
            'value' => true,
            'gump_code' => false,
        ),
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
            // CORE FIELD SETTINGS
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
                'admin_label' => true,
            ),
            array (
                "type" => "textfield",
                "heading" => __ ( "Label (Data Viewing)", VCFF_FORM ),
                "param_name" => "view_label",
            ),
            // ADVANCED SETTING
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
            // FIELD VALIDATION PARAMETERS
            array (
                'type' => 'vcff_filters',
                'heading' => false,
                'param_name' => 'filter',
                'group' => 'Adv. Logic',
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

vcff_front_enqueue_script('vcff_field_single_file', VCFF_FIELDS_URL.'/assets/public/vcff_field_single_file.js',array('vcff-core'));