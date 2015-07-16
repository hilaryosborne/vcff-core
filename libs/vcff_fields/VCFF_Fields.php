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
        // Load helper classes
        $this->_Load_Helpers();
        // Load the core classes
        $this->_Load_Core();
        // Load the context classes
        $this->_Load_Context();
        // Fire the shortcode init action
        do_action('vcff_field_init',$this);
        // Include the admin class
        require_once(VCFF_FIELDS_DIR.'/VCFF_Fields_Admin.php');
        // Otherwise if this is being viewed by the client 
        require_once(VCFF_FIELDS_DIR.'/VCFF_Fields_Public.php');
        // Fire the shortcode init action
        do_action('vcff_field_after_init',$this);
    }
    
    protected function _Load_Helpers() {
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_FIELDS_DIR.'/helpers') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // Include the file
            require_once(VCFF_FIELDS_DIR.'/helpers/'.$FileInfo->getFilename());
        }
        // Fire the shortcode init action
        do_action('vcff_field_helper_init',$this);
    }
    
    protected function _Load_Core() {
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_FIELDS_DIR.'/core') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // If this is not false
            if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; }
            // Include the file
            require_once(VCFF_FIELDS_DIR.'/core/'.$FileInfo->getFilename());
        }
        // Fire the shortcode init action
        do_action('vcff_field_core_init',$this);
    }

    protected function _Load_Context() { 
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_FIELDS_DIR.'/context') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // If this is not false
            if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; }
            // Include the file
            require_once(VCFF_FIELDS_DIR.'/context/'.$FileInfo->getFilename());
            // Retrieve the classname
            $context_classname = $FileInfo->getBasename('.php');
            // Check if the class is a root class           
            if (!property_exists($context_classname,'is_context')) { continue; }
            // Map the context
            vcff_map_field($context_classname);
        }
        // Fire the shortcode init action
        do_action('vcff_field_context_init',$this);
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
            $vc_params = apply_filters('vcff_field_vc_params',$vc_params,$_context_data);
            // Map the field to visual composer
            vc_map($vc_params); 
        }
        // Fire the vc init action
        do_action('vcff_field_vc_init',$this);
    }
    
    public function Load_Admin_Scripts() {
        // Retrieve the global vcff forms class
        $vcff_fields = vcff_get_library('vcff_fields');
        // Retrieve the list of contexts
        $contexts = $vcff_fields->contexts;
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
        // Retrieve the global vcff forms class
        $vcff_fields = vcff_get_library('vcff_fields');
        // Retrieve the list of contexts
        $contexts = $vcff_fields->contexts; 
        // If a list of active fields were returned
        if (!$contexts || !is_array($contexts)) { return; }
        // Loop through each of the active fields
        foreach ($contexts as $_type => $_context) {
            // If this field has custom scripts which need registering
            if (isset($_context['params']['public_scripts'])
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
            if (isset($_context['params']['public_styles'])
                && is_array($_context['params']['public_styles'] )) {
                // Loop through each of the styles
                $i=0; foreach ($_context['params']['public_styles']  as $__k => $_style) {
                    // Retrieve the css url
                    $style_url = vcff_get_file_url($_style); 
                    // Queue the custom script
                    vcff_front_enqueue_style( $_type.'_'.$i, $style_url); $i++;
                }
            }
        }
    }
    
}

$vcff_fields = new VCFF_Fields();

vcff_register_library('vcff_fields',$vcff_fields);

$vcff_fields->Init();