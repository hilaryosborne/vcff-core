<?php

class VCFF_Containers_Helper_Populator extends VCFF_Helper {
	
	protected $form_instance;	
		
	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
	
	protected function _Get_Container_Instance($container_data) { 
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the container name
		$machine_code = $container_data['attributes']['machine_code'];
		// Create the field item classname
		$container_classname = $container_data['context']['class_item'];
		// If no form instance was found
		if (!$machine_code) {
			// Populate with an error and return out
			$this->error = 'No container name could be found'; return;
		}
		// If no form instance was found
		if (!$container_classname) {
			// Populate with an error and return out
			$this->error = 'No container class item could be found'; return;
		}   
		// Create a new item instance for this field
		$container_instance = new $container_classname();
		// Populate the container form
		$container_instance->form = $this->form_instance;
		// Populate the container fields
		$container_instance->machine_code = $machine_code;
        // Populate the container fields
		$container_instance->container_type = $container_data['context']['type'];
		// Populate the handler object
		$container_instance->context = $container_data['context'];
		// Populate the field list
		$container_instance->attributes = $container_data['attributes'];
        // Retrieve the raw field data from the container text
        $raw_field_data = vcff_parse_field_data($container_data['content']);
        // If no fields were returned
        if (!$raw_field_data || !is_array($raw_field_data)) { return $container_instance; }
        // Loop through each of the field data
		foreach ($raw_field_data as $k => $field_data) { 
			// If there is no field name
			if (!isset($field_data['attributes']['machine_code'])) { continue; }
			// Retrieve the field name
			$machine_code = $field_data['attributes']['machine_code'];
            // Retrieve the field instance
            $field_instance = $form_instance->Get_Field($machine_code);
            // If no field instance was returned
            if (!$field_instance) { continue; }
            // Add the field instance to the container
            $container_instance->Add_Field($field_instance);
		} 
		// Return the generated field instance
		return $container_instance;
	}

	public function Populate() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the field data
		$containers_data = vcff_parse_container_data($form_instance->form_content); 
		// If an error has been detected, return out
		if (!$containers_data || !is_array($containers_data)) { return; }
		// Retrieve the form instance
		$form_instance = $this->form_instance; 
		// Loop through each of the containers
		foreach ($containers_data as $k => $container_data) {
			// Retrieve the container instance
			$container_instance = $this->_Get_Container_Instance($container_data);
			// Add the container to the form instance
			$form_instance->Add_Container($container_instance);
		}
	}
}