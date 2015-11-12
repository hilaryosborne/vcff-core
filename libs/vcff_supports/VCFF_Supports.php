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
        // Fire the shortcode init action
        do_action('vcff_supports_after_init',$this);
    }
    
    public function __Init_Core() {
        // Load helper classes
        $this->_Load_Helpers();
        // Load the core classes
        $this->_Load_Core(); 
        // Fire the shortcode init action
        do_action('vcff_supports_init_core',$this);
    }

    public function __Init_Context() {
        // Load the context classes
        $this->_Load_Context();
        // Fire the shortcode init action
        do_action('vcff_supports_init_context',$this);
    }
    
    public function __Init_Misc() {
        // Load the pages
        $this->_Load_Pages();
        // Load AJAX
        $this->_Load_AJAX();
        // Fire the shortcode init action
        do_action('vcff_supports_init_misc',$this);
    }
    
    protected function _Load_Helpers() {
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Recurssively load the directory
        $this->_Recusive_Load_Dir($dir.'/helpers');
        // Fire the shortcode init action
        do_action('vcff_supports_helper_init',$this);
    }
    
    protected function _Load_Core() {
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Recurssively load the directory
        $this->_Recusive_Load_Dir($dir.'/core');
        // Fire the shortcode init action
        do_action('vcff_supports_core_init',$this);
    }

    protected function _Load_Context() {
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Recurssively load the directory
        $this->_Recusive_Load_Dir($dir.'/context');
        // Fire the shortcode init action
        do_action('vcff_supports_context_init',$this);
    }
    
    protected function _Load_Pages() { 
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Recurssively load the directory
        $this->_Recusive_Load_Dir($dir.'/pages');
        // Fire the shortcode init action
        do_action('vcff_supports_pages_init',$this);
    }
    
    protected function _Load_AJAX() {
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Recurssively load the directory
        $this->_Recusive_Load_Dir($dir.'/ajax');
        // Fire the shortcode init action
        do_action('vcff_supports_ajax_init',$this);
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
        // Retrieve the form supports
        $form_supports = $form_instance->supports; 
        // If no support instance could be found
        if (!isset($form_supports[$attr['machine_code']])) { return 'No element instance found for '.$attr['machine_code']; }
        // Retrieve the element instance
        $support_el = $form_supports[$attr['machine_code']];
        // Render the instance
        return $support_el->Render($contents);
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
        foreach ($contexts as $_type => $_context) {
            // Default vc settings
            $vc_params = array(
                "name" => $_context['title'],
                "icon" => "icon-ui-splitter-horizontal",
                "base" => $_context['type'],
                'category' => __('Form Controls', VCFF_NS),
            );
            // Merge the vc params
            $vc_params = array_merge_recursive($vc_params,$_context['vc_map']); 
            // Run the params through a filter
            $vc_params = apply_filters('vcff_support_vc_params',$vc_params,$_context);
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