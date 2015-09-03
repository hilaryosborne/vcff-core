<?php

class VCFF_Container_Item extends VCFF_Item {

    public $form_instance;
    
    public $machine_code;

    public $container_type;

    public $fields = array();
    
    public $supports = array();
    
    public $attributes;
    
    public $context;
    
    public $children;
    
    public $el;

    public $el_children;

    public $is_hidden = false;

    public $is_valid = true;

    public function Get_Machine_Code() {
        // Return the type value
        return $this->machine_code;
    }

    public function Get_Label() {

        return $this->attributes['label'];
    }

    public function Is_Hidden() {

        return $this->is_hidden;
    }

    public function Is_Visible() {

        return $this->is_hidden ? false : true;
    }

    public function Is_Valid() {

        return $this->is_valid;
    }
    
	public function Add_Field($field_instance) {
		
		$machine_code = $field_instance->machine_code;
		
        if (!$machine_code) { return $this; }
        
		$field_instance->container_instance = $this;
		
		$this->fields[$machine_code] = $field_instance;
		
        return $this;
	}
    
    public function Add_Support($support_instance) {
		
		$machine_code = $support_instance->machine_code;
		
        if (!$machine_code) { return $this; }
        
		$support_instance->container_instance = $this;
		
		$this->supports[$machine_code] = $support_instance;
        
        return $this;
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
    
    public function Do_Validation() {
        // If there are no validation rules
        if ($this->Is_Hidden()) { $this->is_valid = true; return; } 
		// Check any fields
		$this->_Check_Fields();
        // Check any attached supports
        $this->_Check_Supports();
	}
    
    protected function _Check_Fields() {
        // Retrieve the form's fields
        $container_fields = $this->fields;
        // If there are no form fields
		if (!$container_fields || !is_array($container_fields)) { return; }
        // Set the invalid number
        $invalid = 0;
        // Loop through each containers
		foreach ($container_fields as $machine_code => $field_instance) {
            // If the field is valid, move on
            if ($field_instance->Is_Valid()) { continue; }
            // Inc up the invalid field
            $invalid++;
        }
        // If there are no invalid fields
        if ($invalid == 0) { return; }
        // Set the form valid flag to false
        $this->is_valid = false;
    }
    
    protected function _Check_Supports() {
        // Retrieve the form's fields
        $container_supports = $this->supports;
        // If there are no form fields
		if (!$container_supports || !is_array($container_supports)) { return; }
        // Set the invalid number
        $invalid = 0;
        // Loop through each containers
		foreach ($container_supports as $machine_code => $support_instance) {
            // If the field is valid, move on
            if ($support_instance->Is_Valid()) { continue; }
            // Inc up the invalid field
            $invalid++;
        }
        // If there are no invalid fields
        if ($invalid == 0) { return; }
        // Set the form valid flag to false
        $this->is_valid = false;
    }
    
    public function Get_TEXT_Value() {
        // If the container is hidden, return out
        if ($this->Is_Hidden()) { return; }
        // The text string
        $text = "# ".strtoupper($this->Get_Label())."\n";
        // Add a line
        $text .= "----------------------------------"."\n\r";
        // Retrieve the form fields
        $container_fields = $this->fields;
        // If there are no form fields
        if (!$container_fields || !is_array($container_fields) || count($container_fields) == 0) { return ''; }
        // Loop through each of the found curly tags
        foreach ($container_fields as $machine_code => $field_instance) {
            // If the field is hidden
            if ($field_instance->Is_Hidden()) { continue; }
            // If the field is hidden
            if (!$field_instance->Get_Value()) { continue; }
            // Build the field html
            $text .= $field_instance->Get_TEXT_Value()."\n\r";
        }
        // Add a line
        $text .= "----------------------------------"."\n\r";
        // Return the text
        return $text;
    }
    
    public function Get_HTML_Value() {
        // If the container is hidden, return out
        if ($this->Is_Hidden()) { return; }
        // The text string
        $html = '<div class="container">';
        // Start the html container
        $html .= '  <div class="container-title">'.$this->Get_Label().'</div>';
        // Start the html container
        $html .= '  <div class="container-fields">';
        // Retrieve the form fields
        $container_fields = $this->fields;
        // If there are no form fields
        if (!$container_fields || !is_array($container_fields) || count($container_fields) == 0) { return ''; }
        // Loop through each of the found curly tags
        foreach ($container_fields as $machine_code => $field_instance) {
            // If the field is hidden
            if ($field_instance->Is_Hidden()) { continue; }
            // If the field is hidden
            if (!$field_instance->Get_Value()) { continue; }
            // Build the field html
            $html .= '<div>'.$field_instance->Get_HTML_Value().'</div>';
        }
        // End the HTML container
        $html .= '  </div>';
        // End the HTML container
        $html .= '</div>';
        // Return the HTML
        return $html;
    }
}