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
        
        $this->redirect_url = vcff_curly_compile($this->form_instance,$this->_Get_Redirect_URL());
        
        $this->redirect_method = $this->_Get_Redirect_Method();
        
        $this->redirect_params = vcff_curly_compile($this->form_instance,$this->_Get_Redirect_Query());
        
        $form_instance->Add_Filter('ajax',array($this,'_AJAX_Filter'));
        
        $form_instance->Add_Filter('render',array($this,'_Standard_Filter'));
    }
    
    public function _AJAX_Filter($value,$args) {
        
        $value['events']['redirect'] = array(
            'method' => $this->redirect_method,
            'url' => $this->redirect_url,
            'params' => $this->redirect_params
        );
        
        return $value;
    }
    
    public function _Standard_Filter($output) {
         // If the redirect is a get redirect
        if ($this->redirect_method == 'get') {
            // Calculate the 
            $get_url = $this->redirect_params ? $this->redirect_url.'?'.$this->redirect_params : $this->redirect_url;
            // Redirect to the new page
            header('Location: '.$get_url);
            // Exit wordpress
            wp_die();
         
        } elseif ($this->redirect_method == 'post') {
            // Calculate the 
            $post_url = $this->redirect_url;
            // Explode the query args against &
            $query_args = explode('&',$this->redirect_params);
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
            // Retrieve the context director
            $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
            // Include the template file
            include(vcff_get_file_dir($dir.'/'.get_class($this)."_HTML.tpl.php"));
            // Get contents
            $output = ob_get_contents();
            // Clean up
            ob_end_clean();
            // Return the contents
            return $output;
        }
    }
}
