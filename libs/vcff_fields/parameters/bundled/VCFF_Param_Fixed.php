<?php


class VCFF_Param_Fixed {

    public function __construct() {

        $this->init();
    }

    public function init() {

        add_shortcode_param( 'vcff_fixed', array($this, 'render') );

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

new VCFF_Param_Fixed();