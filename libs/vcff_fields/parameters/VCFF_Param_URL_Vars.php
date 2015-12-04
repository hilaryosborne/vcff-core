<?php

class VCFF_Param_URL_Vars {
    
    public $value;
    
    public $settings;
    
    public function __construct() {

        add_shortcode_param('vcff_url_vars', array($this, 'render'), VCFF_FIELDS_URL.'/assets/admin/vcff_param_url_vars.js' );
        // Register the vcff admin css
        vcff_admin_enqueue_style('vcff_url_vars', VCFF_FIELDS_URL.'/assets/admin/vcff_param_url_vars.css', array(), '20120608', 'all');
    }
    
    public function render($settings, $value) {  
        // Set the instance settings
        $this->settings = $settings;
        // Set the instance value
        $this->value = $value;
        // Parse the stored vars
        $stored_vars = json_decode(base64_decode($this->value),true); 
        // Start gathering content
        ob_start();
        // Retrieve the context director
        $_dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Include the template file
        include($_dir.'/'.get_class($this).'.tpl.php');
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Return the contents
        return $output;
    }
}

new VCFF_Param_URL_Vars();