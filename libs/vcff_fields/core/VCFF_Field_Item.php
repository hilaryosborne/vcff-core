<?php

class VCFF_Field_Item extends VCFF_Item {

    /**
    * MACHINE CODE
    * Name of the field within the actual form
    */
    public $machine_code;

    /**
    * DATA POSTED
    * Data posted via a form
    */
    public $posted_value;

    /**
    * ATTRIBUTES
    * Attributes for this field instance
    */
    public $attributes;

    /**
     * THE FORM INSTANCE
     */
    public $form_instance;

    public $ajax;

    /**
    * HANDLER CLASS
    * The class which handles vc integration
    */
    public $context;

    public $is_hidden = false;
    
    public $is_valid = true;

    /**
    * VALIDATION
    * The class which handles vc integration
    */
    public $result_validation;

    /**
    * VALIDATION
    * The class which handles vc integration
    */
    public $result_conditions;
    
    public $allow_conditions = true;
    
    /**
     * ALERTS
     */
    public $alerts;
	
	public $container_instance;

    /**
         * CONSTRUCT (Required)
         * Initialises on instancing of this field class
         */
    public function __construct() {

    }

    public function Get_Type() {
        // Retrieve the field context
        $context = $this->context;
        // Return the type value
        return $context['type'];
    }
    
    public function Get_Machine_Code() {
        // Retrieve the field context
        $attributes = $this->attributes;
        // Return the type value
        return $attributes['machine_code'];
    }
    
    public function Get_Label() {
        // Retrieve the field context
        $attributes = $this->attributes;
        // Return the type value
        return $attributes['field_label'];
    }
    
    public function Get_View_Label() {
        // Retrieve the field context
        $attributes = $this->attributes;
        // Return the type value
        return $attributes['view_label'];
    }

    public function Get_Allowed_Conditions() {
        // Retrieve the field context
        $context = $this->context;
        // If there are no allowed conditions
        if (!isset($context['params'])) { return; }
        // If there are no allowed conditions
        if (!isset($context['params']['allowed_conditions'])) { return; }
        // If there are no allowed conditions
        if (!is_array($context['params']['allowed_conditions'])) { return; }
        // Return the type value
        return $context['params']['allowed_conditions'];
    }
    
    public function Check_Condition($condition,$value) {
        // Create the checking method name
        $method = 'Check_Rule_'.$condition;
        // Get the list of allowed conditions
        $allowed_conditions = $this->Get_Allowed_Conditions();
        // If the condition does not exist in the list of allowed conditions
        if (!isset($allowed_conditions[$condition])) { return true; }
        // Check the method exists
        if (!method_exists($this,$method)) { return true; }
        // Call the checking method
        $result = call_user_func_array(array($this, $method), array($value));
        // Increment the correct variable
        return $result ? true : false;
    }
    
    public function Allows_Conditions() {
        
        return $this->Get_Allowed_Conditions() ? true : false;
    }

    /**
         * IS REQUIRED
         * Check if a field is required
         */
    public function Is_Required() {
        // If there are no validation rules
        if (!$this->attributes['validation']) { return false; }
        // Extract the validation rules
        $validation = json_decode(base64_decode($this->attributes['validation'])); 
        // If there are no validation rules
        if (!is_array($validation)) { return false; }
        // loop through each of the validation rules
        foreach ($validation as $k => $_rule) {
            // If this rule is a required rules
            if ($_rule->rule == 'required') { return true; }
        }
        // Otherwise return false
        return false;
    }
    
    public function Is_Valid() {
        
        return $this->is_valid;
    }
    
    public function Is_Hidden() { 
        // If the field is attached to a container
        if ($this->container_instance && is_object($this->container_instance)) {
            // Retrieve the container object
            $field_container = $this->container_instance;
            // Return the hidden value of the container
            if ($field_container->Is_Hidden()) { return true; }
        }
        // Return the hidden flag
        return $this->is_hidden ? true : false;
    }
    
    public function Is_Visible() { 
        // If the field is attached to a container
        if ($this->container_instance && is_object($this->container_instance)) {
            // Retrieve the container object
            $field_container = $this->container_instance;
            // Return the hidden value of the container
            if ($field_container->Is_Hidden()) { return false; }
        }
        // Return the hidden flag
        return $this->is_hidden ? false : true;
    }
    
    public function Has_Dependents() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
        // Retrieve the form's fields
        $form_fields = $form_instance->fields;
        // Loop through each form fields
		foreach ($form_fields as $_machine_code => $_field_instance) {
            // If a list of conditions were returned
			if (!isset($_field_instance->attributes['conditions'])) { continue; }
			// Decode the field's conditions
			$field_conditions = json_decode(base64_decode($_field_instance->attributes['conditions'])); 
			// If a list of conditions were returned
			if (!isset($field_conditions->conditions) || !is_array($field_conditions->conditions)) { continue; }
			// Loop through each condition
			foreach($field_conditions->conditions as $k => $_condition){ 
				// Retrieve the condition's settings
				$check_field = $_condition->check_field;
				// If the field names match
				if ($check_field != $this->machine_code) { continue; }
                // Return true
                return true;
			}
        }
        // Retrieve the form's fields
        $form_containers = $form_instance->containers; 
        // If there is containers
        if ($form_containers && is_array($form_containers)) {
            // Loop through each form fields
            foreach ($form_containers as $_container_name => $container_instance) {
                // If a list of conditions were returned
                if (!isset($container_instance->attributes['conditions'])) { continue; }
                // Decode the field's conditions
                $container_conditions = json_decode(base64_decode($container_instance->attributes['conditions']));
                // If a list of conditions were returned
                if (!$container_conditions || !is_array($container_conditions->conditions)) { continue; }
                // Loop through each condition
                foreach($container_conditions->conditions as $k => $_condition){
                    // Retrieve the condition's settings
                    $check_field = $_condition->check_field;
                    // If the field names match
                    if ($check_field != $this->machine_code) { continue; }

                    return true;
                }
            }
        }
        // Return false
        return false;
    }
    
    public function On_Sanitize() {
    
        $posted_value = $this->posted_value;
        
        $posted_value = $this->_Recursive_Sanitize($posted_value);
    
        $this->posted_value = $posted_value;
    
    }
    
    protected function _Recursive_Sanitize($value) {
        // Create a new gump instance
        $gump = new GUMP();
        // If the value is an array
        if (is_array($value)) {
            // The var to store cleaned xss values
            $xss_values = array();
            // Loop through each value
            foreach ($value as $k => $_value) {
                // If the value is an array
                if (is_array($_value)) {
                    // Populate with the sanitized array
                    $xss_values[$k] = $this->_Recursive_Sanitize($_value);
                    // Continue on
                    continue;
                }
                // Run the data through the gump xss clean function
                $xss_cleaned = $gump->xss_clean(array($_value));
                // Store the cleaned value
                $xss_values[$k] = $xss_cleaned[0];
            }
            // Return the the cleaned values            
            return $xss_values;
        } // Otherwise if this is just a string 
        else {
            // Run the data through the gump xss clean function
            $xss_cleaned = $gump->xss_clean(array($value));
            // Return the cleaned data
            return $xss_cleaned[0];
        }
    }
    
    
    public function Get_Curly_Tags() {
        
        return array();
    }
    
    public function Get_AJAX_Data() {
        
        return $this->ajax;
    }
    
    public function Get_Value() {

        return $this->posted_value;
    }
    
    public function Get_RAW_Value() {
    
        if (is_array($this->posted_value)) { return implode(',',$this->posted_value); }
    
        return $this->posted_value;
    }
    
    public function Get_TEXT_Value() {
    
        if (is_array($this->posted_value)) { return implode(',',$this->posted_value); }
    
        return $this->Get_Label().' : '.$this->posted_value;
    }
    
    public function Get_HTML_Value() {

        $html = '<div class="posted-field">';
        $html .= '<div class="field-label"><strong>'.$this->Get_Label().'</strong></div>';
        $html .= '<div class="field-value">'.is_array($this->posted_value) ? json_encode($this->posted_value) : $this->posted_value.'</div>';
        $html .= '</div>';
        
        return $html;
    }

    public function Get_Stringified_Value() {
    
        if (is_array($this->posted_value)) { return json_encode($this->posted_value); }
    
        return $this->posted_value;
    }

    /**
         * CONDITIONAL FUNCTIONS
         * 
         */        
    public function Check_Rule_IS($against) {

        return $this->posted_value == $against ? true : false;
    }

    public function Check_Rule_IS_NOT($against) {

        return $this->posted_value != $against ? true : false;
    }

    public function Check_Rule_GREATER_THAN($against) {

        return $this->posted_value > $against ? true : false;
    }

    public function Check_Rule_LESS_THAN($against) {

        return $this->posted_value < $against ? true : false;
    }

    public function Check_Rule_CONTAINS($against) {

        return strpos($this->posted_value, $against) !== false ? true : false;
    }

    public function Check_Rule_STARTS_WITH($against) {

        return strpos($this->posted_value, $against) === 0 ? true : false;
    }

    public function Check_Rule_ENDS_WITH($against) {

        return strpos($this->posted_value, $against) === (strlen($this->posted_value) - strlen($against)) ? true : false;
    }
    
    public function Check_Conditions() { 
		// Retrieve the form instance
		$form_instance = $this->form_instance;
        // If the field already has a condition result
        // This means the field has already been checked
        if (isset($this->result_conditions) && is_array($this->result_conditions)) { return false; }
        // Retrieve the form's fields
        $form_fields = $form_instance->fields;
		// If no conditions are yet set
		if (!isset($this->attributes['conditions'])) { 
			// Set the field's conditions result
			$this->result_conditions = array(
				'result' => 'visible',
			); return;
		}
        // Decode the field's conditions
        $field_conditions = json_decode(base64_decode($this->attributes['conditions']),true); 
        // If a list of conditions were returned
		if ($field_conditions && is_array($field_conditions['conditions'])) {}
		// Various condition vars
		$condition_visibility = $field_conditions['visibility'];
		$condition_target = $field_conditions['target'];
		// The incremental vars
		$conditions_failed = array();
		$conditions_passed = array();
		// Loop through each condition
		foreach($field_conditions['conditions'] as $k => $condition_item){
			// Retrieve the condition's settings
			$check_field = $condition_item['check_field'];
			$check_condition = $condition_item['check_condition'];
			$check_value = $condition_item['check_value'];
            // If ther condition item is not valid
            if (!$check_field || !$check_condition || !$check_value) { continue; } 
			// If the required field is present
			if ($form_fields[$check_field]) { 
				// Retrieve the field instance
				$field_instance = $form_fields[$check_field];
				// Create the checking method name
				$field_instance_check_method = 'Check_Rule_'.strtoupper($check_condition);
				// Check the method exists
				if (!method_exists($field_instance,$field_instance_check_method)) { continue; }
				// Call the checking method
				$check_result = call_user_func_array(array($field_instance, $field_instance_check_method), array($check_value));
				// Increment the correct variable
				if ($check_result) { $conditions_passed[$k] = $condition_item; } else { $conditions_failed[$k] = $condition_item; }
			}
		}
        
        if (count($conditions_failed) == 0 && count($conditions_passed) == 0) {
            // Set the field hidden flag
            $this->is_hidden = false;
			// Set the field's conditions result
			$this->result_conditions = array(
				'result' => 'visible',
			); return;
        }
		// If the field is to be show on passing conditions
		if ($condition_visibility == 'show') { 
			// If we require all fields to pass
			if ($condition_target == 'all') {
				// The field will be visible if no conditions failed
				$field_visible = count($conditions_failed) == 0 ? true : false; 
			} // Otherwise if we only require some conditions to pass 
			elseif ($condition_target == 'any') { 
				// The field will be visible if at least one conditions passed
				$field_visible = count($conditions_passed) != 0 ? true : false;
			}
		} // Otherwise if the field is to be hidden on passing conditions 
		elseif ($condition_visibility == 'hide') {
			// If we require all fields to pass
			if ($condition_target == 'all') {
				// The field will not be visible if no conditions failed
				$field_visible = count($conditions_failed) == 0 ? false : true; 
			} // Otherwise if we only require some conditions to pass 
			elseif ($condition_target == 'any') {
				// The field will not be visible if at least one conditions passed
				$field_visible = count($conditions_passed) != 0 ? false : true;
			}
		}
		// If the field is not going to visible
		if (!$field_visible) { 
            // Set the field hidden flag
            $this->is_hidden = true;
			// Set the field's conditions result
			$this->result_conditions = array(
				'result' => 'hidden',
				'triggered_by' => 'fields',
				'conditions_passed' => $conditions_passed,
				'conditions_failed' => $conditions_failed,
			);
		}// Otherwise if the field is visible
		else { 
            // Set the field hidden flag
            $this->is_hidden = false;
			// Set the field's conditions result
			$this->result_conditions = array(
				'result' => 'visible',
				'triggered_by' => 'fields',
				'conditions_passed' => $conditions_passed,
				'conditions_failed' => $conditions_failed,
			);
		}
	}
    
    public function Do_Field_Filter() {
        // If this field requires filtering
        if (!$this->attributes['filter']) { return; }
        // If this field requires filtering
        if ($this->attributes['filter'] == '') { return; }
        // If there are no validation rules
        if ($this->Is_Hidden()) { return; }
        // Retrieve the validation rules
        $raw_filters = $this->attributes['filter'];
        // Decode the validation rules
        $filters = json_decode(base64_decode($raw_filters),true);
        // Check the gump validation
        $this->_Do_GUMP_Filters();
        // Check codeback validation
        $this->_Do_CALLBACK_Filters();
    }
    
    protected function _Do_CALLBACK_Filters() {
        // Retrieve the field name
        $machine_code = $this->Get_Machine_Code(); 
        // Retrieve the field value
        $field_value = $this->Get_Value();
        // Retrieve the field label
        $field_label = $this->Get_Label();
        // Retrieve the validation rules
        $raw_filter = $this->attributes['filter'];
        // Decode the validation rules
        $filter = json_decode(base64_decode($raw_filter),true);
        // If there are validation rules
        if (!$filter || !is_array($filter)) { return false; }
        // Return the rule list
        $filter_rule_codes = VCFF_PARAM_FILTER::Get_Rules($this->Get_Type());
        // The empty array
        $filter_rule_list = array();
        // Loop through each param
        foreach ($filter as $_k => $_param_data) {
            // If the field is already invalid
            if (!$this->is_valid) { continue; }
            // Retrieve the rule code
            $rule_code = $_param_data['rule'];
            // Retrieve the rule value
            $rule_value = $_param_data['value'];
            // If no rule data then continue
            if (!isset($filter_rule_codes[$rule_code])) { continue; }
            // Retrieve the rule data
            $rule_data = $filter_rule_codes[$rule_code];
            // If if this is not a gump rule, continue on
            if (!isset($rule_data['callback'])) { continue; }
            // Retrieve the callback
            $filter_callback = $rule_data['callback'];
            // If there is no callback, continue on
            if (!method_exists($this,$filter_callback)) { continue; }
            // Retrieve the callback
            $this->$filter_callback();
        } 
    }
    
    protected function _Do_GUMP_Filters() {
        // Retrieve the field name
        $machine_code = $this->Get_Machine_Code(); 
        // Retrieve the field value
        $field_value = $this->Get_Value();
        // Retrieve the field label
        $field_label = $this->Get_Label();
        // Retrieve the validation rules
        $raw_filter = $this->attributes['filter'];
        // Decode the validation rules
        $filter = json_decode(base64_decode($raw_filter),true);
        // If there are validation rules
        if (!$filter || !is_array($filter)) { return false; }
        // If there are validation rules
        if ($filter && is_array($filter)) {
            // Return the rule list
            $filter_rule_codes = VCFF_PARAM_FILTER::Get_Rules($this->Get_Type());
            // The empty array
            $filter_rule_list = array();
            // Loop through each param
            foreach ($filter as $_k => $_param_data) {
                // Retrieve the rule code
                $rule_code = $_param_data['rule'];
                // If no rule data then continue
                if (!isset($filter_rule_codes[$rule_code])) { continue; }
                // Retrieve the rule data
                $rule_data = $filter_rule_codes[$rule_code];
                // If if this is not a gump rule, continue on
                if (!isset($rule_data['is_gump']) || !$rule_data['is_gump']) { continue; }
                // Construct each rule string
                $filter_rule_list[] = $rule_value ;
            } 
            // Return the gump string
            $filter_rules = implode('|',$filter_rule_list);
        }
        // Add the advanced filter (if provided)
        $filter_rules = $this->attributes['use_adv_filter'] == 'yes' && $this->attributes['adv_filter'] != '' ? $filter_rules.'|'.$this->attributes['adv_filter'] : $filter_rules ;
        // Retrieve the field value
        $field_value = $this->Get_Value(); 
        // Repopulate the posted value
        $this->posted_value = $this->_Field_Filter_Recur($filter_rules,$field_value);
    }
    
    
    protected function _Field_Filter_Recur($gump_string,$field_value) {
        // Retrieve the field name
        $machine_code = $this->Get_Machine_Code();
        // If the field value is an array
        if (is_array($field_value)) {
            // The array to store the filtered results
            $filtered_valued = array();
            // Loop through each field value
            foreach ($field_value as $k => $v) {
                // Run through the filter
                $filtered_valued[$k] = $this->_Field_Filter_Recur($gump_string,$v);
            }
            // Return the filtered array
            return $filtered_valued;
        }
        // Create a new gump validation class
        $gump = new GUMP();
        // Set the fieldname inside of gump
        $gump->set_field_name($machine_code,$machine_code);
        // Set the filter rules
        $gump->filter_rules(array($machine_code => $gump_string));
        // Retrieve the filtered data
        $filtered_data = $gump->run(array($machine_code => $field_value));
        // Return the filtered data
        return $filtered_data[$machine_code];
    }
    
    /**
     * VALIDATION FUNCTIONS
     * 
     */
     
    public function Get_Validation_Rule($rule) {
        // Retrieve the validation rules
        $raw_validation = $this->attributes['validation'];
        // Decode the validation rules
        $validation = json_decode(base64_decode($raw_validation),true);
        // If there are validation rules
        if (!$validation || !is_array($validation)) { return false; }
        // Return the rule list
        $val_rule_codes = VCFF_PARAM_VAL::Get_Rules($this->Get_Type());
        // Loop through each param
        foreach ($validation as $_k => $_param_data) {
            // If no rule data then continue
            if (!isset($val_rule_codes[$_param_data['rule']])) { continue; }  
            // Retrieve the rule code
            $rule_code = $_param_data['rule'];
            // If this is not the code we are looking for
            if ($rule_code != $rule) { continue; }
            // Return all information about rule
            return array(
                'param' => array(
                    'code' => $_param_data['rule'],
                    'value' => $_param_data['value'],
                    'data' => $_param_data
                ),
                'list' => array(
                    'rule' => $val_rule_codes[$rule_code],
                    'all' => $val_rule_codes
                )
            );
        }
        // Return false
        return false;
    }
    
    public function Check_Field_Validation() {
        // If there are no validation rules
        if ($this->Is_Hidden()) { $this->is_valid = true; return; } 
        // If this field requires filtering
        if (!$this->attributes['validation']) { $this->is_valid = true; return; }
        // If this field requires filtering
        if ($this->attributes['validation'] == '') { $this->is_valid = true; return; }
        // If this is invalid, return out
        if (!$this->is_valid) { return false; }
        // Check the gump validation
        $this->_Check_GUMP_Validation();
        // If this is invalid, return out
        if (!$this->is_valid) { return false; }
        // Check codeback validation
        $this->_Check_CALLBACK_Validation();
        // If this is invalid, return out
        if (!$this->is_valid) { return false; }
        // Update the post value with the sanitized version
        $this->result_validation = array(
            'result' => 'passed'
        );
    }

    protected function _Check_CALLBACK_Validation() {
        // Retrieve the field name
        $machine_code = $this->Get_Machine_Code(); 
        // Retrieve the field value
        $field_value = $this->Get_Value();
        // Retrieve the field label
        $field_label = $this->Get_Label();
        // Retrieve the validation rules
        $raw_validation = $this->attributes['validation'];
        // Decode the validation rules
        $validation = json_decode(base64_decode($raw_validation),true);
        // If there are validation rules
        if (!$validation || !is_array($validation)) { return false; }
        // Return the rule list
        $val_rule_codes = VCFF_PARAM_VAL::Get_Rules($this->Get_Type());
        // The empty array
        $val_rule_list = array();
        // Loop through each param
        foreach ($validation as $_k => $_param_data) {
            // If the field is already invalid
            if (!$this->is_valid) { continue; }
            // Retrieve the rule code
            $rule_code = $_param_data['rule'];
            // If no rule data then continue
            if (!isset($val_rule_codes[$rule_code])) { continue; }
            // Retrieve the rule data
            $rule_data = $val_rule_codes[$rule_code];
            // If if this is not a gump rule, continue on
            if (!isset($rule_data['callback'])) { continue; }
            // Retrieve the callback
            $val_callback = $rule_data['callback'];
            // If there is no callback, continue on
            if (!method_exists($this,$val_callback)) { continue; }
            // Retrieve the callback
            $this->$val_callback();
        } 
    }
    
    protected function _Check_GUMP_Validation() {
        // Retrieve the field name
        $machine_code = $this->Get_Machine_Code(); 
        // Retrieve the field value
        $field_value = $this->Get_Value();
        // Retrieve the field label
        $field_label = $this->Get_Label();
        // Create a new gump validation class
        $gump = new GUMP();
        // Set the fieldname inside of gump
        $gump->set_field_name($machine_code,$machine_code); 
        // Populate the gump validation class
        $validation_data[$machine_code] = $field_value;
        // Retrieve the validation rules
        $raw_validation = $this->attributes['validation'];
        // Decode the validation rules
        $validation = json_decode(base64_decode($raw_validation),true);
        // If there are validation rules
        if ($validation && is_array($validation)) {
            // Return the rule list
            $val_rule_codes = VCFF_PARAM_VAL::Get_Rules($this->Get_Type());
            // The empty array
            $val_rule_list = array();
            // Loop through each param
            foreach ($validation as $_k => $_param_data) {
                // Retrieve the rule code
                $rule_code = $_param_data['rule'];
                // Retrieve the rule value
                $rule_value = $_param_data['value'];
                // If no rule data then continue
                if (!isset($val_rule_codes[$rule_code])) { continue; }
                // Retrieve the rule data
                $rule_data = $val_rule_codes[$rule_code];
                // If if this is not a gump rule, continue on
                if (!isset($rule_data['is_gump']) || !$rule_data['is_gump']) { continue; }
                // Construct each rule string
                $val_rule_list[] = $rule_value && $rule_value != '' ? $rule_code.','.$rule_value : $rule_code ;
            } 
            // Return the gump string
            $validation_rules = implode('|',$val_rule_list);
        }
        // Set both into the gump string
        $gump_string = $validation_rules; 
        // Add the advanced filter (if provided)
        $gump_string = $this->attributes['use_adv_validation'] == 'yes' && $this->attributes['adv_validation'] != '' ? $gump_string.'|'.$this->attributes['adv_validation'] : $gump_string ;
        // If there is no gump string
        if (!$gump_string || $gump_string == '') { $this->is_valid = true; return; }
        // Populate the validation rules
        $gump->validation_rules(array($machine_code => $gump_string));
        // Run the gump validation
        $validated = $gump->run($validation_data);
        // If the field failed to validate
        if (!$validated) {
            // Retrieve the gump errors
            $gump_errors = $gump->get_errors_array();
            // Set the validation flag to false
            $this->is_valid = false;
            // Create the error string
            $error_string = str_replace($machine_code,$field_label,$gump_errors[$machine_code]);
            // Update the post value with the sanitized version
            $this->result_validation = array(
                'result' => 'failed',
                'message' => $error_string,
            );
            // Add a danger alert for this field
            $this->Add_Alert($error_string,'danger');
        }
    }
}