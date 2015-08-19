<?php

/*
* Plugin Name: VC Form Framework - Core
* Plugin URI: http://theblockquote.com/
* Description: The visual composer form framework
* Version: 0.9.0
* Author: Hilary Osborne - BlockQuote
* Author URI: http://theblockquote.com/
* Copyright 2015 theblockquote
*/

if (!defined('VCFF_FORM'))
{ define('VCFF_FORM','vcff-form'); }

if (!defined('VCFF_NS'))
{ define('VCFF_NS','vcff'); }

if (!defined('VCFF_DIR'))
{ define('VCFF_DIR', untrailingslashit(plugin_dir_path(__FILE__))); }

if (!defined('VCFF_URL'))
{ define('VCFF_URL', untrailingslashit(plugins_url('/', __FILE__))); }

if (!defined('VCFF_VERSION'))
{ define('VCFF_VERSION','0.0.5'); }

if (!defined('VCFF_ASSETS_URL'))
{ define('VCFF_ASSETS_URL',untrailingslashit(plugins_url('/assets', __FILE__))); }

class VCFF {

    public $libs = array();

    public $frontend_scripts = array(
        'scripts' => array(),
        'styles' => array()
    );
    
    public $admin_scripts = array(
        'scripts' => array(),
        'styles' => array()
    );

    public function __construct() {
        // Include the core vcff functions
        require_once(VCFF_DIR.'/functions.php');
    }
    
    public function Load_Core() { 
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_DIR.'/core') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // Include the file
            require_once(VCFF_DIR.'/core/'.$FileInfo->getFilename());
        }
        // Return for chaining
        return $this;
    }

    public function Load_Helpers() { 
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_DIR.'/helpers') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; } 
            // Include the file
            require_once(VCFF_DIR.'/helpers/'.$FileInfo->getFilename());
        }
        // Return for chaining
        return $this;
    }

    public function Load_Vendors() {
        // Include the VCFF Meta handling library
        require_once(VCFF_DIR.'/vendors/blq_parser/BLQ_Parser.php');
        require_once(VCFF_DIR.'/vendors/gump/GUMP.php');
        require_once(VCFF_DIR.'/vendors/phpmailer/PHPMailerAutoload.php');
        // Return for chaining
        return $this;
    }

    public function Load_Libs() { 
        // Include the VCFF Meta handling library
        require_once(VCFF_DIR.'/libs/vcff_meta/VCFF_Meta.php');
        require_once(VCFF_DIR.'/libs/vcff_events/VCFF_Events.php');
        require_once(VCFF_DIR.'/libs/vcff_fields/VCFF_Fields.php');
        require_once(VCFF_DIR.'/libs/vcff_containers/VCFF_Containers.php');
        require_once(VCFF_DIR.'/libs/vcff_supports/VCFF_Supports.php');
        require_once(VCFF_DIR.'/libs/vcff_fragments/VCFF_Fragments.php');
        require_once(VCFF_DIR.'/libs/vcff_forms/VCFF_Forms.php');
        require_once(VCFF_DIR.'/libs/vcff_curly/VCFF_Curly.php');
        require_once(VCFF_DIR.'/libs/vcff_settings/VCFF_Settings.php');
        // Return for chaining
        return $this;
    }

    public function Register_Lib($code,$object) {
        // If the lib is not an object data type
        if (!is_object($object)) { die($code.' is not an object'); }
        // Record the object
        $this->libs[$code] = $object;
        // Return for chaining
        return $this;
    }

    public function Get_Lib($code) {
        // If no lib object can be found
        if (!isset($this->libs[$code])) { return; }
        // return the lib object
        return $this->libs[$code];
    }

    public function Init() {
        
        do_action('vcff_init');
        // Load the core classes
        $this->Load_Core();
        // Load the various helper classes
        $this->Load_Helpers();
        // Load the vender libraries
        $this->Load_Vendors();
        // Load the vcff libraries
        $this->Load_Libs();
        // Return for chaining
        return $this;
    }
}  


global $vcff;

$vcff = new VCFF();

vcff_register_library('vcff', $vcff);

$vcff->Init();

add_action('vc_before_init',function(){

    do_action('vcff_init_core');
    
    do_action('vcff_init_context');
    
    do_action('vcff_init_misc');

    $vcff_helper_libs = new VCFF_Helper_Libs();

    $vcff_helper_libs
        ->Map_Visual_Composer()
        ->Load_Scripts_Public()
        ->Load_Scripts_Admin()
        ->Load_Shortcodes()
        ->Handle_Submissions();
});


