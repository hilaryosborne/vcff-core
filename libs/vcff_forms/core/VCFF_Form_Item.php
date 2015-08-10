<?php

class VCFF_Form_Item extends VCFF_Item {

    public $post_id;

    public $form_id;
    
    public $form_uuid;
    
    public $form_name;
    
    public $form_content;

    public $form_state;

    public $form_attributes;
    
    public $context;
    
    public $meta = array();
    
    public $events = array();
    
    public $fields = array();
    
    public $containers = array();
    
    public $supports = array();

    public $is_ajax = false;
    
    public $is_pre_submission = false;
    
    public $is_submission = false;
    
    public $is_valid = true;
    
    public $use_events = true;
    
    public $use_ajax = true;
    
    public $ajax;
    
    public $standard;
    
    public $input_timeout = 4000;
    
    public $result_validation;
    
    public $result_submission;
	
	public $result_events;
    
    public $security_key;
    
    public $is_session = false;
    
    public $session_key;
    
    public $session_data;
    
    /**
     * ALERTS
     */
    public $alerts;
    
    public function Validation_Failed() {
        $this->Add_Alert('Oh Dear, That is bad','danger');
    }
    
    public function Validation_Passed() {
        //$this->Add_Alert('Oh Yey, That is good','success');
    }
    
    public function Submission_Failed() {
    
    }
    
    public function Submission_Passed() {
    
    }
    
    public function Get_ID() {
    
        return $this->form_id;
    }
    
    public function Get_UUID() {
    
        return $this->form_uuid;
    }
    
    public function Get_Type() {
        
        return $this->form_type;
    }
    
    public function Get_Name() {
    
        return $this->form_name;
    }
    
    public function Set_Security_Key($key) {
    
        $this->security_key = $key;
        
        return $this;
    }
    
    public function Get_Security_Key() {
        
        return $this->security_key;
    }
    
    public function Issue_Security_Key() {
    
        $form_security_helper = new VCFF_Forms_Helper_Security();
        
        $form_security_helper
            ->Set_Form_Instance($this)
            ->Issue_Key();
            
        return $this->security_key;
    }
    
	/**
	* NEW METHODS
	*/
	
	public function Add_Container($container_instance) { 
		
        if (!$container_instance) { return $this; }
        
        if (!is_object($container_instance)) { return $this; }
        
        if (!$container_instance->machine_code) { return $this; }
        
		$machine_code = $container_instance->machine_code;
		
		$container_instance->form_instance = $this;
		
		$this->containers[$machine_code] = $container_instance;
		
	}
    
    public function Get_Container($machine_code) {
        
        if (!isset($this->containers[$machine_code])) { return; }
        
        $container_instance = $this->containers[$machine_code];
        
        return $container_instance;
    }
    
    public function Get_State() {
    
        return $this->form_state;
    }
    
    public function Get_Containers() {
        
        return $this->containers;
    }
	
	public function Add_Field($field_instance) {
        
        if (!$field_instance) { return $this; }
        
        if (!is_object($field_instance)) { return $this; }
        
        if (!$field_instance->machine_code) { return $this; }
        
		$machine_code = $field_instance->machine_code;
		
		$field_instance->form_instance = $this;
		
		$this->fields[$machine_code] = $field_instance;
		
	}
	
    public function Get_Meta($machine_code) { 
		
		return $this->meta[$machine_code];
	}
    
	public function Add_Meta($meta_instance) { 
		
        if (!$meta_instance) { return $this; }
        
        if (!is_object($meta_instance)) { return $this; }
        
        if (!$meta_instance->machine_code) { return $this; }
        
		$machine_code = $meta_instance->machine_code;
		
		$meta_instance->form_instance = $this;
		
		$this->meta[$machine_code] = $meta_instance;
		
	}
    
    public function Add_Event($event_instance) {
		
        if (!$event_instance) { return $this; }
        
        if (!is_object($event_instance)) { return $this; }
        
        if (!$event_instance->id) { return $this; }
        
		$event_id = $event_instance->id;
		
		$event_instance->form_instance = $this;
		
		$this->events[$event_id] = $event_instance;
		
	}
    
    public function Add_Support($support_instance) {
		
        if (!$support_instance) { return $this; }
        
        if (!is_object($support_instance)) { return $this; }
        
        if (!$support_instance->machine_code) { return $this; }
        
		$machine_code = $support_instance->machine_code;
		
		$support_instance->form_instance = $this;
		
		$this->supports[$machine_code] = $support_instance;
		
	}
    
    public function Get_Support($machine_code) {
    
        return $this->supports[$machine_code];
    }
    
    public function Get_Event($event_id) {
    
        return $this->events[$event_id];
    }
    
    public function Get_Events() {
    
        return $this->events;
    }
	
    public function Is_Valid() {
        
        return $this->is_valid;
    }
    
    public function Is_Submission() {
        
        return $this->is_submission;
    }
    
    public function Is_Pre_Submission() {
        
        return $this->is_pre_submission;
    }
    
    public function Is_AJAX() {
        
        return $this->is_ajax;
    }
    
    public function Is_Valid_Submission() {
        // Retrieve the submission result
        $submission_result = $this->result_submission;
        
        if (is_array($submission_result)) {
            return true;
        } else {
            return false;
        }
    }

    public function On_Validation() {
        
    }

    public function Get_Fields() {
        
        return $this->fields;
    }

    public function Get_Field_List() {
    
        $field_list = $this->fields;
        
        foreach ($field_list as $machine_code => $field_instance) {
        
            $field_list[$machine_code] = $machine_code;
        }
        
        return $field_list;
    }

    public function Get_Field($machine_code) {
        
        if (!isset($this->fields[$machine_code])) { return; }
        
        $field_instance = $this->fields[$machine_code];
        
        return $field_instance;
    }

    public function Get_Field_Value($machine_code) {
        
        if (!isset($this->fields[$machine_code])) { return; }
        
        $field_instance = $this->fields[$machine_code];
        
        return $field_instance->posted_value;
    }

    public function Get_Meta_Field_Value($machine_code) {
        
        if (!isset($this->meta[$machine_code])) { return; }
        
        $meta_field_instance = $this->meta[$machine_code];
        
        return $meta_field_instance->value;
        
    }
    
    public function Get_Field_Data() {
        // The place to store the field values
        $field_values = array();
        // If no fields, return nothing
        if (!$this->fields || !is_array($this->fields)) { return array(); }
        // Loop through each form field
        foreach ($this->fields as $machine_code => $field_instance) {
            // Populate the current field values
            $field_values[$machine_code] = $field_instance->Get_RAW_Value();
        }
        // Return all of the values
        return $field_values;
    }
    
    public function Setup() {
        // Create a new session helper
        $session_helper = new VCFF_Forms_Helper_Session();
        // Update the form's session
        $session_helper
            ->Set_Form_Instance($this)
            ->Get_Key();
        // Populate the session data
        $this->session_data = $session_helper->Get_Data();
        // Return out
        return $this; 
    }
    
    public function Get_Session_Data() {
        
        return $this->session_data;
    }
    
    public function Set_Session_Data($session_data) {
        // Create a new session helper
        $session_helper = new VCFF_Forms_Helper_Session();
        // Update the session data
        $this->session_data = $session_data;
        // Update the form's session
        $session_helper
            ->Set_Form_Instance($this)
            ->Update();
        // Return out
        return $this; 
    }
    
    public function Get_Curly_Tags() {
        
        return array();
    }

    public function Add_Redirect($url,$method,$params) {
        // Add the alert message
        $this->redirects = array($url,$method,$params);
        // Return for chaining
        return $this;
    }

    public function Get_Redirects() {
        // If no alerts exist, return out
        if (!isset($this->redirects)) { return; }
        // Otherwise return the alerts
        return $this->redirects;
    }
    
    public function Get_Override_HTML() {
        // If no alerts exist, return out
        if (!isset($this->overrides)) { return; }
        // Start the html content
        $html = '<div class="override">';
        // Otherwise return the alerts
        foreach ($this->overrides as $k => $override) {
            // Append the alert html content
            $html .= $override;
        }
        // End the html content
        $html .= '</div>';
        // Return the alert html
        return $html;
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