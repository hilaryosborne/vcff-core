<?php

class VCFF_Containers_Helper_Closure extends VCFF_Helper {

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
		// Retrieve the form containers
        $form_containers = $form_instance->containers;
        // If a list of form containers was returned
		if (!$form_containers || !is_array($form_containers)) { return $this; }
		// Loop through each of the containers
		foreach ($form_containers as $k => $container) {
			// If this field has a custom validation method
			if (method_exists($container,'Pre_Closure')) { $container->Pre_Closure(); }
            // Retrieve the validation result
            do_action('vcff_pre_container_closure', $container);
        }
    }
    
     public function _Check_Standard_Closure() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form containers
        $form_containers = $form_instance->containers;
        // If a list of form containers was returned
		if (!$form_containers || !is_array($form_containers)) { return $this; }
		// Loop through each of the containers
		foreach ($form_containers as $k => $container) {
            // If this field has a custom validation method
			if (method_exists($container,'Do_Closure')) { $container->Do_Closure(); }
			// Retrieve the validation result
            do_action('vcff_container_closure', $container);
        }
	}
    
    protected function _Post_Closure() {
        /// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form containers
        $form_containers = $form_instance->containers;
        // If a list of form containers was returned
		if (!$form_containers || !is_array($form_containers)) { return $this; }
		// Loop through each of the containers
		foreach ($form_containers as $k => $container) {
			// If this field has a custom validation method
			if (method_exists($container,'Post_Closure')) { $container->Post_Closure(); }
            // Retrieve the validation result
            do_action('vcff_post_container_closure', $container);
		}
    }
    
}