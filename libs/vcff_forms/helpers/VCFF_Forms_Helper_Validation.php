<?php

class VCFF_Forms_Helper_Validation extends VCFF_Helper {

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
    
    protected function _Pre_Validation() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
        // If this field has a custom validation method
        if (method_exists($form_instance,'Pre_Validation')) { $form_instance->Pre_Validation(); }
        // Retrieve the validation result
        do_action('vcff_pre_form_validation', $form_instance);
    }
    
    protected function _Check_Standard_Validation() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
    }
    
    protected function _Check_Method_Validation() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
        // If this field has a custom validation method
        if (method_exists($form_instance,'Do_Validation')) { $form_instance->Do_Validation(); }
	}
    
    protected function _Check_Hook_Validation() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the validation result
        do_action('vcff_do_form_validation', $form_instance );
	}
    
    protected function _Post_Validation() {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // If this field has a custom validation method
        if (method_exists($form_instance,'Post_Validation')) { $form_instance->Post_Validation(); }
        // Retrieve the validation result
        do_action('vcff_post_form_validation', $form_instance);
    }
}