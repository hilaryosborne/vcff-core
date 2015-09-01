<?php

function vcff_map_action($mapping) {
    // Retrieve the global vcff forms class
    $vcff_events = vcff_get_library('vcff_events');
    
    $_type = $mapping['type'];
    
    $mapping = apply_filters('vcff_action_map',$mapping);
    
    $vcff_events->actions[$_type] = $mapping;
}

function vcff_map_trigger($mapping) {
    // Retrieve the global vcff forms class
    $vcff_events = vcff_get_library('vcff_events');
    
    $_type = $mapping['type'];
    
    $mapping = apply_filters('vcff_trigger_map',$mapping);
    
    $vcff_events->triggers[$_type] = $mapping;
}


function vcff_map_event($mapping) {
    // Retrieve the global vcff forms class
    $vcff_events = vcff_get_library('vcff_events');
    
    $_type = $mapping['type'];
    
    $mapping = apply_filters('vcff_event_map',$mapping);
    
    $vcff_events->events[$_type] = $mapping;
}


