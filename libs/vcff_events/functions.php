<?php

function vcff_map_action($class) {
    
    $vcff_events = vcff_get_library('vcff_events');
    // Retrieve the form code
    $type = $class::$type;
    $title = $class::$title;
    $class_base = $class;
    $class_item = $class::$class_item;
    $params = $class::Params();
    // Add the form to our list of available forms
    $vcff_events->event_actions[$type] = array(
        'type' => $type,
        'title' => $title,
        'class_base' => $class_base,
        'class_item' => $class_item,
        'params' => $params,
    ); 
}

function vcff_map_trigger($class) {
    
    $vcff_events = vcff_get_library('vcff_events');
    // Retrieve the form code
    $code = $class::$code;
    $title = $class::$title;
    $class_base = $class;
    $class_item = $class::$class_item;
    $params = $class::Params();
    // Add the form to our list of available forms
    $vcff_events->event_triggers[$code] = array(
        'code' => $code,
        'title' => $title,
        'class_base' => $class_base,
        'class_item' => $class_item,
        'params' => $params,
    ); 
}


function vcff_map_event($class) {
    
    $vcff_events = vcff_get_library('vcff_events');
    // Retrieve the form code
    $type = $class::$type;
    $title = $class::$title;
    $class_base = $class;
    $class_item = $class::$class_item;
    $params = $class::Params();
    // Add the form to our list of available forms
    $vcff_events->event_types[$type] = array(
        'type' => $type,
        'title' => $title,
        'class_base' => $class_base,
        'class_item' => $class_item,
        'params' => $params,
    ); 
}


