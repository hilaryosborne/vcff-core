<?php

function vcff_map_field($class) {
    // Retrieve the global vcff forms class
    $vcff_fields = vcff_get_library('vcff_fields');
    // Retrieve the form code
    $field_type = $class::$field_type;
    $field_title = $class::$field_title;
    $field_class_base = $class;
    $field_class_item = $class::$item_class;
    $field_vc = $class::VC_Params();
    $field_settings = $class::Field_Settings();
    $field_params = $class::Field_Params();
    // Add the form to our list of available forms
    $vcff_fields->contexts[$field_type] = array(
        'type' => $field_type,
        'title' => $field_title,
        'class_base' => $field_class_base,
        'class_item' => $field_class_item,
        'vc' => $field_vc,
        'settings' => $field_settings,
        'params' => $field_params
    );
}


function vcff_parse_field_data($text) {
    // Retrieve the global vcff forms class
    $vcff_fields = vcff_get_library('vcff_fields');
    // Retrieve the field context list
    $field_contexts = $vcff_fields->contexts;
    // Our field list
    $field_list = array(); 
    // Allow plugins/themes to override the default caption template.
    $text = apply_filters('vcff_field_pre_parse', $text);
    // Extract all of the shortcodes from the content
    preg_match_all('/\[([^}]*?)\]/', $text, $field_matches); 
    // Loop through each of the field matches
    foreach ($field_matches[1] as $k => $string) {
        // If the first character is an ending
        if (strpos($string,'/') === 0) { continue; }
        // Retrieve the shortcode
        $field_type = strtok($string, " ");
        // If no field handler was returned
        if (!isset($field_contexts[$field_type])) { continue; }
        // Retrieve the field shortcode
        $field_context = $field_contexts[$field_type];
        // Look for all attribute matches
        preg_match_all('/(\w+)\s*=\s*"(.*?)"/', $string, $attribute_matches);
        // Start the attribute list
        $attributes = array();
        // Loop through each attribute match
        foreach ($attribute_matches[1] as $_k => $_attr) {
            // Populate the attributes list
            $attributes[$_attr] = $attribute_matches[2][$_k];
        }
        // Populate the field data
        $field_list[] = array(
            'type' => $field_type,
            'name' => $attributes['machine_code'],
            'label' => $attributes['field_label'],
            'context' => $field_context,
            'attributes' => $attributes
        );
    } 
    // Allow plugins/themes to override the default caption template.
    $field_list = apply_filters('vcff_field_post_parse', $field_list);
    // Return the field list
    return $field_list;
}