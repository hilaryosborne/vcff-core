<?php

class VCFF_Meta_Events {

    static $meta_type = 'events_wizard';
    
    static $meta_title = 'Submission Events Wizard';

    static $item_class = 'VCFF_Meta_Events_Item';

    static function Meta_Params() {
        // Return any field params
        return array();
    }
}

vcff_map_meta_field('VCFF_Meta_Events');

// Register the vcff admin css
vcff_admin_enqueue_script('events_wizard', VCFF_EVENTS_URL.'/assets/admin/events_wizard.js', array('vcff-core'));
// Register the vcff admin css
vcff_admin_enqueue_style('events_wizard', VCFF_EVENTS_URL.'/assets/admin/events_wizard.css');

add_filter('vcff_meta_page_list',function($meta_pages,$form_instance){
    
    $meta_pages[] = array(
        'id' => 'events',
        'title' => 'Events',
        'weight' => 10,
        'description' => 'This new page contains some settings',
        'icon' => '',
    );

    return $meta_pages;
}, 15, 2);

add_filter('vcff_meta_group_list',function($meta_groups,$form_instance){
    
    $meta_groups[] = array(
        'id' => 'submission_events',
        'page_id' => 'events',
        'title' => 'Submission Events',
        'weight' => 2,
        'hint_html' => '<h4><strong>Event Management</strong></h4><p>A form event is an action which takes place either while submitting a form, validating a form\'s input or checking a form\'s conditional rules. To create a form event click the create new event button. To modify an existing event click on the event name in the table to the left.</p>',
        'help_url' => 'http://vcff.theblockquote.com',
    );

    return $meta_groups;
}, 15, 2);

add_filter('vcff_meta_field_list',function($meta_fields,$form_instance){

    $meta_fields[] = array(
        'machine_code' => 'events_wizard',
        'field_label' => 'Submission Events',
        'field_group' => 'submission_events',
        'field_type' => 'events_wizard',
        'field_dependancy' => false
    );

    return $meta_fields;
}, 15, 2);