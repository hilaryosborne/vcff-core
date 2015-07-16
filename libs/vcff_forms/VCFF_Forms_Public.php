<?php

class VCFF_Forms_Public {

    public function __construct() {
        // AJAX Axtions
        add_action('wp_ajax_form_check_conditions', array($this,'AJAX_Form_Check_Conditions'));
        add_action('wp_ajax_nopriv_form_check_conditions', array($this,'AJAX_Form_Check_Conditions'));
        add_action('wp_ajax_form_check_validation', array($this,'AJAX_Form_Check_Validation'));
        add_action('wp_ajax_nopriv_form_check_validation', array($this,'AJAX_Form_Check_Validation'));
        add_action('wp_ajax_form_ajax_submit', array($this,'AJAX_Form_Ajax_Submit'));
        add_action('wp_ajax_nopriv_form_ajax_submit', array($this,'AJAX_Form_Ajax_Submit'));
        // Form submission action
        add_action('vcff_form_submission', array($this,'Form_Submission'));
    }

    public function AJAX_Form_Check_Conditions() {
        // Decode the form data
        $form_data = base64_decode($_POST['form_data']);
        // Parse the form data
        parse_str($form_data,$output);
        // Retrieve the form uuid
        $form_uuid = vcff_get_uuid_by_form($output['vcff_form_id']);
        // Retrieve the form id
        $post_id = $output['vcff_post_id'];
        // Retrieve a new form instance helper
        $form_instance_helper = new VCFF_Forms_Helper_Instance();
        // Generate a new form instance
        $form_instance = $form_instance_helper
            ->Set_Post_ID($post_id)
            ->Set_Form_UUID($form_uuid)
            ->Set_Form_Data($output)
            ->Generate();
        // Set the form state
        $form_instance->form_state = 'conditional_check';
        // Set the form as ajax
        $form_instance->is_ajax = true;
        // If the form instance could not be created
        if (!$form_instance) { die('could not create form instance'); }
        // Complete setting up the form instance
        $form_instance_helper
            ->Add_Fields()
            ->Add_Containers()
            ->Add_Meta()
            ->Add_Events()
            ->Add_Supports()
            ->Check_Conditions();
        // Create a new trigger helper
        $events_trigger_helper = new VCFF_Events_Helper_Trigger();
        // Check events conditions and trigger
        $events_trigger_helper
            ->Set_Form_Instance($form_instance)
            ->Trigger();
        // Create a new ajax helper
        $form_ajax_helper = new VCFF_Forms_Helper_AJAX();
        // Build the json data array
        $json_data = $form_ajax_helper
            ->Set_Form_Instance($form_instance)
            ->Use_Conditions(true)
            ->Get_JSON_Data();
        // Return the json data
        echo json_encode(array(
            'result' => 'success',
            'form' => $json_data
        )); wp_die();
    }

    public function AJAX_Form_Check_Validation() {
        // Decode the form data
        $form_data = base64_decode($_POST['form_data']);
        // Parse the form data
        parse_str($form_data,$output);
        // Retrieve the form uuid
        $form_uuid = vcff_get_uuid_by_form($output['vcff_form_id']);
        // Retrieve the form id
        $post_id = $output['vcff_post_id']; 
        // Retrieve a new form instance helper
        $form_instance_helper = new VCFF_Forms_Helper_Instance();
        // Generate a new form instance
        $form_instance = $form_instance_helper
            ->Set_Post_ID($post_id)
            ->Set_Form_UUID($form_uuid)
            ->Set_Form_Data($output)
            ->Generate();
        // Set the form state
        $form_instance->form_state = 'validation_check';
        // Set the form instance submission to true
        $form_instance->is_pre_submission = true;
        // Set the form as ajax
        $form_instance->is_ajax = true;
        // If the form instance could not be created
        if (!$form_instance) { die('could not create form instance'); } 
        // Complete setting up the form instance
        $form_instance_helper
            ->Add_Fields()
            ->Add_Containers()
            ->Add_Meta()
            ->Add_Events()
            ->Add_Supports()
            ->Filter()
            ->Check_Conditions()
            ->Check_Validation();
        // Create a new trigger helper
        $events_trigger_helper = new VCFF_Events_Helper_Trigger();
        // Check events conditions and trigger
        $events_trigger_helper
            ->Set_Form_Instance($form_instance)
            ->Trigger();
        // Create a new ajax helper
        $form_ajax_helper = new VCFF_Forms_Helper_AJAX();
        // Build the json data array
        $json_data = $form_ajax_helper
            ->Set_Form_Instance($form_instance)
            ->Use_Conditions(true)
            ->Use_Validation(true)
            ->Get_JSON_Data();  
        // Return the json data
        echo json_encode(array(
            'result' => 'success',
            'form' => $json_data
        )); wp_die();
    }

    public function AJAX_Form_Ajax_Submit() { 
        // Decode the form data
        $form_data = base64_decode($_POST['form_data']);
        // Parse the form data
        parse_str($form_data,$output);
        // Retrieve the form uuid
        $form_uuid = vcff_get_uuid_by_form($output['vcff_form_id']);
        // Retrieve the form id
        $post_id = $output['vcff_post_id'];
        // Retrieve a new form instance helper
        $form_instance_helper = new VCFF_Forms_Helper_Instance();
        // Generate a new form instance
        $form_instance = $form_instance_helper
            ->Set_Post_ID($post_id)
            ->Set_Form_UUID($form_uuid)
            ->Set_Form_Data($output)
            ->Generate();
        // Set the form state 
        $form_instance->form_state = 'submission_ajax';
        // Set the form instance submission to true
        $form_instance->is_submission = true;
        // Set the form as ajax
        $form_instance->is_ajax = true;
        // If the form instance could not be created
        if (!$form_instance) { die('could not create form instance'); }
        // Complete setting up the form instance
        $form_instance_helper
            ->Add_Fields()
            ->Add_Containers()
            ->Add_Meta()
            ->Add_Events()
            ->Add_Supports()
            ->Filter()
            ->Check_Conditions()
            ->Check_Validation();
        // Create a new form security helper
        $form_security_helper = new VCFF_Forms_Helper_Security();
        // Set the form's security key
        $form_instance->Set_Security_Key($output['vcff_key']);
        // Check the submission key
        $security_check = $form_security_helper
            ->Set_Form_Instance($form_instance)
            ->Check();
        // If the key fails to verify
        if (!$security_check) {
            // Create a new ajax helper
            $form_ajax_helper = new VCFF_Forms_Helper_AJAX();
            // Build the json data array
            $json_data = $form_ajax_helper
                ->Set_Form_Instance($form_instance)
                ->Use_Conditions(true)
                ->Use_Validation(true)
                ->Get_JSON_Data();
            // Return the json
            echo json_encode(array(
                'result' => 'failed',
                'form' => $json_data,
                'form_key' => $form_instance->Issue_Security_Key()
            )); wp_die();
        }
        // Create a new cache helper
        $form_cache_helper = new VCFF_Forms_Helper_Cache();
        // Cache the submitted form
        $form_cache_helper
            ->Set_Form_Instance($form_instance)
            ->Cache(); 
        // If the form failed to validate
        if (!$form_instance->Is_Valid()) {
            // Create a new trigger helper
            $events_trigger_helper = new VCFF_Events_Helper_Trigger();
            // Check events conditions and trigger
            $events_trigger_helper
                ->Set_Form_Instance($form_instance)
                ->Trigger();
            // Create a new ajax helper
            $form_ajax_helper = new VCFF_Forms_Helper_AJAX();
            // Build the json data array
            $json_data = $form_ajax_helper
                ->Set_Form_Instance($form_instance)
                ->Use_Conditions(true)
                ->Use_Validation(true)
                ->Get_JSON_Data();
            // Return the json
            echo json_encode(array(
                'result' => 'failed',
                'form' => $json_data,
                'form_key' => $form_instance->Issue_Security_Key()
            )); wp_die();
        }
        // Create a new submission helper
        $form_submission_helper = new VCFF_Forms_Helper_Submission();
        // Submit the form
        $form_submission_helper
            ->Set_Form_Instance($form_instance)
            ->Submit_AJAX();
        // Create a new trigger helper
        $events_trigger_helper = new VCFF_Events_Helper_Trigger();
        // Check events conditions and trigger
        $events_trigger_helper
            ->Set_Form_Instance($form_instance)
            ->Trigger();
        // Create a new ajax helper
        $form_ajax_helper = new VCFF_Forms_Helper_AJAX();
        // Build the json data array
        $json_data = $form_ajax_helper
            ->Set_Form_Instance($form_instance)
            ->Use_Conditions(true)
            ->Use_Validation(true)
            ->Get_JSON_Data();
        // Return the json
        echo json_encode(array(
            'result' => 'success',
            'form' => $json_data,
            'form_key' => $form_instance->Issue_Security_Key()
        )); wp_die();
    }

    public function Form_Submission() {
        // Retrieve the form uuid
        $form_uuid = vcff_get_uuid_by_form($_POST['vcff_form_id']);
        // Retrieve the form id
        $post_id = $_POST['vcff_post_id'];
        // Retrieve a new form instance helper
        $form_instance_helper = new VCFF_Forms_Helper_Instance();
        // Generate a new form instance
        $form_instance = $form_instance_helper
            ->Set_Post_ID($post_id)
            ->Set_Form_UUID($form_uuid)
            ->Set_Form_Data($_POST)
            ->Generate();
        // Set the form state
        $form_instance->form_state = 'submission_standard';
        // Set the form instance submission to true
        $form_instance->is_submission = true;
        // Set the form as ajax
        $form_instance->is_ajax = false;
        // Create a new form security helper
        $form_security_helper = new VCFF_Forms_Helper_Security();
        // Set the form's security key
        $form_instance->Set_Security_Key($_POST['vcff_key']);
        // Check the submission key
        $security_check = $form_security_helper
            ->Set_Form_Instance($form_instance)
            ->Check();
        // If the key fails to verify
        if (!$security_check) { return ; }
        // Create a new cache helper
        $form_cache_helper = new VCFF_Forms_Helper_Cache();
        // Cache the submitted form
        $form_cache_helper
            ->Set_Form_Instance($form_instance)
            ->Cache(); 
        // If the form instance could not be created
        if (!$form_instance) { die('could not create form instance'); }
        // Complete setting up the form instance
        $form_instance_helper
            ->Add_Fields()
            ->Add_Containers()
            ->Add_Meta()
            ->Add_Events()
            ->Add_Supports()
            ->Filter()
            ->Check_Conditions()
            ->Check_Validation();
        // If the form failed to validate
        if (!$form_instance->Is_Valid()) {  
            // Create a new trigger helper
            $events_trigger_helper = new VCFF_Events_Helper_Trigger();
            // Check events conditions and trigger
            $events_trigger_helper
                ->Set_Form_Instance($form_instance)
                ->Trigger();
            // Return out
            return; 
        }
        // Create a new submission helper
        $form_submission_helper = new VCFF_Forms_Helper_Submission();
        // Submit the form
        $form_submission_helper
            ->Set_Form_Instance($form_instance)
            ->Submit_Standard(); 
        // Create a new trigger helper
        $events_trigger_helper = new VCFF_Events_Helper_Trigger();
        // Check events conditions and trigger
        $events_trigger_helper
            ->Set_Form_Instance($form_instance)
            ->Trigger();
    }
}

global $vcff_forms_public;

$vcff_forms_public = new VCFF_Forms_Public();