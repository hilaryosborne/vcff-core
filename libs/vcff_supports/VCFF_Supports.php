<?php

if(!defined('VCFF_SUPPORTS_DIR'))
{ define('VCFF_SUPPORTS_DIR',untrailingslashit( plugin_dir_path(__FILE__ ) )); }

if (!defined('VCFF_SUPPORTS_URL'))
{ define('VCFF_SUPPORTS_URL',untrailingslashit( plugins_url( '/', __FILE__ ) )); }


class VCFF_Supports {

    public $contexts = array();

    public function Init() {
        // Fire the shortcode init action
        do_action('vcff_supports_before_init',$this);
        // Include the admin class
        require_once(VCFF_SUPPORTS_DIR.'/functions.php');
        // Initalize core logic
        add_action('vcff_init_core',array($this,'__Init_Core'),5);
        // Initalize context logic
        add_action('vcff_init_context',array($this,'__Init_Context'),5);
        // Initalize misc logic
        add_action('vcff_init_misc',array($this,'__Init_Misc'),5);
        // Fire the shortcode init action
        do_action('vcff_supports_init',$this);
        // Include the admin class
        require_once(VCFF_SUPPORTS_DIR.'/VCFF_Supports_Admin.php');
        // If being viewed as an admin
        require_once(VCFF_SUPPORTS_DIR.'/VCFF_Supports_Public.php');
        // Fire the shortcode init action
        do_action('vcff_supports_after_init',$this);
    }
    
    public function __Init_Core() {
        // Load helper classes
        $this->_Load_Helpers();
        // Load the core classes
        $this->_Load_Core(); 
    }

    public function __Init_Context() {
        // Load the context classes
        $this->_Load_Context();
    }
    
    public function __Init_Misc() {
        // Load the pages
        $this->_Load_Pages();
        // Load AJAX
        $this->_Load_AJAX();
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
            if (stripos($FileInfo->getFilename(),'.tpl') !== false || stripos($FileInfo->getFilename(),'.txt') !== false) { continue; } 
            // Include the file
            require_once($FileInfo->getPathname());
        }
        // Fire the shortcode init action
        do_action('vcff_supports_helper_init',$this);
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
        do_action('vcff_supports_core_init',$this);
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
        do_action('vcff_supports_context_init',$this);
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
        do_action('vcff_supports_pages_init',$this);
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
        do_action('vcff_supports_ajax_init',$this);
    }
    
    public function Load_Shortcodes() {
        // Retrieve the page contexts
        $contexts = $this->contexts;
        // If no contexts were returned
        if (!$contexts || !is_array($contexts)) { return; }
        // Loop through each of the found contexts
        foreach ($contexts as $_type => $_context) { 
            // Add the render function
            add_shortcode($_type, array($this,'Render_Shortcode'));
        }
        // Fire the shortcode init action
        do_action('vcff_supports_shortcode_init',$this);
    }
    
    public function Render_Shortcode($attr,$content,$shortcode) {
        // Retrieve the global vcff forms class
        $vcff_forms = vcff_get_library('vcff_forms');
        // Retrieve the form instance
        $form_instance = $vcff_forms->vcff_focused_form;
        // Retrieve the supports
        $support_instances = $form_instance->supports;
        
        foreach ($support_instances as $k => $support_instance) {
            // Retrieve the support name
            $machine_code = $support_instance->machine_code;
            // If this not the field we are looking for
            if ($machine_code != $attr['machine_code']) { continue; }
            // Render the form
            return $support_instance->Render($contents);
        }
    }

    public function Map_Visual_Composer() {
        // If not allowed to show shortcodes
        if (!vcff_allow_field_vc_shortcodes()) { return; }
        // Retrieve the focused post id
        $vcff_supports = vcff_get_library('vcff_supports');
        // Retrieve the list of contexts
        $contexts = $vcff_supports->contexts;
        // If no contexts were returned
        if (!$contexts || !is_array($contexts)) { return; }
        // Loop through each mapped field
        foreach ($contexts as $_type => $_context_data) {
            // Default vc settings
            $vc_params = array(
                "name" => $_context_data['title'],
                "icon" => "icon-ui-splitter-horizontal",
                "base" => $_context_data['type'],
                'category' => __('Form Controls', VCFF_NS),
            );
            // Merge the vc params
            $vc_params = array_merge_recursive($vc_params,$_context_data['vc']); 
            // Run the params through a filter
            $vc_params = apply_filters('vcff_support_vc_params',$vc_params,$_context_data);
            // Map the field to visual composer
            vc_map($vc_params); 
        }
        // Fire the vc init action
        do_action('vcff_supports_vc_init',$this);
    }
    
}

$vcff_supports = new VCFF_Supports();

vcff_register_library('vcff_supports',$vcff_supports);

$vcff_supports->Init();