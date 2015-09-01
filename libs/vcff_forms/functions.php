<?php

function vcff_map_form($mapping) { 

    // Retrieve the global vcff forms class
    $vcff_forms = vcff_get_library('vcff_forms');

    $_type = $mapping['type'];

    $mapping = apply_filters('vcff_form_map',$mapping);

    $vcff_forms->contexts[$_type] = $mapping;
}

function vcff_get_uuid_by_form($form_id,$generate=false) {
    // Retrieve the form type from meta
    $meta_form_uuid = get_post_meta($form_id, 'form_uuid', true );

    if (!$meta_form_uuid && $generate) { 
        // Generate a new unique id
        $meta_form_uuid = uniqid();
        // Update the form with a unique id
        update_post_meta($form_id, 'form_uuid', $meta_form_uuid);
    } 
    
    return $meta_form_uuid;
}

function vcff_get_type_by_form($form_uuid) {
        
    global $wpdb;

    $raw_meta = $wpdb->get_row($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'form_uuid' AND meta_value = '%s';",$uuid));
       
    if (!is_object($raw_meta) || !isset($raw_meta->post_id) || !$raw_meta->post_id) { return 'vcff_standard_form'; }
    
    $form_type = get_post_meta($raw_meta->post_id,'form_type',true);
    
    return $form_type ? $form_type : 'vcff_standard_form' ;
}

function vcff_get_form_id_by_uuid($uuid) {

    global $wpdb;

    $raw_meta = $wpdb->get_row($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'form_uuid' AND meta_value = '%s';",$uuid));
       
    if (!is_object($raw_meta) || !isset($raw_meta->post_id) || !$raw_meta->post_id) { return false; }
    
    return $raw_meta->post_id ;
}

function vcff_get_form_by_uuid($uuid) {

    global $wpdb;

    $raw_meta = $wpdb->get_row($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'form_uuid' AND meta_value = '%s';",$uuid));
    
    if (!is_object($raw_meta) || !isset($raw_meta->post_id) || !$raw_meta->post_id) { return false; }

    return get_post($raw_meta->post_id);
}
