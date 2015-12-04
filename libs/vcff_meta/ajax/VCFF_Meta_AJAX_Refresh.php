<?php

class VCFF_Meta_AJAX_Refresh {
    
    public function __construct() {
    
        add_action('wp_ajax_form_meta_fields_refresh', array($this,'_AJAX_Refresh'));
    }
    
    public function _AJAX_Refresh() {
        // Parse the form data
        parse_str(base64_decode($_POST['form_data']),$output);
        // Retrieve the form uuid
        $form_uuid = vcff_get_uuid_by_form($output['post_ID']);
        // If there is no form type and form id
        if (!$output['form_type'] && $output['post_ID']) {
            // Get the saved vcff form type
            $meta_form_type = get_post_meta($output['post_ID'], 'form_type',true);
        } // Otherwise use the passed form type 
        else { $meta_form_type = $output['form_type']; }
        // PREPARE PHASE
        $form_prepare_helper = new VCFF_Forms_Helper_Prepare();
        // Get the form instance
        $form_instance = $form_prepare_helper
            ->Get_Form(array(
                'uuid' => $form_uuid,
                'contents' => $output['content'],
                'type' => $meta_form_type,
            ));
        // If the form instance could not be created
        if (!$form_instance) { die('could not create form instance'); }
        // POPULATE PHASE
        $form_populate_helper = new VCFF_Forms_Helper_Populate();
        // Run the populate helper
        $form_populate_helper
            ->Set_Form_Instance($form_instance)
            ->Populate(array(
                'meta_values' => $output
            ));
        // CALCULATE PHASE
        $form_calculate_helper = new VCFF_Forms_Helper_Calculate();
        // Initiate the calculate helper
        $form_calculate_helper
            ->Set_Form_Instance($form_instance)
            ->Calculate(array(
                'validation' => false
            ));
        // Create a new validation helper
        $meta_validation_helper = new VCFF_Meta_Helper_Validation();
        // Check the meta for validation
        $meta_validation_helper
            ->Set_Form_Instance($form_instance)
            ->Check();
        // Create new meta helper
        $form_meta_helper = new VCFF_Meta_Helper_AJAX();
        // Retrieve the json data
        $_json = $form_meta_helper
            ->Set_Form_Instance($form_instance)
            ->Get_JSON_Data();
        // Encode the meta fields and return
        echo json_encode(array(
            'result' => 'success',
            'data' => $_json
        )); wp_die();
    }
    
}

new VCFF_Meta_AJAX_Refresh();