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
    
    public $form_referrer;
    
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
    
    public function Get_Post_ID() {
    
        return $this->post_id;
    }
    
    public function Get_Type() {
        
        return $this->form_type;
    }
    
    public function Get_Name() {
    
        return $this->form_name;
    }

    public function Gen_Origin_Key() {
        // Generate a unique form key
        $origin_key = md5(rand(0,100).time().rand(0,100));
        // Add the key to the session
        $_SESSION['vcff_origin_keys'][$origin_key] = $this->form_type;
        // Return the origin key
        return $origin_key;
    }
    
    public function Gen_Referrer() {
        // Generate a unique form key
        $referrer_url = vcff_url();
        // Pass through a filter
        $referrer_url = $this->Apply_Filters('generate_referrer',$referrer_url,array());
        // Return the origin key
        return $referrer_url;
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
    
    public function Get_Elements() {
    
        $_elements = array_merge($this->events,$this->fields,$this->containers,$this->supports);
        
        $_elements = apply_filters('vcff_form_elements',$_elements,$this);
        
        return $_elements;
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

}