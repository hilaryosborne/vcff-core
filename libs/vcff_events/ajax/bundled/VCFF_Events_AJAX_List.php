<?php

class VCFF_Events_AJAX_List {
    
    public function __construct() {

        add_action('wp_ajax_vcff_events_ajax_list', array($this,'_Process'));
    }
    
    public function _Process() { 
        // Retrieve the flag action
        $ajax_action = $_REQUEST['ajax_action'];
        // Retrieve the flag action
        $ajax_code = $_REQUEST['ajax_code'];
        // Determine which action to take
        switch ($ajax_action) {
            case 'bulk' : $this->_AJAX_Bulk($ajax_code); break;
            case 'move' : $this->_AJAX_Move(); break;
            case 'delete' : $this->_AJAX_Delete(); break;
        }
    }

    protected function _Build_Form_Instance() {
        // Parse the form data
        parse_str(base64_decode($_POST['form_data']),$output);
        // Retrieve the form uuid
        $form_uuid = vcff_get_uuid_by_form($output['post_ID']);
        // If there is no form type and form id
        if (!$output['form_type'] && $output['post_ID']) {
            // Get the saved vcff form type
            $meta_form_type = get_post_meta($output['post_ID'], 'form_type',true);
        } // Otherwise use the passed form type 
        else { $meta_form_type = $output['form_type']; }
        // PREPARE PHASE
        $form_prepare_helper = new VCFF_Forms_Helper_Prepare();
        // Get the form instance
        $form_instance = $form_prepare_helper
            ->Get_Form(array(
                'uuid' => $form_uuid,
                'contents' => $output['content'],
                'type' => $meta_form_type,
            ));
        // If the form instance could not be created
        if (!$form_instance) { die('could not create form instance'); }
        // POPULATE PHASE
        $form_populate_helper = new VCFF_Forms_Helper_Populate();
        // Run the populate helper
        $form_populate_helper
            ->Set_Form_Instance($form_instance)
            ->Populate(array(
                'meta_values' => $output
            ));
        // CALCULATE PHASE
        $form_calculate_helper = new VCFF_Forms_Helper_Calculate();
        // Initiate the calculate helper
        $form_calculate_helper
            ->Set_Form_Instance($form_instance)
            ->Calculate(array(
                'validation' => false
            ));
        // Populate the form instance
        $this->form_instance = $form_instance;
    }

    public function _AJAX_Bulk($ajax_code) {
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
            'result' => 'success'
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
    
}

new VCFF_Events_AJAX_List();