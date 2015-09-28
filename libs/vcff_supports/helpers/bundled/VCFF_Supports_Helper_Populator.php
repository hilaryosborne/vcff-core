<?php

class VCFF_Supports_Helper_Populator extends VCFF_Helper {

    protected $form_instance;	
		
	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
    
    protected function _Get_Instance($_el) { 
        // Retrieve the form instance
		$form_instance = $this->form_instance;
        // Retrieve the support name
		$type = $_el['type'];
        // Retrieve the global vcff forms class
        $vcff_supports = vcff_get_library('vcff_supports');
        // If the context does not exist
        if (!isset($vcff_supports->contexts[$type])) { return; }
        // Retrieve the context
        $_context = $vcff_supports->contexts[$type];
        // Retrieve the support name
		$machine_code = $_el['name']; 
        // If no form instance was found
		if (!$machine_code) { return; }
		// Create the field item classname
		$support_classname = $_context['class'];
		// If no form instance was found
		if (!$support_classname) { return; } 
		// Create a new item instance for this field
		$support_instance = new $support_classname();
		// Populate the support form
		$support_instance->form_instance = $this->form_instance;
		// Populate the support fields
		$support_instance->machine_code = $machine_code;
        // Populate the support fields
		$support_instance->support_type = $_context['type'];
		// Populate the handler object
		$support_instance->context = $_context;
		// Populate the field list
		$support_instance->attributes = $_el['attributes'];
        // If the field has a sanitize method
        if (method_exists($support_instance,'On_Create')) { $support_instance->On_Create(); }
        // Do any create actions
        $support_instance->Do_Action('create');
        // Do a wordpress hook
        do_action('vcff_support_create',$support_instance);
		// Return the generated field instance
		return $support_instance;
	}

    public function Populate() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the field data
		$_els = vcff_parse_support_data($form_instance->form_content);
		// If an error has been detected, return out
		if (!$_els || !is_array($_els)) { return; }
		// Retrieve the form instance
		$form_instance = $this->form_instance; 
		// Loop through each of the containers
		foreach ($_els as $k => $_el) {
			// Retrieve the container instance
			$support_instance = $this->_Get_Instance($_el);
			// Add the container to the form instance
			$form_instance->Add_Support($support_instance);
		}
	}
}