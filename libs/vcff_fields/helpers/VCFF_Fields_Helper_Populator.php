<?php 

class VCFF_Fields_Helper_Populator {
	
	protected $form_instance;	
		
	protected $form_data;
	
	protected $error;
	
	public function Get_Error() {
		
		return $this->error;
	}
		
	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
	
	public function Set_Form_Data($form_data) {
		
		$this->form_data = $form_data; 
		
		return $this;
	}
	
	protected function _Get_Contexts() {
		// Retrieve the global vcff forms class
        $vcff_fields = vcff_get_library('vcff_fields');
        // Get the fields
        $field_contexts = $vcff_fields->contexts;
		// Return the field contexts
		return $field_contexts;
	}
	
    public function Populate() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form contents
		$form_contents = $form_instance->form_content;
		// Retrieve the field data
		$fields_data = vcff_parse_field_data($form_contents); 
		// If an error has been detected, return out
		if ($this->error) { return; } 
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Loop through each of the fields
		foreach ($fields_data as $k => $field_data) { 
			// Retrieve the field instance
			$field_instance = $this->_Get_Field_Instance($field_data); 
			// Add the field to the form instance
			$form_instance->Add_Field($field_instance);
		}
	}

	protected function _Get_Field_Instance($field_data) {
		// Retrieve the field name
		$machine_code = $field_data['attributes']['machine_code'];
		// Create the field item classname
		$field_classname = $field_data['context']['class_item'];
		// If no form instance was found
		if (!$machine_code) {
			// Populate with an error and return out
			$this->error = 'No field name could be found'; return;
		}
		// If no form instance was found
		if (!$field_classname) {
			// Populate with an error and return out
			$this->error = 'No field class item could be found'; return;
		}
		// Create a new item instance for this field
		$field_instance = new $field_classname();
		// Populate the field name
		$field_instance->machine_code = $machine_code;
        // Populate the field name
		$field_instance->field_type = $field_data['context']['type'];
		// Populate the handler object
		$field_instance->context = $field_data['context'];
		// Populate the field list
		$field_instance->attributes = $field_data['attributes'];
		// Populate the field list
		$field_instance->form_instance = $this->form_instance;
        // Populate the field list
		$field_instance->el = $field_data['el'];
		// Get the field value
        $this->_Get_Field_Value($field_instance);
        // If the field has a sanitize method
        if (method_exists($field_instance,'On_Sanitize')) { $field_instance->On_Sanitize(); }
        // If the field has a sanitize method
        if (method_exists($field_instance,'On_Create')) { $field_instance->On_Create(); }
        // Do any create actions
        $field_instance->Do_Action('create');
        // Do a wordpress hook
        do_action('vcff_field_create',$field_instance);
		// Return the generated field instance
		return $field_instance;
	}
    
    protected function _Get_Field_Value($field_instance) {
        // Retrieve the validation result
        do_action('vcff_pre_field_value', $field_instance);
        // If this field has a custom validation method
        if (method_exists($field_instance,'Pre_Value')) { $field_instance->Pre_Value(); }
        // Populate with any posted values
        $this->_Get_Posted_Field_Value($field_instance);
        // Populate with any dynamic values
        $this->_Get_Dynamic_Field_Value($field_instance);
        // Populate with any default value
        $this->_Get_Default_Field_Value($field_instance);
        // Do any create actions
        $field_instance->Do_Action('populate_value');
        // Retrieve the validation result
        do_action('vcff_post_field_value', $field_instance);
        // If this field has a custom validation method
        if (method_exists($field_instance,'Post_Value')) { $field_instance->Post_Value(); }
    }
	
    
    protected function _Get_Posted_Field_Value($field_instance) {
        // Retrieve the form data
		$form_data = $this->form_data;
        // If the field already has content
        if ($field_instance->posted_value) { return; }
        // Check if this field has a custom submitted data method
		if (!is_array($form_data) || !isset($form_data[$field_instance->machine_code])) { return; }
        // Run the data through the field specific processor or store raw data
        $field_instance->posted_value = $form_data[$field_instance->machine_code];
        // Do any create actions
        $field_instance->Do_Action('populate_value_posted');
    }
	
    protected function _Get_Dynamic_Field_Value($field_instance) {
        // If the field already has content
        if ($field_instance->posted_value) { return; }
        // Retrieve the field attributes
        $attributes = $field_instance->attributes;
        // If there are no dynamically populate rules
        if (!$attributes['dynamically_populate'] || $attributes['dynamically_populate'] == "") { return; } 
        // Decode the dynamic rules
        $dynamic_rules = json_decode(base64_decode($attributes['dynamically_populate']),true);
        // Apply filters for dynamic rules
        $dynamic_rules = apply_filters('vcff_fields_dynamic_fields', $dynamic_rules, $field_instance, $this);
        // If there are dynamic rules to apply
        if ($dynamic_rules && is_array($dynamic_rules)) {
            // Loop through each of the rules
            foreach ($dynamic_rules as $k => $rule) {
                // The request method to expect
                $rule_method = $rule['rule'];
                // The request key to look for
                $rule_key = $rule['value'];
                // If this rule is looking for a post request
                if (strtolower($rule_method) == 'post' && isset($_POST[$rule_key])) {
                    // Run the data through the field specific processor or store raw data
                    $field_instance->posted_value = $_POST[$rule_key];
                } // If this rule is looking for a get request
                elseif (strtolower($rule_method) == 'get' && isset($_GET[$rule_key])) {
                    // Run the data through the field specific processor or store raw data
                    $field_instance->posted_value = $_GET[$rule_key]; 
                } // If this rule is looking for a general request
                elseif (strtolower($rule_method) == 'request' && isset($_GET[$rule_key])) {
                    // Run the data through the field specific processor or store raw data
                    $field_instance->posted_value = $_REQUEST[$rule_key];
                } 
                // Do any create actions
                $field_instance->Do_Action('populate_value_dynamic');
            }
        }
        // Apply the hook
        do_action('vcff_fields_dynamic_fields',$field_instance, $this);
    }
    
    protected function _Get_Default_Field_Value($field_instance) {
        // If the field already has content
        if ($field_instance->posted_value) { return; }
        // Retrieve the field attributes
        $attributes = $field_instance->attributes;
        // If there is no default value
        if (!isset($attributes['default_value']) || !$attributes['default_value']) { return; }
        // Run the data through the field specific processor or store raw data
        $field_instance->posted_value = $attributes['default_value'];
        // Do any create actions
        $field_instance->Do_Action('populate_value_default');
    }
}