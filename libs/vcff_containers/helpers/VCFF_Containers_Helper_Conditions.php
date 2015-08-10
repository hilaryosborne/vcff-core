<?php

class VCFF_Containers_Helper_Conditions {
	
	protected $form_instance;	

	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
	
	public function Check() {
    
		$this->_Pre_Conditional();

		$this->_Check_Conditional();
        
        $this->_Post_Conditional();
		
		return $this;
	}
	
    protected function _Pre_Conditional() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's containers
        $form_containers = $form_instance->containers;
		// If there are no form containers
		if (!$form_containers || !is_array($form_containers)) { return; }
		// Loop through each containers
		foreach ($form_containers as $machine_code => $container_instance) {
			// If this container has a custom validation method
			if (method_exists($container_instance,'Pre_Conditional')) { $container_instance->Pre_Conditional(); }
            // Do any actions
            $container_instance->Do_Action('before_conditional',array());
            // Retrieve the validation result
            do_action('vcff_pre_container_conditional', $container_instance);
        }
    }
    
    protected function _Check_Conditional() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form containers
        $form_containers = $form_instance->containers;
        // If a list of form containers was returned
		if (!$form_containers || !is_array($form_containers)) { return $this; }
		// Loop through each containers
		foreach ($form_containers as $k => $container_instance) {
            // If this container has a custom validation method
			if (method_exists($container_instance,'Check_Conditions')) { $container_instance->Check_Conditions(); }
            // If this container has a custom validation method
			if (method_exists($container_instance,'Do_Conditional')) { $container_instance->Do_Conditional(); }
            // Do any actions
            $container_instance->Do_Action('conditional',array());
            // Retrieve the validation result
            do_action('vcff_do_container_conditional', $container_instance );
		}
	}

    protected function _Post_Conditional() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's containers
        $form_containers = $form_instance->containers;
		// If there are no form containers
		if (!$form_containers || !is_array($form_containers)) { return; }
		// Loop through each containers
		foreach ($form_containers as $machine_code => $container_instance) { 
			// If this container has a custom validation method
			if (method_exists($container_instance,'Post_Conditional')) { $container_instance->Post_Conditional(); }
            // Do any actions
            $container_instance->Do_Action('after_conditional',array());
            // Retrieve the validation result
            do_action('vcff_post_container_conditional', $container_instance);
		}
    }
	
}