<?php

class VCFF_Containers_Helper_Validation extends VCFF_Helper {

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
    }
    
    public function Get_Passed() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's containers
		$form_containers = $form_instance->containers;
		// If there are no form containers
		if (!$form_containers || !is_array($form_containers)) { return array(); }
        // List to store the relevant fields
        $qualifying_list = array();
		// Loop through each of the form's containers
		foreach ($form_containers as $k => $container_instance) {
            // If the container is not valid
            if (!$container_instance->Is_Valid()) { continue; }
            // Add the instance to the list
            $qualifying_list[] = $container_instance;
        }
        // Return the list
        return $qualifying_list;
    }
    
    public function Get_Failed() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's containers
		$form_containers = $form_instance->containers;
		// If there are no form containers
		if (!$form_containers || !is_array($form_containers)) { return array(); }
        // List to store the relevant fields
        $qualifying_list = array();
		// Loop through each of the form's containers
		foreach ($form_containers as $k => $container_instance) {
            // If the container is not valid
            if ($container_instance->Is_Valid()) { continue; }
            // Add the instance to the list
            $qualifying_list[] = $container_instance;
        }
        // Return the list
        return $qualifying_list;
    }
    
    protected function _Pre_Validation() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's fields
		$form_containers = $form_instance->containers;
		// If there are no form containers
		if (!$form_containers || !is_array($form_containers)) { return; }
		// Loop through each of the form's containers
		foreach ($form_containers as $k => $container_instance) {
			// If this field has a custom validation method
			if (method_exists($container_instance,'Pre_Validation')) { $container_instance->Pre_Validation(); }
            // Retrieve the validation result
            do_action('vcff_pre_container_validation', $container_instance);
        }
    }
    
    protected function _Post_Validation() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's fields
		$form_containers = $form_instance->containers;
		// If there are no form containers
		if (!$form_containers || !is_array($form_containers)) { return; }
		// Loop through each of the form's containers
		foreach ($form_containers as $k => $container_instance) {
			// If this field has a custom validation method
			if (method_exists($container_instance,'Post_Validation')) { $container_instance->Post_Validation(); }
            // Retrieve the validation result
            do_action('vcff_post_container_validation', $container_instance);
		}
    }
    
    protected function _Check_Method_Validation() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's fields
		$form_containers = $form_instance->containers;
		// If there are no form containers
		if (!$form_containers || !is_array($form_containers)) { return; }
		// Loop through each of the form's containers
		foreach ($form_containers as $k => $container_instance) {
			// If this field has a custom validation method
			if (!method_exists($container_instance,'Do_Validation')) { continue; }
			// Retrieve the validation result
			$container_instance->Do_Validation();
		}
	}
    
    protected function _Check_Hook_Validation() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's fields
		$form_containers = $form_instance->containers;
		// If there are no form containers
		if (!$form_containers || !is_array($form_containers)) { return; }
		// Loop through each of the form's containers
		foreach ($form_containers as $k => $container_instance) {
			// Retrieve the validation result
            do_action('vcff_do_container_validation', $container_instance );
		}
	}
    
	public function _Check_Standard_Validation() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's fields
		$form_containers = $form_instance->containers;
		// If there are no form containers
		if (!$form_containers || !is_array($form_containers)) { return; }
		// Loop through each of the form's containers
		foreach ($form_containers as $k => $container_instance) {
			// Validation result lists
			$validation_fields_failed = array();
			$validation_fields_passed = array();
			// Retrieve the form's fields
			$container_fields = $container_instance->fields;
			// If a list of form containers was returned
			if (!$container_fields || !is_array($container_fields)) { 
                // Update the form's validation status
                $container_instance->result_validation = array(
                    'result' => 'passed',
                    'fields_passed' => array(),
                    'fields_failed' => array()
                );
            }
			// Loop through each containers
			foreach ($container_fields as $k => $field_instance) {
				// If the field passed validation
				if ($field_instance->Is_Valid()) {
					// Add the field to the field passed list
					$validation_fields_passed[$k] = $field_instance;
				} // If the field validation failed
				else {
					// Add the field to the failed field list
					$validation_fields_failed[$k] = $field_instance;
				} 
			}
            // Set the container validation flag
            $container_instance->is_valid = count($validation_fields_failed) > 0 ? false : true;
			// Update the form's validation status
			$container_instance->result_validation = array(
				'result' => count($validation_fields_failed) > 0 ? 'failed' : 'passed',
				'fields_passed' => $validation_fields_passed,
				'fields_failed' => $validation_fields_failed
			);
		}
	}
	
}