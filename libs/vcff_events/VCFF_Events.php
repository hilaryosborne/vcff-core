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
        // Initalize core logic
        add_action('vcff_init_core',array($this,'__Init_Core'),15);
        // Initalize context logic
        add_action('vcff_init_context',array($this,'__Init_Context'),15);
        // Initalize misc logic
        add_action('vcff_init_misc',array($this,'__Init_Misc'),15);
        // Fire the shortcode init action
        do_action('vcff_events_init',$this);
        // Include the admin class
        require_once(VCFF_EVENTS_DIR.'/VCFF_Events_Admin.php');
        // Otherwise if this is being viewed by the client 
        require_once(VCFF_EVENTS_DIR.'/VCFF_Events_Public.php');
        // Fire the shortcode init action
        do_action('vcff_events_after_init',$this);
    }

    public function __Init_Core() {
        // Load helper classes
        $this->_Load_Helpers();
        // Load the core classes
        $this->_Load_Core(); 
        // Fire the shortcode init action
        do_action('vcff_events_init_core',$this);
    }

    public function __Init_Context() {
        // Load the context classes
        $this->_Load_Context();
        // Fire the shortcode init action
        do_action('vcff_events_init_context',$this);
    }
    
    public function __Init_Misc() {
        // Load the meta fields
        $this->_Load_Meta();
        // Load the pages
        $this->_Load_Pages();
        // Load AJAX
        $this->_Load_AJAX();
        // Fire the shortcode init action
        do_action('vcff_events_init_misc',$this);
    }

    protected function _Load_Helpers() {
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Recurssively load the directory
        $this->_Recusive_Load_Dir($dir.'/helpers');
        // Fire the shortcode init action
        do_action('vcff_events_helper_init',$this);
    }
	
	protected function _Load_Core() {
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Recurssively load the directory
        $this->_Recusive_Load_Dir($dir.'/core');
        // Fire the shortcode init action
        do_action('vcff_events_core_init',$this);
    }

    protected function _Load_Meta() { 
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Recurssively load the directory
        $this->_Recusive_Load_Dir($dir.'/meta');
    }
    
    protected function _Load_Context() {
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Recurssively load the directory
        $this->_Recusive_Load_Dir($dir.'/context');
        // Fire the shortcode init action
        do_action('vcff_events_context_init',$this);
    }
    
    protected function _Load_Pages() { 
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Recurssively load the directory
        $this->_Recusive_Load_Dir($dir.'/pages');
        // Fire the shortcode init action
        do_action('vcff_events_pages_init',$this);
    }
    
    protected function _Load_AJAX() {
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Recurssively load the directory
        $this->_Recusive_Load_Dir($dir.'/ajax');
        // Fire the shortcode init action
        do_action('vcff_events_ajax_init',$this);
    }
    
    protected function _Recusive_Load_Dir($dir) {
        // If the directory doesn't exist
        if (!is_dir($dir)) { return; }
        // Load each of the field shortcodes
        foreach (new DirectoryIterator($dir) as $FileInfo) {
            // If this is a directory dot
            if ($FileInfo->isDot()) { continue; }
            // If this is a directory
            if ($FileInfo->isDir()) { 
                // Load the directory
                $this->_Recusive_Load_Dir($FileInfo->getPathname());
            } // Otherwise load the file
            else {
                // If this is not false
                if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; } 
                // If this is not false
                if (stripos($FileInfo->getFilename(),'.php') === false) { continue; } 
                // Include the file
                require_once($FileInfo->getPathname());
            }
        }
    }

}

$vcff_events = new VCFF_Events();

vcff_register_library('vcff_events',$vcff_events);

$vcff_events->Init();

// Register the vcff admin css
vcff_admin_enqueue_script('vcff_events_list', VCFF_EVENTS_URL.'/assets/admin/vcff_events_list.js',array('vcff-core'));
// Register the vcff admin css
vcff_admin_enqueue_script('vcff_events_form', VCFF_EVENTS_URL.'/assets/admin/vcff_events_form.js',array('vcff-core'));