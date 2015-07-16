<?php

class VCFF_Container_Item extends VCFF_Item {

    public $form;
    
    /**
    * MACHINE CODE
    * Name of the field within the actual form
    */
    public $machine_code;
    
    public function Get_Machine_Code() {
        // Return the type value
        return $this->machine_code;
    }
    
    public $container_type;

    public $fields = array();

    public $attributes;
    
    public $context;

    public $result_validation;

    public $result_conditions;

    public $is_hidden = false;

    public $is_valid = false;

    public function Is_Hidden() {

        return $this->is_hidden;
    }

    public function Is_Visible() {

        return $this->is_hidden ? false : true;
    }

    public function Is_Valid() {

        return $this->is_valid;
    }
    
    public function Get_Label() {
        
        
        return $this->attributes['label'];
    }

    /**
     * ALERTS
     */
    public $alerts;
    
	public function Post_Conditional() { }
    
    
    public function Get_AJAX_Data() {
        
        return array();
    }
    
	public function Add_Field($field_instance) {
		
		$machine_code = $field_instance->machine_code;
		
		$field_instance->container_instance = $this;
		
		$this->fields[$machine_code] = $field_instance;
		
	}
    
    public function Get_TEXT_Value() {
        // If the container is hidden, return out
        if ($this->Is_Hidden()) { return; }
        // The text string
        $text = "# ".strtoupper($this->Get_Label())."\n";
        // Add a line
        $text .= "----------------------------------"."\n\r";
        // Retrieve the form fields
        $container_fields = $this->fields;
        // If there are no form fields
        if (!$container_fields || !is_array($container_fields) || count($container_fields) == 0) { return ''; }
        // Loop through each of the found curly tags
        foreach ($container_fields as $machine_code => $field_instance) {
            // If the field is hidden
            if ($field_instance->Is_Hidden()) { continue; }
            // If the field is hidden
            if (!$field_instance->Get_Value()) { continue; }
            // Build the field html
            $text .= $field_instance->Get_TEXT_Value()."\n\r";
        }
        // Add a line
        $text .= "----------------------------------"."\n\r";
        // Return the text
        return $text;
    }
    
    public function Get_HTML_Value() {
        // If the container is hidden, return out
        if ($this->Is_Hidden()) { return; }
        // The text string
        $html = '<div class="container">';
        // Start the html container
        $html .= '  <div class="container-title">'.$this->Get_Label().'</div>';
        // Start the html container
        $html .= '  <div class="container-fields">';
        // Retrieve the form fields
        $container_fields = $this->fields;
        // If there are no form fields
        if (!$container_fields || !is_array($container_fields) || count($container_fields) == 0) { return ''; }
        // Loop through each of the found curly tags
        foreach ($container_fields as $machine_code => $field_instance) {
            // If the field is hidden
            if ($field_instance->Is_Hidden()) { continue; }
            // If the field is hidden
            if (!$field_instance->Get_Value()) { continue; }
            // Build the field html
            $html .= '<div>'.$field_instance->Get_HTML_Value().'</div>';
        }
        // End the HTML container
        $html .= '  </div>';
        // End the HTML container
        $html .= '</div>';
        // Return the HTML
        return $html;
    }
    
    public function Get_Curly_Tags() {
        
        return array();
    }
    
    public function Check_Conditions() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// If the container already has a condition result
        // This means the container has already been checked
        if (isset($this->result_conditions) && is_array($this->result_conditions)) { return ; }
        // If no conditions, return true
        if (!isset($this->attributes['conditions'])) {
            // Set the field hidden flag
            $this->is_hidden = false;
			// Set the field's conditions result
			$this->result_conditions = array(
				'result' => 'visible',
			); return;
		}
        // Retrieve the form's fields
        $form_fields = $form_instance->fields;
        // Decode the field's conditions
        $container_conditions = json_decode(base64_decode($this->attributes['conditions']));
        // Various condition vars
        $condition_use = $container_conditions->use_conditions;
        $condition_visibility = $container_conditions->visibility;
        $condition_target = $container_conditions->target;
        // The incremental vars
        $conditions_failed = array();
        $conditions_passed = array();
        // Loop through each condition
        foreach($container_conditions->conditions as $k => $_container_condition){
            // Retrieve the condition's settings
            $check_field = $_container_condition->check_field;
            $check_condition = $_container_condition->check_condition;
            $check_value = $_container_condition->check_value;
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
                if ($check_result) { $conditions_passed[$k] = $_container_condition; } else { $conditions_failed[$k] = $_container_condition; }
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
		// Set the container
		$container_visible = false;
        // If the container is to be show on passing conditions
        if ($condition_visibility == 'show') {
            // If we require all fields to pass
            if ($condition_target == 'all') {
                // The container will be visible if no conditions failed
                $container_visible = count($conditions_failed) == 0 ? true : false; 
            } // Otherwise if we only require some conditions to pass 
            elseif ($condition_target == 'any') {
                // The container will be visible if at least one conditions passed
                $container_visible = count($conditions_passed) != 0 ? true : false;
            }
        } // Otherwise if the container is to be hidden on passing conditions 
        elseif ($condition_visibility == 'hide') {
            // If we require all fields to pass
            if ($condition_target == 'all') {
                // The container will not be visible if no conditions failed
                $container_visible = count($conditions_failed) == 0 ? false : true; 
            } // Otherwise if we only require some conditions to pass 
            elseif ($condition_target == 'any') {
                // The container will not be visible if at least one conditions passed
                $container_visible = count($conditions_passed) != 0 ? false : true;
            }
        }
        // If the container is not going to visible
        if (!$container_visible) {
            // Set the field hidden flag
            $this->is_hidden = true;
            // Set the container's conditions result
            $this->result_conditions = array(
                'result' => 'hidden',
                'triggered_by' => 'fields',
                'conditions_passed' => $conditions_passed,
                'conditions_failed' => $conditions_failed,
            ); 
        }// Otherwise if the container is visible
        else {
            // Set the field hidden flag
            $this->is_hidden = false;
            // Set the container's conditions result
            $this->result_conditions = array(
                'result' => 'visible',
                'triggered_by' => 'fields',
                'conditions_passed' => $conditions_passed,
                'conditions_failed' => $conditions_failed,
            );
        }
	}
}