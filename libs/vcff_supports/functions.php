<?php

function vcff_map_support($mapping) {
    // Retrieve the global vcff forms class
    $vcff_supports = vcff_get_library('vcff_supports');
    
    $_type = $mapping['type'];
    
    $mapping = apply_filters('vcff_support_map',$mapping);
    
    $vcff_supports->contexts[$_type] = $mapping;
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
        $support_list[$machine_code] = array(
            'type' => $_shortcode,
            'name' => $machine_code,
            'el' => $el,
            'context' => $_context,
            'attributes' => $_attributes
        );
    }
    // Allow plugins/themes to override the default caption template.
    $support_list = apply_filters('vcff_support_post_parse', $support_list);
    // Return the field list
    return $support_list;
}