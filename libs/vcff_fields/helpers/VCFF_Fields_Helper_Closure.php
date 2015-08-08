<?php

class VCFF_Fields_Helper_Closure extends VCFF_Helper {

    protected $form_instance;	
	
	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
	
    public function Check() {
        
        $this->_Pre_Closure();
        
        $this->_Check_Standard_Closure();
        
        $this->_Post_Closure();
		
		return $this;
    }
    
    protected function _Pre_Closure() {
        /// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form fields
        $form_fields = $form_instance->fields;
        // If a list of form fields was returned
		if (!$form_fields || !is_array($form_fields)) { return $this; }
		// Loop through each of the fields
		foreach ($form_fields as $k => $field) {
			// If this field has a custom validation method
			if (method_exists($field,'Pre_Closure')) { $field->Pre_Closure(); }
            // Retrieve the validation result
            do_action('vcff_pre_field_closure', $field);
        }
    }
    
     public function _Check_Standard_Closure() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form fields
        $form_fields = $form_instance->fields;
        // If a list of form fields was returned
		if (!$form_fields || !is_array($form_fields)) { return $this; }
		// Loop through each of the fields
		foreach ($form_fields as $k => $field) {
            // If this field has a custom validation method
			if (method_exists($field,'Do_Closure')) { $field->Do_Closure(); }
			// Retrieve the validation result
            do_action('vcff_field_closure', $field);
        }
	}
    
    protected function _Post_Closure() {
        /// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form fields
        $form_fields = $form_instance->fields;
        // If a list of form fields was returned
		if (!$form_fields || !is_array($form_fields)) { return $this; }
		// Loop through each of the fields
		foreach ($form_fields as $k => $field) {
			// If this field has a custom validation method
			if (method_exists($field,'Post_Closure')) { $field->Post_Closure(); }
            // Retrieve the validation result
            do_action('vcff_post_field_closure', $field);
		}
    }
    
}