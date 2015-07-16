<?php

function vcff_map_support($class) {
    // Retrieve the global vcff forms class
    $vcff_supports = vcff_get_library('vcff_supports');
    // Retrieve the form code
    $type = $class::$type;
    $title = $class::$title;
    $class_base = $class;
    $class_item = $class::$item_class;
    $vc = $class::VC_Params();
    $params = $class::Params();
    // Add the form to our list of available forms
    $vcff_supports->contexts[$type] = array(
        'type' => $type,
        'title' => $title,
        'class_base' => $class_base,
        'class_item' => $class_item,
        'vc' => $vc,
        'params' => $params
    );
}


function vcff_parse_support_data($text) {
    // Retrieve the global vcff forms class
    $vcff_supports = vcff_get_library('vcff_supports');
    // Retrieve the field context list
    $contexts = $vcff_supports->contexts;
    // Our field list
    $support_list = array(); 
    // Allow plugins/themes to override the default caption template.
    $text = apply_filters('vcff_support_pre_parse', $text);
    // Extract all of the shortcodes from the content
    preg_match_all('/\[([^}]*?)\]/', $text, $support_matches);
    // Loop through each of the field matches
    foreach ($support_matches[1] as $k => $string) {
        // If the first character is an ending
        if (strpos($string,'/') === 0) { continue; }
        // Retrieve the shortcode
        $support_type = strtok($string, " ");
        // If no field handler was returned
        if (!isset($contexts[$support_type])) { continue; }
        // Retrieve the field shortcode
        $context = $contexts[$support_type];
        // If no context could be found
        if (!$context) { continue; }
        // Look for all attribute matches
        preg_match_all('/(\w+)\s*=\s*"(.*?)"/', $string, $attribute_matches);
        // Start the attribute list
        $attributes = array();
        // Loop through each attribute match
        foreach ($attribute_matches[1] as $_k => $_attr) {
            // Populate the attributes list
            $attributes[$_attr] = $attribute_matches[2][$_k];
        }
        // Retrieve the support name
        $machine_code = $attributes['machine_code'];
        // If no machine name could be found
        if (!$machine_code) { continue; }
        // Populate the field data
        $support_list[$machine_code] = array(
            'name' => $machine_code,
            'type' => $support_type,
            'context' => $context,
            'attributes' => $attributes
        );
    } 
    // Allow plugins/themes to override the default caption template.
    $support_list = apply_filters('vcff_support_post_parse', $support_list);
    // Return the field list
    return $support_list;
}