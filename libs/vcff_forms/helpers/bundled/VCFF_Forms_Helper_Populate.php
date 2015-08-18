<?php

class VCFF_Forms_Helper_Populate extends VCFF_Helper {

    protected $form_instance;
    
    protected $params = array(
        'fields' => true,
        'fields_values' => false,
        'containers' => true,
        'supports' => true,
        'events' => true,
        'meta' => true,
        'meta_values' => false,
    );

    public function Set_Form_Instance($form_instance) {
		// Set the form instance
		$this->form_instance = $form_instance;
		// Return for chaining
		return $this;
	}
    
    public function Populate($params = array()) {
        // Save the provided params
        $this->params = array_merge($this->params,$params);
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Do any form actions on create
        $form_instance->Do_Action('before_populate',array('helper' => $this));
        // Retrieve the validation result
        do_action('vcff_form_before_populate', $form_instance);
        // Create the Instance
        $this->_Add_Fields();
        $this->_Add_Supports();
        $this->_Add_Containers();
        $this->_Add_Events();
        $this->_Add_Meta();
        // Do any form actions on create
        $form_instance->Do_Action('populate',array('helper' => $this));
        // Do any form actions on create
        $form_instance->Do_Action('after_populate',array('helper' => $this));
        // Retrieve the validation result
        do_action('vcff_form_after_populate', $form_instance);
    }
    
    protected function _Add_Fields() {
        // Retrieve the params
        $params = $this->params;
        // If we are not going to populate the fields
        if (!$params['fields']) { return; }
		// Retrieve the form instance
		$form_instance = $this->form_instance;
        // Create a new field populator
		$fields_helper = new VCFF_Fields_Helper_Populator();
        // Populate the form
		$fields_helper
			->Set_Form_Instance($form_instance)
			->Set_Form_Data(isset($params['fields_values']) && is_array($params['fields_values']) ? $params['fields_values'] : $form_instance->form_data)
			->Populate();
		// Do any form actions on create
        $form_instance->Do_Action('populate_fields',array('helper' => $this));
	}

    protected function _Add_Supports() {
        // Retrieve the params
        $params = $this->params;
        // If we are not going to populate the fields
        if (!$params['supports']) { return; }
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Create a new support helper instance
        $support_helper = new VCFF_Supports_Helper_Populator();
        // Populate with support instances
        $support_helper
            ->Set_Form_Instance($form_instance)
			->Populate();
        // Return for chaining
        return $this;
		// Do any form actions on create
        $form_instance->Do_Action('populate_supports',array('helper' => $this));
	}
    
    protected function _Add_Containers() {
        // Retrieve the params
        $params = $this->params;
        // If we are not going to populate the fields
        if (!$params['containers']) { return; }
		// Retrieve the form instance
		$form_instance = $this->form_instance;
        // Create a new field populator
		$container_populator_helper = new VCFF_Containers_Helper_Populator();
        // Populate the form with the containers
		$container_populator_helper
			->Set_Form_Instance($form_instance)
			->Populate();
		// Do any form actions on create
        $form_instance->Do_Action('populate_containers',array('helper' => $this));
	}
    
    protected function _Add_Events() {
        // Retrieve the params
        $params = $this->params;
        // If we are not going to populate the fields
        if (!$params['events']) { return; }
		// Retrieve the form instance
		$form_instance = $this->form_instance;
        // Create a new field populator
		$events_populator_helper = new VCFF_Events_Helper_Populator();
        // Populate with the events
        $events_populator_helper
            ->Set_Form_Instance($form_instance)
            ->Populate();
		// Do any form actions on create
        $form_instance->Do_Action('populate_events',array('helper' => $this));
	}
    
    protected function _Add_Meta() {
        // Retrieve the params
        $params = $this->params;
        // If we are not going to populate the fields
        if (!$params['meta']) { return; }
		// Retrieve the form instance
		$form_instance = $this->form_instance;
        // Create a new field populator
		$meta_populator_helper = new VCFF_Meta_Helper_Populator();
        // Populate the form with meta fields
		$meta_populator_helper
			->Set_Form_Instance($form_instance)
			->Set_Field_Data(isset($params['meta_values']) && is_array($params['meta_values']) ? $params['meta_values'] : null)
			->Populate();
		// Do any form actions on create
        $form_instance->Do_Action('populate_meta',array('helper' => $this));
	}
}