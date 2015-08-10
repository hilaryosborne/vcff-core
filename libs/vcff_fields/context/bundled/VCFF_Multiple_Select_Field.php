<?php

class VCFF_Multiple_Select_Field {

    static $field_type = 'vcff_field_multiple_select';

    static $field_title = 'Mulitple Select';

    static $item_class = 'VCFF_Multiple_Select_Field_Item';

    static $is_context = true;

    static function Field_Settings() {
        // Return the required meta fields
        return array(
            // Add any custom pages for this form type
            'pages' => array(),
            // Add any custom form groups
            'groups' => array(),
            // Add any custom form fields
            'fields' => array()
        );
    }

    static function VC_Params() {
        // Return any visual composer params
        return array(
            'params' => array(
                // CORE FIELD SETTINGS
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
                    'value' => __('Enter a field label..'),
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
                // ADVANCED SETTING
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
                // FIELD VALIDATION PARAMETERS
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
                // FIELD FILTER PARAMETERS
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
                // FIELD CONDITIONAL PARAMETERS
                array (
                    'type' => 'vcff_conditional',
                    'heading' => false,
                    'param_name' => 'conditions',
                    'group' => 'Adv. Logic'
                ),
                // VC CSS EDITOR
                array(
                    'type' => 'css_editor',
                    'heading' => __('CSS',VCFF_FORM),
                    'param_name' => 'css',
                    'group' => __('Design Options',VCFF_FORM),
                ),
            )
        );
    }

    static function Field_Params() {
        // Return any field params
        return array(
            'allowed_conditions' => array(
                'IS' => 'Is',
                'IS_NOT' => 'Is Not',
                'GREATER_THAN' => 'Greater Than',
                'LESS_THAN' => 'Less Than',
                'CONTAINS' => 'Contains',
                'STARTS_WITH' => 'Starts With',
                'ENDS_WITH' => 'Ends With'
            )
        );
    }
}

vcff_map_field('VCFF_Multiple_Select_Field');