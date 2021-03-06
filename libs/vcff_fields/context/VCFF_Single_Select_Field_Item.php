<?php

class VCFF_Single_Select_Field_Item extends VCFF_Field_Item {

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
            'options'=>'',
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
        // Add css classes
        $css_class = apply_filters('vcff_el_css',$css_class,$this->attributes,$this);
        // The options list
        $options_list = array();
        // Explode the options by a new line
        $exploded = explode("\n",urldecode(base64_decode($options)));
        // If a list of items were returned
        if ($exploded && is_array($exploded)) {
            // Loop through each exploded option
            foreach ($exploded as $k => $_raw_line) {
                // Explode the line by the bar
                $exploded_line = explode('|',$_raw_line);
                // Extract the field item value
                $field_item_value = $exploded_line[0];
                // Extract the field label
                $field_item_label = count($exploded_line) == 1 ? $exploded_line[0] : $exploded_line[1];
                // Populate the options list
                $options_list[$field_item_value] = $field_item_label;
            } 
        }
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