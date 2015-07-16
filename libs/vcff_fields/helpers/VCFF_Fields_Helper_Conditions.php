<?php

class VCFF_Fields_Helper_Conditions {
	
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
    
		$this->_Pre_Conditional();
        
        $this->_Check_Standard_Conditional();
        
        $this->_Check_Method_Conditional();
        
		$this->_Check_Hook_Conditional();
        
        $this->_Post_Conditional();
		
		return $this;
	}
	
    protected function _Pre_Conditional() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's fields
        $form_fields = $form_instance->fields;
		// If there are no form fields
		if (!$form_fields || !is_array($form_fields)) { return; }
		// Loop through each containers
		foreach ($form_fields as $_name => $field) {
			// If this field has a custom validation method
			if (method_exists($field,'Pre_Conditional')) { $field->Pre_Conditional(); }
            // Retrieve the validation result
            do_action('vcff_pre_field_conditional', $field);
        }
    }
    
    protected function _Check_Standard_Conditional() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form fields
        $form_fields = $form_instance->fields;
        // If a list of form containers was returned
		if (!$form_fields || !is_array($form_fields)) { return $this; }
		// Loop through each containers
		foreach ($form_fields as $k => $field) {
			// Check the containers conditions
			$field->Check_Conditions();
		}
	}
    
    protected function _Check_Method_Conditional() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's fields
        $form_fields = $form_instance->fields;
		// If there are no form fields
		if (!$form_fields || !is_array($form_fields)) { return; }
		// Loop through each containers
		foreach ($form_fields as $_name => $field) {
			// If this field has a custom validation method
			if (!method_exists($field,'Do_Conditional')) { continue; }
			// Retrieve the validation result
			$field->Do_Conditional();
		}
	}
    
    protected function _Check_Hook_Conditional() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's fields
        $form_fields = $form_instance->fields;
		// If there are no form fields
		if (!$form_fields || !is_array($form_fields)) { return; }
		// Loop through each containers
		foreach ($form_fields as $_name => $field) {
			// Retrieve the validation result
            do_action('vcff_do_field_conditional', $field );
		}
	}
    
    protected function _Post_Conditional() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's fields
        $form_fields = $form_instance->fields;
		// If there are no form fields
		if (!$form_fields || !is_array($form_fields)) { return; }
		// Loop through each containers
		foreach ($form_fields as $_name => $field) { 
			// If this field has a custom validation method
			if (method_exists($field,'Post_Conditional')) { $field->Post_Conditional(); }
            // Retrieve the validation result
            do_action('vcff_post_field_conditional', $field);
		}
    }
	
}