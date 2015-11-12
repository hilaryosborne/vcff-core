<?php

class VCFF_Supports_Helper_Conditions {
	
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
		// Retrieve the form's supports
        $form_supports = $form_instance->supports;
		// If there are no form supports
		if (!$form_supports || !is_array($form_supports)) { return; }
		// Loop through each containers
		foreach ($form_supports as $machine_code => $support_instance) {
			// If this support has a custom validation method
			if (method_exists($support_instance,'Pre_Conditional')) { $support_instance->Pre_Conditional(); }
            // Do any actions
            $support_instance->Do_Action('before_conditional',array());
            // Retrieve the validation result
            do_action('vcff_pre_support_conditional', $support_instance);
        }
    }
    
    protected function _Check_Conditional() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form supports
        $form_supports = $form_instance->supports;
        // If a list of form containers was returned
		if (!$form_supports || !is_array($form_supports)) { return $this; }
		// Loop through each containers
		foreach ($form_supports as $k => $support_instance) {
            // If this support has a custom validation method
			if (method_exists($support_instance,'Check_Conditions')) { $support_instance->Check_Conditions(); }
            // If this support has a custom validation method
			if (method_exists($support_instance,'Do_Conditional')) { $support_instance->Do_Conditional(); }
            // Do any actions
            $support_instance->Do_Action('conditional',array());
            // Retrieve the validation result
            do_action('vcff_do_support_conditional', $support_instance );
		}
	}

    protected function _Post_Conditional() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's supports
        $form_supports = $form_instance->supports;
		// If there are no form supports
		if (!$form_supports || !is_array($form_supports)) { return; }
		// Loop through each containers
		foreach ($form_supports as $machine_code => $support_instance) { 
			// If this support has a custom validation method
			if (method_exists($support_instance,'Post_Conditional')) { $support_instance->Post_Conditional(); }
            // Do any actions
            $support_instance->Do_Action('after_conditional',array());
            // Retrieve the validation result
            do_action('vcff_post_support_conditional', $support_instance);
		}
    }
	
}