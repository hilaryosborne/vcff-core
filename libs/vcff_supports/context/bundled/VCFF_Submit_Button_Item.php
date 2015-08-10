<?php

class VCFF_Submit_Button_Item extends VCFF_Support_Item {

    public function Render() { 
        // Convert attrs to vars
        extract(shortcode_atts(array(
            'btn_label'=>'',
            'btn_value'=>'',
            'extra_class'=>'',
            'el_extra_class' => '',
            'css'=>'',
        ), $this->attributes)); 
        // Compile the css class
        $css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts);
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