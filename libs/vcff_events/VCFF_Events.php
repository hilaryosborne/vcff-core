<?php

if(!defined('VCFF_EVENTS_DIR'))
{ define('VCFF_EVENTS_DIR',untrailingslashit( plugin_dir_path(__FILE__ ) )); }

if (!defined('VCFF_EVENTS_URL'))
{ define('VCFF_EVENTS_URL',untrailingslashit( plugins_url( '/', __FILE__ ) )); }

class VCFF_Events {

    public $event_types = array();

    public $event_triggers = array();

    public function Init() {
        // Fire the shortcode init action
        do_action('vcff_events_before_init',$this);
        // Include the admin class
        require_once(VCFF_EVENTS_DIR.'/functions.php');
        // Load helper classes
        $this->_Load_Helpers();
		// Load the core classes
        $this->_Load_Core();
        // Load the actions
        $this->_Load_Actions();
        // Load the trigger classes
        $this->_Load_Triggers();
        // Load the core classes
        $this->_Load_Events();
        // Load the meta fields
        $this->_Load_Meta();
        // Fire the shortcode init action
        do_action('vcff_events_init',$this);
        // Include the admin class
        require_once(VCFF_EVENTS_DIR.'/VCFF_Events_Admin.php');
        // Otherwise if this is being viewed by the client 
        require_once(VCFF_EVENTS_DIR.'/VCFF_Events_Public.php');
        // Fire the shortcode init action
        do_action('vcff_events_after_init',$this);
    }

    protected function _Load_Helpers() {
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_EVENTS_DIR.'/helpers') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // Include the file
            require_once(VCFF_EVENTS_DIR.'/helpers/'.$FileInfo->getFilename());
        }
        // Fire the shortcode init action
        do_action('vcff_events_helper_init',$this);
    }
	
	protected function _Load_Core() {
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_EVENTS_DIR.'/core') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // If this is not false
            if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; }
            // Include the file
            require_once(VCFF_EVENTS_DIR.'/core/'.$FileInfo->getFilename());
        }
        // Fire the shortcode init action
        do_action('vcff_events_core_init',$this);
    }

    protected function _Load_Meta() { 
        // Add the meta events class
        add_action('init', function() {
            // Retrieve the meta
            $vcff_meta = vcff_get_library('vcff_meta');
            // Include the admin class
            require_once(VCFF_EVENTS_DIR.'/meta/VCFF_Meta_Events.php');
            // Include the admin class
            require_once(VCFF_EVENTS_DIR.'/meta/VCFF_Meta_Events_Item.php');
            // Map the meta field
            vcff_map_meta_field('VCFF_Meta_Events'); 
        });
    }
    
    protected function _Load_Actions() {
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_EVENTS_DIR.'/context/actions') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // If this is not false
            if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; }
            // Include the file
            require_once(VCFF_EVENTS_DIR.'/context/actions/'.$FileInfo->getFilename());
            // If this is not false
            if (stripos($FileInfo->getFilename(),'_Item') !== false) { continue; }
            // Retrieve the classname
            $context_classname = $FileInfo->getBasename('.php');
            // Retrieve the form code
            vcff_map_action($context_classname);
        }
        // Fire the shortcode init action
        do_action('vcff_events_actions_init',$this);
    }
    
    protected function _Load_Triggers() {
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_EVENTS_DIR.'/context/triggers') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // If this is not false
            if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; }
            // Include the file
            require_once(VCFF_EVENTS_DIR.'/context/triggers/'.$FileInfo->getFilename());
            // If this is not false
            if (stripos($FileInfo->getFilename(),'_Item') !== false) { continue; }
            // Retrieve the classname
            $context_classname = $FileInfo->getBasename('.php');
            // Retrieve the form code
            vcff_map_trigger($context_classname); 
        }
        // Fire the shortcode init action
        do_action('vcff_events_triggers_init',$this);
    }
    
    protected function _Load_Events() { 
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_EVENTS_DIR.'/context/events') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // If this is not false
            if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; }
            // Include the file
            require_once(VCFF_EVENTS_DIR.'/context/events/'.$FileInfo->getFilename());
            // If this is not false
            if (stripos($FileInfo->getFilename(),'_Item') !== false) { continue; }
            // Retrieve the classname
            $context_classname = $FileInfo->getBasename('.php');
            // Retrieve the form code
            vcff_map_event($context_classname);
        }
        // Fire the shortcode init action
        do_action('vcff_events_events_init',$this);
    }
    
    public function Load_Admin_Scripts() {
        // Register the vcff admin css
        vcff_admin_enqueue_script('vcff_events_list', VCFF_EVENTS_URL.'/assets/admin/vcff_events_list.js',array('vcff-core'));
        // Register the vcff admin css
        vcff_admin_enqueue_script('vcff_events_form', VCFF_EVENTS_URL.'/assets/admin/vcff_events_form.js',array('vcff-core'));
    }

    public function Load_Public_Scripts() {

    }

}

$vcff_events = new VCFF_Events();

vcff_register_library('vcff_events',$vcff_events);

$vcff_events->Init();