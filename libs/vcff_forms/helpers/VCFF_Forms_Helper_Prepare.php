<?php

class VCFF_Forms_Helper_Prepare extends VCFF_Helper {
    
    public $params;
    
    public $default_type = 'vcff_standard_form';
    
    public $form_instance;

    public function Get_Form($params) {
        // Save the provided params
        $this->params = $params;
        // Retrieve the validation result
        do_action('vcff_form_before_create', $form_instance);
        // Create the Instance
        $this->_Build_Instance();
        // Check the instance
        $this->_Check_Instance();
        // Retrieve the validation result
        do_action('vcff_form_after_create', $form_instance);
        // Return the form instance
        return $this->form_instance;
    }
    
    protected function _Build_Instance() {
        // Retrieve the global vcff forms class
        $vcff_forms = vcff_get_library('vcff_forms');
        // Return the params
        $params = $this->params;
        // If a form type was provided
        if (isset($params['type']) && isset($vcff_forms->contexts[$params['type']])) {
            // Use the provided form type
            $form_type = $params['type'];
        } // If a form id was passed
        elseif (isset($params['id'])) {
            // Retrieve the form type from the post meta
            $form_type = get_post_meta($params['id'],'form_type',true); 
            // If still no form type, use the default
            if (!$form_type) { $form_type = $this->default_type; } 
        } // If a form id was passed
        elseif (isset($params['uuid'])) {
        	// Retrieve the post object
            $wp_post = vcff_get_form_by_uuid($params['uuid']);
            // Retrieve the form type from the post meta
            $form_type = get_post_meta($wp_post->ID,'form_type',true); 
            // If still no form type, use the default
            if (!$form_type) { $form_type = $this->default_type; }
        } // Otherwise use the default form type
        else { $form_type = $this->default_type; } 
        // If no context was found
        if (!isset($vcff_forms->contexts[$form_type])) { die('A context for this form type does not exist'); }
        // Retrieve the form context
        $form_context = $vcff_forms->contexts[$form_type];
        // Retrieve the form item class name
        $form_item_class = $form_context['class'];
        // BUILD THE FORM INSTANCE
        $form_instance = new $form_item_class(); 
        // Populate the form instance
        $form_instance->form_id = isset($params['type']) ? $params['type'] : null ;
        $form_instance->form_type = $form_type;
        $form_instance->form_uuid = isset($params['uuid']) ? $params['uuid'] : null ;
        $form_instance->form_attributes = isset($params['attributes']) ? $params['attributes'] : null ;
        $form_instance->context = $form_context;
        $form_instance->post_id = isset($params['post_id']) ? $params['post_id'] : null ;
        $form_instance->form_data = isset($params['data']) ? $params['data'] : null ;
        $form_instance->form_referrer = isset($params['vcff_referrer']) ? $params['vcff_referrer'] : null ;
        $form_instance->form_name = isset($params['name']) ? $params['name'] : null ; 
        $form_instance->form_content = isset($params['contents']) ? stripslashes($params['contents']) : null ;
        $form_instance->form_state = isset($params['state']) ? $params['state'] : null ;
        $form_instance->is_submission = isset($params['is_submission']) ? $params['is_submission'] : null ;
        $form_instance->is_ajax = isset($params['is_ajax']) ? $params['is_ajax'] : null ;
        $form_instance->security_key = isset($params['security_key']) ? $params['security_key'] : null ;
        // If there is missing form information
        if (!$form_instance->form_name || !$form_instance->form_content) {
            // Retrieve the post object
            $wp_post = vcff_get_form_by_uuid($form_instance->form_uuid);
            // If a post object was provided
            if ($wp_post && is_object($wp_post)) {
                // Set the form name
                $form_instance->form_id = $wp_post->ID;
                $form_instance->form_name = $wp_post->post_title;
                $form_instance->form_content = stripslashes($wp_post->post_content);
                // Save the wordpress object as well
                $form_instance->wp_object_form = $wp_post; 
            }
        } 
        // We need to save the original for rendering
        $form_instance->form_render = $form_instance->form_content ;
        // Parse any fragments
        $form_instance->form_content = stripslashes(vcff_parse_fragment($form_instance->form_content));
        // Do any form actions on create
        $form_instance->Do_Action('instance_create',array('helper' => $this));
        // If this field has a custom validation method
        if (method_exists($form_instance,'On_Create')) { $form_instance->On_Create(); }
        // Retrieve the validation result
        $form_instance = apply_filters('vcff_form_create', $form_instance);
        // Retrieve the validation result
        do_action('vcff_form_create', $form_instance);
        // Return the form instance
		$this->form_instance = $form_instance;
    }
    
    protected function _Check_Instance() {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Do any form actions on create
        $form_instance->Do_Action('instance_check',array('helper' => $this));
    }

}