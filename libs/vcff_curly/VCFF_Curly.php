<?php 

if (!defined('VCFF_CURLY_DIR'))
{ define('VCFF_CURLY_DIR',untrailingslashit(plugin_dir_path(__FILE__ ))); }

if (!defined('VCFF_CURLY_URL'))
{ define('VCFF_CURLY_URL',untrailingslashit(plugins_url('/', __FILE__ ))); }
 
class VCFF_Curly {
    
    public $context = array();
    
    public function __construct() {
        // Include the core vcff functions
        require_once(VCFF_CURLY_DIR.'/functions.php');
    }
    
    public function _Load_Helpers() {
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_CURLY_DIR.'/helpers') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; } 
            // Include the file
            require_once(VCFF_CURLY_DIR.'/helpers/'.$FileInfo->getFilename());
        }
        // Fire the shortcode init action
        do_action('vcff_curly_helper_init',$this);
        // Return for chaining
        return $this;
    }
    
    protected function _Load_Tags() {
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_CURLY_DIR.'/context') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; } 
            // Include the file
            require_once(VCFF_CURLY_DIR.'/context/'.$FileInfo->getFilename());
        }
        // Fire the shortcode init action
        do_action('vcff_curly_context_init',$this);
    }
    
    public function Init() {
        // Fire the shortcode init action
        do_action('vcff_curly_before_init',$this);
        // Load the various helper classes
        $this->_Load_Helpers();
        // Load the various helper classes
        $this->_Load_Tags();
        // Fire the shortcode init action
        do_action('vcff_curly_init',$this);
        // Fire the shortcode init action
        do_action('vcff_curly_after_init',$this);
        // Return for chaining
        return $this;
    }
    
}

$vcff_curly = new VCFF_Curly();

vcff_register_library('vcff_curly',$vcff_curly);

$vcff_curly->Init();