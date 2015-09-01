<?php 

class VCFF_Meta_Helper_Populator {
	
	protected $form_instance;	
	
	protected $field_data;
	
	protected $default_fields = array();
	
	protected $default_pages = array(
        
        array(
            'id' => 'general_settings',
            'title' => 'General Settings',
            'weight' => 1,
            'icon' => '',
        )
        
    );

    protected $default_groups = array(
        
        array(
            'id' => 'form_settings',
            'page_id' => 'general_settings',
            'title' => 'General Settings',
            'weight' => 1,
            'hint_html' => '<h4><strong>Instructions</strong></h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur cursus erat at lectus commodo tempor eget vel turpis. Praesent vitae eros semper, aliquet ipsum vel, porttitor tellus.</p>',
            'help_url' => 'http://vcff.theblockquote.com',
        )
        
    );
	
	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
	
	public function Set_Field_Data($field_data) {
		
		$this->field_data = $field_data;
		
		return $this;
	}

	protected function _Add_Default_Fields() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// If there are no default groups
		if (!isset($this->default_fields) || !is_array($this->default_fields)) { return $this; }
		// Retrieve the meta groups
		$default_meta_fields = $this->default_fields;
        // Meta fields list
        $meta_field_list = is_array($form_instance->context['meta']['fields']) ? $form_instance->context['meta']['fields'] : array() ;
		// Loop through each meta groups
		foreach ($default_meta_fields as $k => $meta_field) {
			// Retrieve the meta group id
			$meta_machine_code = $meta_group['machine_code'];
			// If the meta group is already present
			if (array_key_check('machine_code', $meta_machine_code, $form_instance->context['meta']['fields'])) { continue; }
			// Add the meta group
			$meta_field_list[] = $meta_field;
		}
        // Run through the appropriate filters
        $meta_field_list = apply_filters('vcff_meta_field_list', $meta_field_list, $form_instance);
        // Populate the form context with the meta fields
        $form_instance->context['meta']['fields'] = $meta_field_list;
		// Return for chaining
		return $this;
	}
	
	protected function _Add_Default_Groups() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// If there are no default groups
		if (!isset($this->default_groups) || !is_array($this->default_groups)) { return $this; }
		// Meta fields list
        $meta_group_list = is_array($form_instance->context['meta']['groups']) ? $form_instance->context['meta']['groups'] : array();
        // Retrieve the meta groups
		$default_meta_groups = $this->default_groups;
		// Loop through each meta groups
		foreach ($default_meta_groups as $k => $meta_group) {
			// Retrieve the meta group id
			$meta_group_id = $meta_group['id'];
			// If the meta group is already present
			if (array_key_check('id', $meta_group_id, $form_instance->context['meta']['groups'])) { continue; }
			// Add the meta group
			$meta_group_list[] = $meta_group;
		}
        // Run through the appropriate filters
        $meta_group_list = apply_filters('vcff_meta_group_list', $meta_group_list, $form_instance);
        // Populate the form context with the meta fields
        $form_instance->context['meta']['groups'] = $meta_group_list;
		// Return for chaining
		return $this;
	}
	
	protected function _Add_Default_Pages() {
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// If there are no default groups
		if (!isset($this->default_pages) || !is_array($this->default_pages)) { return $this; }
        // Meta fields list
        $meta_page_list = is_array($form_instance->context['meta']['pages']) ? $form_instance->context['meta']['pages'] : array() ;
		// Retrieve the meta groups
		$default_meta_pages = $this->default_pages;
		// Loop through each meta groups
		foreach ($default_meta_pages as $k => $meta_pages) {
			// Retrieve the meta group id
			$meta_page_id = $meta_pages['id'];
			// If the meta group is already present
			if (array_key_check('id', $meta_page_id, $form_instance->context['meta']['pages'])) { continue; }
			// Add the meta group
			$meta_page_list[] = $meta_pages;
		}
        // Run through the appropriate filters
        $meta_page_list = apply_filters('vcff_meta_page_list', $meta_page_list, $form_instance);
        // Populate the form context with the meta fields
        $form_instance->context['meta']['pages'] = $meta_page_list;
		// Return for chaining
		return $this;
	}
	
	protected function _Get_Meta_Instance($data) { 
		// Retrieve the form instance
		$form_instance = $this->form_instance;
        // Get the vcff containers
        $vcff_meta = vcff_get_library('vcff_meta');
        // Retrieve the meta contexts
        $context = $vcff_meta->contexts;
		// Retrieve the field type
		$type = $data['type'];
        // If there is no context
		if (!$type || !isset($context[$type])) { return; }
		// Retrieve the field context information
		$field_context = $context[$type];
		// Retrieve the field name
		$machine_code = $data['machine_code'];
        // Retrieve the form idw
        $form_id = vcff_get_form_id_by_uuid($form_instance->Get_UUID());
        // Retrieve the class name
		$class = $field_context['class'];
		// Create a new meta field instance
		$field_instance = new $class();
		// Set the meta instance handler
		$field_instance->form_instance = $form_instance;
		// Set the meta instance handler
		$field_instance->context = $field_context;
		// Set the meta instance field name
		$field_instance->machine_code = $machine_code;
		// Set the meta instance field name
		$field_instance->label = $data['label'];
		// Set the meta instance data
		$field_instance->data = $data;
        // Set the meta instance data
		$field_instance->validation = $data['validation'];
		// Retrieve the field data
		$field_data = $this->field_data;
		// Retrieve any stored meta value
		$stored_meta_value = get_post_meta($form_id,$machine_code,true); 
		// Retrieve any posted value
		$posted_meta_value = isset($field_data[$machine_code]) ? $field_data[$machine_code] : false;
		// Retrieve any set meta value
		$set_meta_value = isset($data['value']) ? $data['value'] : false ;
		// Retrieve any default meta value
		$default_meta_value = isset($data['default_value']) ? $data['default_value'] : false ;
		// If there is posted meta value
		if (isset($field_data[$machine_code])) { 
			
			$field_instance->value = $posted_meta_value; 
		} // Otherwise if there is stored meta value
		elseif ($stored_meta_value) { 
			
			$field_instance->value = $stored_meta_value; 
		} // Otherwise if there is set meta value
		elseif ($set_meta_value) { 
			
			$field_instance->value = $set_meta_value; 
		} // Otherwise if there is a default meta value
		elseif ($default_meta_value) { 
			
			$field_instance->value = $default_meta_value; 
		} else { $field_instance->value = ''; }
		
		return $field_instance;
	}
	
	public function Populate() {

		$this->_Add_Default_Fields();
		
		$this->_Add_Default_Groups();
		
		$this->_Add_Default_Pages();
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form handler class
        $meta_fields = $form_instance->context['meta']['fields'];
		// Loop through each meta field
		foreach ($meta_fields as $k => $meta_data) { 
			// Generate a new meta fiel instance
			$meta_field_instance = $this->_Get_Meta_Instance($meta_data);
			// Add the meta field to the form
			$form_instance->Add_Meta($meta_field_instance);
		} 
	}
	
}