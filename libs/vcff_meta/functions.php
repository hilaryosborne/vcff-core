<?php

/**
 * 
 */
function vcff_map_meta_field($mapping) {
    // Retrieve the global vcff forms class
    $vcff_meta = vcff_get_library('vcff_meta');
    
    $_type = $mapping['type'];
    
    $mapping = apply_filters('vcff_meta_map',$mapping);
    
    $vcff_meta->contexts[$_type] = $mapping;
}

