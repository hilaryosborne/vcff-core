<?php

class VCFF_Supports_Helper_AJAX extends VCFF_Helper {

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
        $form_instance->Do_Action('support_before_ajax',array('helper' => $this));
        // Retrieve the validation result
        do_action('vcff_support_before_ajax', $form_instance);
        // Create the Instance
        $this->_AJAX_Supports();
        // Do any form actions on create
        $form_instance->Do_Action('support_ajax',array('helper' => $this));
        // Do any form actions on create
        $form_instance->Do_Action('support_after_ajax',array('helper' => $this));
        // Retrieve the validation result
        do_action('vcff_support_after_ajax', $form_instance);
        // Return the resulting data
        return $this->data;
    }
    
    protected function _AJAX_Supports() {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
		// Retrieve the form supports
		$supports = $form_instance->supports;
        // If there are no supports, return out
        if (!$supports || !is_array($supports)) { return; }
        // Loop through each form supports
        foreach ($supports as $machine_code => $support_instance) {
            // The ajax array var
            $_ajax = array();
			// Populate the support type
            $_ajax['type'] = $support_instance->support_type;
            // Populate the support type
            $_ajax['visibility'] = $support_instance->Is_Visible() ? 'visible' : 'hidden';
            // Populate the field type
            $_ajax['validation'] = $support_instance->Is_Valid() ? 'passed' : 'failed';
            // Populate the support type
            $_ajax['alerts'] = $support_instance->Get_Alerts_HTML();
            // Populate the support type
            $_ajax['data'] = $support_instance->ajax;
            // Run the ajax data through any filters
            $_ajax = $support_instance->Apply_Filters('support_ajax',$_ajax,array('helper' => $this, '_ajax' => $_ajax));
            // Populate the ajax data
            $this->data[$machine_code] = $_ajax;
        }
    }

}