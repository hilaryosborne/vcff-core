<?php

class VCFF_Forms_Helper_Calculate extends VCFF_Helper {

    protected $form_instance;
    
    protected $params = array(
        'filter' => true,
        'conditions' => true,
        'validation' => true
    );

    public function Set_Form_Instance($form_instance) {
		// Set the form instance
		$this->form_instance = $form_instance;
		// Return for chaining
		return $this;
	}
    
    public function Calculate($params = array()) {
        // Save the provided params
        $this->params = array_merge($this->params,$params);
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Do any form actions on create
        $form_instance->Do_Action('before_calculate',array('helper' => $this));
        // Retrieve the validation result
        do_action('vcff_form_before_calculate', $form_instance);
        // Create the Instance
        $this->_Calculate_Filtering();
        $this->_Calculate_Conditions();
        $this->_Calculate_Validation();
        // Do any form actions on create
        $form_instance->Do_Action('calculate',array('helper' => $this));
        // Do any form actions on create
        $form_instance->Do_Action('after_calculate',array('helper' => $this));
        // Retrieve the validation result
        do_action('vcff_form_after_calculate', $form_instance);
    }
    
    protected function _Calculate_Filtering() {
        // Retrieve the params
        $params = $this->params;
        // If we are not going to populate the fields
        if (!$params['filter']) { return; }
        // Retrieve the form instance
		$form_instance = $this->form_instance;
        // Create a new filter helper
        $field_filter_helper = new VCFF_Fields_Helper_Filter();
        // Execute the helper
        $field_filter_helper
            ->Set_Form_Instance($form_instance)
            ->Filter();
        // Do any form actions on create
        $form_instance->Do_Action('calculate_filtering',array('helper' => $this));
        // Retrieve the validation result
        do_action('vcff_form_filtering', $form_instance);
    }
    
    protected function _Calculate_Conditions() {
        // Retrieve the params
        $params = $this->params;
        // If we are not going to populate the fields
        if (!$params['conditions']) { return; }
		// Retrieve the form instance
		$form_instance = $this->form_instance;
        // Retrieve the validation result
        do_action('vcff_form_before_conditional', $form_instance);
        // Create a new helper instance
        $fields_conditions_helper = new VCFF_Fields_Helper_Conditions();
		// Execute the helper
		$fields_conditions_helper
			->Set_Form_Instance($form_instance)
			->Check();
		// Create a new helper instance
        $suports_conditions_helper = new VCFF_Supports_Helper_Conditions();
		// Execute the helper
		$suports_conditions_helper
			->Set_Form_Instance($form_instance)
			->Check();
        // Create a new helper instance
		$containers_conditions_helper = new VCFF_Containers_Helper_Conditions();
		// Execute the helper
		$containers_conditions_helper
			->Set_Form_Instance($form_instance)
			->Check();
        // Create a new helper instance
        $meta_conditions_helper = new VCFF_Meta_Helper_Conditions();
        // Execute the helper
		$meta_conditions_helper
			->Set_Form_Instance($form_instance)
			->Check();
        // Do any form actions on create
        $form_instance->Do_Action('calculate_conditions',array('helper' => $this));
        // Retrieve the validation result
        do_action('vcff_form_after_conditional', $form_instance);
    }
    
    protected function _Calculate_Validation() { 
        // Retrieve the params
        $params = $this->params;
        // If we are not going to populate the fields
        if (!$params['validation']) { return; }
		// Retrieve the form instance
		$form_instance = $this->form_instance;
        // Retrieve the validation result
        do_action('vcff_form_before_validation', $form_instance);
        // Create a field validation helper
        $fields_validation_helper = new VCFF_Fields_Helper_Validation();
		// Check the fields
		$fields_validation_helper
			->Set_Form_Instance($form_instance)
			->Check(); 
        // Create a field validation helper
        $supports_validation_helper = new VCFF_Supports_Helper_Validation();
		// Check the fields
		$supports_validation_helper
			->Set_Form_Instance($form_instance)
			->Check();
		// Create a containers validation helper
		$containers_validation_helper = new VCFF_Containers_Helper_Validation();
		// Check the container
		$containers_validation_helper
			->Set_Form_Instance($form_instance)
			->Check();
        // Create a new helper instance
        $form_validation_helper = new VCFF_Forms_Helper_Validation();
        // Execute the helper
        $form_validation_helper
            ->Set_Form_Instance($form_instance)
            ->Check();
        // Do any form actions on create
        $form_instance->Do_Action('calculate_validation',array('helper' => $this));
        // Retrieve the validation result
        do_action('vcff_form_after_validation', $form_instance);
        
    }
    
}