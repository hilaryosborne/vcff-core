<?php

class Event_Clear_Item extends VCFF_Event_Item {
	
	public function Render() {
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

    public function Trigger() {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        
        $form_instance->Add_Filter('ajax',array($this,'_AJAX_Filter'));
        
        $form_instance->Add_Action('vcff_form_after_result',array($this,'_Clear_Form'));
    }
    
    public function _AJAX_Filter($value,$args) {
        
        $value['events']['clear'] = true;
        
        return $value;
    }
    
    public function _Clear_Form() {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Retrieve the fields from the form instance
        $fields = $form_instance->fields;
        // If there are no fields
        if (!$fields || !is_array($fields)) { return; }
        // Loop through each 
        foreach ($fields as $machine_code => $field_instance) {
            // Set the field instance to null
            $field_instance->posted_value = null;
        }
    }
}
