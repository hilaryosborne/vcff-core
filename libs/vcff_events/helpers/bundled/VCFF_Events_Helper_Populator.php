<?php

class VCFF_Events_Helper_Populator extends VCFF_Helper {

    protected $form_instance;	

	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
    
    public function Populate() {
        // Retrieve the form instance
        $form_instance = $this->form_instance; 
        // If the form is not using events
        if (!$form_instance->use_events) { return $this; }  
        // Retrieve any stored meta value
		$_actions = get_post_meta($form_instance->form_id,'vcff_meta_event_actions');
        // If the form is not using events
        if (!$_actions || !is_array($_actions)) { return $this; } 
        //die(print_r($stored_meta_actions));
        uasort($_actions, function($a,$b){
            if ($a == $b) { return 0; }
            return ($a['order'] < $b['order']) ? -1 : 1;
        });
        // Loop through the meta data
        foreach ($_actions as $k => $_action) { 
            // Create a new instance helper
            $action_instance = $this->_Build_Action($_action);
            
            $this->_Populate_Events($action_instance);
            
            $this->_Populate_Triggers($action_instance);
            // Add the event to the form instance
            $form_instance->Add_Event($action_instance);
        } 
        // Return for chaining
        return $this;
    }
    
    public function Update($_data) {
        // Retrieve the form instance
        $form_instance = $this->form_instance; 
        
        $action_instance = $this->_Build_Action($_data);
        
        $this->_Populate_Events($action_instance);
        
        $this->_Populate_Triggers($action_instance);
        // Add the event to the form instance
        $form_instance->Add_Event($action_instance);
        
        return $action_instance;
    }
    
    protected function _Build_Action($_data) {
        // Retrieve the global vcff forms class
        $vcff_events = vcff_get_library('vcff_events');
        // Support custom actions in the future
        $type = 'standard_action';
        // If the context does not exist
        if (!isset($vcff_events->actions[$type])) { return; }
		// Retrieve the context
        $_context = $vcff_events->actions[$type];
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Create the new action item instance
        $action_instance = new Action_Standard_Item();
        // Populate with the form instance
        $action_instance->id = isset($_data['id']) ? $_data['id'] : uniqid() ;
        // Populate with the form instance
        $action_instance->form_instance = $form_instance;
        // Populate with the form instance
        $action_instance->name = $_data['name'];
        // Populate with the form instance
        $action_instance->order = $_data['order'];
        // Populate with the form instance
        $action_instance->code = $_data['code'];
        // Populate with the form instance
        $action_instance->context = $_context;
        // Populate with the form instance
        $action_instance->description = $_data['description'];
        // Populate with raw data
        $action_instance->data = is_array($_data) ? $_data : array();
        // If the field has a sanitize method
        if (method_exists($action_instance,'On_Create')) { $action_instance->On_Create(); }
        // Fire any on create action
        $action_instance->Do_Action('create');
        // Do a wordpress hook
        do_action('vcff_action_create',$action_instance);
        
        return $action_instance;
    }
    
    protected function _Populate_Events($action_instance) {
        // Retrieve the global events
        $vcff_events = vcff_get_library('vcff_events');
        // Retrieve the list of trigger contextrs
        $_events = $vcff_events->events;
        // Loop through each trigger
        foreach ($_events as $_code => $_context) {
            // Build the trigger instance
            $event_instance = $this->_Build_Event($_context);
            // If no trigger instance skip
            if (!$event_instance) { continue; }
            // Add the trigger
            $action_instance->Add_Event($event_instance);
        }
    }
    
    protected function _Build_Event($context) {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Retrieve the event class item
        $class = $context['class'];
        // Generate a new event class
        $event_instance = new $class();
        // Populate with the form instance
        $event_instance->form_instance = $form_instance;
        // Populate with the form instance
        $event_instance->type = $context['type'];
        // Populate with the form instance
        $event_instance->title = $context['title'];
        // Populate with the event context
        $event_instance->context = $context;
        // If the field has a sanitize method
        if (method_exists($event_instance,'On_Create')) { $event_instance->On_Create(); }
        // Fire any on create action
        $event_instance->Do_Action('create');
        // Do a wordpress hook
        do_action('vcff_event_create',$event_instance);
        // Fire any on create action
        $event_instance->Do_Action('after_create');
        // Do a wordpress hook
        do_action('vcff_trigger_after_create',$event_instance);
        // Return the event instance
        return $event_instance;
    }
    
    protected function _Populate_Triggers($action_instance) {
        // Retrieve the global events
        $vcff_events = vcff_get_library('vcff_events');
        // Retrieve the list of trigger contextrs
        $_triggers = $vcff_events->triggers;
        // Loop through each trigger
        foreach ($_triggers as $_code => $_context) {
            // Build the trigger instance
            $trigger_instance = $this->_Build_Trigger($_context);
            // If no trigger instance skip
            if (!$trigger_instance) { continue; }
            // Add the trigger
            $action_instance->Add_Trigger($trigger_instance);
        }
    }

    protected function _Build_Trigger($context) {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Retrieve the trigger class item
        $class = $context['class']; 
        // Generate a new trigger class
        $trigger_instance = new $class(); 
        // Populate with the form instance
        $trigger_instance->form_instance = $form_instance; 
        // Populate with the form instance
        $trigger_instance->type = $context['type'];
        // Populate with the form instance
        $trigger_instance->title = $context['title'];
        // Populate with the trigger context
        $trigger_instance->context = $context;
        // If the field has a sanitize method
        if (method_exists($trigger_instance,'On_Create')) { $trigger_instance->On_Create(); }
        // Fire any on create action
        $trigger_instance->Do_Action('create');
        // Do a wordpress hook
        do_action('vcff_trigger_create',$trigger_instance);
        // Fire any on create action
        $trigger_instance->Do_Action('after_create');
        // Do a wordpress hook
        do_action('vcff_trigger_after_create',$trigger_instance);
        // Return the trigger instance
        return $trigger_instance;
    }
    
    public function Get_Action($action_id) {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Retrieve the action instance list
        $action_instances = $form_instance->events;
        // If the form is not using events
        if (!$action_instances || !is_array($action_instances)) { return ; }
        // Loop through the meta data
        foreach ($action_instances as $k => $action_instance) {
            // If the action ids do not match
            if ($action_instance->id != $action_id) { continue; }
            // Return the found action instance
            return $action_instance;
        }
        // Otherwise return null
        return;
    }
    
}