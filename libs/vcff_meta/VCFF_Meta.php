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
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_META_DIR.'/helpers') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // Include the file
            require_once(VCFF_META_DIR.'/helpers/'.$FileInfo->getFilename());
        }
        // Fire the shortcode init action
        do_action('vcff_meta_helper_init',$this);
    }

    protected function _Load_Core() {
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_META_DIR.'/core') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // If this is not false
            if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; }
            // Include the file
            require_once(VCFF_META_DIR.'/core/'.$FileInfo->getFilename());
        }
        // Fire the shortcode init action
        do_action('vcff_meta_core_init',$this);
    }

    protected function _Load_Context() {
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_META_DIR.'/context') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // If this is not false
            if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; }
            // Include the file
            require_once(VCFF_META_DIR.'/context/'.$FileInfo->getFilename());
            // If this is not false
            if (stripos($FileInfo->getFilename(),'_Item') !== false) { continue; }
            // Retrieve the classname
            $context_classname = $FileInfo->getBasename('.php');
            
            vcff_map_meta_field($context_classname);
        }
        // Fire the shortcode init action
        do_action('vcff_meta_context_init',$this);
    }
    
    public function Load_Admin_Scripts() {
        // Register the vcff admin css
        vcff_admin_enqueue_script('vcff-admin-meta', VCFF_META_URL . '/assets/admin/vcff.admin.meta.js', array('jquery'), '20120608', 'all');
        // Register the vcff admin css
        vcff_admin_enqueue_style('vcff-admin-meta', VCFF_META_URL . '/assets/admin/vcff.admin.meta.css', array(), '20120608', 'all');
    }

    public function Load_Public_Scripts() {

    }
}

$vcff_meta = new VCFF_Meta();

vcff_register_library('vcff_meta',$vcff_meta);

$vcff_meta->Init();