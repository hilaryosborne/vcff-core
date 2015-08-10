<?php

class VCFF_Single_File_Field {

    static $field_type = 'vcff_field_single_file';

    static $field_title = 'Single File Upload';

    static $item_class = 'VCFF_Single_File_Field_Item';
    
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
                // FIELD VALIDATION PARAMETERS
                array (
                    'type' => 'vcff_validation',
                    'heading' => false,
                    'param_name' => 'validation',
                    'group' => 'Adv. Logic',
                    'validation_rules' => array(
                        'file_upload_max_size',
                        'file_upload_extensions',
                        'file_upload_required'
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
            'validation_rules' => array(
                
                'file_upload_max_size' => array(
                    'label' => 'Maximum Filesize',
                    'description' => 'Insures the specified key value exists and is not empty',
                    'has_value' => true
                ),
                
                'file_upload_extensions' => array(
                    'label' => 'Allowed Extensions',
                    'description' => 'Insures the specified key value exists and is not empty',
                    'has_value' => true
                ),

                'file_upload_required' => array(
                    'label' => 'Required',
                    'description' => 'Insures the specified key value exists and is not empty',
                    'has_value' => false,
                    'callback' => '_Val_Required'
                )
            ));
    }
}

vcff_front_enqueue_script('vcff_field_single_file', VCFF_FIELDS_URL.'/assets/public/vcff_field_single_file.js',array('vcff-core'));

vcff_map_field('VCFF_Single_File_Field');