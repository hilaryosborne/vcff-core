<?php

class VCFF_Forms_Admin {

    public function __construct() {
        add_action('wp_ajax_form_get_field_list', array($this,'AJAX_Get_Field_List'));
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
    
    public function AJAX_Get_Field_List() {
        // Decode the form data
        $form_data = base64_decode($_POST['form_data']);
        // Parse the form data
        parse_str($form_data,$output);
        // Retrieve the form id
        $post_id = $output['vcff_post_id'];
        // Retrieve the form uuid
        $form_uuid = vcff_get_uuid_by_form($output['vcff_form_id']);
        // Retrieve a new form instance helper
        $form_instance_helper = new VCFF_Forms_Helper_Instance();
        // Generate a new form instance
        $form_instance = $form_instance_helper
            ->Set_Post_ID($post_id)
            ->Set_Form_UUID($form_uuid)
            ->Set_Form_Data($form_data)
            ->Generate();
        // If the form instance could not be created
        if (!$form_instance) { die('could not create form instance'); }
        // Complete setting up the form instance
        $form_instance_helper
            ->Add_Fields()
            ->Add_Containers()
            ->Add_Meta();
        // Retrieve all of the form fields
        $form_fields = $form_instance->fields;
        // The var to store the fields
        $form_fields_json = array();
        // Loop through and get all of the fields
        foreach ($form_fields as $machine_code => $field_instance) {
            // Populate the field name
            $form_fields_json[] = array(
                'machine_code' => $machine_code
            );
        }
        // Encode the meta fields and return
        echo json_encode(array(
            'result' => 'success',
            'fields' => $form_fields_json
        ));
        // Die
        wp_die();
    }
}

global $vcff_forms_admin;

$vcff_forms_admin = new VCFF_Forms_Admin();