<?php

class VCFF_Settings_Password_Item extends VCFF_Settings_Item {
    
    public function Render() {
        // Convert attrs to vars
        extract(shortcode_atts(array(
            'machine_code' => '',
            'label' => '',
            'type' => '',
            'group' => '',
            'required' => '',
            'default_value' => '',
        ), $this->data));
        // Start gathering content
        ob_start();
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Include the template file
        include(vcff_get_file_dir($dir.'/'.get_class($this).".tpl.php"));
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Return the contents
        return $output;
    }
}