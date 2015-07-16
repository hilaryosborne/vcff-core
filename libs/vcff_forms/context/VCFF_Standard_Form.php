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
        return array(
            'tags' => array(       
                array('Custom Form Tag','custom_form','custom_form',function($form_instance){ })
            )
        );
    }
}
/**
add_filter('vcff_settings_page_list',function($page_list, $form_instance){
    
    $page_list['form_settings'] = array(
        'id' => 'form_settings',
        'title' => 'Form Settings',
        'weight' => 1,
        'description' => 'This page contains the general settings',
        'icon' => '',
    );
    
    return $page_list;
},10,2);

add_filter('vcff_settings_group_list',function($group_list, $form_instance){

    $group_list['form_settings'] = array(
        'id' => 'form_settings',
        'page_id' => 'general_settings',
        'title' => 'General Settings',
        'weight' => 1,
        'description' => 'This page contains the general settings',
        'icon' => '',
    );
    
    return $group_list;
},10,2);
**/
add_filter('vcff_settings_field_list',function($field_list, $form_instance){
    
    $field_list['form_attributes'] = array(
        'machine_code' => 'form_attributes',
        'field_label' => 'Form Attributes',
        'weight' => 2,
        'validation' => array('required' => true),
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