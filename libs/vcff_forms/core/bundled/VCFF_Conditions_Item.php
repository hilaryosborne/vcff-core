<?php

class VCFF_Conditions_Item extends VCFF_Item {

    public $form_instance;
    
    public $els;
    
    public $el_instance;
    
    public $rules = array();
    
    public $_dependents = array();
    
    public $_triggered = array();
    
    public $_nontriggered = array();
    
    public $_is_hidden = false;
    
    public function __construct($el_instance) {
        
        $this->el_instance = $el_instance;
        
        return $this;
    }
    
    public function Set_Form_Instance($form_instance) {
        
        $this->form_instance = $form_instance;
        
        return $this;
    }
    
    public function Set_Rules($rules) {
        
        $this->rules = $rules;
        
        return $this;
    }
    
    public function Prepare() {
        $this->_Prepare_Elements();
        $this->_Prepare_Form();
        $this->els = apply_filters('vcff_conditional_els', $this->els, $this);
        
        return $this;
    }
    
    protected function _Prepare_Elements() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's fields
        $form_els = $form_instance->Get_Elements(); 
		// If there are no form fields
		if (!$form_els || !is_array($form_els)) { return; }
		// Loop through each containers
		foreach ($form_els as $machine_code => $el_instance) {
            // If no context parameter
            if (!isset($el_instance->context)) { continue; }
            // Retrieve the context
            $context = $el_instance->context;
            // Retrieve the set conditional logic
            $_context_logic = isset($context['conditional_logic']) ? $context['conditional_logic'] : false ;
            // Create the field logic var
            $field_logic = is_array($_context_logic) ? $_context_logic : array();
            // Do any actions
            $field_logic = $el_instance->Apply_Filters('conditional_logic',$field_logic,array('_helper' => $this)); 
            // If logic rules were returned
            if (count($field_logic) > 0) { 
                // Build the els entry
                $this->els[$machine_code] = array(
                    'machine_code' => $machine_code,
                    'logic_rules' => $field_logic,
                    'el' => $el_instance
                );
            }
        }
    }
    
    protected function _Prepare_Form() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
        // Retrieve the context
        $context = $form_instance->context;
        // Retrieve the set conditional logic
        $_context_logic = isset($context['conditional_logic']) ? $context['conditional_logic'] : false ;
        // Create the field logic var
        $form_logic = is_array($_context_logic) ? $_context_logic : array();
        // Do any actions
        $form_logic = $form_instance->Apply_Filters('conditional_logic',$form_logic,array('_helper' => $this));
        // If logic rules were returned
        if (count($form_logic) > 0) {
            // Build the els entry
            $this->els[$form_instance->form_type] = array(
                'machine_code' => $form_instance->form_type,
                'logic_rules' => $form_logic,
                'el' => $form_instance
            );
        }
        // Do
        $form_instance->Do_Action('conditional_logic',array('_helper' => $this));
    }
    
    /**
    {"visibility":"show","target":"all","conditions":[{"check_field":"this_example_fld","check_condition":"IS","check_value":"ok"},{"check_field":"another_field","check_condition":"IS","check_value":"awesome"}]}
    **/
    
    public function Check_Rules() {
        // The list of rules
        $_rules = $this->rules;
        // If no rules were found
        if (!$_rules || !is_array($_rules) || count($_rules) == 0) { return false; }
        // The list of els
        $_els = $this->els;
        // If no rules were found
        if (!$_els || !is_array($_els) || count($_els) == 0) { return false; } 
        // Loop through each rule
        foreach ($_rules as $k => $_rule) {
            // If no el data then move on
            if (!isset($_els[$_rule['machine_code']])) { continue; } 
            // Retrieve the el data
            $el_data = $_els[$_rule['machine_code']];
            // If no logic rules, move on
            if (!$el_data['logic_rules'] || !is_array($el_data['logic_rules'])) { continue; }
            // Retrieve the logic rules
            $el_logic_rules = $el_data['logic_rules']; 
            // Var to store the found logic rule
            $el_logic_rule = false;
            // Loop through each el logic rule
            foreach ($el_logic_rules as $_k => $el_logic_item) {
                // If this is the rule we are looking for
                if ($el_logic_item['machine_code'] != $_rule['code']) { continue; }
                // Populate the el logic rule
                $el_logic_rule = $el_logic_item;
            } 
            // If no logic rule was found, move on
            if (!$el_logic_rule) { continue; } 
            // Retrieve the el instance
            $el_instance = $el_data['el'];
            // Check the method exists
            if (!method_exists($el_instance,$el_logic_rule['callback'])) { continue; } 
            // Call the checking method
            $el_check = call_user_func_array(array($el_instance, $el_logic_rule['callback']), array($_rule['value']));
            // Increment the correct variable
            if ($el_check) { $this->_triggered[] = $_rule; } else { $this->_nontriggered[] = $_rule; } 
        }
        
        return $this;
    }
    
    public function Check_Dependents() {
        // Retrieve this element instance
        $el_instance = $this->el_instance;
        // Retrieve the element machine code
        $el_machine_code = $el_instance->machine_code;
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Retrieve the form's fields
        $form_els = $form_instance->Get_Elements(); 
        // If no rules were found
        if (!$form_els || !is_array($form_els) || count($form_els) == 0) { return $this; } 
        // Loop through each element
        foreach ($form_els as $_machine_code => $_el_instance) {
            // If we have found the original element
            if ($el_machine_code == $_machine_code) { continue; }
            // Retrieve the field's attributes
            $_attributes = $_el_instance->attributes;
            // If there are no conditions set
            if (!isset($_attributes['conditions'])) { continue; }
            // Retrieve the conditions data
            $_conditions = json_decode(base64_decode($_attributes['conditions']),true);
            // If there are no rules
            if (!isset($_conditions['rules']) || count($_conditions['rules']) == 0) { continue; }
            // Loop through each rule
            foreach ($_conditions['rules'] as $_k => $_rule) {
                
                if ($_rule['machine_code'] != $el_machine_code) { continue; }
                
                $this->_dependents[] = $_el_instance;
            }
        }
        
        return $this;
    }

    public function Get_Triggered() {
    
        return $this->_triggered;
    }
    
    public function Get_Non_Triggered() {
        
        return $this->_nontriggered;
    }
    
    public function Get_Dependents() {
        
        return $this->_dependents;
    }
    
    public function Get_Rules() {
        
        return $this->rules;
    }
}