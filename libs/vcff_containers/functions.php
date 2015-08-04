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
    // Create a new parser
    $blq_parser = new BLQ_Parser($text);
    // Retrieve a list of shortcodes
    $_shortcodes = $blq_parser
        ->Set_Ends('[',']')
        ->Parse()
        ->Get_Flattened();
    // If no shortcodes were returned
    if (!$_shortcodes || !is_array($_shortcodes)) { return; }
    // Loop through each shortcode
    foreach ($_shortcodes as $k => $el) {
        // If this is not a tag
        if (!$el->is_tag || !$el->tag) { continue; }
        // If this is not a tag
        $_shortcode = $el->tag;
        // If no field handler was returned
        if (!isset($contexts[$_shortcode])) { continue; }
        // Retrieve the field shortcode
        $_context = $contexts[$_shortcode];
        // Retrieve the attributes
        $_attributes = $el->attributes;
        // Retrieve the machine code
        $machine_code = $_attributes['machine_code'];
        // Populate the field data
        $container_list[$machine_code] = array(
            'type' => $_shortcode,
            'name' => $machine_code,
            //'el' => $el,
            'context' => $_context,
            'attributes' => $_attributes
        );
    }
    // Allow plugins/themes to override the default caption template.
    $container_list = apply_filters('vcff_container_post_parse', $container_list);
    // Return the container list
    return $container_list;
}