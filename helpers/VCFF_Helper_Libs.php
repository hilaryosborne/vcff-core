<?php

class VCFF_Helper_Libs extends VCFF_Helper {
    
    protected $scripts_public_loaded = false;
    
    protected $scripts_admin_loaded = false;
    
    protected $shortcodes_loaded = false;
    
    protected $vc_mapped = false;
    
    protected $submission_handler_set = false;
    
    public function Load_Scripts_Public() {
        // If the admin scripts have already been loaded
        if ($this->scripts_public_loaded) { return $this; }
        // Retrieve the vcff global var
        global $vcff;
        // Register the vcff admin css
        vcff_front_enqueue_script('jquery', array('jquery'));
        vcff_front_enqueue_script('base64', VCFF_URL.'/assets/vendors/base64.js', array(), '20120608', 'all');
        vcff_front_enqueue_script('json', VCFF_URL.'/assets/vendors/json.js', array(), '20120608', 'all');
        vcff_front_enqueue_script('vcff-actions', VCFF_URL.'/assets/public/vcff.actions.js', array(), '20120608', 'all');
        vcff_front_enqueue_script('vcff-core', VCFF_URL.'/assets/public/vcff.core.js', array('jquery','vcff-actions','base64','json'), '20120608', 'all');
        
        add_action('wp_print_scripts',function(){
            print '<script type="text/javascript">';
            print 'var ajaxurl = "'.admin_url('admin-ajax.php').'";';
            print '</script>';
        });
        // Retrieve the set lib instances
        $libs = $vcff->libs;
        // If there are no libs, return out
        if (!$libs || !is_array($libs) || count($libs) == 0) { return $this; }
        // Add the enqueue scripts action
        add_action('wp_enqueue_scripts',function() {
            // Retrieve the vcff library
            $vcff = vcff_get_library('vcff');
            // Retrieve the frontend scripts
            $front_scripts = $vcff->frontend_scripts;
            // Loop through and queue all of the scripts
            foreach ($front_scripts['scripts'] as $k => $script) {
                // Enqueue the script
                wp_enqueue_script($script[0],$script[1],$script[2],$script[3],$script[4]);
            }
            // Loop through and queue all the styles
            foreach ($front_scripts['styles'] as $k => $style) {
                // Enqueue the script
                wp_enqueue_style($style[0],$style[1],$style[2],$style[3],$style[4]);
            }
        });
        // Set the loaded flag
        $this->scripts_public_loaded = true;
        // Return for chaining
        return $this;
    }

    public function Load_Scripts_Admin() {
        // Retrieve the vcff global var
        global $vcff;
        // If the admin scripts have already been loaded
        if ($this->scripts_admin_loaded) { return $this; }
        // Register the vcff admin css
        vcff_admin_enqueue_script('jquery', array('jquery'));
        vcff_admin_enqueue_script('jquery-ui-core', array('jquery'));
        vcff_admin_enqueue_script('jquery-ui-tabs', array('jquery'));
        vcff_admin_enqueue_script('handlebars', VCFF_URL.'/assets/vendors/handlebars.js', array(), '20120608', 'all');
        vcff_admin_enqueue_script('vcff-actions', VCFF_URL.'/assets/public/vcff.actions.js', array(), '20120608', 'all');
        vcff_admin_enqueue_script('base64', VCFF_URL.'/assets/vendors/base64.js', array(), '20120608', 'all');
        vcff_admin_enqueue_script('json', VCFF_URL.'/assets/vendors/json.js', array(), '20120608', 'all');
        vcff_admin_enqueue_script('vcff-core', VCFF_URL.'/assets/public/vcff.core.js', array('jquery','vcff-actions','base64','json'), '20120608', 'all');
        vcff_admin_enqueue_script('bootstrap', VCFF_URL.'/assets/vendors/bootstrap.js', array('vcff-core'), '20120608', 'all');
        vcff_admin_enqueue_script('vcff-admin', VCFF_URL.'/assets/admin/vcff.admin.js', array('vcff-core'), '20120608', 'all');
        vcff_admin_enqueue_style('bootstrap', VCFF_URL.'/assets/vendors/bootstrap.css', array(), '20120608', 'all');
        
        vcff_admin_enqueue_style('vcff-admin', VCFF_URL.'/assets/admin/vcff.admin.css', array(), '20120608', 'all');
        
        add_action('wp_print_scripts',function(){
            print '<script type="text/javascript">';
            print 'var vcff_data = '.json_encode(array(
                'ajaxurl' => admin_url('admin-ajax.php')
            )).';';
            print '</script>';
        });
        // Retrieve the set lib instances
        $libs = $vcff->libs;
        // If there are no libs, return out
        if (!$libs || !is_array($libs) || count($libs) == 0) { return $this; }
        // Add the enqueue scripts action
        add_action('admin_enqueue_scripts',function() {
            // Retrieve the vcff library
            $vcff = vcff_get_library('vcff');
            // Retrieve the frontend scripts
            $admin_scripts = $vcff->admin_scripts;
            // Loop through and queue all of the scripts
            foreach ($admin_scripts['scripts'] as $k => $script) {
                // Enqueue the script
                wp_enqueue_script($script[0],$script[1],$script[2],$script[3],$script[4]);
            }
            // Loop through and queue all the styles
            foreach ($admin_scripts['styles'] as $k => $style) {
                // Enqueue the script
                wp_enqueue_style($style[0],$style[1],$style[2],$style[3],$style[4]);
            }
        });
        // Set the loaded flag
        $this->scripts_admin_loaded = true;
        // Return for chaining
        return $this;
    }
    
    public function Load_Shortcodes() {
        // If the admin scripts have already been loaded
        if ($this->shortcodes_loaded) { return $this; }
        // Setup the init action
        add_action('init',function(){
            // Retrieve the vcff global var
            global $vcff;
            // Retrieve the set lib instances
            $libs = $vcff->libs;
            // If there are no libs, return out
            if (!$libs || !is_array($libs) || count($libs) == 0) { return $this; }
            // Loop through each lib
            foreach ($libs as $code => $instance) {
                // If the instance does not have a public scripts load
                if (!method_exists($instance,'Load_Shortcodes')) { continue; }
                // Load all public scripts
                $instance->Load_Shortcodes();
            }
        },100);
        // Set the loaded flag
        $this->shortcodes_loaded = true;
        // Return for chaining
        return $this;
    }
    
    public function Map_Visual_Composer() {
        // If the admin scripts have already been loaded
        if ($this->vc_mapped) { return $this; }
        // Setup the init action
        add_action('vc_before_init',function(){ 
            // Retrieve the vcff global var
            global $vcff;
            // Retrieve the set lib instances
            $libs = $vcff->libs;
            // If there are no libs, return out
            if (!$libs || !is_array($libs) || count($libs) == 0) { return $this; }
            // Loop through each lib
            foreach ($libs as $code => $instance) { 
                // If the instance does not have a public scripts load
                if (!method_exists($instance,'Map_Visual_Composer')) { continue; }
                // Load all public scripts
                $instance->Map_Visual_Composer();
            }
        },100);
        // Set the loaded flag
        $this->vc_mapped = true;
        // Return for chaining
        return $this;
    }
    
    public function Handle_Submissions() {
        // If the admin scripts have already been loaded
        if ($this->submission_handler_set) { return $this; }
        // Setup the init action
        add_action('init',function(){
            // We need sessions
            if (!session_id()) { session_start(); } 
            // If we are submitting a form
            if (isset($_POST['vcff_form'])) {
                // Run the submission actions
                do_action('vcff_form_submission');
            }
        },100);
        // Set the loaded flag
        $this->submission_handler_set = true;
        // Return for chaining
        return $this;
    }
    
}