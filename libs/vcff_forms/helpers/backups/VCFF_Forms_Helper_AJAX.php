<?php 

class VCFF_Forms_Helper_AJAX extends VCFF_Helper {
	
	protected $form_instance;	
	
	protected $error;
	
	protected $json_data = array(
		'form' => array(),
		'fields' => array(),
		'containers' => array(),
	);
	
	public function Get_Error() {
		
		return $this->error;
	}

	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
	
	public function Use_Conditions($flag) {
		
		$this->use_conditions = $flag;
		
		return $this;
	}
	
	public function Use_Validation($flag) {
		
		$this->use_validation = $flag;
		
		return $this;
	}
	
	public function Get_JSON_Data() {
		
		$this->_Get_Form_Status();
		
		$this->_Get_Form_Fields();
		
		$this->_Get_Form_Containers();
		
		return $this->json_data;
	}
	
	protected function _Get_Form_Status() {
		
		$form_instance = $this->form_instance;
		
        $json = array();
        
        if ($this->use_validation) {
				
            $json['result'] = $form_instance->Is_Valid() ? 'passed' : 'failed' ;
        }
        
        if ($form_instance->Get_Alerts()) {

            $json['alerts'] = $form_instance->Get_Alerts_HTML();
        }
        
        if ($form_instance->ajax) {

            $json['ajax'] = $form_instance->ajax;
        }
        
        if ($form_instance->Get_Redirects()) {
    
            $redirect = $form_instance->Get_Redirects();
    
            $redirect_exploded = explode('&',$redirect[2]);
            
            $params = array();
            
            foreach ($redirect_exploded as $k => $param) {
                
                $param_exploded = explode('=',$param);
                
                $params[$param[0]] = $param[1];
            }
            
            $redirect[2] = $params;
    
            $json['redirects'] = $redirect;
        }
        // Allow plugins/themes to override the default caption template.
        $json = apply_filters('vcff_forms_ajax_form_data', $json);
        // Populate the submission data
        $this->json_data['form'] = $json;
	}
    
	protected function _Get_Form_Fields() {
		
		$form_instance = $this->form_instance;
		
		$form_fields = $form_instance->fields;
		
		if (!$form_fields || !is_array($form_fields)) { return; }
		
		foreach ($form_fields as $machine_code => $field_instance) {
			
			$json_field_data = array();
			
            $json_field_data['type'] = $field_instance->field_type;
            
            $json_field_data['data'] = $field_instance->Get_AJAX_Data();
            
			if ($this->use_conditions) {
            
				$json_field_data['conditions'] = array(
					'visibility' => $field_instance->Is_Visible() ? 'visible' : 'hidden'
				);
			}
			
			if ($this->use_validation) {

				$json_field_data['validation'] = array(
					'result' => $field_instance->Is_Valid() ? 'passed' : 'failed'
				);
			}
            
            if ($field_instance->Get_Alerts()) {
            
                $json_field_data['alerts'] = $field_instance->Get_Alerts_HTML();
            }
			
			$this->json_data['fields'][$machine_code] = $json_field_data;
		}
	}
	
	protected function _Get_Form_Containers() {
		
		$form_instance = $this->form_instance;
		
		$form_containers = $form_instance->containers;
		
		if (!$form_containers || !is_array($form_containers)) { return; }
		
		foreach ($form_containers as $container_name => $container_instance) {
			
			$json_container_data = array();
			
            $json_container_data['type'] = $container_instance->container_type;
            
            $json_container_data['data'] = $container_instance->Get_AJAX_Data();
            
			if ($this->use_conditions) {
				
				$json_container_data['conditions'] = array(
					'visibility' => $container_instance->Is_Visible() ? 'visible' : 'hidden'
				);
			}
			
			if ($this->use_validation && isset($container_instance->result_validation)) {
				
				$validation_check = $container_instance->result_validation;
				
				$json_container_data['validation'] = array(
					'result' => $validation_check['result']
				);
			}
            
            if ($container_instance->Get_Alerts()) {
            
                $json_container_data['alerts'] = $container_instance->Get_Alerts_HTML();
            }
			
			$this->json_data['containers'][$container_name] = $json_container_data;
		}
	}
}