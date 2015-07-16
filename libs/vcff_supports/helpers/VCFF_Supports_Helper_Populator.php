<?php

class VCFF_Supports_Helper_Populator extends VCFF_Helper {

    protected $form_instance;	
		
	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
    
    protected function _Get_Instance($_data) { 
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the container name
		$machine_code = $_data['name'];
		// Create the field item classname
		$support_classname = $_data['context']['class_item'];
		// If no form instance was found
		if (!$machine_code || !$support_classname) { return; }
		// Create a new item instance for this field
		$support_instance = new $support_classname();
		// Populate the container form
		$support_instance->form = $this->form_instance;
		// Populate the container fields
		$support_instance->machine_code = $machine_code;
        // Populate the container fields
		$support_instance->support_type = $_data['type'];
		// Populate the handler object
		$support_instance->context = $_data['context'];
		// Populate the field list
		$support_instance->attributes = $_data['attributes'];
		// Return the generated field instance
		return $support_instance;
	}

    public function Populate() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the field data
		$parsed = vcff_parse_support_data($form_instance->form_content); 
		// If an error has been detected, return out
		if (!$parsed || !is_array($parsed)) { return; }
		// Retrieve the form instance
		$form_instance = $this->form_instance; 
		// Loop through each of the containers
		foreach ($parsed as $k => $_data) { 
			// Retrieve the container instance
			$support_instance = $this->_Get_Instance($_data);
			// Add the container to the form instance
			$form_instance->Add_Support($support_instance);
		}
	}
}