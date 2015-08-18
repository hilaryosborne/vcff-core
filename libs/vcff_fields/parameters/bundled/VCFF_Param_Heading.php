<?php

class VCFF_Param_Heading {

    public function __construct() {

        add_shortcode_param('vcff_heading', array($this, 'render'));
        // Register the vcff admin css
        vcff_admin_enqueue_style('vcff_param_heading', VCFF_FIELDS_URL.'/assets/admin/vcff_param_heading.css', array(), '20120608', 'all');
    }
    
    public function render($settings, $value) {  
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

new VCFF_Param_Heading();