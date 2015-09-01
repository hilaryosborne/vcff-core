<?php

class VCFF_Meta_Item extends VCFF_Item {

    public $machine_code;
    
    public $field_label;
    
    public $context;
    
    public $data;
    
    public $value;
    
    public $is_valid = false;
    
    public $is_hidden = false;
    
    public $form_instance;
    
    public $condition_check;

    public function Get_Machine_Code() {
        // Return the type value
        return $this->machine_code;
    }

    public function Get_Label() {
        
        return $this->field_label;
    }
    
    public function Get_Data() {
        
        return $this->data;
    }
    
    public function Get_Value() {
    
        return $this->value;
    }

    /**
         * IS REQUIRED
         * Check if a field is required
         */
    public function Is_Required() {
        // If there are no validation rules
        if (!$this->validation || !isset($this->validation['required'])) { return false; }
        // Otherwise return true
        return true;
    }
    
    public function Is_Valid() {
        
        return $this->is_valid;
    }
    
    public function Is_Hidden() {
        
        return $this->is_hidden;
    }

    public function Store_Value() {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Retrieve the form id
        $form_id = vcff_get_form_id_by_uuid($form_instance->Get_UUID());
        // Update the post meta
        update_post_meta($form_id, $this->machine_code, $this->value);
    }

    /**
     * CHECK FIELD CONDITIONS
     * Checks an individual field for display conditions
     * Assigns visible or hidden depending on outcome
     */
    public function Check_Field_Conditions() {
        // If there are no conditions
        if (!isset($this->data['dependancy'])) {
            // Update the is hidden
            $this->is_hidden = false;
            // Update the field's conditions
            $this->condition_check = array(
                'result' => 'visible'
            ); return;
        }
		// Retrieve the dependancy conditions
        $dependancy = $this->data['dependancy']; 
		// If there are no conditions
        if (!is_array($dependancy)) {
            // Update the is hidden
            $this->is_hidden = false;
            // Update the field's conditions
            $this->condition_check = array(
                'result' => 'visible'
            ); return;
        } 
		// Retrieve the form instance
		$form_instance = $this->form_instance;
        // Retrieve the dependancy outcome
        $dependancy_outcome = $dependancy['outcome'] ? $dependancy['outcome'] : 'show';
        // Retrieve the dependancy outcome
        $dependancy_require = $dependancy['requires'] ? $dependancy['requires'] : 'all';
        // Retrieve the dependancy conditions
        $dependancy_conditions = $dependancy['conditions'];
        // The incremental vars
        $conditions_failed = array();
        $conditions_passed = array();
        // Loop through each of the conditions
        foreach ($dependancy_conditions as $k => $meta_condition) { 
            // Retrieve the rule params
            $condition_target_meta_field = $meta_condition[0];
            $condition_rule = $meta_condition[1];
            $condition_value = $meta_condition[2]; 
			// If no target meta field was returned
            if (!isset($form_instance->meta[$condition_target_meta_field])) { continue; } 
            // Retrieve the target meta field
            $target_meta_field = $form_instance->meta[$condition_target_meta_field]; 
            // Create the checking method name
            $target_check_method = 'Check_Rule_'.strtoupper($condition_rule); 
            // Check the method exists
            if (!method_exists($target_meta_field,$target_check_method)) { continue; }
            // Call the checking method
            $check_result = call_user_func_array(array($target_meta_field, $target_check_method), array($condition_value)); 
            // Increment the correct variable
            if ($check_result) { $conditions_passed[$k] = $meta_condition; } else { $conditions_failed[$k] = $meta_condition; }
        }   
        // If the field is to be show on passing conditions
        if ($dependancy_outcome == 'show') {
            // If we require all fields to pass
            if ($dependancy_require == 'all') {
                // The field will be visible if no conditions failed
                $field_visible = count($conditions_failed) == 0 ? true : false; 
            } // Otherwise if we only require some conditions to pass 
            elseif ($dependancy_require == 'any') {
                // The field will be visible if at least one conditions passed
                $field_visible = count($conditions_passed) == 0 ? true : false;
            }
        } // Otherwise if the field is to be hidden on passing conditions 
        elseif ($dependancy_outcome == 'hide') {
            // If we require all fields to pass
            if ($dependancy_require == 'all') {
                // The field will not be visible if no conditions failed
                $field_visible = count($conditions_failed) == 0 ? false : true; 
            } // Otherwise if we only require some conditions to pass 
            elseif ($dependancy_require == 'any') {
                // The field will not be visible if at least one conditions passed
                $field_visible = count($conditions_passed) == 0 ? false : true;
            }
        }
        // If the field is not going to visible
        if (!$field_visible) { 
            // Update the is hidden
            $this->is_hidden = true;
        }// Otherwise if the field is visible
        else { $this->is_hidden = false; }
    }
    
    /**
     * CHECK FIELD CONDITIONS
     * Checks an individual field for display conditions
     * Assigns visible or hidden depending on outcome
     */
    public function Check_Field_Validation(){
        // Retrieve the field name
        $machine_code = $this->Get_Machine_Code();
        // Retrieve the field value
        $field_value = $this->Get_Value();
        // Retrieve the field label
        $field_label = $this->Get_Label();
        // If there are no validation rules
        if (!$this->validation) { $this->is_valid = true; return; }
        // If there are no validation rules
        if (!is_array($this->validation)) { $this->is_valid = true; return; }
        // If there are no validation rules
        if (count($this->validation) == 0) { $this->is_valid = true; return; }
        // If there are no validation rules
        if ($this->Is_Hidden()) { $this->is_valid = true; return; }
        // Populate the gump validation class
        $data[$machine_code] = $field_value;
        // Create a new gump validation class
        $gump = new GUMP();
        // Set the fieldname inside of gump
        $gump->set_field_name($machine_code,$machine_code);
        // Retrieve the gump string
        $gump_string = $this->_Get_GUMP_String($this->validation);
        // Populate the validation rules
        $gump->validation_rules(array($machine_code => $gump_string));
        // Run the gump validation
        $validated = $gump->run($data); 
        // If the field failed to validate
        if (!$validated) {
            // Retrieve the gump errors
            $gump_errors = $gump->get_errors_array();
            // Set the validation flag to false
            $this->is_valid = false;
            // Create the error string
            $error_string = str_replace($machine_code,$field_label,$gump_errors[$machine_code]);
            // Add a danger alert for this field
            $this->Add_Alert($error_string,'danger');
        } // Otherwise if the field validated
        else { $this->is_valid = true; }
    }
 
    protected function _Get_GUMP_String($params) {
		// Rule list var
        $rule_list = array();
        // If no list was passed
        if (!is_array($params)) { return ''; }
        // Loop through each param
        foreach ($params as $rule => $rule_param) {
            // Construct each rule string
            $rule_list[] = !is_bool($rule_param) ? $rule.','.$rule_param : $rule ;
        } 
        // Return the gump string
        return implode('|',$rule_list);
	}
 
    /**
         * CONDITIONAL FUNCTIONS
         * 
         */        
    public function Check_Rule_IS($against) {

        return $this->value == $against ? true : false;
    }

    public function Check_Rule_IS_NOT($against) {

        return $this->value != $against ? true : false;
    }

    public function Check_Rule_GREATER_THAN($against) {

        return $this->value > $against ? true : false;
    }

    public function Check_Rule_LESS_THAN($against) {

        return $this->value < $against ? true : false;
    }

    public function Check_Rule_CONTAINS($against) {

        return strpos($this->value, $against) !== false ? true : false;
    }

    public function Check_Rule_STARTS_WITH($against) {

        return strpos($this->value, $against) === 0 ? true : false;
    }

    public function Check_Rule_ENDS_WITH($against) {

        return strpos($this->value, $against) === (strlen($this->value) - strlen($against)) ? true : false;
    }
}