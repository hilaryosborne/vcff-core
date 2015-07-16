<?php

class VCFF_reCAPTCHA_Field {
    
    static $field_type = 'vcff_recaptcha_field';
    
    static $field_title = 'reCAPTCHA';

    static $item_class = 'VCFF_reCAPTCHA_Field_Item';
    
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
                    'type' => 'textfield',
                    'heading' => __ ( 'Extra Class', VCFF_FORM ),
                    'param_name' => 'extra_class',
                    'group' => 'Adv. Settings',
                ),
                // VC CSS EDITOR
                array(
                    'type' => 'css_editor',
                    'heading' => __('CSS',VCFF_FORM),
                    'param_name' => 'css',
                    'group' => __('Design Options',VCFF_FORM),
                ),
            )
            // ADVANCED SETTING
        );
    }
    
    static function Field_Params() {
        // Return any field params
        return array();
    }
}

// Register the vcff admin css
vcff_front_enqueue_script('recaptcha', 'https://www.google.com/recaptcha/api.js');

add_filter('vcff_settings_group_list',function($group_list, $form_instance){
    
    $group_list[] = array(
        'id' => 'recaptcha_config',
        'title' => 'reCAPTCHA Configuration',
        'weight' => 5,
        'hint_html' => '<h4><strong>Instructions</strong></h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur cursus erat at lectus commodo tempor eget vel turpis. Praesent vitae eros semper, aliquet ipsum vel, porttitor tellus.</p>',
        'help_url' => 'http://vcff.theblockquote.com',
    );
    
    return $group_list;
    
},0,2);

add_filter('vcff_settings_field_list',function($field_list, $form_instance){
    
    $field_list[] = array(
        'machine_code' => 'recaptcha_site_key',
        'field_label' => 'Site Key',
        'field_group' => 'recaptcha_config',
        'weight' => 1,
        'field_type' => 'textfield',
        'field_dependancy' => false
    );
    
    $field_list[] = array(
        'machine_code' => 'recaptcha_secret_key',
        'field_label' => 'Secret Key',
        'field_group' => 'recaptcha_config',
        'weight' => 2,
        'field_type' => 'textfield',
        'field_dependancy' => false
    );
    
    return $field_list;
    
},0,2);