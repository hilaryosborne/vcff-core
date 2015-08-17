<?php

class VCFF_Containers_Helper_Validation extends VCFF_Helper {

    protected $form_instance;	
	
	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
	
    public function Check() {
        
        $this->_Before_Validation();
      
        $this->_Check_Validation();
        
        $this->_After_Validation();
        
        $this->_Update_Form();
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
    
    protected function _Before_Validation() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's fields
		$form_containers = $form_instance->containers;
		// If there are no form containers
		if (!$form_containers || !is_array($form_containers)) { return; }
		// Loop through each of the form's containers
		foreach ($form_containers as $k => $container_instance) {
			// If this field has a custom validation method
			if (method_exists($container_instance,'Before_Validation')) { $container_instance->Pre_Validation(); }
            // Do any actions
            $container_instance->Do_Action('before_validation',array());
            // Retrieve the validation result
            do_action('vcff_before_container_validation', $container_instance);
        }
    }
    
    protected function _Check_Validation() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's fields
		$form_containers = $form_instance->containers;
		// If there are no form containers
		if (!$form_containers || !is_array($form_containers)) { return; }
		// Loop through each of the form's containers
		foreach ($form_containers as $k => $container_instance) {
			// If this field has a custom validation method
			if (method_exists($container_instance,'Check_Container_Validation')) { $container_instance->Check_Container_Validation(); }
            // If this field has a custom validation method
			if (method_exists($container_instance,'Do_Validation')) { $container_instance->Do_Validation(); }
            // Do any actions
            $container_instance->Do_Action('validation',array());
            // Retrieve the validation result
            do_action('vcff_do_container_validation', $container_instance );
		}
	}
    
    protected function _After_Validation() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's fields
		$form_containers = $form_instance->containers;
		// If there are no form containers
		if (!$form_containers || !is_array($form_containers)) { return; }
		// Loop through each of the form's containers
		foreach ($form_containers as $k => $container_instance) {
			// If this field has a custom validation method
			if (method_exists($container_instance,'After_Validation')) { $container_instance->Post_Validation(); }
            // Do any actions
            $container_instance->Do_Action('after_validation',array());
            // Retrieve the validation result
            do_action('vcff_after_container_validation', $container_instance);
		}
    }
    
    protected function _Update_Form() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's fields
		$form_containers = $form_instance->containers;
		// If there are no form fields
		if (!$form_containers || !is_array($form_containers)) { return; }
        // Set the invalid number
        $invalid = 0;
		// Loop through each of the form's containers
		foreach ($form_containers as $k => $container_instance) {
            // If the field is valid, move on
            if ($container_instance->Is_Hidden()) { continue; }
            // If the field is valid, move on
            if ($container_instance->Is_Valid()) { continue; }
            // Inc up the invalid field
            $invalid++;
        }
        // If there are no invalid fields
        if ($invalid == 0) { return; }
        // Set the form valid flag to false
        $form_instance->is_valid = false;
    }
    
}