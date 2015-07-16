<?php

class VCFF_Events_Page_Event_Form extends VCFF_Page {
    
    public $form_instance;
    
    public $action_instance;
    
    public function __construct() {
        
        add_action('wp_ajax_vcff_event_modify',array($this,'_AJAX_Render'));
        
        add_action('wp_ajax_vcff_event_save',array($this,'_AJAX_Save'));
    }
    
    protected function _Build_Form_Instance() {
        // Decode the form data
        $_FORM = array();
        // Parse the form data
        parse_str(base64_decode($_POST['form_data']),$_FORM);
        // Retrieve the form uuid
        $form_uuid = vcff_get_uuid_by_form($_FORM['post_ID']);
        // Retrieve a new form instance helper
        $form_instance_helper = new VCFF_Forms_Helper_Instance();
        // Generate a new form instance
        $this->form_instance = $form_instance_helper
            ->Set_Form_UUID($form_uuid)
            ->Set_Form_Contents($_FORM['content'])
            ->Set_Form_Type($_FORM['form_type'])
            ->Generate(); 
        // If the form instance could not be created
        if (!$this->form_instance) { die('could not create form instance'); }
        // Complete setting up the form instance
        $form_instance_helper
            ->Add_Fields()
            ->Add_Containers()
            ->Add_Meta($_FORM)
            ->Add_Supports(); 
        // Create a new event populator
        $events_populator_helper = new VCFF_Events_Helper_Populator();
        // Populate with the events
        $events_populator_helper
            ->Set_Form_Instance($this->form_instance)
            ->Populate();
        
        $form_instance = $this->form_instance;
        // If an action id was provided
        if ($_POST['action_id']) {
            // Retrieve the action id
            $action_id = $_POST['action_id'];
            // Get an empty action 
            $action_instance = $form_instance
                ->Get_Event($action_id); 
            // If no action instance was returned
            if (!$action_instance) {
                // Create a new instance helper
                $events_helper_instance = new VCFF_Events_Helper_Instance();
                // Create an instance instance from the posted data
                $this->action_instance = $events_helper_instance
                    ->Set_Form_Instance($this->form_instance)
                    ->Build(array());
            } // Otherwise load the action instance
            else {   
                // Update the update flag
                $action_instance->is_update = true;
                // Save the action instance
                $this->action_instance = $action_instance; 
            }
        } // Otherwise if we are creating a new instance 
        else {
            // Create a new instance helper
            $events_helper_instance = new VCFF_Events_Helper_Instance();
            // Create an instance instance from the posted data
            $this->action_instance = $events_helper_instance
                ->Set_Form_Instance($this->form_instance)
                ->Build(array());
        }
    }
    
    public function _AJAX_Save() {
        // Retrieve the form instance
        $this->_Build_Form_Instance();
        // Decode the form data
        $_FORM = array();
        // Parse the form data
        parse_str(base64_decode($_POST['form_data']),$_FORM);
        // Populate the form instance
        $form_instance = $this->form_instance;
        // Create a new validation helper
        $events_validation_helper = new VCFF_Events_Helper_Validation(); 
        // Create a new instance helper
        $events_helper_instance = new VCFF_Events_Helper_Instance();
        // Create an instance instance from the posted data
        $this->action_instance = $events_helper_instance
            ->Set_Form_Instance($this->form_instance)
            ->Build($_FORM['event_action']); 
        // Update the update flag
        $this->action_instance->is_update = true;
        // Check the action instance
        $events_validation_helper
            ->Set_Action_Instance($this->action_instance)
            ->Check();
        // If the action instance is not valid
        if (!$this->action_instance->Is_Valid()) {
            // Add the error message
            $this->action_instance->Add_Alert('There was a problem updating the action','danger');
            // Return the event code
            echo json_encode(array(
                'result' => 'failed',
                'alerts' => $this->action_instance->Get_Alerts_HTML(),
                'data' => array(
                    'form' => $this->action_instance->Render()
                )
            )); wp_die();
        }
        // Create a new list helper
        $events_store_helper = new VCFF_Events_Helper_Store();
        // Retrieve the ajax data
        $events_store_helper
            ->Set_Action_Instance($this->action_instance)
            ->Store();
        // Return the event code
        echo json_encode(array(
            'result' => 'success',
            'alerts' => $this->action_instance->Get_Alerts_HTML()
        )); wp_die();
    }
    
    public function _AJAX_Render() {
        // Retrieve the form instance
        $this->_Build_Form_Instance();
        // Populate the form instance
        $form_instance = $this->form_instance;
        // Populate the form instance
        $action_instance = $this->action_instance;
        // Retrieve the context director
        $tmp_dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Start gathering content
        ob_start();
        // Include the template file
        include(vcff_get_file_dir($tmp_dir.'/'.get_class($this).".tpl.php"));
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Return the contents
        echo $output;
        
        exit();
    }
}

new VCFF_Events_Page_Event_Form();
