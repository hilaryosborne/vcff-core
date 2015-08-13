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
		$stored_meta_actions = get_post_meta($form_instance->form_id,'vcff_meta_event_actions');
        // If the form is not using events
        if (!$stored_meta_actions) { return $this; } 
        //die(print_r($stored_meta_actions));
        uasort($stored_meta_actions, function($a,$b){
            if ($a == $b) { return 0; }
            return ($a['order'] < $b['order']) ? -1 : 1;
        });
        // Loop through the meta data
        foreach ($stored_meta_actions as $k => $meta_item_data) { 
            // Create a new instance helper
            $events_helper_instance = new VCFF_Events_Helper_Instance();
            // Retrieve the action instance from the stored data
            $action_instance = $events_helper_instance
                ->Set_Form_Instance($form_instance)
                ->Build($meta_item_data);
            // If the field has a sanitize method
            if (method_exists($action_instance,'Is_Compatible') && !$action_instance->Is_Compatible()) { continue; }
            // Add the event to the form instance
            $form_instance->Add_Event($action_instance);
        } 
        // Return for chaining
        return $this;
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