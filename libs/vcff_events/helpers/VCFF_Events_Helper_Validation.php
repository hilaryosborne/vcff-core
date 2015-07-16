<?php

class VCFF_Events_Helper_Validation extends VCFF_Helper {

    protected $action_instance;	
	
	public function Set_Action_Instance($action_instance) {
		
		$this->action_instance = $action_instance;
		
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
		$action_instance = $this->action_instance;
        // If there is an action instance (which there should be)
        if ($action_instance && is_object($action_instance)) {
            // If this field has a custom validation method
            if (method_exists($action_instance,'Pre_Validation')) { $action_instance->Pre_Validation(); }
            // Retrieve the validation result
            do_action('vcff_pre_action_validation', $action_instance);
        }
        // Retrieve the event instance
        $event_instance = $action_instance->Get_Selected_Event_Instance();
        // If there is an event instance (which there should be)
        if ($event_instance && is_object($event_instance)) {
            // If this field has a custom validation method
            if (method_exists($event_instance,'Pre_Validation')) { $event_instance->Pre_Validation(); }
            // Retrieve the validation result
            do_action('vcff_pre_event_validation', $event_instance);
        }
        // Retrieve the trigger instance
        $trigger_instance = $action_instance->Get_Selected_Trigger_Instance();
        // If there is an trigger instance (which there should be)
        if ($trigger_instance && is_object($trigger_instance)) {
            // If this field has a custom validation method
            if (method_exists($trigger_instance,'Pre_Validation')) { $trigger_instance->Pre_Validation(); }
            // Retrieve the validation result
            do_action('vcff_pre_trigger_validation', $trigger_instance);
        }
    }
    
    protected function _Check_Standard_Validation() {
		// Retrieve the form instance
		$action_instance = $this->action_instance;
        // If there is an action instance (which there should be)
        $action_instance->Check_Validation();
        // Retrieve the event instance
        $event_instance = $action_instance->Get_Selected_Event_Instance();
        // If there is an event instance (which there should be)
        if ($event_instance && is_object($event_instance)) {
            // If this field has a custom validation method
            $event_instance->Check_Validation(); 
        }
        // Retrieve the trigger instance
        $trigger_instance = $action_instance->Get_Selected_Trigger_Instance();
        // If there is an trigger instance (which there should be)
        if ($trigger_instance && is_object($trigger_instance)) {
            // If this field has a custom validation method
            $trigger_instance->Check_Validation(); 
        }
        // If there is no event or trigger instance
        if (!is_object($event_instance) || !is_object($trigger_instance)) { 
            // Set the action instance as false
            $action_instance->is_valid = false;
            // Add a danger alert
            $action_instance->Add_Alert('Please select a trigger and an event','danger');
        }
	} 
    
    protected function _Check_Method_Validation() {
        // Retrieve the form instance
		$action_instance = $this->action_instance;
        // If there is an action instance (which there should be)
        if ($action_instance && is_object($action_instance)) {
            // If this field has a custom validation method
            if (method_exists($action_instance,'Do_Validation')) { $action_instance->Do_Validation(); }
        }
        // Retrieve the event instance
        $event_instance = $action_instance->Get_Selected_Event_Instance();
        // If there is an event instance (which there should be)
        if ($event_instance && is_object($event_instance)) {
            // If this field has a custom validation method
            if (method_exists($event_instance,'Do_Validation')) { $event_instance->Do_Validation(); }
        }
        // Retrieve the trigger instance
        $trigger_instance = $action_instance->Get_Selected_Trigger_Instance();
        // If there is an trigger instance (which there should be)
        if ($trigger_instance && is_object($trigger_instance)) {
            // If this field has a custom validation method
            if (method_exists($trigger_instance,'Do_Validation')) { $trigger_instance->Do_Validation(); }
        }
	}
    
    protected function _Check_Hook_Validation() {
        // Retrieve the form instance
		$action_instance = $this->action_instance;
        // If there is an action instance (which there should be)
        if ($action_instance && is_object($action_instance)) {
            // Retrieve the validation result
            do_action('vcff_do_action_validation', $action_instance );
        }
        // Retrieve the event instance
        $event_instance = $action_instance->Get_Selected_Event_Instance();
        // If there is an event instance (which there should be)
        if ($event_instance && is_object($event_instance)) {
            // Retrieve the validation result
            do_action('vcff_do_event_validation', $event_instance );
        }
        // Retrieve the trigger instance
        $trigger_instance = $action_instance->Get_Selected_Trigger_Instance();
        // If there is an trigger instance (which there should be)
        if ($trigger_instance && is_object($trigger_instance)) {
            // Retrieve the validation result
            do_action('vcff_do_trigger_validation', $trigger_instance );
        }
	}
    
    protected function _Post_Validation() {
        // Retrieve the form instance
		$action_instance = $this->action_instance;
        // If there is an action instance (which there should be)
        if ($action_instance && is_object($action_instance)) {
            // If this field has a custom validation method
            if (method_exists($action_instance,'Post_Validation')) { $action_instance->Pre_Validation(); }
            // Retrieve the validation result
            do_action('vcff_post_action_validation', $action_instance);
        }
        // Retrieve the event instance
        $event_instance = $action_instance->Get_Selected_Event_Instance();
        // If there is an event instance (which there should be)
        if ($event_instance && is_object($event_instance)) {
            // If this field has a custom validation method
            if (method_exists($event_instance,'Post_Validation')) { $event_instance->Pre_Validation(); }
            // Retrieve the validation result
            do_action('vcff_post_event_validation', $event_instance);
        }
        // Retrieve the trigger instance
        $trigger_instance = $action_instance->Get_Selected_Trigger_Instance();
        // If there is an trigger instance (which there should be)
        if ($trigger_instance && is_object($trigger_instance)) {
            // If this field has a custom validation method
            if (method_exists($trigger_instance,'Post_Validation')) { $trigger_instance->Pre_Validation(); }
            // Retrieve the validation result
            do_action('vcff_post_trigger_validation', $trigger_instance);
        }
    }
}