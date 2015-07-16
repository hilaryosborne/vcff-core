<?php

class VCFF_Events_Admin {

    public function __construct() {
        
        $this->_Load_Pages(); 
        
        add_action('admin_init',function(){
            add_action('wp_ajax_vcff_action_list',array($this,'AJAX_Action_List'));
            add_action('wp_ajax_vcff_action_list_ordering',array($this,'AJAX_Action_List_Ordering'));
            add_action('wp_ajax_vcff_action_list_delete',array($this,'AJAX_Action_Bulk_Delete'));
            add_action('wp_ajax_vcff_action_form_new',array($this,'AJAX_Action_Form_New'));
            add_action('wp_ajax_vcff_action_form_update',array($this,'AJAX_Action_Form_Update')); 
            add_action('wp_ajax_vcff_action_delete',array($this,'AJAX_Action_Delete'));
            add_action('wp_ajax_vcff_action_create',array($this,'AJAX_Action_Create'));
            add_action('wp_ajax_vcff_action_update',array($this,'AJAX_Action_Update'));
            add_action('vcff_form_import_export_do',array($this,'_Hook_Export'));
            add_action('vcff_form_import_upload_do',array($this,'_Hook_Import_Upload'));
            
            add_filter('vcff_meta_page_list',array($this,'_Filter_Add_Meta_Pages'), 15, 2);
            add_filter('vcff_meta_group_list',array($this,'_Filter_Add_Meta_Groups'), 15, 2);
            add_filter('vcff_meta_field_list',array($this,'_Filter_Add_Meta_Fields'), 15, 2);
        });
        
        add_action('vcff_form_export_form_inputs',array($this,'_Hook_Export_Fields'));
        add_action('vcff_form_import_form_inputs',array($this,'_Hook_Import_Fields'));
        
    }
    
    protected function _Load_Pages() { 
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_EVENTS_DIR.'/pages') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // If this is not false
            if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; }
            // Include the file
            require_once(VCFF_EVENTS_DIR.'/pages/'.$FileInfo->getFilename());
        }
    }
    
    public function _Filter_Add_Meta_Pages($meta_pages, $form_instance) {

        $meta_pages[] = array(
			'id' => 'events',
			'title' => 'Events',
			'weight' => 10,
			'description' => 'This new page contains some settings',
			'icon' => '',
		);
        
        return $meta_pages;
    }
    
    public function _Filter_Add_Meta_Groups($meta_groups, $form_instance) {
    
        $meta_groups[] = array(
			'id' => 'submission_events',
			'page_id' => 'events',
			'title' => 'Submission Events',
			'weight' => 2,
			'hint_html' => '<h4><strong>Event Management</strong></h4><p>A form event is an action which takes place either while submitting a form, validating a form\'s input or checking a form\'s conditional rules. To create a form event click the create new event button. To modify an existing event click on the event name in the table to the left.</p>',
            'help_url' => 'http://vcff.theblockquote.com',
		);
        
        return $meta_groups;
    }
    
    public function _Filter_Add_Meta_Fields($meta_fields, $form_instance) {
    
        $meta_fields[] = array(
			'machine_code' => 'events_wizard',
			'field_label' => 'Submission Events',
			'field_group' => 'submission_events',
			'field_type' => 'events_wizard',
			'field_dependancy' => false
		);
        
        return $meta_fields;
    }
    
    public function _Hook_Export($export_helper) {
        // If we want to export the settings
        if (!isset($export_helper->settings['export_events'])) { return; }
        // Retrieve the selected form ids
        $forms = $export_helper->export['forms'];
        // If there are no forms, return out
        if (!$forms || !is_array($forms)) { return; }
        // Loop through each form
        foreach ($forms as $form_uuid => $export_data) {
            // Retrieve a new form instance helper
            $form_instance_helper = new VCFF_Forms_Helper_Instance();
            // Generate a new form instance
            $form_instance = $form_instance_helper
                ->Set_Form_UUID($form_uuid)
                ->Generate();
            // If the form instance could not be created
            if (!$form_instance) { continue; }
            // Complete setting up the form instance
            $form_instance_helper
                ->Add_Fields()
                ->Add_Containers()
                ->Add_Meta()
                ->Add_Events()
                ->Add_Supports();
            // If this form has no meta, continue on
            if (!$form_instance->events || !is_array($form_instance->events)) { continue; }
            // Loop through each meta instance
            foreach ($form_instance->events as $k => $action_instance) {
                // Add the fields to the form meta array
                $export_helper->export['forms'][$form_uuid]['form_events'][] = $action_instance->data;
            }
        }
    }
    
    public function _Hook_Export_Fields($page) {
        // Compile the setting html
        $html = '<div class="checkbox">';
        $html .= '  <label>';
        $html .= '      <input type="checkbox" name="settings[export_events]" value="y" checked="checked"> Export Events';
        $html .= '  </label>';
        $html .= '</div>';
        // Echo the html
        echo $html;
    }
    
    public function _Hook_Import_Fields($page) {
        // Compile the setting html
        $html = '<div class="checkbox">';
        $html .= '  <label>';
        $html .= '      <input type="checkbox" name="settings[import_events]" value="y" checked="checked"> Import Events';
        $html .= '  </label>';
        $html .= '</div>';
        // Echo the html
        echo $html;
    }
    
    public function _Hook_Import_Upload($import_helper) {
        // If we want to export the settings
        if (!isset($import_helper->settings['import_events'])) { return; }
        // Extract the events to import
        $import_forms = $import_helper->import['forms'];
        // Loop through each meta instance
         $u=0; $c=0; foreach ($import_forms as $k => $form_data) {
            // If there are no events
            if (!isset($form_data['form_events'])) { continue; }
            // If there are no events, continue on
            if (!is_array($form_data['form_events'])) { continue; }
            // Retrieve the list of importable events
            $import_events = $form_data['form_events'];
            // Retrieve a new form instance helper
            $form_instance_helper = new VCFF_Forms_Helper_Instance();
            // Generate a new form instance
            $form_instance = $form_instance_helper
                ->Set_Form_UUID($form_data['form_uuid'])
                ->Generate();
            // If the form instance could not be created
            if (!$form_instance) { continue; }
            // Complete setting up the form instance
            $form_instance_helper
                ->Add_Fields()
                ->Add_Containers()
                ->Add_Meta()
                ->Add_Events()
                ->Add_Supports();
            // Loop through each importable event
            foreach ($import_events as $k => $event_data) {
                // Attempt to retrieve the current action
                $current_action = $form_instance->Get_Event($event_data['id']);
                // Create a new instance helper
                $events_helper_instance = new VCFF_Events_Helper_Instance();
                // Create an instance instance from the posted data
                $action_instance = $events_helper_instance
                    ->Set_Form_Instance($form_instance)
                    ->Build($event_data);
                // Create a new list helper
                $events_store_helper = new VCFF_Events_Helper_Store();
                // Retrieve the ajax data
                $events_store_helper
                    ->Set_Action_Instance($action_instance)
                    ->Store();
                // Update the relevant flag
                if ($current_action) { $u++; } else { $c++; }
            }
        }
        // Add a success note
        if ($c > 0) { $import_helper->Add_Alert('<strong>Success!</strong> ... '.$c.' Events Imported','success'); }
        
        if ($u > 0) { $import_helper->Add_Alert('<strong>Success!</strong> ... '.$u.' Events Updated','success'); }
    }
    
    public function AJAX_Action_Bulk_Delete() {
        // Retrieve the form id
        $form_id = $_POST['form_id'];
        // Retrieve the form uuid
        $form_uuid = vcff_get_uuid_by_form($form_id);
        // If the form could not be found
        if (!$form_uuid) { 
            // Return the event code
            echo json_encode(array(
                'result' => 'failed',
            )); wp_die();
        }
        // Retrieve a new form instance helper
        $form_instance_helper = new VCFF_Forms_Helper_Instance();
        // Generate a new form instance
        $form_instance = $form_instance_helper
            ->Set_Form_UUID($form_uuid)
            ->Generate(); 
        // If the form instance could not be created
        if (!$form_instance) { die('could not create form instance'); }
        // Complete setting up the form instance
        $form_instance_helper
            ->Add_Fields()
            ->Add_Containers()
            ->Add_Meta()
            ->Add_Supports();
        // Create a new event populator
        $events_populator_helper = new VCFF_Events_Helper_Populator();
        // Populate with the events
        $events_populator_helper
            ->Set_Form_Instance($form_instance)
            ->Populate();
        // Retrieve the action list
        $action_list = $_POST['action_list'];
        // If no action list was shown
        if (!$action_list || !is_array($action_list)) { 
            // Return the event code
            echo json_encode(array(
                'result' => 'failed',
            )); wp_die();
        }
        // Loop through each action list
        foreach ($action_list as $k => $id) {
            // Get an empty action 
            $action_instance = $events_populator_helper
                ->Get_Action($id);
            // If no action instance was returned
            if (!$action_instance) { continue; }
            // Create a new list helper
            $events_store_helper = new VCFF_Events_Helper_Store();
            // Retrieve the ajax data
            $events_store_helper
                ->Set_Action_Instance($action_instance)
                ->Delete();
        } 
        // Return the event code
        echo json_encode(array(
            'result' => 'success',
        )); wp_die();
    }
    
    public function AJAX_Action_List_Ordering() {
        // Retrieve the form id
        $form_id = $_POST['form_id'];
        // Retrieve the form uuid
        $form_uuid = vcff_get_uuid_by_form($form_id);
        // If the form could not be found
        if (!$form_uuid) { 
            // Return the event code
            echo json_encode(array(
                'result' => 'failed',
            )); wp_die();
        }
        // Retrieve a new form instance helper
        $form_instance_helper = new VCFF_Forms_Helper_Instance();
        // Generate a new form instance
        $form_instance = $form_instance_helper
            ->Set_Form_UUID($form_uuid)
            ->Generate(); 
        // If the form instance could not be created
        if (!$form_instance) { die('could not create form instance'); }
        // Complete setting up the form instance
        $form_instance_helper
            ->Add_Fields()
            ->Add_Containers()
            ->Add_Meta()
            ->Add_Supports();
        // Create a new event populator
        $events_populator_helper = new VCFF_Events_Helper_Populator();
        // Populate with the events
        $events_populator_helper
            ->Set_Form_Instance($form_instance)
            ->Populate();
        // Retrieve the action list
        $action_list = $_POST['action_list'];
        // If no action list was shown
        if (!$action_list || !is_array($action_list)) { 
            // Return the event code
            echo json_encode(array(
                'result' => 'failed',
            )); wp_die();
        }
        // Loop through each action list
        $i=1; foreach ($action_list as $k => $id) {
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

    public function AJAX_Action_List() {
        // Decode the form data
        $form_data = base64_decode($_REQUEST['form_data']);
        // Parse the form data
        parse_str($form_data,$output);
        // Update the request array with
        $_REQUEST = array_merge($_REQUEST,$output);
        // Retrieve the form uuid
        $form_uuid = vcff_get_uuid_by_form($_REQUEST['post_ID']);
        // Retrieve the form id
        $form_content = $_REQUEST['content'];
        // If there is no form type and form id
        $meta_form_type = $_REQUEST['form_type']; 
        // Retrieve a new form instance helper
        $form_instance_helper = new VCFF_Forms_Helper_Instance();
        // Generate a new form instance
        $form_instance = $form_instance_helper
            ->Set_Form_UUID($form_uuid)
            ->Set_Form_Contents($form_content)
            ->Set_Form_Type($meta_form_type)
            ->Generate(); 
        // If the form instance could not be created
        if (!$form_instance) { die('could not create form instance'); }
        // Complete setting up the form instance
        $form_instance_helper
            ->Add_Fields()
            ->Add_Containers()
            ->Add_Meta($_REQUEST)
            ->Add_Supports();  
        // Create a new event populator
        $events_populator_helper = new VCFF_Events_Helper_Populator();
        // Populate with the events
        $events_populator_helper
            ->Set_Form_Instance($form_instance)
            ->Populate();
        // Start gathering content
        ob_start();
        // Include the template file
        include(vcff_get_file_dir(VCFF_EVENTS_DIR.'/templates/Meta_Events_List.tpl.php'));
        // Get contents
        $html = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Return the event code
        echo json_encode(array(
            'result' => 'success',
            'data' => $html
        )); wp_die();
    }
    
    public function AJAX_Action_Form_New() {
        // Decode the form data
        $form_data = base64_decode($_REQUEST['form_data']);
        // Parse the form data
        parse_str($form_data,$output);
        // Update the request array with
        $_REQUEST = array_merge($_REQUEST,$output);
        // Retrieve the form uuid
        $form_uuid = vcff_get_uuid_by_form($_REQUEST['post_ID']);
        // Retrieve the form id
        $form_content = $_REQUEST['content'];
        // If there is no form type and form id
        $meta_form_type = $_REQUEST['form_type'];
        // Retrieve a new form instance helper
        $form_instance_helper = new VCFF_Forms_Helper_Instance();
        // Generate a new form instance
        $form_instance = $form_instance_helper
            ->Set_Form_UUID($form_uuid)
            ->Set_Form_Contents($form_content)
            ->Set_Form_Type($meta_form_type)
            ->Generate(); 
        // If the form instance could not be created
        if (!$form_instance) { die('could not create form instance'); }
        // Complete setting up the form instance
        $form_instance_helper
            ->Add_Fields()
            ->Add_Containers()
            ->Add_Meta($_REQUEST)
            ->Add_Supports();
        // Create a new event populator
        $events_populator_helper = new VCFF_Events_Helper_Populator();
        // Populate with the events
        $events_populator_helper
            ->Set_Form_Instance($form_instance)
            ->Populate();
        // Create a new instance helper
        $events_helper_instance = new VCFF_Events_Helper_Instance();
        // Create an instance instance from the posted data
        $action_instance = $events_helper_instance
            ->Set_Form_Instance($form_instance)
            ->Build(array());
        // Return the event code
        echo json_encode(array(
            'result' => 'success',
            'data' => array(
                'form' => $action_instance->Render(),
                'js' => $action_instance->Get_JS_Assets()
            )
        )); wp_die();
    }
    
    public function AJAX_Action_Form_Update() {
        // Decode the form data
        $form_data = base64_decode($_REQUEST['form_data']); 
        // Parse the form data
        parse_str($form_data,$output);
        // Update the request array with
        $_REQUEST = array_merge($_REQUEST,$output);
        // Retrieve the form uuid
        $form_uuid = vcff_get_uuid_by_form($_REQUEST['post_ID']);
        // Retrieve the form id
        $form_content = $_REQUEST['content'];
        // If there is no form type and form id
        $meta_form_type = $_REQUEST['form_type'];
        // Retrieve a new form instance helper
        $form_instance_helper = new VCFF_Forms_Helper_Instance(); 
        // Generate a new form instance
        $form_instance = $form_instance_helper
            ->Set_Form_UUID($form_uuid)
            ->Set_Form_Contents($form_content)
            ->Set_Form_Type($meta_form_type)
            ->Generate();
        // If the form instance could not be created
        if (!$form_instance) { die('could not create form instance'); }
        // Complete setting up the form instance
        $form_instance_helper
            ->Add_Fields()
            ->Add_Containers()
            ->Add_Meta($_REQUEST)
            ->Add_Supports();
        // Create a new event populator
        $events_populator_helper = new VCFF_Events_Helper_Populator();
        // Populate with the events
        $events_populator_helper
            ->Set_Form_Instance($form_instance)
            ->Populate();
        // Retrieve the action id
        $action_id = $_REQUEST['action_id'];
        // Get an empty action 
        $action_instance = $events_populator_helper
            ->Get_Action($action_id);
        // Update the update flag
        $action_instance->is_update = true;
        // If no action instance could be retrieved                
        if (!$action_instance || !is_object($action_instance)) { die('no action instance found'); }
        // Return the event code
        echo json_encode(array(
            'result' => 'success',
            'data' => array(
                'form' => $action_instance->Render(),
                'js' => $action_instance->Get_JS_Assets()
            )
        )); wp_die();
    }
    
    public function AJAX_Action_Delete() {
        // Decode the form data
        $form_data = base64_decode($_REQUEST['form_data']);
        // Parse the form data
        parse_str($form_data,$output);
        // Update the request array with
        $_REQUEST = array_merge($_REQUEST,$output);
        // Retrieve the form uuid
        $form_uuid = vcff_get_uuid_by_form($_REQUEST['post_ID']);
        // Retrieve the form id
        $form_content = $_REQUEST['content'];
        // If there is no form type and form id
        $meta_form_type = $_REQUEST['form_type'];
        // Retrieve a new form instance helper
        $form_instance_helper = new VCFF_Forms_Helper_Instance();
        // Generate a new form instance
        $form_instance = $form_instance_helper
            ->Set_Form_UUID($form_uuid)
            ->Set_Form_Contents($form_content)
            ->Set_Form_Type($meta_form_type)
            ->Generate(); 
        // If the form instance could not be created
        if (!$form_instance) { die('could not create form instance'); }
        // Complete setting up the form instance
        $form_instance_helper
            ->Add_Fields()
            ->Add_Containers()
            ->Add_Meta($_REQUEST)
            ->Add_Supports();
        // Create a new event populator
        $events_populator_helper = new VCFF_Events_Helper_Populator();
        // Populate with the events
        $events_populator_helper
            ->Set_Form_Instance($form_instance)
            ->Populate();
        // Retrieve the action id
        $action_id = $_REQUEST['action_id'];
        // Get an empty action 
        $action_instance = $events_populator_helper
            ->Get_Action($action_id);
        // Create a new list helper
        $events_store_helper = new VCFF_Events_Helper_Store();
        // Retrieve the ajax data
        $events_store_helper
            ->Set_Action_Instance($action_instance)
            ->Delete();
        // Return the event code
        echo json_encode(array(
            'result' => 'success',
            'alerts' => $events_model_helper->Get_Alerts_HTML()
        )); wp_die();
    }
    
    public function AJAX_Action_Create() {
        // Decode the form data
        $form_data = base64_decode($_REQUEST['form_data']); 
        // Parse the form data
        parse_str($form_data,$output);
        // Update the request array with
        $_REQUEST = array_merge($_REQUEST,$output);
        // Retrieve the form uuid
        $form_uuid = vcff_get_uuid_by_form($_REQUEST['post_ID']);
        // Retrieve the form id
        $form_content = $_REQUEST['content'];
        // If there is no form type and form id
        $meta_form_type = $_REQUEST['form_type'];
        // Retrieve a new form instance helper
        $form_instance_helper = new VCFF_Forms_Helper_Instance();
        // Generate a new form instance
        $form_instance = $form_instance_helper
            ->Set_Form_UUID($form_uuid)
            ->Set_Form_Contents($form_content)
            ->Set_Form_Type($meta_form_type)
            ->Generate(); 
        // If the form instance could not be created
        if (!$form_instance) { die('could not create form instance'); }
        // Complete setting up the form instance
        $form_instance_helper
            ->Add_Fields()
            ->Add_Containers()
            ->Add_Meta($_REQUEST)
            ->Add_Supports();
        // Create a new instance helper
        $events_helper_instance = new VCFF_Events_Helper_Instance();
        // Create an instance instance from the posted data
        $action_instance = $events_helper_instance
            ->Set_Form_Instance($form_instance)
            ->Build($_REQUEST['event_action']);
        // Update the update flag
        $action_instance->is_update = true;
        // Create a new validation helper
        $events_validation_helper = new VCFF_Events_Helper_Validation();    
        // Check the action instance
        $events_validation_helper
            ->Set_Action_Instance($action_instance)
            ->Check();
        // If the action instance is not valid
        if (!$action_instance->Is_Valid()) {
            // Add the error message
            $action_instance->Add_Alert('There was a problem updating the action','danger');
            // Return the event code
            echo json_encode(array(
                'result' => 'failed',
                'alerts' => $action_instance->Get_Alerts_HTML(),
                'data' => array(
                    'form' => $action_instance->Render(),
                    'js' => $action_instance->Get_JS_Assets()
                )
            )); wp_die();
        }
        // Create a new list helper
        $events_store_helper = new VCFF_Events_Helper_Store();
        // Retrieve the ajax data
        $events_store_helper
            ->Set_Action_Instance($action_instance)
            ->Store();
        // Return the event code
        echo json_encode(array(
            'result' => 'success',
            'alerts' => $action_instance->Get_Alerts_HTML()
        )); wp_die();
    }
    
    public function AJAX_Action_Update() {
        // Decode the form data
        $form_data = base64_decode($_REQUEST['form_data']); 
        // Parse the form data
        parse_str($form_data,$output);
        // Update the request array with
        $_REQUEST = array_merge($_REQUEST,$output);
        // Retrieve the form uuid
        $form_uuid = vcff_get_uuid_by_form($_REQUEST['post_ID']);
        // Retrieve the form id
        $form_content = $_REQUEST['content'];
        // If there is no form type and form id
        $meta_form_type = $_REQUEST['form_type'];
        // Retrieve a new form instance helper
        $form_instance_helper = new VCFF_Forms_Helper_Instance();
        // Generate a new form instance
        $form_instance = $form_instance_helper
            ->Set_Form_UUID($form_uuid)
            ->Set_Form_Contents($form_content)
            ->Set_Form_Type($meta_form_type)
            ->Generate(); 
        // If the form instance could not be created
        if (!$form_instance) { die('could not create form instance'); }
        // Complete setting up the form instance
        $form_instance_helper
            ->Add_Fields()
            ->Add_Containers()
            ->Add_Meta($_REQUEST)
            ->Add_Supports();
        // Create a new instance helper
        $events_helper_instance = new VCFF_Events_Helper_Instance();
        // Create an instance instance from the posted data
        $action_instance = $events_helper_instance
            ->Set_Form_Instance($form_instance)
            ->Build($_REQUEST['event_action']);
        // Update the update flag
        $action_instance->is_update = true;
        // Create a new validation helper
        $events_validation_helper = new VCFF_Events_Helper_Validation();    
        // Check the action instance
        $events_validation_helper
            ->Set_Action_Instance($action_instance)
            ->Check();
        // If the action instance is not valid
        if (!$action_instance->Is_Valid()) {
            // Add the error message
            $action_instance->Add_Alert('There was a problem updating the action','danger');
            // Return the event code
            echo json_encode(array(
                'result' => 'failed',
                'alerts' => $action_instance->Get_Alerts_HTML(),
                'data' => array(
                    'form' => $action_instance->Render(),
                    'js' => $action_instance->Get_JS_Assets()
                )
            )); wp_die();
        }
        // Create a new list helper
        $events_store_helper = new VCFF_Events_Helper_Store();
        // Retrieve the ajax data
        $events_store_helper
            ->Set_Action_Instance($action_instance)
            ->Store();
        // Return the event code
        echo json_encode(array(
            'result' => 'success',
            'alerts' => $action_instance->Get_Alerts_HTML()
        )); wp_die();
    }
}

global $vcff_events_admin;

$vcff_events_admin = new VCFF_Events_Admin();
