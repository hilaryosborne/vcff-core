<?php

class VCFF_Settings_Checkbox_Item extends VCFF_Settings_Item {
    
    public function Render() {
        // Convert attrs to vars
        extract(shortcode_atts(array(
            'machine_code' => '',
            'field_label' => '',
            'field_type' => '',
            'field_group' => '',
            'field_extra_class' => '',
            'extra_class' => '',
            'hints_html' => '',
            'checkbox_value' => '',
        ), $this->data));
        // Start gathering content
        ob_start();
        // Include the template file
        include(VCFF_SETTINGS_DIR.'/context/'.get_class($this).".tpl.php");
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Retrieve the output html
        $output_html = apply_filters('vcff_settings_render_field',$output,$this);
        // Return the contents
        return $output_html;
    }
    
}