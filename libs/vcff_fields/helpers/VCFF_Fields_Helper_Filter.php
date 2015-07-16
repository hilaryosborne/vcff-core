<?php

class VCFF_Fields_Helper_Filter {
    
    protected $form_instance;	
	
	protected $error;
	
	public function Get_Error() {
		
		return $this->error;
	}

	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
	
	public function Filter() {
		
        $this->_Pre_Filter();
        
        $this->_Do_Standard_Filter();
        
        $this->_Do_Method_Filter();
        
		$this->_Do_Hook_Filter();
        
        $this->_Post_Filter();
	}
    
    protected function _Pre_Filter() {
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
			// If this field has a custom filter method
			if (method_exists($field,'Pre_Filter')) { $field->Pre_Filter(); }
            // Retrieve the filter result
            do_action('vcff_pre_field_filter', $field);
        }
    }
    
    protected function _Post_Filter() {
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
			// If this field has a custom filter method
			if (method_exists($field,'Post_Filter')) { $field->Post_Filter(); }
            // Retrieve the filter result
            do_action('vcff_post_field_filter', $field);
		}
    }
    
    protected function _Do_Standard_Filter() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's fields
        $form_fields = $form_instance->fields;
        // If a list of form containers was returned
		if (!$form_fields || !is_array($form_fields)) { return $this; }
		// Loop through each containers
		foreach ($form_fields as $machine_code => $field_instance) {
			// Check the containers conditions
			$field_instance->Do_Field_Filter();
		}
	} 
    
	protected function _Do_Method_Filter() {
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
			// If this field has a custom filter method
			if (!method_exists($field,'Do_Filter')) { continue; }
			// Retrieve the filter result
			$field->Do_Filter();
		}
	}
    
    protected function _Do_Hook_Filter() {
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
			// Retrieve the filter result
            do_action('vcff_do_field_filter', $field );
		}
	}
    
}