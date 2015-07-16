<?php 

class VCFF_Fields_Helper_Validation {
	
	protected $form_instance;	
	
	protected $error;
	
	public function Get_Error() {
		
		return $this->error;
	}

	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
	
	public function Check() {
		
        $this->_Pre_Validation();
        
        $this->_Check_Standard_Validation();
        
        $this->_Check_Method_Validation();
        
		$this->_Check_Hook_Validation();
        
        $this->_Post_Validation();
	}
	
    public function Get_Passed() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's fields
        $form_fields = $form_instance->fields;
		// If there are no form fields
		if (!$form_fields || !is_array($form_fields)) { return array(); }
        // List to store the relevant fields
        $qualifying_list = array();
        // Loop through each containers
		foreach ($form_fields as $machine_code => $field_instance) {
            // If the feidl is not valid
            if (!$field_instance->Is_Valid()) { continue; }
            // Add the instance to the list
            $qualifying_list[] = $field_instance;
        }
        // Return the list
        return $qualifying_list;
    }
    
    public function Get_Failed() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's fields
        $form_fields = $form_instance->fields;
		// If there are no form fields
		if (!$form_fields || !is_array($form_fields)) { return array(); }
        // List to store the relevant fields
        $qualifying_list = array();
        // Loop through each containers
		foreach ($form_fields as $machine_code => $field_instance) {
            // If the feidl is valid
            if ($field_instance->Is_Valid()) { continue; }
            // Add the instance to the list
            $qualifying_list[] = $field_instance;
        }
        // Return the list
        return $qualifying_list;
    }
    
    protected function _Pre_Validation() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's fields
        $form_fields = $form_instance->fields;
		// If there are no form fields
		if (!$form_fields || !is_array($form_fields)) { return; }
		// Loop through each containers
		foreach ($form_fields as $_name => $field) {
			// If this field has a condition result and the field is hidden
			if ($field->Is_Hidden()) { continue; }
			// If this field has a custom validation method
			if (method_exists($field,'Pre_Validation')) { $field->Pre_Validation(); }
            // Retrieve the validation result
            do_action('vcff_pre_field_validation', $field);
        }
    }
    
    protected function _Post_Validation() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's fields
        $form_fields = $form_instance->fields;
		// If there are no form fields
		if (!$form_fields || !is_array($form_fields)) { return; }
		// Loop through each containers
		foreach ($form_fields as $_name => $field) {
			// If this field has a condition result and the field is hidden
			if ($field->Is_Hidden()) { continue; }  
			// If this field has a custom validation method
			if (method_exists($field,'Post_Validation')) { $field->Post_Validation(); }
            // Retrieve the validation result
            do_action('vcff_post_field_validation', $field);
		}
    }
    
    protected function _Check_Standard_Validation() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's fields
        $form_fields = $form_instance->fields;
        // If a list of form containers was returned
		if (!$form_fields || !is_array($form_fields)) { return $this; }
		// Loop through each containers
		foreach ($form_fields as $machine_code => $field_instance) {
			// Check the containers conditions
			$field_instance->Check_Field_Validation();
		}
	} 
    
	protected function _Check_Method_Validation() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's fields
        $form_fields = $form_instance->fields;
		// If there are no form fields
		if (!$form_fields || !is_array($form_fields)) { return; }
		// Loop through each containers
		foreach ($form_fields as $_name => $field) {
			// If this field has a condition result and the field is hidden
			if ($field->Is_Hidden()) { continue; }   
			// If this field has a custom validation method
			if (!method_exists($field,'Do_Validation')) { continue; }
			// Retrieve the validation result
			$field->Do_Validation();
		}
	}
    
    protected function _Check_Hook_Validation() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's fields
        $form_fields = $form_instance->fields;
		// If there are no form fields
		if (!$form_fields || !is_array($form_fields)) { return; }
		// Loop through each containers
		foreach ($form_fields as $_name => $field) {
			// If this field has a condition result and the field is hidden
			if ($field->Is_Hidden()) { continue; }  
			// Retrieve the validation result
            do_action('vcff_do_field_validation', $field );
		}
	}
	
}