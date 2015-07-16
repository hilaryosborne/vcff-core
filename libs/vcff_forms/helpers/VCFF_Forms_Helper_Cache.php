<?php

class VCFF_Forms_Helper_Cache extends VCFF_Helper {

    protected $form_instance;	

	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}

    protected function _Get_Instance_Key() {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
		// If there is no post id
		if (!isset($form_instance->post_id)) { return; }
		// Retrieve the post id
		$post_id = $form_instance->post_id;
		// If there is no form id
		if (!isset($form_instance->form_id)) { return; }
		// Retrieve the form id
		$form_id = $form_instance->form_id;
		// Get the instance reference
		$instance_key = $form_id.'_'.$post_id;
		// Return the instance key
		return $instance_key;
	}

    public function Cache() {
        // Retrieve the global vcff forms class
        $vcff_forms = vcff_get_library('vcff_forms');
        // If no form instance was returned
        if (!isset($this->form_instance)) { return false; }
		// Retrieve the form instance
        $form_instance = $this->form_instance;
		// Retrieve the form instance
		$form_instance_key = $this->_Get_Instance_Key();
		// If no form type, return null
		if (!$form_instance_key) { return; }
		// Populate the form context
		$vcff_forms->cached[$form_instance_key] = $form_instance;
    }
    
    public function Retrieve() {
        // Retrieve the global vcff forms class
        $vcff_forms = vcff_get_library('vcff_forms');
        // If no form instance was returned
        if (!isset($this->form_instance)) { return false; }
		// Retrieve the form instance
        $form_instance = $this->form_instance;
		// Retrieve the form instance
		$form_instance_key = $this->_Get_Instance_Key();
		// If no form type, return null
		if (!$form_instance_key) { return $form_instance; }  
		// Populate the form context
		if (isset($vcff_forms->cached[$form_instance_key])) {
            return $vcff_forms->cached[$form_instance_key];
        } else {
            return $form_instance;
        }
    
    }
}