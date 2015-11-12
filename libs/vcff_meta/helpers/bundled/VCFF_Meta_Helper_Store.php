<?php

class VCFF_Meta_Helper_Store {
	
	protected $form_instance;	

	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
	
	public function Save() {
		// Retrieve the form instance6
		$form_instance = $this->form_instance;
        // Retrieve the form id
        $form_id = $form_instance->form_id; 
        // Retrieve the form unique id
        $form_unique_id = get_post_meta($form_id, 'form_uuid', true);
        // If the form does not yet have a unique id
        if (!$form_unique_id) {
            // Generate a new unique id
            $form_unique_id = uniqid();
            // Update the form with a unique id
            update_post_meta($form_id, 'form_uuid', $form_unique_id);
        }
        // Retrieve the meta fields
        $meta_fields = $form_instance->meta;
        // If a list of meta fields was returned
        if (!$meta_fields || !is_array($meta_fields)) { return;  }
        // Loop through each of the meta fields
        foreach ($meta_fields as $machine_code => $field_instance) {
            // If the field is not visible
            if ($field_instance->condition_check['result'] != 'visible') { 
                // Delete any post meta
                delete_post_meta($form_id, $machine_code); continue; 
            }
            // If the field has a storage method
            if (method_exists($field_instance,'Get_Storage_Value')) {
                // Retrieve the processed storage value
                $meta_field_value = $field_instance->Get_Storage_Value();
            } // Otherwise get the raw value 
            else { $meta_field_value = $field_instance->value; }
            // Update the post meta
            update_post_meta($form_id, $machine_code, $meta_field_value);
            // Add the field to the field list
            $present_fields[] = $machine_code;
        }
        // Retrieve all of the forms meta data
        $found_meta_fields = get_post_meta($form_id, 'index_vcff_fields',true);
        // If meta data was returned
        if ($found_meta_fields && is_array($found_meta_fields)) {
            // Loop through each meta value
            foreach ($found_meta_fields as $k => $machine_code) {
                // If this meta field is not a form field
                if (in_array($machine_code,$present_fields)) { continue; }
                // Delete the post meta
                delete_post_meta($form_id, $machine_code);
            }
        }
        // Update the post meta
        update_post_meta($form_id, 'index_vcff_fields', $found_meta_fields);
	}
}