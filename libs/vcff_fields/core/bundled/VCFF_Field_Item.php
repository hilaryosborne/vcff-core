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
    
    public function Get_Contents() {
        
        $el = $this->el;
        
        if (!is_array($el->children) || !isset($el->children[0])) { return; }
        
        return $el->children[0]->string;
    }
    
    public function Check_Conditions() {
        // Retrieve the field's attributes
        $_attributes = $this->attributes;
        // If there are no conditions set
        if (!isset($_attributes['conditions'])) { return; }
        // Retrieve the conditions data
        $_conditions = json_decode(base64_decode($_attributes['conditions']),true);
        // If there are no rules
        if (!isset($_conditions['rules']) || count($_conditions['rules']) == 0) { return; }
        // Create a new conditions item
        $conditions_item = new VCFF_Conditions_Item($this);
        // Check the conditions item
        $conditions_item
            ->Set_Form_Instance($this->form_instance)
            ->Set_Rules($_conditions['rules'])
            ->Prepare()
            ->Check_Rules(); 
        // Retrieve the result settings
        $_settings_result = $_conditions['result'];
        // Retrieve the matching settings
        $_settings_match = $_conditions['match'];
        // Retrieve the number of triggered rules
        $_triggered = count($conditions_item->Get_Triggered());
        // Retrieve the number of non triggered rules
        $_non_triggered = count($conditions_item->Get_Non_Triggered());
        // If the container is to be show on passing conditions
        if ($_settings_result == 'show') {
            // If we require all fields to pass
            if ($_settings_match == 'all') {
                // The container will be visible if no conditions failed
                $this->is_hidden = $_non_triggered == 0 ? false : true; 
            } // Otherwise if we only require some conditions to pass 
            elseif ($_settings_match == 'any') {
                // The container will be visible if at least one conditions passed
                $this->is_hidden = $_triggered != 0 ? false : true;
            }
        } // Otherwise if the container is to be hidden on passing conditions 
        elseif ($_settings_result == 'hide') {
            // If we require all fields to pass
            if ($_settings_match == 'all') {
                // The container will not be visible if no conditions failed
                $this->is_hidden = $_non_triggered == 0 ? true : false; 
            } // Otherwise if we only require some conditions to pass 
            elseif ($_settings_match == 'any') {
                // The container will not be visible if at least one conditions passed
                $this->is_hidden = $_triggered != 0 ? true : false;
            }
        }
    }
    
    public function Has_Dependents() {
        // Create a new conditions item
        $conditions_item = new VCFF_Conditions_Item($this);
        // Check the conditions item
        $deps = $conditions_item
            ->Set_Form_Instance($this->form_instance)
            ->Prepare()
            ->Check_Dependents()
            ->Get_Dependents();
        // Return false
        return count($deps) > 0 ? true : false ;
    }

    /**
         * IS REQUIRED
         * Check if a field is required
         */
    public function Is_Required() {
        // If there are no validation rules
        if (!$this->attributes['validation']) { return false; }
        // Extract the validation rules
        $validation = json_decode(base64_decode($this->attributes['validation']),true);
        // If there are no validation rules
        if (!is_array($validation)) { return false; }
        // loop through each of the validation rules
        foreach ($validation as $k => $_rule) {
            // If this rule is a required rules
            if ($_rule['rule'] == 'REQUIRED') { return true; }
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
    
    public function Get_TEXT_Value($use_label=true) {
    
        if (is_array($this->posted_value)) { return implode(',',$this->posted_value); }
    
        return $use_label ? $this->Get_Label().' : '.$this->posted_value : $this->posted_value;
    }
    
    public function Get_HTML_Value($use_label=true) {
        
        $field_value = is_array($this->posted_value) ? json_encode($this->posted_value) : $this->posted_value ;
        
        $html = '<div class="posted-field">';
        if ($use_label) { $html .= '<div class="field-label"><strong>'.$this->Get_Label().'</strong></div>'; }
        $html .= '<div class="field-value">'.$field_value.'</div>';
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
    public function IS($against) {

        return $this->posted_value == $against ? true : false;
    }

    public function IS_NOT($against) {

        return $this->posted_value != $against ? true : false;
    }
    
    public function IS_EMPTY() {

        return !$this->posted_value ? true : false;
    }

    public function GREATER_THAN($against) {

        return $this->posted_value > $against ? true : false;
    }

    public function LESS_THAN($against) {

        return $this->posted_value < $against ? true : false;
    }

    public function CONTAINS($against) {

        return strpos($this->posted_value, $against) !== false ? true : false;
    }

    public function STARTS_WITH($against) {

        return strpos($this->posted_value, $against) === 0 ? true : false;
    }

    public function ENDS_WITH($against) {

        return strpos($this->posted_value, $against) === (strlen($this->posted_value) - strlen($against)) ? true : false;
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
        $raw_filters_list = json_decode(base64_decode($raw_filters),true);
        // Store the selected filters
        $_filters = array();
        // If there are raw filters
        if ($raw_filters_list && is_array($raw_filters_list)) {
            // Loop through each raw filter
            foreach ($raw_filters_list as $k => $v) {
                // Populate the filter list
                $_filters[$v['rule']] = true;
            }
        }
        // Retrieve the field context
        $_context = $this->context;
        // If there are context filter logic
        if (isset($_context['filter_logic'])) {
            // Retrieve the filter logic
            $_filter_logic = $_context['filter_logic'];
            // Loop through each filter logic
            foreach ($_filter_logic as $k => $_filter) {
                // If this filter is not being used, move on
                if (!isset($_filters[$_filter['machine_code']])) { continue; }
                // If this is a callback filter
                if (!$_filter['gump_code']) {
                    // Retrieve the filter callback
                    $filter_callback = $_filter['callback'];
                    // If there is no callback, continue on
                    if (!method_exists($this,$filter_callback)) { continue; }
                    // Retrieve the callback
                    $this->$filter_callback();
                } // Otherwise if this is a gump filter
                else { $this->posted_value = $this->_Field_Filter_Recur($_filter['gump_code'],$this->posted_value); }
            }
        }
        // If the field is to use advanced filters
        if ($this->attributes['use_adv_filter'] == 'yes') {
            // Run the posted value through the advanced filters
            $this->posted_value = $this->_Field_Filter_Recur($this->attributes['adv_filter'],$this->posted_value);
        }
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
     
    public function Get_Validation() {
        
        $attributes = $this->attributes;
        
        $validation = $attributes['validation'] ? json_decode(base64_decode($attributes['validation']),true) : false;
        
        if (!$validation || !is_array($validation)) { return false; }
        
        $_rules = array();
        
        foreach ($validation as $k => $_rule) {
            
            $_rules[$_rule['rule']] = $_rule;
        }
        
        return $_rules;
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
        // Retrieve the validation rules
        $raw_validation = $this->attributes['validation'];
        // Decode the validation rules
        $raw_validation_list = json_decode(base64_decode($raw_validation),true);
        // Store the selected filters
        $_validation = array();
        // If there are raw filters
        if ($raw_validation_list && is_array($raw_validation_list)) {
            // Loop through each raw filter
            foreach ($raw_validation_list as $k => $v) {
                // Populate the filter list
                $_validation[$v['rule']] = isset($v['value']) ? $v['value'] : false;
            }
        }
        // Retrieve the field context
        $_context = $this->context;
        // If there are context filter logic
        if (isset($_context['validation_logic'])) {
            // Retrieve the filter logic
            $_validation_logic = $_context['validation_logic'];
            // Loop through each filter logic
            foreach ($_validation_logic as $k => $_validator) {
                // If this filter is not being used, move on
                if (!isset($_validation[$_validator['machine_code']])) { continue; }
                // If this is a callback filter
                if (!$_validator['gump_code']) {
                    // Retrieve the filter callback
                    $validator_callback = $_validator['callback'];
                    // If there is no callback, continue on
                    if (!method_exists($this,$validator_callback)) { continue; }
                    // Retrieve the callback
                    $this->$validator_callback($_validator);
                } // Otherwise if this is a gump filter
                else { 
                    // Create a new gump validation class
                    $gump = new GUMP();
                    // Set the fieldname inside of gump
                    $gump->set_field_name($this->machine_code,$this->machine_code);
                    // Populate the validation rules
                    $gump->validation_rules(array($this->machine_code => $_validator['gump_code']));
                    // Populate the gump validation class
                    $validation_data[$this->machine_code] = $this->posted_value;
                    // Run the gump validation
                    $validated = $gump->run($validation_data);
                    // If the field failed to validate
                    if (!$validated) { 
                        // Retrieve the gump errors
                        $gump_errors = $gump->get_errors_array();
                        // Set the validation flag to false
                        $this->is_valid = false;
                        // Create the error string
                        $error_string = str_replace($this->machine_code,$this->Get_Label(),$gump_errors[$this->machine_code]);
                        // Add a danger alert for this field
                        $this->Add_Alert($error_string,'danger');
                    }
                }
            }
        }
        // If the field is invalid at this point
        if (!$this->is_valid) { return false; }
        // If the field is to use advanced filters
        if ($this->attributes['use_adv_validation'] == 'yes') {
            // Create a new gump validation class
            $gump = new GUMP();
            // Set the fieldname inside of gump
            $gump->set_field_name($this->machine_code,$this->machine_code);
            // Populate the validation rules
            $gump->validation_rules(array($this->machine_code => $this->attributes['adv_validation']));
            // Populate the gump validation class
            $validation_data[$this->machine_code] = $this->posted_value;
            // Run the gump validation
            $validated = $gump->run($validation_data);
            // If the field failed to validate
            if (!$validated) {
                // Retrieve the gump errors
                $gump_errors = $gump->get_errors_array();
                // Set the validation flag to false
                $this->is_valid = false;
                // Create the error string
                $error_string = str_replace($this->machine_code,$this->Get_Label(),$gump_errors[$this->machine_code]);
                // Add a danger alert for this field
                $this->Add_Alert($error_string,'danger');
            }
        }
    }

}