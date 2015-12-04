<?php

class VCFF_Events_Helper_Instance extends VCFF_Helper {

    protected $form_instance;	
	
    protected $action_instance;
    
	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
    
    public function Build($action_data) {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Create the new action item instance
        $action_instance = new Action_Standard_Item();
        // Populate with the form instance
        $action_instance->id = isset($action_data['id']) ? $action_data['id'] : uniqid() ;
        // Populate with the form instance
        $action_instance->form_instance = $form_instance;
        // Populate with the form instance
        $action_instance->name = $action_data['name'];
        // Populate with the form instance
        $action_instance->order = $action_data['order'];
        // Populate with the form instance
        $action_instance->code = $action_data['code'];
        // Populate with the form instance
        $action_instance->description = $action_data['description'];
        // Populate with raw data
        $action_instance->data = is_array($action_data) ? $action_data : array();
        // If the field has a sanitize method
        if (method_exists($action_instance,'On_Create')) { $action_instance->On_Create(); }
        // Fire any on create action
        $action_instance->Do_Action('create');
        // Do a wordpress hook
        do_action('vcff_action_create',$action_instance);
        // Store the action instance
        $this->action_instance = $action_instance;
        // Populate with available triggers
        $this->_Get_Action_Triggers();
        // Populate with available events
        $this->_Get_Action_Events();
        // Fire any on create action
        $action_instance->Do_Action('after_create');
        // Return the action instance
        return $this->action_instance;
    }
    
    protected function _Get_Action_Triggers() {
        // Retrieve the global events
        $vcff_events = vcff_get_library('vcff_events');
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Retrieve the action instance
        $action_instance = $this->action_instance;
        // Retrieve the form type
        $form_type = $form_instance->Get_Type();
        // Retrieve the action data
        $action_data = $action_instance->data;
        // Retrieve the list of trigger contextrs
        $triggers = $vcff_events->event_triggers;  
        // Loop through each trigger
        foreach ($triggers as $trigger_code => $_context) {
            // Retrieve the trigger class item
            $class = $_context['class'];
            // Generate a new trigger class
            $trigger_instance = new $class();
            // Populate with the form instance
            $trigger_instance->form_instance = $form_instance;
            // Populate with the form instance
            $trigger_instance->code = $_context['code'];
            // Populate with the form instance
            $trigger_instance->title = $_context['title'];
            // Populate with the trigger context
            $trigger_instance->context = $_context;
            // Populate with the action instance
            $trigger_instance->action_instance = $action_instance;
            // If the field has a sanitize method
            if (method_exists($trigger_instance,'On_Create')) { $trigger_instance->On_Create(); }
            // Fire any on create action
            $trigger_instance->Do_Action('create');
            // Do a wordpress hook
            do_action('vcff_trigger_create',$trigger_instance);
            // If the field has a sanitize method
            if (method_exists($trigger_instance,'Is_Compatible') && !$trigger_instance->Is_Compatible()) { continue; }
            // Add to the action instance
            $action_instance->triggers[$trigger_code] = $trigger_instance; 
            // If this is the selected trigger
            if (isset($action_data['selected_trigger']) 
                && $action_data['selected_trigger'] == $trigger_code) {
                // Add to the action instance
                $action_instance->selected_trigger = $trigger_instance; 
                // If this trigger has data
                if (isset($action_data['triggers'][$trigger_code])) { 
                    // Populate with the form instance
                    $trigger_instance->value = $action_data['triggers'][$trigger_code];
                }
            }
            // Fire any on create action
            $trigger_instance->Do_Action('after_create');
            // Do a wordpress hook
            do_action('vcff_trigger_after_create',$trigger_instance);
        }
    }
    
    protected function _Get_Action_Events() {
        // Retrieve the global events
        $vcff_events = vcff_get_library('vcff_events');
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Retrieve the action instance
        $action_instance = $this->action_instance;
        // Retrieve the form type
        $form_type = $form_instance->Get_Type();
        // Retrieve the action data
        $action_data = $action_instance->data;
        // Retrieve the list of event contexts
        $events = $vcff_events->event_types;
        // Loop through each trigger
        foreach ($events as $event_type => $event_context) {
            // Retrieve the event class item
            $event_class_item = $event_context['class_item'];
            // Generate a new event class
            $event_instance = new $event_class_item();
            // Populate with the form instance
            $event_instance->form_instance = $form_instance;
            // Populate with the form instance
            $event_instance->type = $event_context['type'];
            // Populate with the form instance
            $event_instance->title = $event_context['title'];
            // Populate with the event context
            $event_instance->context = $event_context;
            // Populate with the action instance
            $event_instance->action_instance = $action_instance;
            // If the field has a sanitize method
            if (method_exists($event_instance,'On_Create')) { $event_instance->On_Create(); }
            // Fire any on create action
            $event_instance->Do_Action('create');
            // Do a wordpress hook
            do_action('vcff_event_create',$event_instance);
            // If the field has a sanitize method
            if (method_exists($event_instance,'Is_Compatible') && !$event_instance->Is_Compatible()) { continue; }
            // Add to the action instance
            $action_instance->events[$event_type] = $event_instance;
            // If this is the selected trigger
            if (isset($action_data['selected_event']) 
                && $action_data['selected_event'] == $event_type) {
                // Add to the action instance
                $action_instance->selected_event = $event_instance; 
                // If this event has data
                if (isset($action_data['events'][$event_type])) { 
                    // Populate with the form instance
                    $event_instance->value = $action_data['events'][$event_type];
                }
            }
            // Fire any on create action
            $event_instance->Do_Action('after_create');
            // Do a wordpress hook
            do_action('vcff_event_after_create',$event_instance);
        }
    }
}