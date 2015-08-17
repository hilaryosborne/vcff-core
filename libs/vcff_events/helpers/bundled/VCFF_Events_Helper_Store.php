<?php

class VCFF_Events_Helper_Store extends VCFF_Helper {
 
    protected $action_instance;	
	
	public function Set_Action_Instance($action_instance) {
		// Populate with the action instance
		$this->action_instance = $action_instance;
		// Return for chaining
		return $this;
	}
    
    public function Delete() {
        // Retrieve the action instance
        $action_instance = $this->action_instance;
        // Retrieve the form instance
        $form_instance = $action_instance->form_instance; 
        // Retrieve any stored meta value
		$stored_meta_actions = get_post_meta($form_instance->form_id,'vcff_meta_event_actions'); 
        // If the form is not using events
        if (!$stored_meta_actions) { return $this; }
        // Loop through the meta data
        foreach ($stored_meta_actions as $k => $meta_item_data) { 
            // If the action instance id's do not match
            if ($action_instance->id != $meta_item_data['id']) { continue; }
            // Delete the post meta
            delete_post_meta($form_instance->form_id,'vcff_meta_event_actions',$meta_item_data);
        }
    }
    
    public function Store() {
        // Retrieve the action instance
        $action_instance = $this->action_instance;
        // Retrieve the action id
        $action_id = $action_instance->Get_ID();
        // If the action does not have an id
        if (!$action_id) { 
            // Assign the action id with a uuid
            $action_instance->id = uniqid();
            // Retrieve the action id
            $action_id = $action_instance->Get_ID();
        }
        // Retrieve the form instance
        $form_instance = $action_instance->form_instance; 
        // Retrieve any stored meta value
		$stored_meta_actions = get_post_meta($form_instance->form_id,'vcff_meta_event_actions'); 
        // Construct the storage data
        $storage_data = $action_instance->data;
        // Set each storage data value
        $storage_data['id'] = $action_instance->id;
        $storage_data['name'] = $action_instance->name;
        $storage_data['order'] = $action_instance->order;
        $storage_data['code'] = $action_instance->code;
        $storage_data['description'] = $action_instance->description;
        // Pass the data through a wp filter (to allow for alterations)
        $storage_data = apply_filters('vcff_event_store_data',$storage_data);
        // If the form is not using events
        if (!$stored_meta_actions) {
            // Delete the post meta
            add_post_meta($form_instance->form_id,'vcff_meta_event_actions',$storage_data);
            // Return out
            return;
        }
        // Loop through the meta data
        foreach ($stored_meta_actions as $k => $meta_item_data) { 
            // If the action instance id's do not match
            if ($action_id != $meta_item_data['id']) { continue; } 
            // Delete the post meta
            update_post_meta($form_instance->form_id,'vcff_meta_event_actions',$storage_data,$meta_item_data);
            // Return out
            return;
        } 
        // Delete the post meta
        add_post_meta($form_instance->form_id,'vcff_meta_event_actions',$storage_data);
    }
 
}