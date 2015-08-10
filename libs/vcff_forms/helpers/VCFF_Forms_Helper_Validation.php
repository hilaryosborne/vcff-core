<?php

class VCFF_Forms_Helper_Validation extends VCFF_Helper {

    protected $form_instance;	
	
	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
    
    public function Check() {
        
        $this->_Pre_Validation();
        
        $this->_Check_Validation();
        
        $this->_Post_Validation();
    }
    
    protected function _Pre_Validation() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
        // If this field has a custom validation method
        if (method_exists($form_instance,'Before_Validation')) { $form_instance->Pre_Validation(); }
        // Do any form native actions
        $form_instance->Do_Action('before_validate',array());
        // Retrieve the validation result
        do_action('vcff_before_form_validation', $form_instance);
    }
    
    protected function _Check_Validation() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
        // If this field has a custom validation method
        if (method_exists($form_instance,'Do_Validation')) { $form_instance->Do_Validation(); }
        // Do any form native actions
        $form_instance->Do_Action('validate',array());
        // Retrieve the validation result
        do_action('vcff_do_form_validation', $form_instance );
	}
    
    protected function _Post_Validation() {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // If this field has a custom validation method
        if (method_exists($form_instance,'After_Validation')) { $form_instance->Post_Validation(); }
        // Do any form native actions
        $form_instance->Do_Action('after_validate',array());
        // Retrieve the validation result
        do_action('vcff_after_form_validation', $form_instance);
    }
}