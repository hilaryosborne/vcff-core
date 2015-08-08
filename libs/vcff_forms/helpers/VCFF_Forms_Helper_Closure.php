<?php

class VCFF_Forms_Helper_Closure extends VCFF_Helper {
    
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
        // If this form has a custom validation method
        if (method_exists($form_instance,'Pre_Closure')) { $form_instance->Pre_Closure(); }
        // Retrieve the validation result
        do_action('vcff_pre_form_closure', $form_instance);
    }
    
     public function _Check_Standard_Closure() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
         // If this form has a custom validation method
         if (method_exists($form_instance,'Do_Closure')) { $form_instance->Do_Closure(); }
         // Retrieve the validation result
         do_action('vcff_form_closure', $form_instance);
	}
    
    protected function _Post_Closure() {
        /// Retrieve the form instance
		$form_instance = $this->form_instance;
        // If this form has a custom validation method
        if (method_exists($form_instance,'Post_Closure')) { $form_instance->Post_Closure(); }
        // Retrieve the validation result
        do_action('vcff_post_form_closure', $form_instance);
    }
    
}