<?php

class VCFF_Meta_Textfield_Item extends VCFF_Meta_Item {
    
    public function Render() {
        // Convert attrs to vars
        extract(shortcode_atts(array(
            'machine_code' => '',
            'label' => '',
            'type' => '',
            'field_group' => '',
            'field_extra_class' => '',
            'extra_class' => '',
            'hints_html' => '',
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
        // Retrieve the output html
        $output_html = apply_filters('vcff_meta_render_field',$output,$this);
        // Return the contents
        return $output_html;
    }
    
}