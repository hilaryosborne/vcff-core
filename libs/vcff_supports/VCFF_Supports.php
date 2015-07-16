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
        // Load helper classes
        $this->_Load_Helpers();
        // Load the core classes
        $this->_Load_Core();
        // Load the context classes
        $this->_Load_Context();
        // Fire the shortcode init action
        do_action('vcff_supports_init',$this);
        // Include the admin class
        require_once(VCFF_SUPPORTS_DIR.'/VCFF_Supports_Admin.php');
        // If being viewed as an admin
        require_once(VCFF_SUPPORTS_DIR.'/VCFF_Supports_Public.php');
        // Fire the shortcode init action
        do_action('vcff_supports_after_init',$this);
    }
    
    protected function _Load_Helpers() {
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_SUPPORTS_DIR.'/helpers') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // Include the file
            require_once(VCFF_SUPPORTS_DIR.'/helpers/'.$FileInfo->getFilename());
        }
        // Fire the shortcode init action
        do_action('vcff_supports_helper_init',$this);
    }
    
    protected function _Load_Core() {
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_SUPPORTS_DIR.'/core') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // If this is not false
            if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; }
            // Include the file
            require_once(VCFF_SUPPORTS_DIR.'/core/'.$FileInfo->getFilename());
        }
        // Fire the shortcode init action
        do_action('vcff_supports_core_init',$this);
    }

    protected function _Load_Context() {
        // Load each of the page shortcodes
        foreach (new DirectoryIterator(VCFF_SUPPORTS_DIR.'/context') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // If this is not false
            if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; }
            // Include the file
            require_once(VCFF_SUPPORTS_DIR.'/context/'.$FileInfo->getFilename());
            // If this is not false
            if (stripos($FileInfo->getFilename(),'_Item') !== false) { continue; }
            // Retrieve the classname
            $context_classname = $FileInfo->getBasename('.php');
            // Map the support class
            vcff_map_support($context_classname);
        }
        // Fire the shortcode init action
        do_action('vcff_supports_context_init',$this);
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
    
    public function Load_Admin_Scripts() {
        // Retrieve the focused post id
        $vcff_supports = vcff_get_library('vcff_supports');
        // Retrieve the list of contexts
        $contexts = $vcff_supports->contexts;
        // If a list of active fields were returned
        if (!$contexts || !is_array($contexts)) { return; }
        // Loop through each of the active fields
        foreach ($contexts as $_type => $_context) {
            // If this field has custom scripts which need registering
            if ($_context['params']['admin_scripts'] 
                && is_array($_context['params']['admin_scripts'])) {
                // Loop through each of the scripts
                $i=0; foreach ($_context['params']['admin_scripts'] as $__k => $_script) {
                    // Queue the custom script
                    vcff_admin_enqueue_script( $_type.'_'.$i, $_script, array('jquery')); $i++;
                }
            }
            // If this field has custom styles which need registering
            if ($_context['params']['admin_styles'] 
                && is_array($_context['params']['admin_styles'] )) {
                // Loop through each of the styles
                $i=0; foreach ($_context['params']['admin_styles']  as $__k => $_style) {
                    // Queue the custom script
                    vcff_admin_enqueue_style( $_type.'_'.$i, $_style); $i++;
                }
            }
        }
    }
    
    public function Load_Public_Scripts() {
        // Retrieve the focused post id
        $vcff_supports = vcff_get_library('vcff_supports');
        // Retrieve the list of contexts
        $contexts = $vcff_supports->contexts;
        // If a list of active fields were returned
        if (!$contexts || !is_array($contexts)) { return; }
        // Loop through each of the active fields
        foreach ($contexts as $_type => $_context) {
            // If this field has custom scripts which need registering
            if ($_context['params']['public_scripts'] 
                && is_array($_context['params']['public_scripts'])) {
                // Loop through each of the scripts
                $i=0; foreach ($_context['params']['public_scripts'] as $__k => $_script) {
                    // Retrieve the script url
                    $script_url = vcff_get_file_url($_script);
                    // Queue the custom script
                    vcff_front_enqueue_script( $_type.'_'.$i, $script_url, array('jquery')); $i++;
                }
            }
            // If this field has custom styles which need registering
            if ($_context['params']['public_scripts'] 
                && is_array($_context['params']['public_scripts'] )) {
                // Loop through each of the styles
                $i=0; foreach ($_context['params']['public_scripts']  as $__k => $_style) {
                    // Retrieve the css url
                    $style_url = vcff_get_file_url($_style);
                    // Queue the custom script
                    vcff_front_enqueue_style( $_type.'_'.$i, $style_url); $i++;
                }
            }
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