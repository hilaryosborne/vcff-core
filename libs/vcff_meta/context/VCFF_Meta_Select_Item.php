<?php

class VCFF_Meta_Select_Item extends VCFF_Meta_Item {

    public function Contextual_Render($context='form') {
        // Convert attrs to vars
        extract(shortcode_atts(array(
            'machine_code' => '',
            'field_label' => '',
            'field_type' => '',
            'field_group' => '',
            'field_extra_class' => '',
            'extra_class' => '',
            'values' => '',
            'hints_html' => '',
            'default_value' => '',
        ), $this->data));
        // Start gathering content
        ob_start();
        // Include the template file
        include(VCFF_META_DIR.'/context/'.get_class($this).".tpl.php");
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