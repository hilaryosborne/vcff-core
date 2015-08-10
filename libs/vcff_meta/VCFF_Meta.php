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
        // Fire the shortcode init action
        do_action('vcff_meta_init',$this);
        // Include the admin class
        require_once(VCFF_META_DIR.'/VCFF_Meta_Admin.php');
        // Include the public class
        require_once(VCFF_META_DIR.'/VCFF_Meta_Public.php');
        // Fire the shortcode init action
        do_action('vcff_meta_after_init',$this);
    }

    protected function _Load_Helpers() {
        // Retrieve the context director
        $dir = untrailingslashit(plugin_dir_path(__FILE__));
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
        do_action('vcff_meta_helper_init',$this);
    }

    protected function _Load_Core() {
        // Retrieve the context director
        $dir = untrailingslashit(plugin_dir_path(__FILE__));
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
        do_action('vcff_meta_core_init',$this);
    }

    protected function _Load_Context() {
        // Retrieve the context director
        $dir = untrailingslashit(plugin_dir_path(__FILE__));
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
        do_action('vcff_meta_context_init',$this);
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
        do_action('vcff_meta_pages_init',$this);
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
        do_action('vcff_meta_ajax_init',$this);
    }
}

$vcff_meta = new VCFF_Meta();

vcff_register_library('vcff_meta',$vcff_meta);

$vcff_meta->Init();

// Register the vcff admin css
vcff_admin_enqueue_script('vcff-admin-meta', VCFF_META_URL . '/assets/admin/vcff.admin.meta.js', array('jquery'), '20120608', 'all');
// Register the vcff admin css
vcff_admin_enqueue_style('vcff-admin-meta', VCFF_META_URL . '/assets/admin/vcff.admin.meta.css', array(), '20120608', 'all');