<?php

class VCFF_Containers_Helper_Conditions extends VCFF_Helper {
	
	protected $form_instance;	
	
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
        /// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form containers
        $form_containers = $form_instance->containers;
        // If a list of form containers was returned
		if (!$form_containers || !is_array($form_containers)) { return $this; }
		// Loop through each of the containers
		foreach ($form_containers as $k => $container) {
			// If this field has a custom validation method
			if (method_exists($container,'Pre_Conditional')) { $container->Pre_Conditional(); }
            // Retrieve the validation result
            do_action('vcff_pre_container_conditional', $container);
        }
    }
    
    public function _Check_Standard_Conditional() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form containers
        $form_containers = $form_instance->containers;
        // If a list of form containers was returned
		if (!$form_containers || !is_array($form_containers)) { return $this; }
		// Loop through each of the containers
		foreach ($form_containers as $k => $container) {
			// Check the containers conditions
			$container->Check_Conditions();
        }
	}
    
    protected function _Check_Method_Conditional() {
		/// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form containers
        $form_containers = $form_instance->containers;
        // If a list of form containers was returned
		if (!$form_containers || !is_array($form_containers)) { return $this; }
		// Loop through each of the containers
		foreach ($form_containers as $k => $container) {
			// If this field has a custom validation method
			if (!method_exists($container,'Do_Conditional')) { continue; }
			// Retrieve the validation result
			$container->Do_Conditional();
		}
	}
    
    protected function _Check_Hook_Conditional() {
		/// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form containers
        $form_containers = $form_instance->containers;
        // If a list of form containers was returned
		if (!$form_containers || !is_array($form_containers)) { return $this; }
		// Loop through each of the containers
		foreach ($form_containers as $k => $container) {
			// Retrieve the validation result
            do_action('vcff_do_container_conditional', $container );
		}
	}
    
    protected function _Post_Conditional() {
        /// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form containers
        $form_containers = $form_instance->containers;
        // If a list of form containers was returned
		if (!$form_containers || !is_array($form_containers)) { return $this; }
		// Loop through each of the containers
		foreach ($form_containers as $k => $container) {
			// If this field has a custom validation method
			if (method_exists($container,'Post_Conditional')) { $container->Post_Conditional(); }
            // Retrieve the validation result
            do_action('vcff_post_container_conditional', $container);
		}
    }

}