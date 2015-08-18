<?php

class VCFF_Forms_Helper_Security extends VCFF_Helper {

    protected $form_instance;

    protected $is_failed = false;

	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
    
    public function Issue_Key() {
        // Clean up any old keys
        $this->_Clean_Keys();
        // Generate a unique form key
        $key = md5(rand(0,100).time().rand(0,100));
        // Retrieve the expire after number
        $input_timeout = $this->form_instance->input_timeout;
        // If the form never expires
        if (!$input_timeout) { $input_timeout = 4000; }
        // Add the key to the session
        $_SESSION['vcff_form_keys'][] = array($key,time(),$input_timeout);
        // Store the security key
        $this->form_instance->security_key = $key;
        // Return the new key
        return $key;
    }
    
    protected function _Clean_Keys() {
        // If there are no form keys, return false
        if (!isset($_SESSION['vcff_form_keys'])) { return false; }
        // Retrieve the list of form keys
        $form_keys = $_SESSION['vcff_form_keys'];
        // If there are no form keys, return false
        if (!is_array($form_keys)) { return false; }
        // Loop through each of the form keys
        foreach ($form_keys as $k => $key) {
            // This is not the key you are looking for
            if ($key[1]+$key[2] < time()) { continue; }
            // Remove the key from the session
            //unset($_SESSION['vcff_form_keys'][$k]);
        }
    }
    
    public function _Check_Key() { 
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // If there are no form keys, return false
        if (!$form_instance->Get_Security_Key()) { 
            // Add a session expired alert
            $this->form_instance->Add_Alert('No form security key passed','danger');
            // Flag as security check failed
            $this->is_failed = true;
            // Return out
            return; 
        } 
        // If there are no form keys, return false
        if (!isset($_SESSION['vcff_form_keys']) || !is_array($_SESSION['vcff_form_keys'])) { 
            // Add a session expired alert
            $this->form_instance->Add_Alert('Session expired, please submit form again','danger');
            // Flag as security check failed
            $this->is_failed = true;
            // Return out
            return; 
        }
        // Retrieve the key
        $security_key = $form_instance->Get_Security_Key();
        // Retrieve the list of form keys
        $form_keys = $_SESSION['vcff_form_keys'];
        // Loop through each of the form keys
        foreach ($form_keys as $k => $cached_key) {
            // This is not the key you are looking for
            if ($cached_key[0] != $security_key) { continue; }
            // Remove the key from the session
            unset($_SESSION['vcff_form_keys'][$k]);
            // Return true
            return ;
        }
        // Add a form alert
        $this->form_instance->Add_Alert('Session expired, please submit form again','danger');
        // Return false
        $this->is_failed = true;
    }
    
    public function Check() {
        
        $this->_Check_Key();
        
        return !$this->is_failed ? true : false;
        
    }

}