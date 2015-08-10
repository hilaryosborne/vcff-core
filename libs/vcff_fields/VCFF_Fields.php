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
        // Load the pages
        $this->_Load_Pages();
        // Load AJAX
        $this->_Load_AJAX();
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
        // Retrieve the context director
        $dir = untrailingslashit(plugin_dir_path(__FILE__));
        // Load each of the form shortcodes
        foreach (new DirectoryIterator($dir.'/helpers') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // If this is not false
            if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; } 
            // Include the file
            require_once($FileInfo->getPathname());
        }
        // Fire the shortcode init action
        do_action('vcff_field_helper_init',$this);
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
            if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; } 
            // Include the file
            require_once($FileInfo->getPathname());
        }
        // Fire the shortcode init action
        do_action('vcff_field_core_init',$this);
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
                    if (stripos($_FileInfo->getFilename(),'.tpl') !== false) { continue; } 
                    // Include the file
                    require_once($_FileInfo->getPathname());
                }
            } // Otherwise this is just a file
            else {
                // If this is not false
                if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; } 
                // Include the file
                require_once($FileInfo->getPathname());
            }
        }
        // Fire the shortcode init action
        do_action('vcff_field_context_init',$this);
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
        do_action('vcff_field_pages_init',$this);
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
        do_action('vcff_field_ajax_init',$this);
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

}

$vcff_fields = new VCFF_Fields();

vcff_register_library('vcff_fields',$vcff_fields);

$vcff_fields->Init();