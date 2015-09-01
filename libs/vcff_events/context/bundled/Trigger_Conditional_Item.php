<?php

class Trigger_Conditional_Item extends VCFF_Trigger_Item {
    
    public $conditions_item;
    
    public function Render() {
        
        $this->_Prepare();
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
    
    protected function _Get_Submission_Status() {
        
        $trigger_value = $this->value;
        
        return isset($trigger_value['submission_status']) ? $trigger_value['submission_status'] : false;
    }
    
    protected function _Get_Rules() {
        
        $trigger_value = $this->value;
        
        return isset($trigger_value['rules']) ? $trigger_value['rules'] : false;
    }
    
    protected function _Get_Criteria() {
    
        $trigger_value = $this->value;
        
        return isset($trigger_value['criteria']) ? $trigger_value['criteria'] : false;
    }
    
    public function Check_Validation() {

        $action_instance = $this->action_instance;

        if (!$this->_Get_Submission_Status()) {
            // Add an alert to notify of field requirements
            $this->validation_errors['submission_status'] = true;
        }
        
        if (!$this->_Get_Criteria()) {
            // Add an alert to notify of field requirements
            $this->validation_errors['criteria'] = true;
        }

        $rules = $this->_Get_Rules();

        if (!$rules || !is_array($rules) || count($rules) == 0) {
            // Add an alert to notify of field requirements
            $this->validation_errors['rules'] = true;
        }

        if (!is_array($this->validation_errors)) { return; }
        
        if (count($this->validation_errors) == 0) { return; }
        
        $action_instance->is_valid = false;
    }
    
    protected function _Prepare() {
        
        $form_instance = $this->form_instance;
        
        $this->conditions_item = new VCFF_Conditions_Item($this);
        
        $this->conditions_item
            ->Set_Form_Instance($form_instance)
            ->Prepare();
    }
    
    protected function _Els() {
        
        $conditions_item = $this->conditions_item;
        
        $_els = $conditions_item->els;
        
        $_json = array();
        
        if (!$_els || !is_array($_els)) { return $_json;  }
        
        foreach ($_els as $k => $_el) {
            
            $_json[$k] = array(
                'machine_code' => $_el['machine_code'],
                'logic_rules' => $_el['logic_rules'],
            );
        }
        
        return $_json;
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
        // Retrieve the trigger condition
        $trigger_value = $this->value; 
		// If the event has no rules
        if (!isset($trigger_value['rules'])) { return false; }
        // Retrieve the saved rules
        $trigger_rules = $trigger_value['rules'];
        // If the event has no criteria
        if (!isset($trigger_value['criteria'])) { return false; }
        // Retrieve the conditions require
        $trigger_criteria = $trigger_value['criteria'];
        // Create a new conditions item
        $conditions_item = new VCFF_Conditions_Item($this);
        // Check the conditions item
        $conditions_item
            ->Set_Form_Instance($this->form_instance)
            ->Set_Rules($trigger_rules)
            ->Prepare()
            ->Check_Rules();
        // Retrieve the number of triggered rules
        $_triggered = count($conditions_item->Get_Triggered());
        // Retrieve the number of non triggered rules
        $_non_triggered = count($conditions_item->Get_Non_Triggered()); 
        // If we require all fields to pass
        if ($trigger_criteria == 'all') {
            // The container will be visible if no conditions failed
            return $_non_triggered == 0 ? true : false; 
        } // Otherwise if we only require some conditions to pass 
        elseif ($trigger_criteria == 'any') { 
            // The container will be visible if at least one conditions passed
            return $_triggered != 0 ? true : false;
        }
    }
}