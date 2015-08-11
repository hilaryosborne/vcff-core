<?php

class Trigger_Conditional_Item extends VCFF_Trigger_Item {
    
    public function Render() {
    
        // Retrieve the current rules
        $current_rules = $this->_Get_Current_Rules();
        // Retrieve the current form fields
        $current_fields = $this->_Get_Field_List();
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
    
    protected function _Get_Field_List() {
        // Retrieve the form instance fields
        $form_fields = $this->form_instance->fields;
        // The array for the form fields
        $field_list = array();
        // If a list of field instances was returned
        if (!$form_fields || !is_array($form_fields)) { return array(); }
        // Loop through each field instance
        foreach ($form_fields as $machine_code => $_field_instance) {
            // Retrieve the allowed conditions
            $allowed_conditions = $_field_instance->Get_Allowed_Conditions();
            // If the field does not allow conditions
            if (!$allowed_conditions) { continue; }
            // Populate the field list
            $field_list[$machine_code] = array(
                'machine_code' => $machine_code,
                'field_label' => $_field_instance->Get_Label(),
                'field_conditions' => $_field_instance->Get_Allowed_Conditions()
            );
        }
        // Return the field list
        return $field_list;
    }
    
    protected function _Get_Field_JSON() {
        // Return the field list
        return json_encode($this->_Get_Field_List());
    }
    
    protected function _Get_Current_Rules() {
        // Retrieve the conditions
        $rules = $this->_Get_Rules();
        // If no rules were present
        if (!$rules || !is_array($rules)) { return; }
        // Retrieve the form instance fields
        $form_fields = $this->form_instance->fields;
        // Loop through each of the rules
        foreach ($rules as $k => $rule) {
            // The field list var
            $field_list = array();
            // The field conditions
            $condition_list = false;
            // Loop through each field instance
            foreach ($form_fields as $machine_code => $_field_instance) {
                // Retrieve the allowed conditions
                $allowed_conditions = $_field_instance->Get_Allowed_Conditions();
                // If the field does not allow conditions
                if (!$allowed_conditions) { continue; }
                // Populate the field list
                $field_list[$machine_code] = array(
                    'machine_code' => $machine_code,
                    'field_label' => $_field_instance->Get_Label(),
                    'selected' => $rule['against'] == $machine_code ? true : false
                );
                // If this is the selected field
                if ($rule['against'] == $machine_code) {
                    // Loop through each rule
                    foreach ($allowed_conditions as $rule_name => $rule_label) {
                        // Populate the condition data
                        $condition_list[$rule_name] = array(
                            'rule_name' => $rule_name,
                            'rule_label' => $rule_label,
                            'selected' => $rule['check'] == $rule_name ? true : false
                        );
                    }
                }
            } 
            // Add to the rule date
            $rule_data[] = array(
                'field_list' => $field_list,
                'field_conditions' => $condition_list,
                'field_value' => $rule['value']
            );
        } 
        // Return the rule data
        return $rule_data;
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
        // Retrieve the form's fields
        $form_fields = $form_instance->fields; 
        // The incremental vars
        $rules_failed = array();
        $rules_passed = array(); 
        // Loop through each condition
        foreach($trigger_rules as $k => $rule){
            // Retrieve the condition's settings
            $check_against = $rule['against'];
            $check_check = $rule['check'];
            $check_value = $rule['value']; 
            // If the required field is present
            if (!isset($form_fields[$check_against])) { continue; } 
            // Retrieve the field instance
            $field_instance = $form_fields[$check_against];
            // If the field does not allow for conditions
            if (!$field_instance->allow_conditions) { continue; }
            // Create the checking method name
            $field_instance_check_method = 'Check_Rule_'.strtoupper($check_check);
            // Check the method exists
            if (!method_exists($field_instance,$field_instance_check_method)) { continue; } 
            // Call the checking method
            $check_result = call_user_func_array(array($field_instance, $field_instance_check_method), array($check_value));
            // Increment the correct variable
            if ($check_result) { $rules_passed[$k] = $rule; } else { $rules_failed[$k] = $rule; }
        }
        // If we require all fields to pass
        if ($trigger_criteria == 'all') {
            // The field will be visible if no conditions failed
            $trigger_passed = count($rules_failed) == 0 ? true : false; 
        } // Otherwise if we only require some conditions to pass 
        elseif ($trigger_criteria == 'any') {
            // The field will be visible if at least one conditions passed
            $trigger_passed = count($rules_passed) > 0 ? true : false;
        }
        // Return the 
        return $trigger_passed;
    }
}