<?php

class VCFF_Containers_Helper_AJAX extends VCFF_Helper {

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
        $form_instance->Do_Action('container_before_ajax',array('helper' => $this));
        // Retrieve the validation result
        do_action('vcff_container_before_ajax', $form_instance);
        // Create the Instance
        $this->_AJAX_Containers();
        // Do any form actions on create
        $form_instance->Do_Action('container_ajax',array('helper' => $this));
        // Do any form actions on create
        $form_instance->Do_Action('container_after_ajax',array('helper' => $this));
        // Retrieve the validation result
        do_action('vcff_container_after_ajax', $form_instance);
        // Return the resulting data
        return $this->data;
    }
    
    protected function _AJAX_Containers() {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
		// Retrieve the form containers
		$containers = $form_instance->containers;
        // If there are no containers, return out
        if (!$containers || !is_array($containers)) { return; }
        // Loop through each form containers
        foreach ($containers as $machine_code => $container_instance) {
            // The ajax array var
            $_ajax = array();
			// Populate the container type
            $_ajax['type'] = $container_instance->container_type;
            // Populate the container type
            $_ajax['visibility'] = $container_instance->Is_Visible() ? 'visible' : 'hidden';
            // Populate the container type
            $_ajax['validation'] = $container_instance->Is_Valid() ? 'passed' : 'failed';
            // Populate the container type
            $_ajax['alerts'] = $container_instance->Get_Alerts_HTML();
            // Run the ajax data through any filters
            $_ajax = $container_instance->Apply_Filters('container_ajax',$_ajax,array('helper' => $this, '_ajax' => $_ajax));
            // Populate the ajax data
            $this->data[$machine_code] = $_ajax;
        }
    }

}