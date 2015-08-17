<?php

if(!defined('VCFF_META_DIR'))
{ define('VCFF_META_DIR',untrailingslashit( plugin_dir_path(__FILE__ ) )); }

if (!defined('VCFF_META_URL'))
{ define('VCFF_META_URL',untrailingslashit( plugins_url( '/', __FILE__ ) )); }


class VCFF_Meta {

    public $contexts = array();

    public function Init() {
        // Fire the shortcode init action
        do_action('vcff_meta_before_init',$this);
        // Include the admin class
        require_once(VCFF_META_DIR.'/functions.php');
        // Initalize core logic
        add_action('vcff_init_core',array($this,'__Init_Core'),20);
        // Initalize context logic
        add_action('vcff_init_context',array($this,'__Init_Context'),20);
        // Initalize misc logic
        add_action('vcff_init_misc',array($this,'__Init_Misc'),20);
        // Fire the shortcode init action
        do_action('vcff_meta_init',$this);
        // Include the admin class
        require_once(VCFF_META_DIR.'/VCFF_Meta_Admin.php');
        // Include the public class
        require_once(VCFF_META_DIR.'/VCFF_Meta_Public.php');
        // Fire the shortcode init action
        do_action('vcff_meta_after_init',$this);
    }
    
    public function __Init_Core() {
        // Load helper classes
        $this->_Load_Helpers();
        // Load the core classes
        $this->_Load_Core(); 
        // Fire the shortcode init action
        do_action('vcff_meta_init_core',$this);
    }

    public function __Init_Context() {
        // Load the context classes
        $this->_Load_Context();
        // Fire the shortcode init action
        do_action('vcff_meta_init_context',$this);
    }
    
    public function __Init_Misc() {
        // Load the pages
        $this->_Load_Pages();
        // Load AJAX
        $this->_Load_AJAX();
        // Fire the shortcode init action
        do_action('vcff_meta_init_misc',$this);
    }

    protected function _Load_Helpers() {
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Recurssively load the directory
        $this->_Recusive_Load_Dir($dir.'/helpers');
        // Fire the shortcode init action
        do_action('vcff_meta_helper_init',$this);
    }

    protected function _Load_Core() {
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Recurssively load the directory
        $this->_Recusive_Load_Dir($dir.'/core');
        // Fire the shortcode init action
        do_action('vcff_meta_core_init',$this);
    }

    protected function _Load_Context() {
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Recurssively load the directory
        $this->_Recusive_Load_Dir($dir.'/context');
        // Fire the shortcode init action
        do_action('vcff_meta_context_init',$this);
    }
    
    protected function _Load_Pages() { 
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Recurssively load the directory
        $this->_Recusive_Load_Dir($dir.'/pages');
        // Fire the shortcode init action
        do_action('vcff_meta_pages_init',$this);
    }
    
    protected function _Load_AJAX() {
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Recurssively load the directory
        $this->_Recusive_Load_Dir($dir.'/ajax');
        // Fire the shortcode init action
        do_action('vcff_meta_ajax_init',$this);
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

$vcff_meta = new VCFF_Meta();

vcff_register_library('vcff_meta',$vcff_meta);

$vcff_meta->Init();

// Register the vcff admin css
vcff_admin_enqueue_script('vcff-admin-meta', VCFF_META_URL . '/assets/admin/vcff.admin.meta.js', array('jquery'), '20120608', 'all');
// Register the vcff admin css
vcff_admin_enqueue_style('vcff-admin-meta', VCFF_META_URL . '/assets/admin/vcff.admin.meta.css', array(), '20120608', 'all');