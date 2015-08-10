<?php

class VCFF_Containers_Helper_Populator extends VCFF_Helper {
	
	protected $form_instance;	
		
    protected $container_instance;
        
	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
	
	protected function _Get_Container_Instance($container_data) { 
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the container name
		$machine_code = $container_data['name'];
		// Create the field item classname
		$container_classname = $container_data['context']['class_item'];
		// If no form instance was found
		if (!$machine_code) { die('No container name could be found'); return; }
		// If no form instance was found
		if (!$container_classname) { die('No container class item could be found'); return; }   
		// Create a new item instance for this field
		$container_instance = new $container_classname();
        // Populate the instance property
        $this->container_instance = $container_instance;
		// Populate the container form
		$container_instance->form = $this->form_instance;
		// Populate the container fields
		$container_instance->machine_code = $machine_code;
        // Populate the container fields
		$container_instance->container_type = $container_data['context']['type'];
		// Populate the handler object
		$container_instance->context = $container_data['context'];
		// Populate the field list
		$container_instance->attributes = $container_data['attributes'];
        // Populate the handler object
		$container_instance->el = $container_data['el'];
        // Add any child fields
        $this->_Add_Child_Fields();
        // Add any child supports
        $this->_Add_Child_Supports();
		// Return the generated field instance
		return $container_instance;
	}
    
    protected function _Add_Child_Fields() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
        // Retrieve the container instance
        $container_instance = $this->container_instance;
        // Retrieve the container's children
        $children = $container_instance->el->children;
        // If no shortcodes were returned
        if (!$children || !is_array($children)) { return $container_instance; }
        // Loop through each shortcode
        foreach ($children as $k => $el) {
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
        } 
    }
    
    protected function _Add_Child_Supports() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
        // Retrieve the container instance
        $container_instance = $this->container_instance;
        // Retrieve the container's children
        $children = $container_instance->el->children;
        // If no shortcodes were returned
        if (!$children || !is_array($children)) { return $container_instance; }
        // Loop through each shortcode
        foreach ($children as $k => $el) {
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
        } 
    }
    
	public function Populate() {
        // Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the field data
		$containers_data = vcff_parse_container_data($form_instance->form_content); 
		// If an error has been detected, return out
		if (!$containers_data || !is_array($containers_data)) { return; }
		// Retrieve the form instance
		$form_instance = $this->form_instance; 
		// Loop through each of the containers
		foreach ($containers_data as $k => $container_data) {
			// Retrieve the container instance
			$container_instance = $this->_Get_Container_Instance($container_data);
			// Add the container to the form instance
			$form_instance->Add_Container($container_instance);
		}
	}
}