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
        // Load the context classes
        $this->_Load_Context();
        // Load the pages
        $this->_Load_Pages();
        // Load AJAX
        $this->_Load_AJAX();
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
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Load each of the form shortcodes
        foreach (new DirectoryIterator($dir.'/helpers') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // If this is not false
            if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; } 
            // Include the file
            require_once($FileInfo->getPathname());
        }
        // Fire the shortcode init action
        do_action('vcff_events_helper_init',$this);
    }
	
	protected function _Load_Core() {
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Load each of the form shortcodes
        foreach (new DirectoryIterator($dir.'/core') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // If this is not false
            if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; } 
            // Include the file
            require_once($FileInfo->getPathname());
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
    
    protected function _Load_Context() {
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Load each of the field shortcodes
        foreach (new DirectoryIterator($dir.'/context') as $FileInfo) { 
            // If this is a directory dot
            if ($FileInfo->isDot()) { continue; }
            // If this is a directory
            if ($FileInfo->isDir()) { 
                // Load each of the field shortcodes
                foreach (new DirectoryIterator($FileInfo->getPathname()) as $_FileInfo) {
                    // If this is a directory dot
                    if ($_FileInfo->isDot()) { continue; }
                    // If this is a directory
                    if ($_FileInfo->isDir()) { continue; }
                    // If this is not false
                    if (stripos($_FileInfo->getFilename(),'.tpl') !== false) { continue; } 
                    // Include the file
                    require_once($_FileInfo->getPathname());
                }
            } // Otherwise this is just a file
            else {
                // If this is not false
                if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; } 
                // Include the file
                require_once($FileInfo->getPathname());
            }
        }
        // Fire the shortcode init action
        do_action('vcff_events_context_init',$this);
    }
    
    protected function _Load_Pages() { 
        // Retrieve the context director
        $dir = untrailingslashit(plugin_dir_path(__FILE__));
        // Load each of the field shortcodes
        foreach (new DirectoryIterator($dir.'/pages') as $FileInfo) { 
            // If this is a directory dot
            if ($FileInfo->isDot()) { continue; }
            // If this is a directory
            if ($FileInfo->isDir()) { 
                // Load each of the field shortcodes
                foreach (new DirectoryIterator($FileInfo->getPathname()) as $_FileInfo) {
                    // If this is a directory dot
                    if ($_FileInfo->isDot()) { continue; }
                    // If this is a directory
                    if ($_FileInfo->isDir()) { continue; }
                    // If this is not false
                    if (stripos($_FileInfo->getFilename(),'.tpl') !== false || stripos($FileInfo->getFilename(),'.txt') !== false) { continue; } 
                    // Include the file
                    require_once($_FileInfo->getPathname());
                }
            } // Otherwise this is just a file
            else {
                // If this is not false
                if (stripos($FileInfo->getFilename(),'.tpl') !== false || stripos($FileInfo->getFilename(),'.txt') !== false) { continue; } 
                // Include the file
                require_once($FileInfo->getPathname());
            }
        }
        // Fire the shortcode init action
        do_action('vcff_events_pages_init',$this);
    }
    
    protected function _Load_AJAX() {
        // Retrieve the context director
        $dir = untrailingslashit(plugin_dir_path(__FILE__));
        // Load each of the field shortcodes
        foreach (new DirectoryIterator($dir.'/ajax') as $FileInfo) { 
            // If this is a directory dot
            if ($FileInfo->isDot()) { continue; }
            // If this is a directory
            if ($FileInfo->isDir()) { 
                // Load each of the field shortcodes
                foreach (new DirectoryIterator($FileInfo->getPathname()) as $_FileInfo) {
                    // If this is a directory dot
                    if ($_FileInfo->isDot()) { continue; }
                    // If this is a directory
                    if ($_FileInfo->isDir()) { continue; }
                    // If this is not false
                    if (stripos($_FileInfo->getFilename(),'.tpl') !== false || stripos($FileInfo->getFilename(),'.txt') !== false) { continue; } 
                    // Include the file
                    require_once($_FileInfo->getPathname());
                }
            } // Otherwise this is just a file
            else {
                // If this is not false
                if (stripos($FileInfo->getFilename(),'.tpl') !== false || stripos($FileInfo->getFilename(),'.txt') !== false) { continue; } 
                // Include the file
                require_once($FileInfo->getPathname());
            }
        }
        // Fire the shortcode init action
        do_action('vcff_events_ajax_init',$this);
    }

}

$vcff_events = new VCFF_Events();

vcff_register_library('vcff_events',$vcff_events);

$vcff_events->Init();

// Register the vcff admin css
vcff_admin_enqueue_script('vcff_events_list', VCFF_EVENTS_URL.'/assets/admin/vcff_events_list.js',array('vcff-core'));
// Register the vcff admin css
vcff_admin_enqueue_script('vcff_events_form', VCFF_EVENTS_URL.'/assets/admin/vcff_events_form.js',array('vcff-core'));