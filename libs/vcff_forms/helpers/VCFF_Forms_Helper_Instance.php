<?php

class VCFF_Forms_Helper_Instance extends VCFF_Helper {
	
	protected $post_id;
	
	protected $form_id;
	
	protected $form_data;
	
	protected $form_type;
	
    protected $form_name;
    
	protected $form_contents;
	
    protected $form_attributes;
    
	protected $default_form_type;
	
	protected $wp_object_form;
	
	protected $wp_object_post;
	
	protected $_instance;
	
	protected $error;
	
	public function Get_Error() {
		
		return $this->error;
	}
	
	public function Get_Form_Context() {
		// Retrieve the global vcff forms class
        $vcff_forms = vcff_get_library('vcff_forms');
		// Retrieve the form type
		$form_type = $this->Get_Form_Type(); 
		// Retrieve the form class
        $form_context = $vcff_forms->contexts[$form_type];
        // If no context could be found
        if (!$form_context || !is_array($form_context)) { $this->error = 'No form context present'; return; }
		// Return the form context
		return $form_context;
	}

    public function Set_Form_UUID($form_uuid) {
		
		$this->form_uuid = $form_uuid;
        // If no wordpress post object is yet provided
        if (!$this->wp_object_form) {
            // Retrieve the post object
            $wp_object_form = vcff_get_form_by_uuid($this->form_uuid);
            // If no wordpress post object was returned
            if ($wp_object_form) {
                // Populate with an error and return out
                $this->wp_object_form = $wp_object_form; 
                // Set the form name
                $this->form_id = $wp_object_form->ID;
                // Set the form name
                $this->form_name = $wp_object_form->post_title;
                // Set the form contents
                $this->form_contents = $wp_object_form->post_content;
            }
        }
        // Return for chaining
		return $this;
	}
	
	public function Get_Form_UUID() {
		
		return $this->form_uuid;
	}
    
    public function Get_Form_ID() {
		
		return $this->form_id;
	}
    
    public function Set_Post_ID($post_id) {
	
		$this->post_id = $post_id;
		
		return $this;
	}
    
	public function Get_Post_ID() {
		
		return $this->post_id;
	}
	
	public function Set_Wp_Object_Form($wp_object) {
		
		$this->wp_object_form = $wp_object;
		
		return $this;
	}
	
	public function Set_Wp_Object_Post($wp_object) {
		
		$this->wp_object_post = $wp_object;
		
		return $this;
	}
	
	public function Set_Form_Type($form_type) {
	
		$this->form_type = $form_type;
		
		return $this;
	}
	
    public function Set_Form_Attributes($attributes) {
        
        $this->form_attributes = $attributes;
     
        return $this;
    }
    
    public function Get_Form_Attributes() {
    
        return $this->form_attributes;
    }
    
	public function Get_Form_Type() {
		// If the form type has been provided
		if ($this->form_type) {
			// Return the provided form type
			return $this->form_type;
		} // Otherwise if a form id has been provided 
		elseif ($this->wp_object_form) {
			// Retrieve the form type from meta
        	$form_type = get_post_meta( $this->wp_object_form->ID, 'form_type', true );
			// If still no form type is provided
			if (!$form_type) {
				// Populate with an error and return out
				return 'vcff_standard_form';
			} // Otherwise return with the form type 
			else { return $form_type; }
		} // Otherwise populate with an error 
		else { return 'vcff_standard_form'; }	
	}
	
	public function Get_Form_Name() {
		
        return $this->form_name;
	}

	public function Set_Form_Contents($form_contents) {
		
		$this->form_contents = $form_contents;
				
		return $this;
	}
	
	public function Get_Form_Contents() {
		// If the form contents has been provided
		if ($this->form_contents) {
			// Return the provided form contents
			$form_contents = stripslashes($this->form_contents);
		} // Otherwise if a form id has been provided 
		elseif ($this->form_uuid) {
			// If no wordpress post object is yet provided
			if (!$this->wp_object_form) {
				// Retrieve the post object
        		$wp_object_form = vcff_get_form_by_uuid($this->form_uuid);
				// If no wordpress post object was returned
				if (!$wp_object_form) {
					// Populate with an error and return out
					$this->error = 'No form object could be found'; return;
				} // Otherwise populate with the form object 
				else { $this->wp_object_form = $wp_object_form; }
			}
			// Retrieve the form post
			$form_post = $this->wp_object_form;
			// Populate with the post contents
			$form_contents = $form_post->post_content;
			// If still no form contents is provided
			if (!$form_contents) { return;
			} // Otherwise return with the form type 
			else { $form_contents = stripslashes($form_contents); }
		}
        // Allow plugins/themes to override the default caption template.
        $form_contents = apply_filters('vcff_form_contents', $form_contents); 
        // Return the form content
        return $form_contents;
	}
	
	public function Set_Form_Data($form_data) {
		
		$this->form_data = $form_data;
		
		return $this;
	}

	public function Generate() {
		// Retrieve the global vcff forms class
        $vcff_forms = vcff_get_library('vcff_forms');
		// Retrieve the form context
		$form_context = $this->Get_Form_Context(); 
		// If errors were generated, return out
		if ($this->error) { return; }
		// Retrieve the form item class name
        $form_item_class = $form_context['class_item'];
		// Instance the form
        $form_instance = new $form_item_class(); 
        // Populate the form id
        $form_instance->form_uuid = $this->Get_Form_UUID();
        // Populate the form id
        $form_instance->form_id = $this->Get_Form_ID();
        // Populate the post id
        $form_instance->post_id = $this->Get_Post_ID();
        // Populate the form id
        $form_instance->form_name = $this->Get_Form_Name(); 
        // Populate the form id
        $form_instance->post_data = $this->form_data;
        // Populate the form id
        $form_instance->form_type = $this->Get_Form_Type(); 
        // Populate the form contents
        $form_instance->form_content = $this->Get_Form_Contents() ;
        // Populate the form contents
        $form_instance->form_attributes = $this->Get_Form_Attributes() ;
        // Populate the handler object
        $form_instance->context = $form_context;
		// If errors were generated, return out
		if ($this->error) { return; }
		// Populate with the form instance
		$this->_instance = $form_instance;
		// Return the form instance
		return $form_instance;
	}
	
	public function Add_Fields($field_data=array()) {
		
		$form_instance = $this->_instance;
		
		$form_data = count($field_data) > 0 ? $field_data : $this->form_data;
		
		$fields_helper = new VCFF_Fields_Helper_Populator();
				
		$fields_helper
			->Set_Form_Instance($form_instance)
			->Set_Form_Data($form_data)
			->Populate();
		
		return $this;
	}
    
    public function Add_Supports() {
        
        $form_instance = $this->_instance;
        // Create a new support helper instance
        $support_helper = new VCFF_Supports_Helper_Populator();
        // Populate with support instances
        $support_helper
            ->Set_Form_Instance($form_instance)
			->Populate();
        // Return for chaining
        return $this;
    }
	
	public function Add_Containers() {
		
		$form_instance = $this->_instance;
		
		$container_helper = new VCFF_Containers_Helper_Populator();
				
		$container_helper
			->Set_Form_Instance($form_instance)
			->Populate();
		
		return $this;
	}
	
	public function Add_Meta($meta_data=array()) {
		
		$form_instance = $this->_instance;

		$meta_helper = new VCFF_Meta_Helper_Populator();
				
		$meta_helper
			->Set_Form_Instance($form_instance)
			->Set_Field_Data($meta_data)
			->Populate();
		
		$meta_conditions_helper = new VCFF_Meta_Helper_Conditions();
				
		$meta_conditions_helper
			->Set_Form_Instance($form_instance)
			->Check();
		
		return $this;
	}
    
    public function Add_Events() {
        
        $form_instance = $this->_instance; 
        // If this form uses the submission events library
        if (!$form_instance->use_events) { return false; }   
        // Create a new event populator
        $events_populator_helper = new VCFF_Events_Helper_Populator();
        // Populate with the events
        $events_populator_helper
            ->Set_Form_Instance($form_instance)
            ->Populate();
            
        return $this;
    }
	
	public function Check_Conditions() {
		
		$form_instance = $this->_instance;
        // Retrieve the validation result
        do_action('vcff_pre_form_conditional', $form_instance);
        
		$fields_conditions_helper = new VCFF_Fields_Helper_Conditions();
		
		$fields_conditions_helper
			->Set_Form_Instance($form_instance)
			->Check();
		
		$containers_conditions_helper = new VCFF_Containers_Helper_Conditions();
		
		$containers_conditions_helper
			->Set_Form_Instance($form_instance)
			->Check();
            
        $suports_conditions_helper = new VCFF_Supports_Helper_Conditions();
		
		$suports_conditions_helper
			->Set_Form_Instance($form_instance)
			->Check();
        // Retrieve the validation result
        do_action('vcff_post_form_conditional', $form_instance);
        
		return $this;
	}
	
	public function Check_Validation() {
		
		$form_instance = $this->_instance;

        $form_validation_helper = new VCFF_Forms_Helper_Validation();
        
        $form_validation_helper
            ->Set_Form_Instance($form_instance)
            ->Check();
		
		return $this;
	}
    
    public function Filter() {
        
        $form_instance = $this->_instance;
        // Create a new filter helper
        $field_filter_helper = new VCFF_Fields_Helper_Filter();
        
        $field_filter_helper
            ->Set_Form_Instance($form_instance)
            ->Filter();
            
        return $this;
    }
	
}