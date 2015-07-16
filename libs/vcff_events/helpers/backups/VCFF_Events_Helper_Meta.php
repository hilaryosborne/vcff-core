<?php

class VCFF_Events_Helper_Meta {
	
	protected $form_instance;	
	
	protected $data;
	
	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
	
	public function Set_Data($data) {
		
		$this->data = $data;
		
		return $this;
	}
	
	public function Get_Event($event_type) {
		// Retrieve trhe global events
        $vcff_events = vcff_get_library('vcff_events');
		// Retrieve the event contexts
		$event_types = $vcff_events->event_types;
		// If there is no event type
		if (!isset($vcff_events->event_types[$event_type])) { return; }
		// Retrieve the event context
		$event_context = $vcff_events->event_types[$event_type]; 
		// If no event type could be found
		if (!isset($event_context['class_item'])) { return; }
		// Retrieve the class item
		$event_class_item = $event_context['class_item'];
		// Create a new instance
		$event_class_instance = new $event_class_item();
		// Populate the form instance
		$event_class_instance->form_instance = $this->form_instance;
		// Populate the form instance
		$event_class_instance->context = $event_context;
		// Populate the form instance
		$event_class_instance->data = isset($this->data) ? $this->data : false;
		// Return the event code
		return array(
			'type' => $event_context['type'],
			'title' => $event_context['title'],
			'js' => isset($event_context['params']['js']) ? $event_context['params']['js'] : null,
			'html' => base64_encode($event_class_instance->Render())
		);
	}
	
	
}