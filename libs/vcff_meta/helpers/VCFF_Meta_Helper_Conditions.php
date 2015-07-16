<?php

class VCFF_Meta_Helper_Conditions {
	
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
		// Retrieve the meta fields
		$meta_fields = $form_instance->meta;
		// If there are no form fields
		if (!$meta_fields || !is_array($meta_fields)) { return; }
		// Loop through each containers
		foreach ($meta_fields as $machine_code => $field_instance) {
			// If this field has a custom validation method
			if (method_exists($field_instance,'Pre_Conditional')) { $field_instance->Pre_Conditional(); }
            // Retrieve the validation result
            do_action('vcff_pre_meta_field_conditional', $field_instance);
        }
    }
    
    protected function _Check_Standard_Conditional() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the meta fields
		$meta_fields = $form_instance->meta;
        // If a list of form containers was returned
		if (!$meta_fields || !is_array($meta_fields)) { return $this; }
		// Loop through each containers
		foreach ($meta_fields as $machine_code => $field_instance) {
			// Check the containers conditions
			$field_instance->Check_Field_Conditions();
		}
	} 
    
    protected function _Check_Method_Conditional() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the meta fields
		$meta_fields = $form_instance->meta;
		// If there are no form fields
		if (!$meta_fields || !is_array($meta_fields)) { return; }
		// Loop through each containers
		foreach ($meta_fields as $machine_code => $field_instance) {
			// If this field has a custom validation method
			if (!method_exists($field_instance,'Do_Conditional')) { continue; }
			// Retrieve the validation result
			$field_instance->Do_Conditional();
		}
	}
    
    protected function _Check_Hook_Conditional() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the meta fields
		$meta_fields = $form_instance->meta;
		// If there are no form fields
		if (!$meta_fields || !is_array($meta_fields)) { return; }
		// Loop through each containers
		foreach ($meta_fields as $machine_code => $field_instance) {
			// Retrieve the validation result
            do_action('vcff_do_meta_field_conditional', $field_instance );
		}
	}
    
    protected function _Post_Conditional() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the meta fields
		$meta_fields = $form_instance->meta;
		// If there are no form fields
		if (!$meta_fields || !is_array($meta_fields)) { return; }
		// Loop through each containers
		foreach ($meta_fields as $machine_code => $field_instance) { 
			// If this field has a custom validation method
			if (method_exists($field_instance,'Post_Conditional')) { $field_instance->Post_Conditional(); }
            // Retrieve the validation result
            do_action('vcff_post_meta_field_conditional', $field_instance);
		}
    }
  
}