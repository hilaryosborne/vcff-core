<?php

function vcff_map_container($class) {
    // Retrieve the global vcff forms class
    $vcff_containers = vcff_get_library('vcff_containers');
    // Retrieve the form code
    $container_type = $class::$container_type;
    $container_title = $class::$container_title;
    $container_base_class = $class;
    $container_item_class = $class::$item_class;
    $container_vc = $class::VC_Params();
    $container_params = $class::Container_Params();
    // Add the form to our list of available forms
    $vcff_containers->contexts[$container_type] = array(
        'type' => $container_type,
        'title' => $container_title,
        'class_base' => $container_base_class,
        'class_item' => $container_item_class,
        'vc' => $container_vc,
        'params' => $container_params
    );
}

function vcff_parse_container_data($text) {
    // Retrieve the global vcff forms class
    $vcff_containers = vcff_get_library('vcff_containers');
    // Retrieve the field context list
    $contexts = $vcff_containers->contexts;
    // Our field list
    $container_list = array(); 
    // Allow plugins/themes to override the default caption template.
    $text = apply_filters('vcff_container_pre_parse', $text);
    // Loop through each context
    foreach ($contexts as $type => $context) { 
        // Extract all of the shortcodes from the content 
        preg_match_all('/\['.$type.' (.*?)\](.*?)\[\/'.$type.'\]/s', $text, $_matches);
        // Loop through each of the field matches
        foreach ($_matches[1] as $k => $shortcode) {
            // Look for all attribute matches
            preg_match_all('/(\w+)\s*=\s*"(.*?)"/', $_matches[1][$k], $attribute_matches);
            // Start the attribute list
            $attributes = array();
            // Loop through each attribute match
            foreach ($attribute_matches[1] as $_k => $_attr) {
                // Populate the attributes list
                $attributes[$_attr] = $attribute_matches[2][$_k];
            } 
            // Populate the container data
            $container_list[] = array(
                'type' => $type,
                'context' => $context,
                'content' => $_matches[2][$k],
                'attributes' => $attributes,
            );
        }
    } 
    // Allow plugins/themes to override the default caption template.
    $container_list = apply_filters('vcff_container_post_parse', $container_list);
    // Return the container list
    return $container_list;
}