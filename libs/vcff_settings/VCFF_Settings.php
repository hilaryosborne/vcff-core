<?php

if(!defined('VCFF_SETTINGS_DIR'))
{ define('VCFF_SETTINGS_DIR',untrailingslashit( plugin_dir_path(__FILE__ ) )); }

if (!defined('VCFF_SETTINGS_URL'))
{ define('VCFF_SETTINGS_URL',untrailingslashit( plugins_url( '/', __FILE__ ) )); }


class VCFF_Settings {
    
    public function Init() {
        // Fire the shortcode init action
        do_action('vcff_settings_before_init',$this);
        // Include the admin class
        require_once(VCFF_SETTINGS_DIR.'/functions.php');
        // Load helper classes
        $this->_Load_Helpers();
        // Load the core classes
        $this->_Load_Core();
        // Load the context classes
        $this->_Load_Context();
        // Fire the shortcode init action
        do_action('vcff_settings_init',$this);
        // Include the admin class
        require_once(VCFF_SETTINGS_DIR.'/VCFF_Settings_Admin.php');
        // Otherwise if this is being viewed by the client 
        require_once(VCFF_SETTINGS_DIR.'/VCFF_Settings_Public.php'); 
        // Fire the shortcode init action
        do_action('vcff_settings_after_init',$this);
    }
    
    protected function _Load_Helpers() {
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_SETTINGS_DIR.'/helpers') as $FileInfo) {
            // If this is a directory dot
            if ($FileInfo->isDot()) { continue; }
            // If this is a directory
            if ($FileInfo->isDir()) { continue; }
            // Include the file
            require_once(VCFF_SETTINGS_DIR.'/helpers/'.$FileInfo->getFilename());
        }
        // Fire the shortcode init action
        do_action('vcff_settings_helper_init',$this);
    }

    protected function _Load_Core() {
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_SETTINGS_DIR.'/core') as $FileInfo) {
            // If this is a directory dot
            if ($FileInfo->isDot()) { continue; }
            // If this is a directory
            if ($FileInfo->isDir()) { continue; }
            // If this is not false
            if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; }
            // Include the file
            require_once(VCFF_SETTINGS_DIR.'/core/'.$FileInfo->getFilename());
        }
        // Fire the shortcode init action
        do_action('vcff_settings_core_init',$this);
    }
    
    protected function _Load_Context() {
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_SETTINGS_DIR.'/context') as $FileInfo) {
            // If this is a directory dot
            if ($FileInfo->isDot()) { continue; }
            // If this is a directory
            if ($FileInfo->isDir()) { continue; }
            // If this is not false
            if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; }
            // Include the file
            require_once(VCFF_SETTINGS_DIR.'/context/'.$FileInfo->getFilename());
            // If this is not false
            if (stripos($FileInfo->getFilename(),'_Item') !== false) { continue; }
            // Retrieve the classname
            $context_classname = $FileInfo->getBasename('.php');
            
            vcff_map_setting($context_classname);
        }
        // Fire the shortcode init action
        do_action('vcff_settings_context_init',$this);
    }
}

$vcff_settings = new VCFF_Settings();

vcff_register_library('vcff_settings',$vcff_settings);

$vcff_settings->Init();