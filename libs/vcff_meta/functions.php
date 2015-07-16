<?php

/**
 * 
 */
function vcff_map_meta_field($class) {
    $vcff_meta = vcff_get_library('vcff_meta');
    $meta_type = $class::$meta_type;
    $meta_title = $class::$meta_title;
    $meta_class_base = $class;
    $meta_class_item = $class::$item_class;
    $meta_params = $class::Meta_Params();
    // Add the form to our list of available forms
    $vcff_meta->contexts[$meta_type] = array(
        'type' => $meta_type,
        'title' => $meta_title,
        'class_base' => $meta_class_base,
        'class_item' => $meta_class_item,
        'params' => $meta_params,
    );
}

