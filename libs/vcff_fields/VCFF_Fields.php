<?php

if(!defined('VCFF_FIELDS_DIR'))
{ define('VCFF_FIELDS_DIR',untrailingslashit( plugin_dir_path(__FILE__ ) )); }

if (!defined('VCFF_FIELDS_URL'))
{ define('VCFF_FIELDS_URL',untrailingslashit( plugins_url( '/', __FILE__ ) )); }


class VCFF_Fields {

    public $contexts = array();

    public function Init() {
        // Fire the shortcode init action
        do_action('vcff_field_before_init',$this);
        // Include the admin class
        require_once(VCFF_FIELDS_DIR.'/functions.php');
        // Initalize core logic
        add_action('vcff_init_core',array($this,'__Init_Core'),1);
        // Initalize context logic
        add_action('vcff_init_context',array($this,'__Init_Context'),1);
        // Initalize misc logic
        add_action('vcff_init_misc',array($this,'__Init_Misc'),1);
        // Fire the shortcode init action
        do_action('vcff_field_init',$this);
        // Fire the shortcode init action
        do_action('vcff_field_after_init',$this);
    }
    
    public function __Init_Core() {
        // Load helper classes
        $this->_Load_Helpers();
        // Load the core classes
        $this->_Load_Core(); 
        // Fire the shortcode init action
        do_action('vcff_field_init_core',$this);
    }

    public function __Init_Context() {
        // Load the context classes
        $this->_Load_Context();
        // Load the parameters
        $this->_Load_Parameters();
        // Fire the shortcode init action
        do_action('vcff_field_init_context',$this);
    }
    
    public function __Init_Misc() {
        // Load the pages
        $this->_Load_Pages();
        // Load AJAX
        $this->_Load_AJAX();
        // Fire the shortcode init action
        do_action('vcff_field_init_misc',$this);
    }
    
    protected function _Load_Helpers() {
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Recurssively load the directory
        $this->_Recusive_Load_Dir($dir.'/helpers');
        // Fire the shortcode init action
        do_action('vcff_field_helper_init',$this);
    }
    
    protected function _Load_Core() {
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Recurssively load the directory
        $this->_Recusive_Load_Dir($dir.'/core');
        // Fire the shortcode init action
        do_action('vcff_field_core_init',$this);
    }

    protected function _Load_Context() { 
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Recurssively load the directory
        $this->_Recusive_Load_Dir($dir.'/context');
        // Fire the shortcode init action
        do_action('vcff_field_context_init',$this);
    }
    
    protected function _Load_Parameters() { 
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Recurssively load the directory
        $this->_Recusive_Load_Dir($dir.'/parameters');
        // Fire the shortcode init action
        do_action('vcff_field_parameters_init',$this);
    }

    protected function _Load_Pages() { 
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Recurssively load the directory
        $this->_Recusive_Load_Dir($dir.'/pages');
        // Fire the shortcode init action
        do_action('vcff_field_pages_init',$this);
    }
    
    protected function _Load_AJAX() {
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Recurssively load the directory
        $this->_Recusive_Load_Dir($dir.'/ajax');
        // Fire the shortcode init action
        do_action('vcff_field_ajax_init',$this);
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
        // Retrieve the feild contexts
        $contexts = $this->contexts;
        // If no contexts were returned
        if (!$contexts || !is_array($contexts)) { return; }
        // Loop through each of the found contexts
        foreach ($contexts as $_type => $_context) {
            // Add the render function
            add_shortcode($_type, function($attr,$content,$shortcode) {
                // Retrieve the global vcff forms class
                $vcff_forms = vcff_get_library('vcff_forms');
                // Retrieve the form instance
                $form_instance = $vcff_forms->vcff_focused_form;
                // If no form instance can be found
                if (!is_object($form_instance)) { return 'No Form Instance Found'; }
                // Loop through the form's instanced fields
                $fields = $form_instance->fields;
                // Loop through the focused fields
                foreach ($fields as $machine_code => $field_instance) {
                    // If this not the field we are looking for
                    if ($machine_code != $attr['machine_code']) { continue; }
                    // Render the form
                    return $field_instance->Form_Render($attr,$content,$shortcode);
                }
            });
        }
        // Fire the shortcode init action
        do_action('vcff_field_shortcode_init',$this);
    }
    
    public function Map_Visual_Composer() {
        // If not allowed to show shortcodes
        if (!vcff_allow_field_vc_shortcodes()) { return; }
        // Retrieve the global vcff forms class
        $vcff_fields = vcff_get_library('vcff_fields');
        // Retrieve the list of contexts
        $contexts = $vcff_fields->contexts;
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
            $vc_params = apply_filters('vcff_field_vc_params',$vc_params,$_context);
            // Map the field to visual composer
            vc_map($vc_params); 
        }
        // Fire the vc init action
        do_action('vcff_field_vc_init',$this);
    }

}

$vcff_fields = new VCFF_Fields();

vcff_register_library('vcff_fields',$vcff_fields);

$vcff_fields->Init();