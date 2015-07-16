<?php

class VCFF_Events_Helper_Form {
    
	protected $form_instance;	
	
	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
	
    protected $event_pages = array(
		
		
	);
	
	protected function _Add_Event_Pages() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// If there are no default groups
		if (!isset($this->event_pages) || !is_array($this->event_pages)) { return $this; }
		// Retrieve the meta groups
		$event_meta_pages = $this->event_pages;
		// Loop through each meta groups
		foreach ($event_meta_pages as $k => $meta_pages) {
			// Retrieve the meta group id
			$meta_page_id = $meta_pages['id'];
			// If the meta group is already present
			if (array_key_check('id', $meta_page_id, $form_instance->context['meta']['pages'])) { continue; }
			// Add the meta group
			$form_instance->context['meta']['pages'][] = $meta_pages;
		}
		// Return for chaining
		return $this;
	}
    
    protected $event_groups = array(
		
		array(
			'id' => 'submission_events',
			'page_id' => 'on_submission',
			'title' => 'Submission Events',
			'weight' => 2,
			'description' => 'This new page contains some settings',
			'icon' => '',
		)
	);
    
	protected function _Add_Event_Groups() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// If there are no default groups
		if (!isset($this->event_groups) || !is_array($this->event_groups)) { return $this; }
		// Retrieve the meta groups
		$event_meta_groups = $this->event_groups;
		// Loop through each meta groups
		foreach ($event_meta_groups as $k => $meta_group) {
			// Retrieve the meta group id
			$meta_group_id = $meta_group['id'];
			// If the meta group is already present
			if (array_key_check('id', $meta_group_id, $form_instance->context['meta']['groups'])) { continue; }
			// Add the meta group
			$form_instance->context['meta']['groups'][] = $meta_group;
		}
		// Return for chaining
		return $this;
	}
	
    protected $event_fields = array(
		
		array(
			'field_name' => 'events_wizard',
			'field_label' => 'Submission Events',
			'field_group' => 'submission_events',
			'field_type' => 'events_wizard',
			'field_dependancy' => false
		)
	);

	protected function _Add_Event_Fields() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// If there are no default groups
		if (!isset($this->event_fields) || !is_array($this->event_fields)) { return $this; }
		// Retrieve the meta groups
		$event_meta_fields = $this->event_fields;
		// Loop through each meta groups
		foreach ($event_meta_fields as $k => $meta_field) {
			// Retrieve the meta group id
			$meta_field_name = $meta_group['field_name'];
			// If the meta group is already present
			if (array_key_check('field_name', $meta_field_name, $form_instance->context['meta']['fields'])) { continue; }
			// Add the meta group
			$form_instance->context['meta']['fields'][] = $meta_field;
		}
		// Return for chaining
		return $this;
	}
	
    public function Add_Event_Meta() {

		$form_instance = $this->form_instance;
		
		$this->_Add_Event_Pages();
		
		$this->_Add_Event_Groups();
		
		$this->_Add_Event_Fields(); 
    }
}