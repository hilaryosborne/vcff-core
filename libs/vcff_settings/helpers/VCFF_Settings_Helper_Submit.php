<?php

class VCFF_Settings_Helper_Submit extends VCFF_Helper {
    
    protected $form_instance;	

	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
    
    public function Submit() {
        $form_instance = $this->form_instance;
        // Retrieve the field instances
        $field_instances = $form_instance->fields;
        
        $prefix = 'vcff_setting_';
        // If there are no field instances
        if (!$field_instances || !is_array($field_instances) || count($field_instances) == 0) { return; }
        // Loop through each field instance
        foreach ($field_instances as $machine_code => $field_instance) {
            // Retrieve the field value
            $field_value = $field_instance->value;
            // Update the option
            update_option($prefix.$machine_code,$field_value);
        }
    }
}