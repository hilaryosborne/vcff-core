<?php

class VCFF_Settings_Helper_Populator extends VCFF_Helper {
    
    public $data;

    public $built_pages = array();
    
    public $built_groups = array();

    public $built_fields = array();
    
    public $instance_fields = array();

    public function Set_Data($data) {
    
        $this->data = $data;
    
        return $this;
    }

    public function Add_Triggers() {
        // Retrieve the global var
        $vcff_events = vcff_get_library('vcff_events');
        // Retrieve the list of contexts
        $contexts = $vcff_events->event_triggers;
        // Loop through each context
        foreach ($contexts as $type => $context) {
            // Retrieve the settings
            $settings = $context['settings'];
            // If the form has no custom settings
            if (!is_array($settings) || count($settings) == 0) { continue; }
            // If the context has custom setting pages
            if (isset($settings['pages']) && is_array($settings['pages'])) {
                // Merge the settings pages in with existing pages
                $this->built_pages = array_merge($this->built_pages,$settings['pages']);
            }
            // If the context has custom setting groups
            if (isset($settings['groups']) && is_array($settings['groups'])) {
                // Merge the settings groups in with existing groups
                $this->built_groups = array_merge($this->built_groups,$settings['groups']);
            }
            // If the context has custom setting fields
            if (isset($settings['fields']) && is_array($settings['fields'])) {
                // Merge the settings fields in with existing fields
                $this->built_fields = array_merge($this->built_fields,$settings['fields']);
            }
        }
        // If there were no built fields
        if (!$this->built_fields || !is_array($this->built_fields) || count($this->built_fields) == 0) { return $this; }
        // Loop through each built field
        foreach ($this->built_fields as $k => $field_data) {
            // Retrieve the field name
		    $field_name = $field_data['field_name'];
            // Populate the field instances
            $this->instance_fields[$field_name] = $this->_Get_Field_Instance($field_data);
        }
        // Return out
        return $this;
    }
    
    public function Add_Events() {
        // Retrieve the global var
        $vcff_events = vcff_get_library('vcff_events');
        // Retrieve the list of contexts
        $contexts = $vcff_events->event_types;
        // Loop through each context
        foreach ($contexts as $type => $context) {
            // Retrieve the settings
            $settings = $context['settings'];
            // If the form has no custom settings
            if (!is_array($settings) || count($settings) == 0) { continue; }
            // If the context has custom setting pages
            if (isset($settings['pages']) && is_array($settings['pages'])) {
                // Merge the settings pages in with existing pages
                $this->built_pages = array_merge($this->built_pages,$settings['pages']);
            }
            // If the context has custom setting groups
            if (isset($settings['groups']) && is_array($settings['groups'])) {
                // Merge the settings groups in with existing groups
                $this->built_groups = array_merge($this->built_groups,$settings['groups']);
            }
            // If the context has custom setting fields
            if (isset($settings['fields']) && is_array($settings['fields'])) {
                // Merge the settings fields in with existing fields
                $this->built_fields = array_merge($this->built_fields,$settings['fields']);
            }
        }
        // If there were no built fields
        if (!$this->built_fields || !is_array($this->built_fields) || count($this->built_fields) == 0) { return $this; }
        // Loop through each built field
        foreach ($this->built_fields as $k => $field_data) {
            // Retrieve the field name
		    $field_name = $field_data['field_name'];
            // Populate the field instances
            $this->instance_fields[$field_name] = $this->_Get_Field_Instance($field_data);
        }
        // Return out
        return $this;
    }

    public function Add_Forms() {
        // Retrieve the global var
        $vcff_forms = vcff_get_library('vcff_forms');
        // Retrieve the list of contexts
        $contexts = $vcff_forms->contexts;
        // Loop through each context
        foreach ($contexts as $type => $context) {
            // Retrieve the settings
            $settings = $context['settings'];
            // If the form has no custom settings
            if (!is_array($settings) || count($settings) == 0) { continue; }
            // If the context has custom setting pages
            if (isset($settings['pages']) && is_array($settings['pages'])) {
                // Merge the settings pages in with existing pages
                $this->built_pages = array_merge($this->built_pages,$settings['pages']);
            }
            // If the context has custom setting groups
            if (isset($settings['groups']) && is_array($settings['groups'])) {
                // Merge the settings groups in with existing groups
                $this->built_groups = array_merge($this->built_groups,$settings['groups']);
            }
            // If the context has custom setting fields
            if (isset($settings['fields']) && is_array($settings['fields'])) {
                // Merge the settings fields in with existing fields
                $this->built_fields = array_merge($this->built_fields,$settings['fields']);
            }
        }
        // If there were no built fields
        if (!$this->built_fields || !is_array($this->built_fields) || count($this->built_fields) == 0) { return $this; }
        // Loop through each built field
        foreach ($this->built_fields as $k => $field_data) {
            // Retrieve the field name
		    $field_name = $field_data['field_name'];
            // Populate the field instances
            $this->instance_fields[$field_name] = $this->_Get_Field_Instance($field_data);
        }
        // Return out
        return $this;
    }
    
    public function Add_Containers() {
        // Retrieve the global var
        $vcff_containers = vcff_get_library('vcff_containers');
        // Retrieve the list of contexts
        $contexts = $vcff_containers->contexts;
        // Loop through each context
        foreach ($contexts as $type => $context) {
            // Retrieve the settings
            $settings = $context['settings'];
            // If the form has no custom settings
            if (!is_array($settings) || count($settings) == 0) { continue; }
            // If the context has custom setting pages
            if (isset($settings['pages']) && is_array($settings['pages'])) {
                // Merge the settings pages in with existing pages
                $this->built_pages = array_merge($this->built_pages,$settings['pages']);
            }
            // If the context has custom setting groups
            if (isset($settings['groups']) && is_array($settings['groups'])) {
                // Merge the settings groups in with existing groups
                $this->built_groups = array_merge($this->built_groups,$settings['groups']);
            }
            // If the context has custom setting fields
            if (isset($settings['fields']) && is_array($settings['fields'])) {
                // Merge the settings fields in with existing fields
                $this->built_fields = array_merge($this->built_fields,$settings['fields']);
            }
        }
        // If there were no built fields
        if (!$this->built_fields || !is_array($this->built_fields) || count($this->built_fields) == 0) { return $this; }
        // Loop through each built field
        foreach ($this->built_fields as $k => $field_data) {
            // Retrieve the field name
		    $field_name = $field_data['field_name'];
            // Populate the field instances
            $this->instance_fields[$field_name] = $this->_Get_Field_Instance($field_data);
        }
        // Return out
        return $this;
    }
    
    public function Add_Fields() {
        // Retrieve the global var
        $vcff_fields = vcff_get_library('vcff_fields');
        // Retrieve the list of contexts
        $contexts = $vcff_fields->contexts;
        // Loop through each context
        foreach ($contexts as $type => $context) {
            // Retrieve the settings
            $settings = $context['settings'];
            // If the form has no custom settings
            if (!is_array($settings) || count($settings) == 0) { continue; }
            // If the context has custom setting pages
            if (isset($settings['pages']) && is_array($settings['pages'])) {
                // Merge the settings pages in with existing pages
                $this->built_pages = array_merge($this->built_pages,$settings['pages']);
            }
            // If the context has custom setting groups
            if (isset($settings['groups']) && is_array($settings['groups'])) {
                // Merge the settings groups in with existing groups
                $this->built_groups = array_merge($this->built_groups,$settings['groups']);
            }
            // If the context has custom setting fields
            if (isset($settings['fields']) && is_array($settings['fields'])) {
                // Merge the settings fields in with existing fields
                $this->built_fields = array_merge($this->built_fields,$settings['fields']);
            }
        }
        // If there were no built fields
        if (!$this->built_fields || !is_array($this->built_fields) || count($this->built_fields) == 0) { return $this; }
        // Loop through each built field
        foreach ($this->built_fields as $k => $field_data) {
            // Retrieve the field name
		    $field_name = $field_data['field_name'];
            // Populate the field instances
            $this->instance_fields[$field_name] = $this->_Get_Field_Instance($field_data);
        }
        // Return out
        return $this;
    }
    
    public function Add_Hooked() {
        // Include any hooked
        do_action('vcff_settings_build',$this);
        // Return out
        return $this;
    }
    
    protected function _Get_Field_Instance($field_data) {
        // Retrieve the global var
        $vcff_settings = vcff_get_library('vcff_settings');
        
        $prefix = 'vcff_setting_';
        // Retrieve the injected data
        $data = $this->data;
        // Retrieve the list of contexts
        $contexts = $vcff_settings->contexts;
        // Retrieve the field name
		$field_name = $field_data['field_name'];
		// Retrieve the field type
		$field_type = $field_data['field_type']; 
		// Retrieve the field type
		$field_label = $field_data['field_label'];
		// If the context does not exist
		if (!isset($contexts[$field_type])) { return; }
        // Retrieve the field context information
		$field_context = $contexts[$field_type]; 
		// Retrieve the class item name
		$field_classname = $field_context['class_item'];
		// Create a new field instance
		$field_instance = new $field_classname();
        // Set the meta instance handler
		$field_instance->context = $field_context;
		// Set the meta instance field name
		$field_instance->field_name = $field_name;
		// Set the meta instance field name
		$field_instance->field_label = $field_label;
		// Set the meta instance data
		$field_instance->data = $field_data;
		// Retrieve any stored meta value
		$stored_value = get_option($prefix.$field_name); 
		// Retrieve any posted value
		$posted_value = isset($data[$field_name]) ? $data[$field_name] : false;
		// Retrieve any set meta value
		$set_value = isset($field_data['value']) ? $field_data['value'] : false ;
		// Retrieve any default meta value
		$default_value = isset($field_data['default_value']) ? $field_data['default_value'] : false ;
		// If there is posted meta value
		if ($posted_value) { 
			// Populate the value with the posted value
			$field_instance->value = $posted_value; 
		} // Otherwise if there is stored meta value
		elseif ($stored_value) { 
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
        // Return the field instance
        return $field_instance;
    }

    public function Check_Conditions() {
        // Loop through each built field
		foreach ($this->instance_fields as $k => $field_instance) {
            // Check the field instance
            $this->_Check_Field_Instance($field_instance);
        }
		// Return for chaining
		return $this;
    }
    
    protected function _Check_Field_Instance($field_instance) {
        // If there are no conditions
        if (!isset($field_instance->data['field_dependancy'])) {
            // Update the field's conditions
            $field_instance->is_hidden = false; return;
        }
		// Retrieve the dependancy conditions
        $dependancy = $field_instance->data['field_dependancy']; 
		// If there are no conditions
        if (!is_array($dependancy)) {
            // Update the field's conditions
            $field_instance->is_hidden = false; return;
        }
        // Retrieve the dependancy outcome
        $dependancy_outcome = $dependancy['outcome'] ? $dependancy['outcome'] : 'show';
        // Retrieve the dependancy outcome
        $dependancy_require = $dependancy['requires'] ? $dependancy['requires'] : 'all';
        // Retrieve the dependancy conditions
        $dependancy_conditions = $dependancy['conditions'];
        // The incremental vars
        $conditions_failed = array();
        $conditions_passed = array();
        // Loop through each of the conditions
        foreach ($dependancy_conditions as $k => $field_condition) {
            // Retrieve the rule params
            $condition_target_field = $field_condition[0];
            $condition_rule = $field_condition[1];
            $condition_value = $field_condition[2]; 
			// If no target meta field was returned
            if (!isset($this->instance_fields[$condition_target_field])) { continue; }
            // Retrieve the target meta field
            $target_field = $this->instance_fields[$condition_target_field]; 
            // Create the checking method name
            $target_check_method = 'Check_Rule_'.strtoupper($condition_rule); 
            // Check the method exists
            if (!method_exists($target_field,$target_check_method)) { continue; }
            // Call the checking method
            $check_result = call_user_func_array(array($target_field, $target_check_method), array($condition_value));
            // Increment the correct variable
            if ($check_result) { $conditions_passed[$k] = $field_condition; } else { $conditions_failed[$k] = $field_condition; }
        } 
        // If the field is to be show on passing conditions
        if ($dependancy_outcome == 'show') {
            // If we require all fields to pass
            if ($dependancy_require == 'all') {
                // The field will be visible if no conditions failed
                $field_visible = count($conditions_failed) == 0 ? true : false; 
            } // Otherwise if we only require some conditions to pass 
            elseif ($dependancy_require == 'any') {
                // The field will be visible if at least one conditions passed
                $field_visible = count($conditions_passed) == 0 ? true : false;
            }
        } // Otherwise if the field is to be hidden on passing conditions 
        elseif ($dependancy_outcome == 'hide') {
            // If we require all fields to pass
            if ($dependancy_require == 'all') {
                // The field will not be visible if no conditions failed
                $field_visible = count($conditions_failed) == 0 ? false : true; 
            } // Otherwise if we only require some conditions to pass 
            elseif ($dependancy_require == 'any') {
                // The field will not be visible if at least one conditions passed
                $field_visible = count($conditions_passed) == 0 ? false : true;
            }
        }
        // If the field is not going to visible
        if (!$field_visible) {
            // Set the field's conditions result
            $field_instance->is_hidden = true;
        }// Otherwise if the field is visible
        else {
            // Set the field's conditions result
            $field_instance->is_hidden = false;
        }
	}
    
    public function Check_Validation() {
        // Loop through each built field
		foreach ($this->instance_fields as $k => $field_instance) {
            // Check the field instance
            $this->_Check_Field_Instance($field_instance);
        }
		// Return for chaining
		return $this;
    }
}