<?php

class VCFF_Events_Page_Event_List extends VCFF_Page {
    
    public $form_instance;
    
    public $action_instance;
    
    public function __construct() {
        // Action to register the page
        add_action('wp_ajax_vcff_event_list',array($this,'_AJAX_Render'));
        // Action to register the page
        add_action('wp_ajax_vcff_event_list_move',array($this,'_AJAX_Move'));
        // Action to register the page
        add_action('wp_ajax_vcff_event_list_delete',array($this,'_AJAX_Delete'));
        // Action to register the page
        add_action('wp_ajax_vcff_event_list_bulk',array($this,'_AJAX_Bulk'));
    }
    
    public function _AJAX_Bulk() {
        // Retrieve the form instance
        $this->_Build_Form_Instance(); 
        // Retrieve the bulk action
        $bulk_action = $_POST['bulk_action'];
        // Retrieve the bulk events list
        $bulk_events = $_POST['event_list'];
        // If we want to delete selected events
        if ($bulk_action == 'delete') {
            // Trigger the bulk delete method
            $this->_AJAX_Bulk_Delete($bulk_events);
        }
        // Action for bulk actions
        do_action('vcff_events_bulk_actions_do',$bulk_action,$bulk_events,$this);
        // Return the event code
        echo json_encode(array(
            'result' => 'success',
            'alerts' => $this->Get_Alerts_HTML()
        )); wp_die();
    }
    
    protected function _AJAX_Bulk_Delete($bulk_events) {
        // Populate the form instance
        $form_instance = $this->form_instance;
        // Loop through each event
        foreach ($bulk_events as $k => $event_id) {
            // Get an empty action 
            $action_instance = $form_instance
                ->Get_Event($event_id); 
            // If no action could be found
            if (!$action_instance) { continue; }
            // Create a new list helper
            $events_store_helper = new VCFF_Events_Helper_Store();
            // Retrieve the ajax data
            $events_store_helper
                ->Set_Action_Instance($action_instance)
                ->Delete();
        }
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
        $form_instance = $form_instance_helper
            ->Set_Form_UUID($form_uuid)
            ->Set_Form_Contents($_FORM['content'])
            ->Set_Form_Type($_FORM['form_type'])
            ->Generate(); 
        // If the form instance could not be created
        if (!$form_instance) { die('could not create form instance'); }
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
            ->Set_Form_Instance($form_instance)
            ->Populate();
        // Populate the form instance
        $this->form_instance = $form_instance;
    }
    
    public function _AJAX_Move() {
        // Retrieve the form instance
        $this->_Build_Form_Instance(); 
        // Populate the form instance
        $form_instance = $this->form_instance;
        // Retrieve the action list
        $event_list = $_POST['event_list'];
        // If no action list was shown
        if (!$event_list || !is_array($event_list)) { 
            // Return the event code
            echo json_encode(array(
                'result' => 'failed',
            )); wp_die();
        }
        // Loop through each action list
        $i=1; foreach ($event_list as $k => $id) {
            // Retrieve the action instance
            $action_instance = $form_instance->Get_Event($id);
            // If no action instance was returned
            if (!$action_instance || !is_object($action_instance)) { continue; }
            // Set the new instance order
            $action_instance->order = $i;
            // Update the raw data order
            $action_instance->data['order'] = $i;
            // Create a new list helper
            $events_store_helper = new VCFF_Events_Helper_Store();
            // Retrieve the ajax data
            $events_store_helper
                ->Set_Action_Instance($action_instance)
                ->Store();
            // Inc up the order var
            $i++;
        } 
        // Return the event code
        echo json_encode(array(
            'result' => 'success',
        )); wp_die();
    }
    
    public function _AJAX_Delete() {
        // Retrieve the form instance
        $this->_Build_Form_Instance(); 
        // Populate the form instance
        $form_instance = $this->form_instance;
        // Create a new event populator
        $events_populator_helper = new VCFF_Events_Helper_Populator();
        // Populate with the events
        $events_populator_helper
            ->Set_Form_Instance($form_instance);
        // Retrieve the action id
        $action_id = $_POST['action_id'];
        // Get an empty action 
        $action_instance = $form_instance
            ->Get_Event($action_id); 
        // Create a new list helper
        $events_store_helper = new VCFF_Events_Helper_Store();
        // Retrieve the ajax data
        $events_store_helper
            ->Set_Action_Instance($action_instance)
            ->Delete();
        // Return the event code
        echo json_encode(array(
            'result' => 'success',
            'alerts' => $events_store_helper->Get_Alerts_HTML()
        )); wp_die();
    }
    
    public function _AJAX_Render() {
        // Retrieve the form instance
        $this->_Build_Form_Instance(); 
        // Populate the form instance
        $form_instance = $this->form_instance;
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
        // Exit out and die
        wp_die();
    }
}

new VCFF_Events_Page_Event_List();
