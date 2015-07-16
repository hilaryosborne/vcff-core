<?php

function vcff_map_setting($class) {
    // Retrieve the global vcff forms class
    $vcff_settings = vcff_get_library('vcff_settings');
    // Retrieve the form code
    $type = $class::$type;
    // Add the form to our list of available forms
    $vcff_settings->contexts[$type] = array(
        'type' => $type,
        'title' => $class::$title,
        'class_base' => $class,
        'class_item' => $class::$item_class,
        'params' => $class::Params(),
    );
}


function vcff_get_setting($code) {
    // Create a new form instance
    $form_instance = new VCFF_Settings_Form();
    // Create a new populator helper
    $settings_helper_populator = new VCFF_Settings_Helper_Populator();
    // Setup the helper populator
    $settings_helper_populator
        ->Set_Form_Instance($form_instance)
        ->Populate()
        ->Check_Conditions()
        ->Check_Validation(); 
    // Retrieve the field instance
    return $form_instance->Get_Field($code);
}

function vcff_get_setting_value($code) {
    // Create a new form instance
    $field_instance = vcff_get_setting($code);
    // If no field instance, return out
    if (!is_object($field_instance)) { return; }
    // Retrieve the field instance
    return $field_instance->Get_Value();
}


function vcff_settings_get($code) {
    
}