<?php

class VCFF_Action_Item extends VCFF_Item {
    
    public $form_instance;
    
    public $data;
    
    public $triggers;
    
    public $order;
    
    public $selected_trigger;
    
    public $events;
    
    public $selected_event;
    
    public $is_update = false;
    
    public $is_valid = true;
    
    public $validation_errors = array();
    
    public function Is_Valid() {
    
        return $this->is_valid;
    }
    
    public function Is_Update() {
    
        return $this->is_update;
    }

    public function Check_Validation() {
    
    }

    public function Get_ID() {
    
        return $this->id;
    }
    
    public function Get_Order() {
    
        return $this->order;
    }
    
    public function Get_Name() {

        return isset($this->data['name']) ? $this->data['name'] : null ;
    }
    
    public function Get_Code() {

        return isset($this->data['code']) ? $this->data['code'] : null ;
    }
    
    public function Get_Description() {
    
        return isset($this->data['description']) ? $this->data['description'] : null ;
    }
    
    public function Get_Selected_Event() {
    
        return isset($this->data['selected_event']) ? $this->data['selected_event'] : null ;
    }
    
    public function Add_Event($event_instance) { 
		
        if (!$event_instance) { return $this; }
        
        if (!is_object($event_instance)) { return $this; }
        
        if (!$event_instance->type) { return $this; }
        
		$_type = $event_instance->type;
		
		$event_instance->action_instance = $this;
		
        $event_instance->value = $this->data['events'][$_type];
        
		$this->events[$_type] = $event_instance;
	}
    
    public function Add_Trigger($trigger_instance) { 
		
        if (!$trigger_instance) { return $this; }
        
        if (!is_object($trigger_instance)) { return $this; }
        
        if (!$trigger_instance->type) { return $this; }
        
		$_type = $trigger_instance->type;
		
		$trigger_instance->action_instance = $this;
		
        $trigger_instance->value = $this->data['triggers'][$_type];
        
		$this->triggers[$_type] = $trigger_instance;
	}
    
    public function Get_Curly_Tags() {
        // Retrieve the selected trigger
        $selected_event = $this->Get_Selected_Event(); 
        
        $curly_tags = array();
        // If there is a selected event
        if (is_object($selected_event) && method_exists($selected_event,'Get_Curly_Tags')) {
            // Retrieve the list of event curly tags
            $event_curly_tags = $selected_event->Get_Curly_Tags();
            // Populate with the curly tags
            $curly_tags = array_merge($curly_tags,$event_curly_tags);
        }
        // Retrieve the selected trigger
        $selected_trigger = $this->Get_Selected_Trigger();
        // If there is a selected event
        if (is_object($selected_trigger) && method_exists($selected_trigger,'Get_Curly_Tags')) {
            // Retrieve the list of event curly tags
            $trigger_curly_tags = $selected_trigger->Get_Curly_Tags();
            // Populate with the curly tags
            $curly_tags = array_merge($curly_tags,$trigger_curly_tags);
        }
        
        return $curly_tags;
    }   
    
    public function Get_Selected_Event_Instance() {
        // Retrieve the selected trigger
        $selected_event = $this->Get_Selected_Event(); 
        // If no selected event
        if (!$selected_event) { return; }
        // Retrieve the list of trigger contextrs
        $events = $this->events;
        // Loop through each trigger
        if (isset($events[$selected_event])) { return $events[$selected_event]; }
    }
    
    public function Get_Selected_Trigger() {
    
        return isset($this->data['selected_trigger']) ? $this->data['selected_trigger'] : null ;
    }
    
    public function Get_Selected_Trigger_Instance() {
        // Retrieve the selected trigger
        $selected_trigger = $this->Get_Selected_Trigger();
        // If no selected event
        if (!$selected_trigger) { return; }
        // Retrieve the list of trigger contextrs
        $triggers = $this->triggers;
        // Loop through each trigger
        if (isset($triggers[$selected_trigger])) { return $triggers[$selected_trigger]; }
    }
    
    public function Get_JS_Assets() { 
        // The list of js assets
        $js_assets = array();
        // Retrieve the triggers
        $triggers = $this->triggers;
        // Loop through each of the triggers
        foreach ($triggers as $k => $trigger) {
            // Retrieve the event params
            $trigger_params = $trigger->context['params'];
            // If there are no script files
            if (!isset($trigger_params['js']) || !is_array($trigger_params['js'])) { continue; }
            // Merge with the current list
            $js_assets = array_merge($js_assets, $trigger_params['js']);
        }
        // Retrieve the triggers
        $events = $this->events;
        // Loop through each of the triggers
        foreach ($events as $k => $event) {
            // Retrieve the event params
            $event_params = $event->context['params'];
            // If there are no script files
            if (!isset($event_params['js']) || !is_array($event_params['js'])) { continue; }
            // Merge with the current list
            $js_assets = array_merge($js_assets, $event_params['js']);
        }
        
        return $js_assets;
    }
  
    public function Check() { 
        // Retrieve the trigger instance
        $trigger_instance = $this->Get_Selected_Trigger_Instance();
        // If no trigger instance was returned
        if (!is_object($trigger_instance)) { return false; }  
        // Return the result
        return $trigger_instance->Check() ? true : false ;
    }
    
    public function Trigger() { 
        // Retrieve the trigger instance
        $event_instance = $this->Get_Selected_Event_Instance();
        // If no trigger instance was returned
        if (!is_object($event_instance)) { return false; }
        // Return the result
        return $event_instance->Trigger() ? true : false ;
    }
    
}