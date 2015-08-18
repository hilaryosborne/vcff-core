<?php

class VCFF_Forms_Helper_Review extends VCFF_Helper {

    protected $form_instance;
    
    protected $params = array(
        'events' => true,
    );

    public function Set_Form_Instance($form_instance) {
		// Set the form instance
		$this->form_instance = $form_instance;
		// Return for chaining
		return $this;
	}
    
    public function Review($params = array()) {
        // Save the provided params
        $this->params = array_merge($this->params,$params);
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Do any form actions on create
        $form_instance->Do_Action('before_review',array('helper' => $this));
        // Retrieve the validation result
        do_action('vcff_form_before_review', $form_instance);
        // Create the Instance
        $this->_Review_Events();
        // Do any form actions on create
        $form_instance->Do_Action('review',array('helper' => $this));
        // Do any form actions on create
        $form_instance->Do_Action('after_review',array('helper' => $this));
        // Retrieve the validation result
        do_action('vcff_form_after_review', $form_instance);
    }
    
    protected function _Review_Events() {
        // Retrieve the params
        $params = $this->params;
        // If we are not going to populate the fields
        if (!$params['events']) { return; }
        // Retrieve the form instance
		$form_instance = $this->form_instance;
        // Create a new trigger helper
        $events_trigger_helper = new VCFF_Events_Helper_Trigger();
        // Check events conditions and trigger
        $events_trigger_helper
            ->Set_Form_Instance($form_instance)
            ->Trigger();
        // Do any form actions on create
        $form_instance->Do_Action('review_events',array('helper' => $this));
        // Retrieve the validation result
        do_action('vcff_review_events', $form_instance);
    }
    
}