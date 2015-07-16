<?php

class VCFF_Fields_Admin {

    public function __construct() {
        
        add_action('wp_ajax_field_condition_list', array($this,'AJAX_Field_Condition_List'));  
        
        $this->_Load_Parameters();
    }
    
    protected function _Load_Parameters() {
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_FIELDS_DIR.'/parameters') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // If this is not false
            if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; }
            // Include the file
            require_once(VCFF_FIELDS_DIR.'/parameters/'.$FileInfo->getFilename());
            // Retrieve the classname
            $param_classname = $FileInfo->getBasename('.php');
            // Create a new instance of the param
            new $param_classname();
        }
    }
    
    public function AJAX_Field_Condition_List() {
        // Decode the form data
        $form_data = base64_decode($_POST['form_data']);
        // Parse the form data
        parse_str($form_data,$output);
        // Retrieve the form id
        $form_id = $output['vcff_form_id'];
        // Retrieve the form id
        $post_id = $output['vcff_post_id'];
        // Retrieve a new form instance helper
        $form_instance_helper = new VCFF_Forms_Helper_Instance();
        // Generate a new form instance
        $form_instance = $form_instance_helper
            ->Set_Post_ID($post_id)
            ->Set_Form_ID($form_id)
            ->Set_Form_Data($form_data)
            ->Generate();
        // If the form instance could not be created
        if (!$form_instance) { die('could not create form instance'); }
        // Complete setting up the form instance
        $form_instance_helper
            ->Add_Fields()
            ->Add_Containers()
            ->Add_Meta()
            ->Add_Supports();
        // Retrieve all of the form fields
        $form_fields = $form_instance->fields;
        // The var to store the fields
        $form_fields_json = array();
        // Loop through and get all of the fields
        foreach ($form_fields as $machine_code => $field_instance) {
            // If the field does not allow conditions
            if (!$field_instance->Get_Allowed_Conditions()) { continue; }
            // Populate the field name
            $form_fields_json[] = array(
                'machine_code' => $field_instance->Get_Name(),
                'field_label' => $field_instance->Get_Label(),
                'field_conditions' => $field_instance->Get_Allowed_Conditions()
            );
        }
        // Encode the meta fields and return
        echo json_encode(array(
            'result' => 'success',
            'fields' => $form_fields_json
        ));
        // Die
        wp_die();
    }
}

global $vcff_fields_admin;

$vcff_fields_admin = new VCFF_Fields_Admin();