<?php

class VCFF_Fields_Helper_AJAX extends VCFF_Helper {

    protected $form_instance;
    
    protected $data;
    
    protected $params = array();

    public function Set_Form_Instance($form_instance) {
		// Set the form instance
		$this->form_instance = $form_instance;
		// Return for chaining
		return $this;
	}
    
    public function Build($params = array()) {
        // Save the provided params
        $this->params = array_merge($this->params,$params); 
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Do any form actions on create
        $form_instance->Do_Action('field_before_ajax',array('helper' => $this));
        // Retrieve the validation result
        do_action('vcff_field_before_ajax', $form_instance);
        // Create the Instance
        $this->_AJAX_Fields();
        // Do any form actions on create
        $form_instance->Do_Action('field_ajax',array('helper' => $this));
        // Do any form actions on create
        $form_instance->Do_Action('field_after_ajax',array('helper' => $this));
        // Retrieve the validation result
        do_action('vcff_field_after_ajax', $form_instance);
        // Return the resulting data
        return $this->data;
    }
    
    protected function _AJAX_Fields() {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
		// Retrieve the form fields
		$fields = $form_instance->fields;
        // If there are no fields, return out
        if (!$fields || !is_array($fields)) { return; }
        // Loop through each form fields
        foreach ($fields as $machine_code => $field_instance) {
            // The ajax array var
            $_ajax = array();
			// Populate the field type
            $_ajax['type'] = $field_instance->field_type;
            // Populate the field type
            $_ajax['visibility'] = $field_instance->Is_Visible() ? 'visible' : 'hidden';
            // Populate the field type
            $_ajax['validation'] = $field_instance->Is_Valid() ? 'passed' : 'failed';
            // Populate the field type
            $_ajax['alerts'] = $field_instance->Get_Alerts_HTML();
            // Run the ajax data through any filters
            $_ajax = $field_instance->Apply_Filters('field_ajax',$_ajax,array('helper' => $this, '_ajax' => $_ajax));
            // Populate the ajax data
            $this->data[$machine_code] = $_ajax;
        }
    }

}