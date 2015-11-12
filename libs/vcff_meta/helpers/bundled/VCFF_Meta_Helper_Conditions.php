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
    
		$this->_Before_Conditional();
        
		$this->_Check_Conditional();
        
        $this->_After_Conditional();
		
		return $this;
	}
    
    protected function _Before_Conditional() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the meta fields
		$meta_fields = $form_instance->meta;
		// If there are no form fields
		if (!$meta_fields || !is_array($meta_fields)) { return; }
		// Loop through each containers
		foreach ($meta_fields as $machine_code => $field_instance) {
			// If this field has a custom validation method
			if (method_exists($field_instance,'Before_Conditional')) { $field_instance->Pre_Conditional(); }
            // Do any actions
            $field_instance->Do_Action('before_conditional',array());
            // Retrieve the validation result
            do_action('vcff_pre_meta_field_conditional', $field_instance);
        }
    }
    
    protected function _Check_Conditional() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the meta fields
		$meta_fields = $form_instance->meta;
		// If there are no form fields
		if (!$meta_fields || !is_array($meta_fields)) { return; }
		// Loop through each containers
		foreach ($meta_fields as $machine_code => $field_instance) {
            // If this field has a custom validation method
			if (method_exists($field_instance,'Check_Field_Conditions')) { $field_instance->Check_Field_Conditions(); }
            // If this field has a custom validation method
			if (method_exists($field_instance,'Do_Conditional')) { $field_instance->Do_Conditional(); }
            // Do any actions
            $field_instance->Do_Action('conditional',array());
            // Retrieve the validation result
            do_action('vcff_do_meta_field_conditional', $field_instance );
		}
	}
    
    protected function _After_Conditional() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the meta fields
		$meta_fields = $form_instance->meta;
		// If there are no form fields
		if (!$meta_fields || !is_array($meta_fields)) { return; }
		// Loop through each containers
		foreach ($meta_fields as $machine_code => $field_instance) { 
			// If this field has a custom validation method
			if (method_exists($field_instance,'After_Conditional')) { $field_instance->Post_Conditional(); }
            // Do any actions
            $field_instance->Do_Action('after_conditional',array());
            // Retrieve the validation result
            do_action('vcff_post_meta_field_conditional', $field_instance);
		}
    }
  
}