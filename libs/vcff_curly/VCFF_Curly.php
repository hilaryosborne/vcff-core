<?php 

if (!defined('VCFF_CURLY_DIR'))
{ define('VCFF_CURLY_DIR',untrailingslashit(plugin_dir_path(__FILE__ ))); }

if (!defined('VCFF_CURLY_URL'))
{ define('VCFF_CURLY_URL',untrailingslashit(plugins_url('/', __FILE__ ))); }
 
class VCFF_Curly {
    
    public $context = array();
    
    public function Init() {
        // Fire the shortcode init action
        do_action('vcff_curly_before_init',$this);
        // Include the core vcff functions
        require_once(VCFF_CURLY_DIR.'/functions.php');
        // Initalize core logic
        add_action('vcff_init_core',array($this,'__Init_Core'),20);
        // Initalize context logic
        add_action('vcff_init_context',array($this,'__Init_Context'),10);
        // Fire the shortcode init action
        do_action('vcff_curly_init',$this);
        // Fire the shortcode init action
        do_action('vcff_curly_after_init',$this);
        // Return for chaining
        return $this;
    }
    
    public function __Init_Core() {
        // Load helper classes
        $this->_Load_Helpers();
        // Load the core classes
        $this->_Load_Core(); 
        // Fire the shortcode init action
        do_action('vcff_curly_init_core',$this);
    }
    
    public function __Init_Context() {
        // Load the context classes
        $this->_Load_Context();
        // Fire the shortcode init action
        do_action('vcff_curly_init_context',$this);
    }
    
    public function __Init_Misc() {
        // Fire the shortcode init action
        do_action('vcff_curly_init_misc',$this);
    }
    
    public function _Load_Helpers() {
        // Retrieve the context director
        $dir = untrailingslashit(plugin_dir_path(__FILE__));
        // Load each of the form shortcodes
        foreach (new DirectoryIterator($dir.'/helpers') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // If this is not false
            if (stripos($FileInfo->getFilename(),'.tpl') !== false || stripos($FileInfo->getFilename(),'.txt') !== false) { continue; } 
            // Include the file
            require_once($FileInfo->getPathname());
        }
        // Fire the shortcode init action
        do_action('vcff_curly_helper_init',$this);
        // Return for chaining
        return $this;
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
            if (stripos($FileInfo->getFilename(),'.tpl') !== false || stripos($FileInfo->getFilename(),'.txt') !== false) { continue; } 
            // Include the file
            require_once($FileInfo->getPathname());
        }
        // Fire the shortcode init action
        do_action('vcff_curly_core_init',$this);
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
        do_action('vcff_curly_context_init',$this);
    }

}

$vcff_curly = new VCFF_Curly();

vcff_register_library('vcff_curly',$vcff_curly);

$vcff_curly->Init();