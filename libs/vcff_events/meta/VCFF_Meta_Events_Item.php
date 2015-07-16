<?php

class VCFF_Meta_Events_Item extends VCFF_Meta_Item {

    public function Contextual_Render($context='form') {
        // Convert attrs to vars
        extract(shortcode_atts(array(
            'machine_code' => '',
            'field_label' => '',
            'field_type' => '',
            'field_group' => '',
            'required' => '',
            'values' => '',
            'default_value' => '',
        ), $this->data));
        
        $form_instance = $this->form_instance;
        // Start gathering content
        ob_start();
        // Include the template file
        include(VCFF_EVENTS_DIR.'/meta/'.get_class($this).".tpl.php");
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Return the contents
        return $output;
    }
    
}