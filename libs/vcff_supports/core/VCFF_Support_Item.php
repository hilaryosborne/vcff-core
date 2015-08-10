<?php

class VCFF_Support_Item extends VCFF_Item {

    /**
         * ATTRIBUTES
         * Attributes for this field instance
         */
    public $attributes;
    
    /**
     * THE FORM INSTANCE
     */
    public $form_instance;
    
    /**
    * CONTEXT DATA
    * The class which handles vc integration
    */
    public $context;
    
    public function Is_Visible() { 
        // If the field is attached to a container
        if ($this->container_instance && is_object($this->container_instance)) {
            // Retrieve the container object
            $field_container = $this->container_instance;
            // Return the hidden value of the container
            if ($field_container->Is_Hidden()) { return false; }
        }
        // Return the hidden flag
        return $this->is_hidden ? false : true;
    }
    
    public function Check_Conditions() { 
		// Retrieve the form instance
		$form_instance = $this->form_instance;
        // If the field already has a condition result
        // This means the field has already been checked
        if (isset($this->result_conditions) && is_array($this->result_conditions)) { return false; }
        // Retrieve the form's fields
        $form_fields = $form_instance->fields;
		// If no conditions are yet set
		if (!isset($this->attributes['conditions'])) { 
			// Set the field's conditions result
			$this->result_conditions = array(
				'result' => 'visible',
			); return;
		}
        // Decode the field's conditions
        $field_conditions = json_decode(base64_decode($this->attributes['conditions']),true); 
        // If a list of conditions were returned
		if ($field_conditions && is_array($field_conditions['conditions'])) {}
		// Various condition vars
		$condition_visibility = $field_conditions['visibility'];
		$condition_target = $field_conditions['target'];
		// The incremental vars
		$conditions_failed = array();
		$conditions_passed = array();
		// Loop through each condition
		foreach($field_conditions['conditions'] as $k => $condition_item){
			// Retrieve the condition's settings
			$check_field = $condition_item['check_field'];
			$check_condition = $condition_item['check_condition'];
			$check_value = $condition_item['check_value'];
            // If ther condition item is not valid
            if (!$check_field || !$check_condition || !$check_value) { continue; } 
			// If the required field is present
			if ($form_fields[$check_field]) { 
				// Retrieve the field instance
				$field_instance = $form_fields[$check_field];
				// Create the checking method name
				$field_instance_check_method = 'Check_Rule_'.strtoupper($check_condition);
				// Check the method exists
				if (!method_exists($field_instance,$field_instance_check_method)) { continue; }
				// Call the checking method
				$check_result = call_user_func_array(array($field_instance, $field_instance_check_method), array($check_value));
				// Increment the correct variable
				if ($check_result) { $conditions_passed[$k] = $condition_item; } else { $conditions_failed[$k] = $condition_item; }
			}
		}
        
        if (count($conditions_failed) == 0 && count($conditions_passed) == 0) {
            // Set the field hidden flag
            $this->is_hidden = false;
			// Set the field's conditions result
			$this->result_conditions = array(
				'result' => 'visible',
			); return;
        }
		// If the field is to be show on passing conditions
		if ($condition_visibility == 'show') { 
			// If we require all fields to pass
			if ($condition_target == 'all') {
				// The field will be visible if no conditions failed
				$field_visible = count($conditions_failed) == 0 ? true : false; 
			} // Otherwise if we only require some conditions to pass 
			elseif ($condition_target == 'any') { 
				// The field will be visible if at least one conditions passed
				$field_visible = count($conditions_passed) != 0 ? true : false;
			}
		} // Otherwise if the field is to be hidden on passing conditions 
		elseif ($condition_visibility == 'hide') {
			// If we require all fields to pass
			if ($condition_target == 'all') {
				// The field will not be visible if no conditions failed
				$field_visible = count($conditions_failed) == 0 ? false : true; 
			} // Otherwise if we only require some conditions to pass 
			elseif ($condition_target == 'any') {
				// The field will not be visible if at least one conditions passed
				$field_visible = count($conditions_passed) != 0 ? false : true;
			}
		}
		// If the field is not going to visible
		if (!$field_visible) { 
            // Set the field hidden flag
            $this->is_hidden = true;
			// Set the field's conditions result
			$this->result_conditions = array(
				'result' => 'hidden',
				'triggered_by' => 'fields',
				'conditions_passed' => $conditions_passed,
				'conditions_failed' => $conditions_failed,
			);
		}// Otherwise if the field is visible
		else { 
            // Set the field hidden flag
            $this->is_hidden = false;
			// Set the field's conditions result
			$this->result_conditions = array(
				'result' => 'visible',
				'triggered_by' => 'fields',
				'conditions_passed' => $conditions_passed,
				'conditions_failed' => $conditions_failed,
			);
		}
	}
}