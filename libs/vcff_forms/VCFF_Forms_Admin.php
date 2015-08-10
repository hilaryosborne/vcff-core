<?php

class VCFF_Forms_Admin {

    public function __construct() {
        add_filter('vcff_meta_field_list',array($this,'_Filter_Add_Meta_Field_Type'), 15, 2);
        add_filter('vcff_meta_field_list',array($this,'_Filter_Add_Meta_Field_AJAX'), 15, 2);
    }
    
    public function _Filter_Add_Meta_Field_Type($meta_fields, $form_instance) {
		// Retrieve the global vcff forms class
        $vcff_forms = vcff_get_library('vcff_forms');
		// Retrieve the form class
        $form_context = $vcff_forms->contexts;
        // If no context could be found
        if (!$form_context || !is_array($form_context)) { return; }
        // Storage var
        $contexts_list = array();
		// Loop through each form context
		foreach ($form_context as $type => $context) {
			// Populate the context list
			$contexts_list[$type] = $context['title'];
		}
		// Create the form type field
        $meta_fields[] = array(
            'machine_code' => 'form_type',
            'field_label' => 'Form Type',
            'field_type' => 'select',
            'validation' => array(
                'required' => true
            ),
            'default_value' => 'vcff_standard_form',
            'weight' => 1,
            'values' => $contexts_list
        );

        return $meta_fields;
    }
    
    public function _Filter_Add_Meta_Field_AJAX($meta_fields, $form_instance) {
        // If the form allows for ajax submission
        if (!$form_instance->use_ajax) { return; }
		// Create the form type field
        $meta_fields[] = array(
            'machine_code' => 'use_ajax',
            'field_label' => 'Submit Via AJAX',
            'field_type' => 'select',
            'validation' => array(
                'required' => true
            ),   
            'required' => true,
            'weight' => 2,
            'default_value' => 'yes',
            'values' => array(
                'yes' => 'Yes, Use AJAX Submission',
                'no' => 'No, Use Standard Submission'
            )
        );
        
        return $meta_fields;
    }
    
    public function _Hook_Edit_Title($post) {
        // Retrieve the form uuid
        $form_uuid = vcff_get_uuid_by_form($post->ID);

        $edit_url = get_site_url(false,'index.php?page=vcff_preview_form&form_uuid='.$form_uuid);

        echo '<a href="'.$edit_url.'" class="button button-primary button-large">Preview Form</a>';
    }
}

global $vcff_forms_admin;

$vcff_forms_admin = new VCFF_Forms_Admin();