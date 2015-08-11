<?php

class VCFF_Forms_Public {

    public function __construct() {
        // Form submission action
        add_action('vcff_form_submission', array($this,'Form_Submission'));
    }

    public function Form_Submission() {
        // PREPARE PHASE
        $form_prepare_helper = new VCFF_Forms_Helper_Prepare();
        // Get the form instance
        $form_instance = $form_prepare_helper
            ->Get_Form(array(
                'post_id' => $_POST['vcff_post_id'],
                'uuid' => $_POST['vcff_form_uuid'],
                'data' => $_POST,
                'state' => 'submission_standard',
                'is_submission' => true
            ));
        // If the form instance could not be created
        if (!$form_instance) { die('could not create form instance'); }
        // POPULATE PHASE
        $form_populate_helper = new VCFF_Forms_Helper_Populate();
        // Run the populate helper
        $form_populate_helper
            ->Set_Form_Instance($form_instance)
            ->Populate(array(
                'fields_values' => $_POST
            ));
        // CALCULATE PHASE
        $form_calculate_helper = new VCFF_Forms_Helper_Calculate();
        // Initiate the calculate helper
        $form_calculate_helper
            ->Set_Form_Instance($form_instance)
            ->Calculate(array());
        // REVIEW PHASE
        $form_review_helper = new VCFF_Forms_Helper_Review();
        // Initiate the calculate helper
        $form_review_helper
            ->Set_Form_Instance($form_instance)
            ->Review(array());
        // FINALIZE PHASE
        $form_finalize_helper = new VCFF_Forms_Helper_Finalize();
        // Initiate the calculate helper
        $form_finalize_helper
            ->Set_Form_Instance($form_instance)
            ->Finalize(array());
        // DISPLAY PHASE
        $form_display_helper = new VCFF_Forms_Helper_Result();
        // Initiate the calculate helper
        $form_display_helper
            ->Set_Form_Instance($form_instance)
            ->Result(array());
        // Retrieve the global vcff forms class
        $vcff_forms = vcff_get_library('vcff_forms');
        // Create a simple form cache id
        $form_cache_id = $form_instance->post_id ? $form_instance->post_id.'_'.$form_instance->form_uuid : $form_instance->form_uuid ;
        // Add to the cached forms
        $vcff_forms->cached_forms[$form_cache_id] = $form_instance;
    }
}

global $vcff_forms_public;

$vcff_forms_public = new VCFF_Forms_Public();