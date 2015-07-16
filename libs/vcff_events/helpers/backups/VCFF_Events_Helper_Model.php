<?php

class VCFF_Events_Helper_Model {

    protected $form_instance;	
	
    protected $data;
    
    public $alerts;
    
	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
    
    public function Set_Data($data,$is_post=false) {
        
        if (!$is_post) {
            $this->data = $data;
		} else {
            $this->data = array(
                'id' => isset($data['id']) ? $data['id'] : uniqid(),
                'order' => $data['order'],
                'name' => $data['name'],
                'code' => $data['code'],
                'description' => $data['description'],
                'event_selected' => $data['selected_event'],
                'event' => isset($data['events'][$data['selected_event']]) ? $data['events'][$data['selected_event']] : null ,
                'trigger_selected' => $data['selected_trigger'],
                'trigger' => isset($data['triggers'][$data['selected_trigger']]) ? $data['triggers'][$data['selected_trigger']] : null ,
            );
        }
		return $this;
    }
    
    public function Create($data) {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Create a new event populator
        $events_populator_helper = new VCFF_Events_Helper_Populator();
        // Populate with the events
        $action_instance = $events_populator_helper
            ->Set_Form_Instance($form_instance)
            ->Get_Instance($data);
        // Retrieve the meta values for the action instance
        $meta_values = $this->_Get_Meta_Values($action_instance);
        // If there was an error retrieving the meta values
        if (is_string($meta_values)) { return $meta_values; }
        // Update the post meta actions list
        add_post_meta($form_instance->Get_ID(),'vcff_meta_event_actions',$meta_values);
        // Otherwise return the data
        return $meta_values;
    }
    
    public function Update() { 
        // Retrieve the form instance
        $form_instance = $this->form_instance; 
        // Retrieve any stored meta value
		$stored_meta_actions = get_post_meta($form_instance->form_id,'vcff_meta_event_actions');
        // Create a new event populator
        $events_populator_helper = new VCFF_Events_Helper_Populator();
        // If the form is not using events
        if (!$stored_meta_actions) { return $this; }
        // Loop through the meta data
        foreach ($stored_meta_actions as $k => $meta_item_data) { 
            // If the action instance id's do not match
            if ($this->data['id'] != $meta_item_data['id']) { continue; }
            // Populate with the events
            $action_instance = $events_populator_helper
                ->Set_Form_Instance($form_instance)
                ->Get_Instance($this->data);
            // Retrieve the meta values for the action instance
            $meta_values = $this->_Get_Meta_Values($action_instance);
            // If there was an error retrieving the meta values
            if (is_string($meta_values)) { return 'could not update action'; }
            // Delete the post meta
            update_post_meta($form_instance->form_id,'vcff_meta_event_actions',$meta_values,$meta_item_data);
            // Return out
            return $action_instance;
        }
    }
    
    public function Update_All() {
        // Retrieve the form instance
        $form_instance = $this->form_instance; 
        // Retrieve any stored meta value
		$stored_meta_actions = get_post_meta($form_instance->form_id,'vcff_meta_event_actions');
        // Create a new event populator
        $events_populator_helper = new VCFF_Events_Helper_Populator();
        // If the form is not using events
        if (!$stored_meta_actions) { return $this; }
        // Loop through the meta data
        foreach ($stored_meta_actions as $k => $meta_item_data) { 
            // Populate with the events
            $action_instance = $form_instance->Get_Event($meta_item_data['id']);
            // Retrieve the meta values for the action instance
            $meta_values = $this->_Get_Meta_Values($action_instance); 
            // If there was an error retrieving the meta values
            if (is_string($meta_values)) { return 'could not update action'; }
            // Delete the post meta
            update_post_meta($form_instance->form_id,'vcff_meta_event_actions',$meta_values,$meta_item_data);
        }
    }
    
    public function Delete($action_id) {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Retrieve any stored meta value
		$stored_meta_actions = get_post_meta($form_instance->form_id,'vcff_meta_event_actions'); 
        // Create a new event populator
        $events_populator_helper = new VCFF_Events_Helper_Populator();
        // If the form is not using events
        if (!$stored_meta_actions) { return $this; }
        // Loop through the meta data
        foreach ($stored_meta_actions as $k => $meta_item_data) { 
            // If the action instance id's do not match
            if ($action_id != $meta_item_data['id']) { continue; }
            // Delete the post meta
            delete_post_meta($form_instance->form_id,'vcff_meta_event_actions',$meta_item_data);
        }
    }
    
    protected function _Get_Meta_Values($action_instance) {
        // Retrieve the selected event instance
        $event_instance = $action_instance->Get_Selected_Event_Instance();
        // If no event instance, return out
        if (!$event_instance || !is_object($event_instance)) { return 'no event selected'; }
        // Retrieve the trigger instance
        $trigger_instance = $action_instance->Get_Selected_Trigger_Instance();
        // If no trigger instance, return out
        if (!$trigger_instance || !is_object($trigger_instance)) { return 'no trigger selected'; }
        // Return the meta values
        return array(
            'id' => $action_instance->Get_ID(),
            'order' => $action_instance->Get_Order(),
            'name' => $action_instance->Get_Name(),
            'code' => $action_instance->Get_Code(),
            'description' => $action_instance->Get_Description(),
            'event_selected' => $action_instance->Get_Selected_Event(),
            'event' => $event_instance->Get_Value(),
            'trigger_selected' => $action_instance->Get_Selected_Trigger(),
            'trigger' => $trigger_instance->Get_Value(),
        );
    }
    
    // Add a form alert
    public function Add_Alert($message,$type) { 
        // Ensure the type is allowable
        if (!in_array($type,array('success','info','warning','danger'))) { return $this; }
        // If there are no current alerts matching the type, populate with empty array
        if (!isset($this->alerts[$type])) { $this->alerts[$type] = array(); } 
        // Add the alert message
        $this->alerts[$type][] = $message;
        // Return for chaining
        return $this;
    }
    
    public function Get_Alerts() {
        // If no alerts exist, return out
        if (!isset($this->alerts)) { return; }
        // Otherwise return the alerts
        return $this->alerts;
    }
    
    public function Get_Alerts_HTML() {
        // Retrieve the current alerts
        $alerts = $this->alerts;
        // If there are no alerts, return out
        if (!$alerts || !is_array($alerts) || count($alerts) == 0) { return; }
        // Start the html var
        $html = '';
        // Populate with any danger alerts
        $html .= $this->Get_Danger_Alerts_HTML();
        // Populate with any success alerts
        $html .= $this->Get_Success_Alerts_HTML();
        // Populate with any warning alerts
        $html .= $this->Get_Warning_Alerts_HTML();
        // Populate with any info alerts
        $html .= $this->Get_Info_Alerts_HTML();
        // Otherwise return the alerts
        return $html;
    }
    
    public function Get_Success_Alerts() {
        // If no alerts exist, return out
        if (!isset($this->alerts)) { return; }
        // If no alerts exist, return out
        if (!isset($this->alerts['success'])) { return; }
        // Otherwise return the alerts
        return $this->alerts['success'];
    }
    
    public function Get_Success_Alerts_HTML() {
        // If no alerts exist, return out
        if (!isset($this->alerts)) { return; }
        // If no alerts exist, return out
        if (!isset($this->alerts['success'])) { return; }
        // Start the html content
        $html = '<div class="alert alert-success" role="alert">';
        // Otherwise return the alerts
        foreach ($this->alerts['success'] as $k => $alert) {
            // Append the alert html content
            $html .= $alert;
        }
        // End the html content
        $html .= '</div>';
        // Return the alert html
        return $html;
    }
    
    public function Get_Info_Alerts() {
        // If no alerts exist, return out
        if (!isset($this->alerts)) { return; }
        // If no alerts exist, return out
        if (!isset($this->alerts['info'])) { return; }
        // Otherwise return the alerts
        return $this->alerts['info'];
    }
    
    public function Get_Info_Alerts_HTML() {
        // If no alerts exist, return out
        if (!isset($this->alerts)) { return; }
        // If no alerts exist, return out
        if (!isset($this->alerts['info'])) { return; }
        // Start the html content
        $html = '<div class="alert alert-info" role="alert">';
        // Otherwise return the alerts
        foreach ($this->alerts['info'] as $k => $alert) {
            // Append the alert html content
            $html .= $alert;
        }
        // End the html content
        $html .= '</div>';
        // Return the alert html
        return $html;
    }
    
    public function Get_Warning_Alerts() {
        // If no alerts exist, return out
        if (!isset($this->alerts)) { return; }
        // If no alerts exist, return out
        if (!isset($this->alerts['warning'])) { return; }
        // Otherwise return the alerts
        return $this->alerts['warning'];
    }
    
    public function Get_Warning_Alerts_HTML() {
        // If no alerts exist, return out
        if (!isset($this->alerts)) { return; }
        // If no alerts exist, return out
        if (!isset($this->alerts['warning'])) { return; }
        // Start the html content
        $html = '<div class="alert alert-warning" role="alert">';
        // Otherwise return the alerts
        foreach ($this->alerts['warning'] as $k => $alert) {
            // Append the alert html content
            $html .= $alert;
        }
        // End the html content
        $html .= '</div>';
        // Return the alert html
        return $html;
    }
    
    public function Get_Danger_Alerts() {
        // If no alerts exist, return out
        if (!isset($this->alerts)) { return; }
        // If no alerts exist, return out
        if (!isset($this->alerts['danger'])) { return; }
        // Otherwise return the alerts
        return $this->alerts['danger'];
    }
    
    public function Get_Danger_Alerts_HTML() {
        // If no alerts exist, return out
        if (!isset($this->alerts)) { return; }
        // If no alerts exist, return out
        if (!isset($this->alerts['danger'])) { return; }
        // Start the html content
        $html = '<div class="alert alert-danger" role="alert">';
        // Otherwise return the alerts
        foreach ($this->alerts['danger'] as $k => $alert) {
            // Append the alert html content
            $html .= $alert;
        }
        // End the html content
        $html .= '</div>';
        // Return the alert html
        return $html;
    }
}