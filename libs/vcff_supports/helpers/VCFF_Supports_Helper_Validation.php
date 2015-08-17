<?php 

class VCFF_Supports_Helper_Validation {
	
	protected $form_instance;	
	
	protected $error;
	
	public function Get_Error() {
		
		return $this->error;
	}

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
		// Retrieve the form's supports
        $form_supports = $form_instance->supports;
		// If there are no form supports
		if (!$form_supports || !is_array($form_supports)) { return array(); }
        // List to store the relevant supports
        $qualifying_list = array();
        // Loop through each containers
		foreach ($form_supports as $machine_code => $support_instance) {
            // If the feidl is not valid
            if (!$support_instance->Is_Valid()) { continue; }
            // Add the instance to the list
            $qualifying_list[] = $support_instance;
        }
        // Return the list
        return $qualifying_list;
    }
    
    public function Get_Failed() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's supports
        $form_supports = $form_instance->supports;
		// If there are no form supports
		if (!$form_supports || !is_array($form_supports)) { return array(); }
        // List to store the relevant supports
        $qualifying_list = array();
        // Loop through each containers
		foreach ($form_supports as $machine_code => $support_instance) {
            // If the feidl is valid
            if ($support_instance->Is_Valid()) { continue; }
            // Add the instance to the list
            $qualifying_list[] = $support_instance;
        }
        // Return the list
        return $qualifying_list;
    }
    
    protected function _Before_Validation() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's supports
        $form_supports = $form_instance->supports;
		// If there are no form supports
		if (!$form_supports || !is_array($form_supports)) { return; }
		// Loop through each containers
		foreach ($form_supports as $machine_code => $support_instance) {
			// If this support has a custom validation method
			if (method_exists($support_instance,'Before_Validation')) { $support_instance->Pre_Validation(); }
            // Do any actions
            $support_instance->Do_Action('before_validation',array());
            // Retrieve the validation result
            do_action('vcff_pre_support_validation', $support_instance);
        }
    }
    
    protected function _Check_Validation() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's supports
        $form_supports = $form_instance->supports;
        // If a list of form containers was returned
		if (!$form_supports || !is_array($form_supports)) { return $this; }
		// Loop through each containers
		foreach ($form_supports as $machine_code => $support_instance) {
            // If this support has a custom validation method
			if (method_exists($support_instance,'Do_Validation')) { $support_instance->Do_Validation(); }
            // Do any actions
            $support_instance->Do_Action('validation',array());
            // Retrieve the validation result
            do_action('vcff_do_support_validation', $support_instance );
		}
	} 
    
    protected function _After_Validation() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's supports
        $form_supports = $form_instance->supports;
		// If there are no form supports
		if (!$form_supports || !is_array($form_supports)) { return; }
		// Loop through each containers
		foreach ($form_supports as $machine_code => $support_instance) {
			// If this support has a custom validation method
			if (method_exists($support_instance,'After_Validation')) { $support_instance->Post_Validation(); }
            // Do any actions
            $support_instance->Do_Action('after_validation',array());
            // Retrieve the validation result
            do_action('vcff_post_support_validation', $support_instance);
		}
    }

    protected function _Update_Form() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's supports
        $form_supports = $form_instance->supports;
		// If there are no form supports
		if (!$form_supports || !is_array($form_supports)) { return; }
        // Set the invalid number
        $invalid = 0;
		// Loop through each containers
		foreach ($form_supports as $machine_code => $support_instance) {
            // If the support is valid, move on
            if ($support_instance->Is_Hidden()) { continue; }
            // If the support is valid, move on
            if ($support_instance->Is_Valid()) { continue; }
            // Inc up the invalid support
            $invalid++;
        }
        // If there are no invalid supports
        if ($invalid == 0) { return; }
        // Set the form valid flag to false
        $form_instance->is_valid = false;
    }
}