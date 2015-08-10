<?php

class VCFF_Forms_Helper_Finalize extends VCFF_Helper {

    protected $form_instance;
    
    protected $params = array(
        'submission' => true,
    );

    public function Set_Form_Instance($form_instance) {
		// Set the form instance
		$this->form_instance = $form_instance;
		// Return for chaining
		return $this;
	}
    
    public function Finalize($params = array()) {
        // Save the provided params
        $this->params = array_merge($this->params,$params);
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Do any form actions on create
        $form_instance->Do_Action('before_finalize',array('helper' => $this));
        // Retrieve the validation result
        do_action('vcff_form_before_finalize', $form_instance);
        // Create the Instance
        $this->_Finalize_Submission();
        // Do any form actions on create
        $form_instance->Do_Action('finalize',array('helper' => $this));
        // Do any form actions on create
        $form_instance->Do_Action('after_finalize',array('helper' => $this));
        // Retrieve the validation result
        do_action('vcff_form_after_finalize', $form_instance);
    }
    
    protected function _Finalize_Submission() {
        // Retrieve the params
        $params = $this->params;
        // If we are not going to populate the fields
        if (!$params['submission']) { return; }
        // Retrieve the form instance
		$form_instance = $this->form_instance;
        // If this is not a form submission
        if (!$form_instance->Is_Submission()) { return; }
        // If this is not a form submission
        if (!$form_instance->Is_Valid()) { return; }
        // Do any form actions on create
        $form_instance->Do_Action('finalize_submission',array('helper' => $this));
        // Retrieve the validation result
        do_action('vcff_finalize_submission', $form_instance);
    }
    
}