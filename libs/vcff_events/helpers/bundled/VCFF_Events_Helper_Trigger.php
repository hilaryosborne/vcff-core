<?php

class VCFF_Events_Helper_Trigger extends VCFF_Helper {

    protected $form_instance;	
    
	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
    
    public function Trigger() {
        // Retrieve the form instance
        $form_instance = $this->form_instance; 
        // If the form is not using events
        if (!$form_instance->use_events) { return $this; }
        // Retrieve the events
        $events = $form_instance->events;
        // If there are no events
        if (!$events || !is_array($events)) { return $this; } 
        // The passed and failed actions
        $actions_passed = array();
        $actions_failed = array();
        // Loop through each of the events
        foreach ($events as $k => $action_instance) {
            // If the action fails the check
            if (!$action_instance->Check()) { continue; } 
            // Trigger the action
            $action_instance->Trigger();
        }
        // Return the object for chaining
        return $this;
    }
   
}