<?php

class VCFF_Checkbox_List_Item extends VCFF_Field_Item {

    /**
         * RENDER FORM FIELD FOR INPUT (Required)
         * Use shortcode logic, attributes and template files
         * to display the form field shortcode within a form context
         */
    public function Form_Render() {
        // Convert attrs to vars
        extract(shortcode_atts(array(
            'field_label'=>'',
            'view_label'=>'',
            'machine_code'=>'',
            'options'=>'',
            'default_value'=>'',
            'dynamically_populate'=>'',
            'conditions'=>'',
            'validation'=>'',
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

    /**
         * CONDITIONAL FUNCTIONS
         * 
         */        
    public function Check_Rule_IS($against) {
        // If no value was provided, return false
        if (!is_array($this->posted_value)) { return false; }
        // If more or less than one value was posted through
        if (count($this->posted_value) != 1) { return false; }
        // If the first value matches the against
        if ($this->posted_value[0] == $against) { return true; }
        // Otherwise return false
        return false;
    }

    public function Check_Rule_IS_NOT($against) {
        // If no value was provided, return false
        if (!is_array($this->posted_value)) { return false; }
        // If more or less than one value was posted through
        if (count($this->posted_value) != 1) { return false; }
        // If the first value does not match the against
        if ($this->posted_value[0] != $against) { return true; }
        // Otherwise return false
        return false;
    }

    public function Check_Rule_GREATER_THAN($against) {
        // If no value was provided, return false
        if (!is_array($this->posted_value)) { return false; }
        // If the number of posted values is higher
        if (count($this->posted_value) > $against) { return true; } else { return false; }
    }

    public function Check_Rule_LESS_THAN($against) {
        // If no value was provided, return false
        if (!is_array($this->posted_value)) { return false; }
        // If the number of posted values is lower
        if (count($this->posted_value) < $against) { return true; } else { return false; }
    }

    public function Check_Rule_CONTAINS($against) {
        // If no value was provided, return false
        if (!is_array($this->posted_value)) { return false; }
        // If the against can be found within the submitted values
        return in_array($against, $this->posted_value) ? true : false;
    }

    public function Check_Rule_STARTS_WITH($against) {
        // If no value was provided, return false
        if (!is_array($this->posted_value)) { return false; }
        // If the first value matches with the against
        if ($this->posted_value[0] == $against) { return true; }
        // Otherwise return false
        return false;
    }

    public function Check_Rule_ENDS_WITH($against) {
        // If no value was provided, return false
        if (!is_array($this->posted_value)) { return false; }
        // If the last value matches with the against
        if ($this->posted_value[(count($this->posted_value)-1)] == $against) { return true; }
        // Otherwise return false
        return false;
    }
}