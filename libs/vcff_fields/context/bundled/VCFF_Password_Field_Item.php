<?php

class VCFF_Password_Field_Item extends VCFF_Field_Item {

    /**
         * RENDER FORM FIELD FOR INPUT (Required)
         * Use shortcode logic, attributes and template files
         * to display the form field shortcode within a form context
         */
    public function Form_Render() { 
        // Convert attrs to vars
        extract(shortcode_atts(array(
            'field_label'=>'',
            'hide_label'=>'',
            'machine_code' => '',
            'placeholder'=>'',
            'default_value'=>'',
            'attributes'=>'',
            'is_disabled'=>'',
            'validation' => '',
            'filter'=>'',
            'conditions'=>'',
            'extra_class'=>'',
            'css'=>'',
        ), $this->attributes));
        // Compile the css class
        $css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $this->attributes);
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