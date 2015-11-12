<?php

class VCFF_Meta_Helper_Validation {

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
		
        $this->_Before_Validation();
        
		$this->_Check_Validation();
        
        $this->_After_Validation();
        
        return $this;
	}
	
    public function Get_Passed() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's fields
        $meta_fields = $form_instance->meta;
		// If there are no form fields
		if (!$meta_fields || !is_array($meta_fields)) { return array(); }
        // List to store the relevant fields
        $qualifying_list = array();
        // Loop through each containers
		foreach ($meta_fields as $machine_code => $field_instance) {
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
        $meta_fields = $form_instance->meta;
		// If there are no form fields
		if (!$meta_fields || !is_array($meta_fields)) { return array(); }
        // List to store the relevant fields
        $qualifying_list = array();
        // Loop through each containers
		foreach ($meta_fields as $machine_code => $field_instance) {
            // If the feidl is valid
            if ($field_instance->Is_Valid()) { continue; } 
            // Add the instance to the list
            $qualifying_list[] = $field_instance;
        }
        // Return the list
        return $qualifying_list;
    }
    
    protected function _Before_Validation() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's fields
        $meta_fields = $form_instance->meta;
		// If there are no form fields
		if (!$meta_fields || !is_array($meta_fields)) { return array(); }
		// Loop through each containers
		foreach ($meta_fields as $machine_code => $field_instance) {
			// If this field has a condition result and the field is hidden
			if ($field_instance->Is_Hidden()) { continue; }
			// If this field has a custom validation method
			if (method_exists($field_instance,'Before_Validation')) { $field_instance->Pre_Validation(); }
            // Do any actions
            $field_instance->Do_Action('before_validation',array());
            // Retrieve the validation result
            do_action('vcff_pre_meta_field_validation',$field_instance);
        }
    }

    protected function _Check_Validation() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the meta fields
		$meta_fields = $form_instance->meta;
        // If a list of form containers was returned
		if (!$meta_fields || !is_array($meta_fields)) { return $this; }
		// Loop through each containers
		foreach ($meta_fields as $machine_code => $field_instance) {
            // If this field has a condition result and the field is hidden
			if ($field_instance->Is_Hidden()) { continue; } 
			// If this field has a custom validation method
			if (method_exists($field_instance,'Check_Field_Validation')) { $field_instance->Check_Field_Validation(); }
            // If this field has a custom validation method
			if (method_exists($field_instance,'Do_Validation')) { $field_instance->Do_Validation(); }
            // Do any actions
            $field_instance->Do_Action('validation',array());
            // Retrieve the validation result
            do_action('vcff_do_meta_field_validation',$field_instance);
		}
	} 
    
    protected function _After_Validation() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's fields
        $meta_fields = $form_instance->meta;
		// If there are no form fields
		if (!$meta_fields || !is_array($meta_fields)) { return array(); }
		// Loop through each containers
		foreach ($meta_fields as $machine_code => $field_instance) {
			// If this field has a condition result and the field is hidden
			if ($field_instance->Is_Hidden()) { continue; }  
			// If this field has a custom validation method
			if (method_exists($field_instance,'After_Validation')) { $field_instance->Post_Validation(); }
            // Do any actions
            $field_instance->Do_Action('after_validation',array());
            // Retrieve the validation result
            do_action('vcff_post_meta_field_validation',$field_instance);
		}
    }
    
}