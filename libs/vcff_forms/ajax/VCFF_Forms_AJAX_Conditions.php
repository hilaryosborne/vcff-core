<?php

class VCFF_Forms_AJAX_Conditions {
    
    public function __construct() {
        add_action('wp_ajax_form_check_conditions', array($this,'_AJAX_Conditions'));
        add_action('wp_ajax_nopriv_form_check_conditions', array($this,'_AJAX_Conditions'));
    }
    
    public function _AJAX_Conditions() { 
        // Parse the form data
        parse_str(base64_decode($_POST['form_data']),$output);
        // Do actions for ajax
        do_action('vcff_pre_ajax',$output);
        do_action('vcff_pre_ajax_conditions',$output);
        // PREPARE PHASE
        $form_prepare_helper = new VCFF_Forms_Helper_Prepare(); 
        // Get the form instance
        $form_instance = $form_prepare_helper
            ->Get_Form(array(
                'post_id' => $output['vcff_post_id'],
                'uuid' => vcff_get_uuid_by_form($output['vcff_form_id']),
                'data' => $output,
                'is_ajax' => true,
                'state' => 'conditional_check'
            ));
        // If the form instance could not be created
        if (!$form_instance) { die('could not create form instance'); } 
        // POPULATE PHASE
        $form_populate_helper = new VCFF_Forms_Helper_Populate();
        // Run the populate helper
        $form_populate_helper
            ->Set_Form_Instance($form_instance)
            ->Populate(array()); 
        // CALCULATE PHASE
        $form_calculate_helper = new VCFF_Forms_Helper_Calculate();
        // Initiate the calculate helper
        $form_calculate_helper
            ->Set_Form_Instance($form_instance)
            ->Calculate(array(
                'validation' => false,
                'origin' => false
            ));
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
            ->Result(array(
                'exit_out' => true
            ));
    }
    
}

new VCFF_Forms_AJAX_Conditions();