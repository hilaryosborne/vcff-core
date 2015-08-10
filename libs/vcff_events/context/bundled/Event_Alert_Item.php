<?php

class Event_Alert_Item extends VCFF_Event_Item {
	
	public function Render() {
        // Retrieve any validation errors
        $validation_errors = $this->validation_errors;
        // Retrieve the context director
        $action_dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // If context data was passed
        $posted_data = $this->data;
        // Start gathering content
        ob_start();
        // Include the template file
        include($action_dir.'/'.get_class($this).'.tpl.php');
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Return the contents
        return $output;
    }
    
    public function Check_Validation() {

        $action_instance = $this->action_instance;

        if (!$this->Get_Type()) {
            // Add an alert to notify of field requirements
            $this->validation_errors['type'] = true;
        }
        
        if (!$this->Get_Message()) {
            // Add an alert to notify of field requirements
            $this->validation_errors['message'] = true;
        }

        if (!is_array($this->validation_errors)) { return; }
        
        if (count($this->validation_errors) == 0) { return; }
        
        $action_instance->is_valid = false;
    }
    
    protected function Get_Type() {
        
        if (!isset($this->value['type'])) { return; }
        
        return $this->value['type'];
    }
    
    protected function Get_Message() {
        
        if (!isset($this->value['message'])) { return; }
        
        return $this->value['message'];
    }

    public function Trigger() {
        
        $html_content = vcff_curly_compile($this->form_instance,$this->Get_Message());
        
        $form_instance = $this->form_instance;
        
        $form_instance->Add_Alert($html_content,$this->Get_Type());
    }
}
