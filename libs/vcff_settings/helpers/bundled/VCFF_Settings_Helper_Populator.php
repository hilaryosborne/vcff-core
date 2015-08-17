<?php

class VCFF_Settings_Helper_Populator extends VCFF_Helper {
    
    public $form_instance;
    
    public $data;
	
	public $default_fields = array();
	
	public $default_pages = array(
        
        array(
            'id' => 'general_settings',
            'title' => 'General Settings',
            'weight' => 1,
            'description' => 'This page contains the general settings',
            'icon' => '',
        )
        
    );

    public $default_groups = array(
        
        array(
            'id' => 'form_settings',
            'page_id' => 'general_settings',
            'title' => 'General Settings',
            'weight' => 1,
            'description' => 'This page contains the general settings',
            'icon' => '',
        )
        
    );
    
    public function Set_Form_Instance($form_instance) {
        
        $this->form_instance = $form_instance;
        
        return $this;
    }
    
    public function Set_Data($data) {
		
		$this->data = $data;
		
		return $this;
	}
    
    protected function _Add_Fields() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// If there are no default groups
		if (!isset($this->default_fields) || !is_array($this->default_fields)) { return $this; }
		// Retrieve the meta groups
		$default_fields = $this->default_fields;
        // Meta fields list
        $field_list = is_array($form_instance->built['fields']) ? $form_instance->built['fields'] : array() ;
		// Loop through each meta groups
		foreach ($default_fields as $k => $field_data) {
			// Retrieve the meta group id
			$machine_code = $meta_group['machine_code'];
			// If the meta group is already present
			if (isset($form_instance->built['fields'][$machine_code])) { continue; }
			// Add the meta group
			$field_list[] = $field_data;
		}
        // Run through the appropriate filters
        $field_list = apply_filters('vcff_settings_field_list', $field_list, $form_instance);
        // Populate the form context with the meta fields
        $form_instance->built['fields'] = $field_list;
		// Return for chaining
		return $this;
	}
	
	protected function _Add_Groups() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// If there are no default groups
		if (!isset($this->default_groups) || !is_array($this->default_groups)) { return $this; }
		// Meta fields list
        $group_list = is_array($form_instance->built['groups']) ? $form_instance->built['groups'] : array();
        // Retrieve the meta groups
		$default_groups = $this->default_groups;
		// Loop through each meta groups
		foreach ($default_groups as $k => $group_data) {
			// Retrieve the meta group id
			$group_id = $group_data['id'];
			// If the meta group is already present
			if (isset($form_instance->built['groups'][$group_id])) { continue; }
			// Add the meta group
			$group_list[] = $group_data;
		}
        // Run through the appropriate filters
        $group_list = apply_filters('vcff_settings_group_list', $group_list, $form_instance);
        // Populate the form context with the meta fields
        $form_instance->built['groups'] = $group_list;
		// Return for chaining
		return $this;
	}
	
	protected function _Add_Pages() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// If there are no default groups
		if (!isset($this->default_pages) || !is_array($this->default_pages)) { return $this; }
        // Meta fields list
        $page_list = is_array($form_instance->built['pages']) ? $form_instance->built['pages'] : array() ;
		// Retrieve the meta groups
		$default_pages = $this->default_pages;
		// Loop through each meta groups
		foreach ($default_pages as $k => $page_data) {
			// Retrieve the meta group id
			$page_id = $page_data['id'];
            // If the meta group is already present
			if (isset($form_instance->built['pages'][$page_id])) { continue; }
			// Add the meta group
			$page_list[] = $page_data;
		}
        // Run through the appropriate filters
        $page_list = apply_filters('vcff_settings_page_list', $page_list, $form_instance);
        // Populate the form context with the meta fields
        $form_instance->built['pages'] = $page_list;
		// Return for chaining
		return $this;
	}
    
    protected function _Get_Field_Instance($field_data) {
    
        $form_instance = $this->form_instance;
        // Retrieve the global var
        $vcff_settings = vcff_get_library('vcff_settings');
        // Set the settings options prefix
        $prefix = 'vcff_setting_';
        // Retrieve the injected data
        $data = $this->data;
        // Retrieve the list of contexts
        $contexts = $vcff_settings->contexts;
        // Retrieve the field name
		$machine_code = $field_data['machine_code'];
		// Retrieve the field type
		$field_type = $field_data['field_type']; 
		// Retrieve the field type
		$field_label = $field_data['field_label'];
        // Retrieve the field type
		$validation = $field_data['validation'];
		// If the context does not exist
		if (!isset($contexts[$field_type])) { return; }
        // Retrieve the field context information
		$field_context = $contexts[$field_type]; 
		// Retrieve the class item name
		$field_classname = $field_context['class_item'];
		// Create a new field instance
		$field_instance = new $field_classname();
        // Populate the form instance
        $field_instance->form_instance = $form_instance;
        // Set the meta instance handler
		$field_instance->context = $field_context;
		// Set the meta instance field name
		$field_instance->machine_code = $machine_code;
		// Set the meta instance field name
		$field_instance->field_label = $field_label;
		// Set the meta instance data
		$field_instance->data = $field_data;
        // Set the meta instance data
		$field_instance->validation = $validation;
		// Retrieve any stored meta value
		$stored_value = get_option($prefix.$machine_code); 
		// Retrieve any posted value
		$posted_value = isset($data[$machine_code]) ? $data[$machine_code] : false;
		// Retrieve any set meta value
		$set_value = isset($field_data['value']) ? $field_data['value'] : false ;
		// Retrieve any default meta value
		$default_value = isset($field_data['default_value']) ? $field_data['default_value'] : false ;
		// If there is posted meta value
		if (isset($data[$machine_code])) {
			// Populate the value with the posted value
			$field_instance->value = $posted_value; 
		} // Otherwise if there is stored meta value
		elseif ($form_instance->is_update != true && $stored_value) { 
			// Populate the value with the stored value
			$field_instance->value = $stored_value; 
		} // Otherwise if there is set meta value
		elseif ($set_value) { 
			// Populate the value with the set value
			$field_instance->value = $set_value; 
		} // Otherwise if there is a default meta value
		elseif ($default_value) { 
			// Populate the value with the default value
			$field_instance->value = $default_value; 
		}
        // Run through the appropriate filters
        $field_instance = apply_filters('vcff_settings_create_field_instance', $field_instance, $form_instance);
        // Return the field instance
        return $field_instance;
    }
    
    protected function _Build_Field_Instances() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form handler class
        $fields = $form_instance->built['fields'];
        
        if (!$fields || !is_array($fields)) { return; }
		// Loop through each meta field
		foreach ($fields as $k => $field_data) { 
            // Retrieve the field name
            $machine_code = $field_data['machine_code'];
            // If no field name, continue on
            if (!$machine_code) { continue; }
			// Generate a new meta fiel instance
			$field_instance = $this->_Get_Field_Instance($field_data);
            // If no field instance was returned
            if (!$field_instance || !is_object($field_instance)) { continue; }
			// Add the meta field to the form
			$form_instance->fields[$machine_code] = $field_instance;
		} 
    }
    
    public function Populate() {
        // Add settings fields
		$this->_Add_Fields();
		// Add settings groups
		$this->_Add_Groups();
		// Add settings pages
		$this->_Add_Pages();
        
        $this->_Build_Field_Instances();
        // Return for chaining
		return $this;
	}
    
    public function Check_Conditions() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
        // Retrieve the validation result
        do_action('vcff_pre_settings_conditional', $form_instance);
        // Create a new settings conditions checker
		$settings_conditions_helper = new VCFF_Settings_Helper_Conditions();
		// Execute the conditions check
		$settings_conditions_helper
			->Set_Form_Instance($form_instance)
			->Check();
        // Retrieve the validation result
        do_action('vcff_post_settings_conditional', $form_instance);
        // Return for chaining
		return $this;
	}
	
	public function Check_Validation() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
        // Retrieve the validation result
        do_action('vcff_pre_settings_validation', $form_instance);
        // Create a new settings conditions checker
		$settings_validation_helper = new VCFF_Settings_Helper_Validation();
		// Execute the conditions check
		$settings_validation_helper
			->Set_Form_Instance($form_instance)
			->Check();
        // Retrieve the validation result
        do_action('vcff_post_settings_validation', $form_instance);
        // Return for chaining
		return $this;
	}
}