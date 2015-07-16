<?php

class Event_Redirect_Item extends VCFF_Event_Item {
	
	public function Render() {
        // Retrieve any validation errors
        $validation_errors = $this->validation_errors;
        // Retrieve the context director
        $action_dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // If context data was passed
        $posted_data = $this->data;
        // Start gathering content
        ob_start();
        // Include the template file
        include($action_dir.'/'.get_class($this).'.tpl.php');
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Return the contents
        return $output;
    }
    
    public function Check_Validation() {

        $action_instance = $this->action_instance;

        if (!$this->_Get_Redirect_URL()) {
            // Add an alert to notify of field requirements
            $this->validation_errors['url'] = true;
        }
        
        if (!$this->_Get_Redirect_Method()) {
            // Add an alert to notify of field requirements
            $this->validation_errors['method'] = true;
        }

        if (!is_array($this->validation_errors)) { return; }
        
        if (count($this->validation_errors) == 0) { return; }
        
        $action_instance->is_valid = false;
    }
    
    protected function _Get_Redirect_URL() {

        if (!isset($this->value['url'])) { return; }
        
        return $this->value['url'];
    }
    
    protected function _Get_Redirect_Method() {

        if (!isset($this->value['method'])) { return; }
        
        return $this->value['method'];
    }
    
    protected function _Get_Redirect_Query() {
    
        if (!isset($this->value['query'])) { return; }
        
        return $this->value['query'];
    }
    
    public function Trigger() {
        
        $form_instance = $this->form_instance;
        
        $redirect_params = vcff_curly_compile($this->form_instance,$this->_Get_Redirect_Query());
        
        $redirect_url = $this->_Get_Redirect_URL();
        
        $redirect_method = $this->_Get_Redirect_Method();
        
        $form_instance->Add_Redirect($redirect_url,$redirect_method,$redirect_params);
    }
}
