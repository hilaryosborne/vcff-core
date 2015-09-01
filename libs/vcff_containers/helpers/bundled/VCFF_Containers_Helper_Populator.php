<?php

class VCFF_Containers_Helper_Populator extends VCFF_Helper {
	
	protected $form_instance;	
		
    protected $container_instance;
        
	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
	
	protected function _Get_Container_Instance($_el) {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
        // Retrieve the container name
		$type = $_el['type'];
        // Retrieve the global vcff forms class
        $vcff_containers = vcff_get_library('vcff_containers');
        // If the context does not exist
        if (!isset($vcff_containers->contexts[$type])) { return; }
        // Retrieve the context
        $_context = $vcff_containers->contexts[$type]; 
        // Retrieve the container name
		$machine_code = $_el['name'];
        // If no form instance was found
		if (!$machine_code) { return; }
		// Create the field item classname
		$container_classname = $_context['class'];
		// If no form instance was found
		if (!$container_classname) { return; }   
		// Create a new item instance for this field
		$container_instance = new $container_classname();
        // Populate the instance property
        $this->container_instance = $container_instance;
		// Populate the container form
		$container_instance->form_instance = $this->form_instance;
		$container_instance->machine_code = $machine_code;
		$container_instance->container_type = $_context['type'];
		$container_instance->context = $_context;
		$container_instance->attributes = $_el['attributes'];
        // Populate the handler object
		$container_instance->el = $_el['el'];
		$container_instance->el_children = $_el['children'];
        // Add any child fields
        $this->_Add_Child_Fields();
        $this->_Add_Child_Supports();
        // If the field has a sanitize method
        if (method_exists($container_instance,'On_Create')) { $container_instance->On_Create(); }
        // Do any create actions
        $container_instance->Do_Action('create');
        // Do a wordpress hook
        do_action('vcff_container_create',$container_instance);
		// Return the generated field instance
		return $container_instance;
	}
    
    protected function _Add_Child_Fields() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
        // Retrieve the container instance
        $container_instance = $this->container_instance;
        // Retrieve the container's children
        $el_children = $container_instance->el_children;
        // If no shortcodes were returned
        if (!$el_children || !is_array($children)) { return $container_instance; } 
        // Loop through each shortcode
        foreach ($el_children as $k => $el) {
            // If this is not a tag
            if (!$el->is_tag || !$el->tag) { continue; }
            // Retrieve the attributes
            $_attributes = $el->attributes;
            // If no machine code, move on
            if (!isset($_attributes['machine_code'])) { continue; }
            // Retrieve the field instance
            $field_instance = $form_instance->Get_Field($_attributes['machine_code']);
            // If no field instance was returned
            if (!$field_instance) { continue; }
            // Add the field instance to the container
            $container_instance->Add_Field($field_instance);
            // Do any create actions
            $container_instance->Do_Action('add_field',array('field_instance' => $field_instance));
            // Do a wordpress hook
            do_action('vcff_container_add_field',$container_instance,$field_instance);
        }
    }
    
    protected function _Add_Child_Supports() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
        // Retrieve the container instance
        $container_instance = $this->container_instance;
        // Retrieve the container's el_children
        $el_children = $container_instance->el_children;
        // If no shortcodes were returned
        if (!$el_children || !is_array($el_children)) { return $container_instance; }
        // Loop through each shortcode
        foreach ($el_children as $k => $el) {
            // If this is not a tag
            if (!$el->is_tag || !$el->tag) { continue; }
            // Retrieve the attributes
            $_attributes = $el->attributes;
            // If no machine code, move on
            if (!isset($_attributes['machine_code'])) { continue; }
            // Retrieve the field instance
            $support_instance = $form_instance->Get_Support($_attributes['machine_code']);
            // If no field instance was returned
            if (!$support_instance) { continue; } 
            // Add the field instance to the container
            $container_instance->Add_Support($support_instance);
            // Do any create actions
            $container_instance->Do_Action('add_support',array('support_instance' => $support_instance));
            // Do a wordpress hook
            do_action('vcff_container_add_support',$container_instance,$support_instance);
        } 
    }
    
	public function Populate() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the field data
		$_els = vcff_parse_container_data($form_instance->form_content);
		// If an error has been detected, return out
		if (!$_els || !is_array($_els)) { return; }
		// Retrieve the form instance
		$form_instance = $this->form_instance; 
		// Loop through each of the containers
		foreach ($_els as $k => $_el) {
			// Retrieve the container instance
			$container_instance = $this->_Get_Container_Instance($_el);
			// Add the container to the form instance
			$form_instance->Add_Container($container_instance);
		}
	}
}