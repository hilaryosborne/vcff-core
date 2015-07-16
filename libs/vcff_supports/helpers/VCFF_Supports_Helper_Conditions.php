<?php

class VCFF_Supports_Helper_Conditions extends VCFF_Helper {
	
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
		// Retrieve the form supports
        $form_supports = $form_instance->supports;
        // If a list of form supports was returned
		if (!$form_supports || !is_array($form_supports)) { return $this; }
		// Loop through each of the supports
		foreach ($form_supports as $k => $support) {
			// If this field has a custom validation method
			if (method_exists($support,'Pre_Conditional')) { $support->Pre_Conditional(); }
            // Retrieve the validation result
            do_action('vcff_pre_support_conditional', $support);
        }
    }
    
    public function _Check_Standard_Conditional() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form supports
        $form_supports = $form_instance->supports;
        // If a list of form supports was returned
		if (!$form_supports || !is_array($form_supports)) { return $this; }
		// Loop through each of the supports
		foreach ($form_supports as $k => $support) {
			// Check the supports conditions
			$support->Check_Conditions();
        }
	}
    
    protected function _Check_Method_Conditional() {
		/// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form supports
        $form_supports = $form_instance->supports;
        // If a list of form supports was returned
		if (!$form_supports || !is_array($form_supports)) { return $this; }
		// Loop through each of the supports
		foreach ($form_supports as $k => $support) {
			// If this field has a custom validation method
			if (!method_exists($support,'Do_Conditional')) { continue; }
			// Retrieve the validation result
			$support->Do_Conditional();
		}
	}
    
    protected function _Check_Hook_Conditional() {
		/// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form supports
        $form_supports = $form_instance->supports;
        // If a list of form supports was returned
		if (!$form_supports || !is_array($form_supports)) { return $this; }
		// Loop through each of the supports
		foreach ($form_supports as $k => $support) {
			// Retrieve the validation result
            do_action('vcff_do_support_conditional', $support);
		}
	}
    
    protected function _Post_Conditional() {
        /// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form supports
        $form_supports = $form_instance->supports;
        // If a list of form supports was returned
		if (!$form_supports || !is_array($form_supports)) { return $this; }
		// Loop through each of the supports
		foreach ($form_supports as $k => $support) {
			// If this field has a custom validation method
			if (method_exists($support,'Post_Conditional')) { $support->Post_Conditional(); }
            // Retrieve the validation result
            do_action('vcff_post_support_conditional', $support);
		}
    }

}