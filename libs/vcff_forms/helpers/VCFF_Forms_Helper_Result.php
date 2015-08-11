<?php

class VCFF_Forms_Helper_Result extends VCFF_Helper {
    
    protected $form_instance;
    
    protected $params = array();

    public function Set_Form_Instance($form_instance) {
		// Set the form instance
		$this->form_instance = $form_instance;
		// Return for chaining
		return $this;
	}
    
    public function Result($params = array()) {
        // Save the provided params
        $this->params = array_merge($this->params,$params);
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Do any form actions on create
        $form_instance->Do_Action('before_result',array('helper' => $this));
        // Retrieve the validation result
        do_action('vcff_form_before_result', $form_instance);
        // Do any form actions on create
        $form_instance->Do_Action('result',array('helper' => $this));
        // Create the Instance
        if ($form_instance->is_ajax) {
            // Run the ajax response
            $this->_For_AJAX();
        } // Otherwise if this is a standard response
        else { $this->_For_Standard(); }
        // Do any form actions on create
        $form_instance->Do_Action('after_result',array('helper' => $this));
        // Retrieve the validation result
        do_action('vcff_form_after_result', $form_instance);
        // If we are not going to populate the fields
        if (isset($params['exit_out']) && $params['exit_out']) { wp_die(); }
    }
    
    protected function _For_AJAX() {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // The ajax array
        $_ajax = array();
        // Add the form values to the ajax array
        $_ajax['form'] = array(
            'result' => $form_instance->Is_Valid() ? 'passed' : 'failed',
            'alerts' => $form_instance->Get_Alerts_HTML(),
        ); 
        // Create a new fields ajax helper
        $fields_helper_AJAX = new VCFF_Fields_Helper_AJAX();
        // Populate the fields ajax data
        $_ajax['fields'] = $fields_helper_AJAX
            ->Set_Form_Instance($form_instance)
            ->Build();
        // Create a new fields ajax helper
        $supports_helper_AJAX = new VCFF_Supports_Helper_AJAX();
        // Populate the fields ajax data
        $_ajax['supports'] = $supports_helper_AJAX
            ->Set_Form_Instance($form_instance)
            ->Build();
        // Create a new fields ajax helper
        $containers_helper_AJAX = new VCFF_Containers_Helper_AJAX();
        // Populate the fields ajax data
        $_ajax['containers'] = $containers_helper_AJAX
            ->Set_Form_Instance($form_instance)
            ->Build();
        // Pass the ajax through the filter
        $_ajax = $form_instance->Apply_Filters('ajax',$_ajax,array('helper' => $this));
         // Encode the meta fields and return
        echo json_encode(array(
            'result' => 'success',
            'data' => $_ajax
        ));
    }
    
    protected function _For_Standard() {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Do any form actions on create
        $form_instance->Do_Action('result_standard',array('helper' => $this));
    }
    
}