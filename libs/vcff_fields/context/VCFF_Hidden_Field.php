<?php

class VCFF_Hidden_Field {
    
    static $field_type = 'vcff_hidden_field';
    
    static $field_title = 'Hidden Field';

    static $item_class = 'VCFF_Hidden_Field_Item';

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
                // FIELD CONDITIONAL PARAMETERS
                array (
                    'type' => 'vcff_conditional',
                    'heading' => false,
                    'param_name' => 'conditions',
                    'group' => 'Adv. Logic'
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