<?php

class VCFF_Standard_Form {

    static $form_type = 'vcff_standard_form';
    
    static $form_title = 'Standard Form';
    
    static $item_class = 'VCFF_Standard_Form_Item';
    
    static function Form_Meta() {
        // Return the required meta fields
        return array(
            // Add any custom pages for this form type
            'pages' => array(),
            // Add any custom form groups
            'groups' => array(),
            // Add any custom form fields
            'fields' => array(
            
                array(
                    'machine_code' => 'form_attributes',
                    'field_label' => 'Form Attributes',
                    'weight' => 2,
                    'field_type' => 'textfield',
                    'field_dependancy' => false,
                ),
                
                array(
                    'machine_code' => 'form_extra_class',
                    'field_label' => 'Extra Form Classes',
                    'field_type' => 'textfield',
                    'weight' => 5,
                    'field_dependancy' => array(
                        'outcome' => 'show',
                        'requires' => 'all',
                        'conditions' => array(
                            array('form_attributes','is','hello')
                        )
                    )
                )
            )
        );
    }
    
    static function Form_Settings() {
        // Return the required meta fields
        return array(
            // Add any custom pages for this form type
            'pages' => array(
            
                array(
                    'id' => 'general_settings',
                    'title' => 'General Settings',
                    'weight' => 1,
                    'description' => 'This page contains the general settings',
                    'icon' => '',
                )
            ),
            // Add any custom form groups
            'groups' => array(
            
                array(
                    'id' => 'form_settings',
                    'page_id' => 'general_settings',
                    'title' => 'General Settings',
                    'weight' => 1,
                    'description' => 'This page contains the general settings',
                    'icon' => '',
                )
            ),
            // Add any custom form fields
            'fields' => array(
            
                array(
                    'machine_code' => 'form_attributes',
                    'field_label' => 'Form Attributes',
                    'weight' => 2,
                    'field_type' => 'textfield',
                    'field_dependancy' => false,
                ),
                
                array(
                    'machine_code' => 'form_extra_class',
                    'field_label' => 'Extra Form Classes',
                    'field_type' => 'textfield',
                    'weight' => 5,
                    'field_dependancy' => array(
                        'outcome' => 'show',
                        'requires' => 'all',
                        'conditions' => array(
                            array('form_attributes','is','hello')
                        )
                    )
                )         
            )
        );
    }
    
    static function Form_Params() {
        // Return any form params
        return array();
    }
}

add_filter('vcff_meta_field_list',function($meta_fields,$form_instance){
    // Retrieve the global vcff forms class
    $vcff_forms = vcff_get_library('vcff_forms');
    // Retrieve the form class
    $form_context = $vcff_forms->contexts;
    // If no context could be found
    if (!$form_context || !is_array($form_context)) { return; }
    // Storage var
    $contexts_list = array();
    // Loop through each form context
    foreach ($form_context as $type => $context) {
        // Populate the context list
        $contexts_list[$type] = $context['title'];
    }
    // Create the form type field
    $meta_fields[] = array(
        'machine_code' => 'form_type',
        'field_label' => 'Form Type',
        'field_type' => 'select',
        'validation' => array(
            'required' => true
        ),
        'default_value' => 'vcff_standard_form',
        'weight' => 1,
        'values' => $contexts_list
    );

    return $meta_fields;
}, 15, 2);

add_filter('vcff_meta_field_list',function($meta_fields,$form_instance){
    // Create the form type field
    $meta_fields[] = array(
        'machine_code' => 'use_ajax',
        'field_label' => 'Submit Via AJAX',
        'field_type' => 'select',
        'validation' => array(
            'required' => true
        ),   
        'required' => true,
        'weight' => 2,
        'default_value' => 'yes',
        'values' => array(
            'yes' => 'Yes, Use AJAX Submission',
            'no' => 'No, Use Standard Submission'
        )
    );

    return $meta_fields;
}, 15, 2);

add_filter('vcff_settings_field_list',function($field_list, $form_instance){
    
    $field_list['form_attributes'] = array(
        'machine_code' => 'form_attributes',
        'field_label' => 'Form Attributes',
        'weight' => 2,
        'validation' => array(),
        'field_type' => 'textfield',
        'field_dependancy' => false,
    );
    
    $field_list['form_extra_class'] = array(
        'machine_code' => 'form_extra_class',
        'field_label' => 'Extra Form Classes',
        'field_type' => 'textfield',
        'weight' => 5,
        'field_dependancy' => array(
            'outcome' => 'show',
            'requires' => 'all',
            'conditions' => array(
                array('form_attributes','is','hello')
            )
        )
    );
    
    return $field_list;
},10,2);

vcff_map_form('VCFF_Standard_Form');