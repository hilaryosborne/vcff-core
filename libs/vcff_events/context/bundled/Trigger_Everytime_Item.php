<?php

class Trigger_Everytime_Item extends VCFF_Trigger_Item {
    
    public function Render() {
        // Retrieve the context director
        $trigger_dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // If context data was passed
        $posted_data = $this->data;
        // Start gathering content
        ob_start();
        // Include the template file
        include($trigger_dir.'/'.get_class($this).'.tpl.php');
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Return the contents
        return $output;
    }
    
    public function Check_Validation() {

        $action_instance = $this->action_instance;

        if (!$this->_Get_Submission_Status()) {
            // Add an alert to notify of field requirements
            $this->validation_errors['submission_status'] = true;
        }

        if (!is_array($this->validation_errors)) { return; }
        
        if (count($this->validation_errors) == 0) { return; }
        
        $action_instance->is_valid = false;
    }
    
    protected function _Get_Submission_Status() {
        
        $trigger_value = $this->value;
        
        return isset($trigger_value['submission_status']) ? $trigger_value['submission_status'] : false;
    }
    
    public function Check() {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // If this trigger requires a successful submission
        if ($this->_Get_Submission_Status() == 'submission') { 
            // If the form did not validate
            if (!in_array($form_instance->form_state,array('submission_ajax','submission_standard'))) { return false; } 
        }
        // If this trigger requires a successful submission
        if ($this->_Get_Submission_Status() == 'submission_success') { 
            // If the form did not validate
            if (!in_array($form_instance->form_state,array('submission_ajax','submission_standard'))) { return false; } 
            // If the form did not validate
            if (!$form_instance->Is_Valid()) { return false; } 
        }
        // If this trigger requires a successful submission
        if ($this->_Get_Submission_Status() == 'submission_failed') {
            // If the form did not validate
            if (!in_array($form_instance->form_state,array('validation_check','submission_ajax','submission_standard'))) { return false; } 
            // If the form did not validate
            if ($form_instance->Is_Valid()) { return false; }  
        }
        // Returns true everytime
        return true;
    }
}