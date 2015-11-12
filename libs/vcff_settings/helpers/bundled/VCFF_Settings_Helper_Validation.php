<?php

class VCFF_Settings_Helper_Validation extends VCFF_Helper {
	
    protected $form_instance;	

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
        
        $this->_Validate_Form();
        
        return $this;
	}
	
    public function Get_Passed() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the meta fields
		$fields = $form_instance->fields;
		// If there are no form fields
		if (!$fields || !is_array($fields)) { return array(); }
        // List to store the relevant fields
        $qualifying_list = array();
        // Loop through each containers
		foreach ($fields as $machine_code => $field_instance) {
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
		// Retrieve the meta fields
		$fields = $form_instance->fields;
		// If there are no form fields
		if (!$fields || !is_array($fields)) { return array(); }
        // List to store the relevant fields
        $qualifying_list = array();
        // Loop through each containers
		foreach ($fields as $machine_code => $field_instance) {
            // If the feidl is valid
            if ($field_instance->Is_Valid()) { continue; } 
            // Add the instance to the list
            $qualifying_list[] = $field_instance;
        }
        // Return the list
        return $qualifying_list;
    }
    
    protected function _Validate_Form() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
        // Retrieve the list of failed fields
        $failed_fields = $this->Get_Failed();
        // If there are failed fields
        if (is_array($failed_fields) && count($failed_fields) > 0) {
            // Set the form validity as false
            $form_instance->is_valid = false;
        } // Otherwise set as true
        else { $form_instance->is_valid = true; }
        // If this field has a custom validation method
        if (method_exists($form_instance,'Do_Validation')) { $form_instance->Do_Validation(); }
        // Retrieve the validation result
        do_action('vcff_setting_form_validation',$form_instance);
    }
    
    protected function _Pre_Validation() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the meta fields
		$fields = $form_instance->fields;
		// If there are no form fields
		if (!$fields || !is_array($fields)) { return; }
		// Loop through each containers
		foreach ($fields as $machine_code => $field_instance) {
			// If this field has a condition result and the field is hidden
			if ($field_instance->Is_Hidden()) { continue; }
			// If this field has a custom validation method
			if (method_exists($field_instance,'Pre_Validation')) { $field_instance->Pre_Validation(); }
            // Retrieve the validation result
            do_action('vcff_pre_setting_field_validation',$field_instance);
        }
    }
    
    protected function _Post_Validation() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the meta fields
		$fields = $form_instance->fields;
		// If there are no form fields
		if (!$fields || !is_array($fields)) { return; }
		// Loop through each containers
		foreach ($fields as $machine_code => $field_instance) {
			// If this field has a condition result and the field is hidden
			if ($field_instance->Is_Hidden()) { continue; }  
			// If this field has a custom validation method
			if (method_exists($field_instance,'Post_Validation')) { $field_instance->Post_Validation(); }
            // Retrieve the validation result
            do_action('vcff_post_setting_field_validation',$field_instance);
		}
    }
    
    protected function _Check_Standard_Validation() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the meta fields
		$fields = $form_instance->fields; 
		// If there are no form fields
		if (!$fields || !is_array($fields)) { return; }
		// Loop through each containers
		foreach ($fields as $machine_code => $field_instance) { 
			// Check the containers conditions
			$field_instance->Check_Field_Validation();
		}
	} 
    
	protected function _Check_Method_Validation() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the meta fields
		$fields = $form_instance->fields;
		// If there are no form fields
		if (!$fields || !is_array($fields)) { return; }
		// Loop through each containers
		foreach ($fields as $machine_code => $field_instance) {
			// If this field has a custom validation method
			if (!method_exists($field_instance,'Do_Validation')) { continue; }
			// Retrieve the validation result
			$field_instance->Do_Validation();
		}
	}
    
    protected function _Check_Hook_Validation() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the meta fields
		$fields = $form_instance->fields;
		// If there are no form fields
		if (!$fields || !is_array($fields)) { return; }
		// Loop through each containers
		foreach ($fields as $machine_code => $field_instance) {
			// If this field has a condition result and the field is hidden
			if ($field_instance->Is_Hidden()) { continue; }  
			// Retrieve the validation result
            do_action('vcff_do_setting_field_validation',$field_instance);
		}
	}
    
}