<?php

class VCFF_Forms_Helper_Session {

    protected $form_instance;	

	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
    
    public function Get_Key() {
        // Retrieve the post data
        $post_data = $this->form_instance->post_data; 
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // If a session key has been provided via post data
        if (isset($post_data['vcff_session_key']) && $post_data['vcff_session_key']) {
            // Generate the new session key
            $form_instance->session_key = $post_data['vcff_session_key'];
        } // Otherwise generate a new session key
        else { $form_instance->session_key = md5(uniqid()); }
    }
    
    public function Get_Data() {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // If this is not this form
        if (!$form_instance->session_key) { return; }
        // If there is no session data, skip storing any session information
        if (!isset($_SESSION['vcff'][$form_instance->session_key])) { return; }
        // Return the session data
        return $_SESSION['vcff'][$form_instance->session_key];
    }

    public function Update() {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // If this is not this form
        if (!$form_instance->session_key) { return $this; }
        // Retrieve the session data
        $session_data = $form_instance->session_data;
        // If there is no session data, skip storing any session information
        if (!$session_data || !is_array($session_data) || count($session_data) == 0) { 
            // If this is currently some session information
            if (isset($_SESSION['vcff'][$form_instance->session_key])) {
                // Remove the current form's session informatino
                unset($_SESSION['vcff'][$form_instance->session_key]);
            } 
            // Return out
            return $this; 
        }
        // Create the storage data
        $store_data = $form_instance->session_data;
        // Update the last accessed value
        $store_data['last_accessed'] = time();
        // Update the last accessed value
        $store_data['form_data'] = $form_instance->post_data;
        // Update the session data
        $_SESSION['vcff'][$form_instance->session_key] = $store_data;
        // Return out
        return $this; 
    }

}