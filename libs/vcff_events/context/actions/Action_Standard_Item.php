<?php

class Action_Standard_Item extends VCFF_Action_Item {

    public function Render() {
        // Retrieve any validation errors
        $validation_errors = $this->validation_errors;
        // Retrieve the context director
        $template_dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Start gathering content
        ob_start();
        // Include the template file
        include(vcff_get_file_dir($template_dir.'/'.get_class($this).'.tpl.php'));
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Return the output
        return $output;
    }

    public function Check_Validation() {
        
        $data = $this->data;

        if (!isset($data['name']) || $data['name'] == '') {
            // Add an alert to notify of field requirements
            $this->validation_errors['name'] = true;
        }
        
        if (!isset($data['code']) || $data['code'] == '') {
            // Add an alert to notify of field requirements
            $this->validation_errors['code'] = true;
        }
        
        if (!isset($data['selected_event']) || $data['selected_event'] == '') {
            // Add an alert to notify of field requirements
            $this->validation_errors['selected_event'] = true;
        }
        
        if (!isset($data['selected_trigger']) || $data['selected_trigger'] == '') {
            // Add an alert to notify of field requirements
            $this->validation_errors['selected_trigger'] = true;
        }
        
        if (!is_array($this->validation_errors)) { return; }
        
        if (count($this->validation_errors) == 0) { return; }
        
        $this->is_valid = false;
    }
}