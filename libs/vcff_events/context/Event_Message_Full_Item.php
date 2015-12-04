<?php

class Event_Message_Full_Item extends VCFF_Event_Item {
	
    public $_html;
    
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
        include($action_dir.'/Event_Message_Full_Item.tpl.php');
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Return the contents
        return $output;
    }
    
    public function Check_Validation() {

        $action_instance = $this->action_instance;

        if (!$this->Get_Message()) {
            // Add an alert to notify of field requirements
            $this->validation_errors['message'] = true;
        }

        if (!is_array($this->validation_errors)) { return; }
        
        if (count($this->validation_errors) == 0) { return; }
        
        $action_instance->is_valid = false;
    }
    
    protected function Get_Message() {
        
        if (!isset($this->value['message'])) { return; }
        
        return $this->value['message'];
    }
    
    public function Trigger() {
        
        $form_instance = $this->form_instance;
        
        $content = vcff_curly_compile($this->form_instance,$this->Get_Message());
        
        $this->_html = $content; 
    
        $form_instance->Add_Filter('ajax',array($this,'_AJAX_Filter'));
        
        $form_instance->Add_Filter('render',array($this,'_Standard_Filter'));
    }
    
    public function _AJAX_Filter($value,$args) {
        
        $value['events']['full_message'][] = $this->_html;
        
        return $value;
    }
    
    public function _Standard_Filter($value,$args) {
    
        return $this->_html;
    }
    
}
