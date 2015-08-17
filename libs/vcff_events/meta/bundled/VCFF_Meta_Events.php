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