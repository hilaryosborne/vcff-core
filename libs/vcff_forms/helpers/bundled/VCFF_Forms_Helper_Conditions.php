<?php

class VCFF_Forms_Helper_Conditions {
    
    public $els;
    
    protected $form_instance;
    
    public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
    
    public function Get_Elements() {
        
        return $this->els;
    }
    
    public function Build() {
        $this->_Build_Fields();
        $this->_Build_Supports();
        $this->_Build_Containers();
        $this->_Build_Form();
        
        $this->els = apply_filters('vcff_conditional_els', $this->els, $this);
    }
    
    protected function _Build_Fields() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's fields
        $form_fields = $form_instance->fields; 
		// If there are no form fields
		if (!$form_fields || !is_array($form_fields)) { return; }
		// Loop through each containers
		foreach ($form_fields as $machine_code => $field_instance) {
            // Retrieve the context
            $context = $field_instance->context;
            // Retrieve the set conditional logic
            $_context_logic = isset($context['conditional_logic']) ? $context['conditional_logic'] : false ;
            // Create the field logic var
            $field_logic = is_array($_context_logic) ? $_context_logic : array();
            // Do any actions
            $field_logic = $field_instance->Apply_Filters('conditional_logic',$field_logic,array('_helper' => $this));
            // If logic rules were returned
            if (count($field_logic) > 0) {
                // Build the els entry
                $this->els[$machine_code] = array(
                    'machine_code' => $machine_code,
                    'logic_rules' => $field_logic
                );
            }
        }
    }
    
    protected function _Build_Supports() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's supports
        $form_supports = $form_instance->supports;
		// If there are no form supports
		if (!$form_supports || !is_array($form_supports)) { return; }
		// Loop through each containers
		foreach ($form_supports as $machine_code => $support_instance) {
			// Retrieve the context
            $context = $support_instance->context;
            // Retrieve the set conditional logic
            $_context_logic = isset($context['conditional_logic']) ? $context['conditional_logic'] : false ;
            // Create the field logic var
            $support_logic = is_array($_context_logic) ? $_context_logic : array();
            // Do any actions
            $support_logic = $support_instance->Apply_Filters('conditional_logic',$support_logic,array('_helper' => $this));
            // If logic rules were returned
            if (count($support_logic) > 0) {
                // Build the els entry
                $this->els[$machine_code] = array(
                    'machine_code' => $machine_code,
                    'logic_rules' => $support_logic
                );
            }
        }
    }
    
    protected function _Build_Containers() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form's fields
		$form_containers = $form_instance->containers;
		// If there are no form containers
		if (!$form_containers || !is_array($form_containers)) { return; }
		// Loop through each of the form's containers
		foreach ($form_containers as $k => $container_instance) {
            // Retrieve the context
            $context = $container_instance->context;
			// Retrieve the set conditional logic
            $_context_logic = isset($context['conditional_logic']) ? $context['conditional_logic'] : false ;
            // Create the field logic var
            $container_logic = is_array($_context_logic) ? $_context_logic : array();
            // Do any actions
            $container_logic = $container_instance->Apply_Filters('conditional_logic',$container_logic,array('_helper' => $this));
            // If logic rules were returned
            if (count($container_logic) > 0) {
                // Build the els entry
                $this->els[$machine_code] = array(
                    'machine_code' => $machine_code,
                    'logic_rules' => $container_logic
                );
            }
        }
    }
    
    protected function _Build_Form() {
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
                'logic_rules' => $form_logic
            );
        }
        // Do
        $form_instance->Do_Action('conditional_logic',array('_helper' => $this));
    }
    
    
}