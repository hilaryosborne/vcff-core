<?php

class VCFF_Alert_Panel_Item extends VCFF_Support_Item {
        
    public function Render() { 
        // Convert attrs to vars
        extract(shortcode_atts(array(
            'extra_class'=>'',
            'css'=>'',
        ), $this->attributes));
        // Compile the css class
        $css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts);
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Start gathering content
        ob_start();
        // Include the template file
        include(vcff_get_file_dir(VCFF_SUPPORTS_DIR.'/context/'.get_class($this).".tpl.php"));
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Return the contents
        return $output;
    }
}