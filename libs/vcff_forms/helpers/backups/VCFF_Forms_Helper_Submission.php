<?php

class VCFF_Forms_Helper_Submission extends VCFF_Helper {

	protected $form_instance;	
	
	protected $error;
	
	public function Get_Error() {
		
		return $this->error;
	}

	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
	
    public function Submit_Standard() {
        // If no form instance was returned
        if (!isset($this->form_instance)) { return false; }
		// Retrieve the form instance
        $form_instance = $this->form_instance;
        // Do required actions
        do_action('vcff_form_submit_standard',$form_instance);
        // If the active field check fails
        if (!$form_instance->Is_Valid()) {
            // Complete the form
            $form_instance->Submission_Failed();
            // Do required actions
            do_action('vcff_form_submit_failed_standard',$form_instance);
            do_action('vcff_form_submit_failed',$form_instance);
        } // Otherwise if the submission works 
        elseif ($form_instance->Is_Valid()) {
            // Complete the form
            $form_instance->Submission_Passed();
            // Do required actions
            do_action('vcff_form_submit_passed_standard',$form_instance);
            do_action('vcff_form_submit_passed',$form_instance);
            // Do any redirects
            $this->_Do_Standard_Redirects();
        }
    }
    
    protected function _Do_Standard_Redirects() {
        // If no form instance was returned
        if (!isset($this->form_instance)) { return false; }
		// Retrieve the form instance
        $form_instance = $this->form_instance;
        // Retrieve the submission result
        $submission_result = $form_instance->result_submission; 
        // If there is no submission result
        if (!is_array($submission_result)) { return; }
        // If there are no submission messages
        if (!isset($submission_result['redirect'])) { return; }
        // Retrieve the redirect params
        $redirect_params = $submission_result['redirect'];
        // Extract the required vars
        $redirect_url = $redirect_params['url'];
        $redirect_method = $redirect_params['method'];
        $redirect_query = $redirect_params['query'];
        // If the redirect is a get redirect
        if ($redirect_method == 'get') {
            // Calculate the 
            $get_url = $redirect_query ? $redirect_url.'?'.$redirect_query : $redirect_url;
            // Redirect to the new page
            header('Location: '.$get_url);
            // Exit wordpress
            wp_die();
         
        } elseif ($redirect_method == 'post') {
            // Calculate the 
            $post_url = $redirect_url;
            // Explode the query args against &
            $query_args = explode('&',$redirect_query);
            // The var to store hidden fields within
            $hidden_fields = array();
            // If a list of query args was returned
            if ($query_args && is_array($query_args)) {
                // Loop through each query arg
                foreach ($query_args as $k => $arg) {
                    // Explode against the = sign
                    $arg_exploded = explode('=',$arg);
                    // Populate the hidden fields with the key value
                    $hidden_fields[$arg_exploded[0]] = $arg_exploded[1];
                }
            }
            // Start gathering content
            ob_start();
            // Include the template file
            include(vcff_get_file_dir(VCFF_FORMS_DIR.'/templates/VCFF_Post_Redirect.tpl.php'));
            // Get contents
            $output = ob_get_contents();
            // Clean up
            ob_end_clean();
            // Return the contents
            echo $output;
            // Exit wordpress
            wp_die();
        }
    }
    
    public function Submit_AJAX() { 
        // If no form instance was returned
        if (!isset($this->form_instance)) { return false; }
		// Retrieve the form instance
        $form_instance = $this->form_instance; 
        // Do required actions
        do_action('vcff_form_submit_ajax',$form_instance);
        // If the active field check fails
        if (!$form_instance->Is_Valid()) { 
            // Complete the form
            $form_instance->Submission_Failed();
            // Do required actions
            do_action('vcff_form_submit_failed_ajax',$form_instance);
            do_action('vcff_form_submit_failed',$form_instance);
        } // Otherwise if the submission works 
        elseif ($form_instance->Is_Valid()) { 
            // Complete the form
            $form_instance->Submission_Passed();
            // Do required actions
            do_action('vcff_form_submit_passed_ajax',$form_instance);
            do_action('vcff_form_submit_passed',$form_instance);
        }
    }

}