<?php

class VCFF_Fields_AJAX_Upload extends VCFF_Page {
    
    public function __construct() {

        add_action('wp_ajax_vcff_field_upload', array($this,'_Process'));
        add_action('wp_ajax_nopriv_vcff_field_upload', array($this,'_Process'));
    }
    
    public function _Process() { 
        // Retrieve the flag action
        $ajax_action = $_REQUEST['ajax_action'];
        // Retrieve the flag action
        $ajax_code = $_REQUEST['ajax_code'];
        // Determine which action to take
        switch ($ajax_action) {
            case 'upload' : $this->_AJAX_Upload($ajax_code); break;
            case 'remove' : $this->_AJAX_Remove($ajax_code); break;
        }
    }
    
    protected function _Build_Form_Instance() {
        // Retrieve the form id
        $form_uuid = $_POST['vcff_form_uuid'];
        // Retrieve the form id
        $field_machine_name = $_POST['machine_name'];
        // PREPARE PHASE
        $form_prepare_helper = new VCFF_Forms_Helper_Prepare();
        // Get the form instance
        $form_instance = $form_prepare_helper
            ->Get_Form(array(
                'uuid' => $form_uuid
            ));
        // If the form instance could not be created
        if (!$form_instance) { die('could not create form instance'); }
        // POPULATE PHASE
        $form_populate_helper = new VCFF_Forms_Helper_Populate();
        // Run the populate helper
        $form_populate_helper
            ->Set_Form_Instance($form_instance)
            ->Populate(array());
        // CALCULATE PHASE
        $form_calculate_helper = new VCFF_Forms_Helper_Calculate();
        // Initiate the calculate helper
        $form_calculate_helper
            ->Set_Form_Instance($form_instance)
            ->Calculate(array(
                'validation' => false
            ));
        // If the form instance could not be created
        if (!$form_instance) { 
            // Add the field message
            $this->Add_Alert('Internal Error (Could not create form instance)','danger');
            // End with an AJAX error response
            $this->_AJAX_Error();
        }
        // Store the form instance
        $this->form_instance = $form_instance;
    }
    
    public function _AJAX_Upload($ajax_code) {
        // Retrieve the form instance
        $this->_Build_Form_Instance();
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Retrieve the machine name
        $field_instance = $form_instance
            ->Get_Field($ajax_code);
        // If the form instance could not be created
        if (!$field_instance) {
            // Add the field message
            $this->Add_Alert('Internal Error (Could not retrieve the field)','danger');
            // End with an AJAX error response
            $this->_AJAX_Error();
        }
        // Create a new upload helper
        $file_upload_helper = new VCFF_Fields_Helper_Upload();
        // Setup the helper
        $file_upload_helper
            ->Set_Form_Instance($form_instance);
        // Check to make sure this field accepts form uploads
        if (!isset($field_instance->is_upload) || !$field_instance->is_upload) { return 'field is not upload enabled'; }
        // If no files information can be found
        if (!isset($_FILES['file_upload']['tmp_name'])) { 
            // Add the field message
            $this->Add_Alert('No file upload found','danger');
            // End with an AJAX error response
            $this->_AJAX_Error();
        }
        // Create a new file info resource
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        // Retrieve the file mimetype
        $file_mime_type = finfo_file($finfo, $_FILES['file_upload']['tmp_name']); 
        // Retrieve the allowed mimetypes
        $allowed_extensions = $field_instance
            ->Get_Allowed_Extensions();
        // Retrieve the mimetype list
        $mimetypes = $file_upload_helper->Get_Mime_Types();  
        // The found extension list
        $ext_list = array();
        // Loop through each mimetype
        foreach ($mimetypes as $_ext => $_mime) {
            // If the mimes do not match
            if ($_mime != $file_mime_type) { continue; }
            // Store the extension
            $ext_list[] = $_ext;
        } 
        // Retrieve the supplied name
        $file_name = sanitize_file_name($_FILES['file_upload']['name']);
        // Retrieve the file extension
        $extension = pathinfo($file_name, PATHINFO_EXTENSION);
        // If the file type could not be found
        if (!in_array($extension,$ext_list)) { 
            // Add the field message
            $this->Add_Alert('File type not allowed','danger');
            // End with an AJAX error response
            $this->_AJAX_Error();
        }
        // If the file type could not be found
        if (!in_array($extension,$allowed_extensions)) { 
            // Add the field message
            $this->Add_Alert('File type not allowed','danger');
            // End with an AJAX error response
            $this->_AJAX_Error();
        }
        // Retrieve the filesize
        $file_size = filesize($_FILES['file_upload']['tmp_name']);
        // Get the allowed file size
        $allowed_file_size = $field_instance->Get_Allowed_Filesize();
        // If the file type could not be found
        if ($file_size > $allowed_file_size) { 
            // Add the field message
            $this->Add_Alert('File is too large, '.$allowed_file_size.' allowed','danger');
            // End with an AJAX error response
            $this->_AJAX_Error();
        }
        // Retrieve the upload path
        $upload_path = $file_upload_helper->Get_Upload_Path();
        // Retrieve the upload url
        $upload_url = $file_upload_helper->Get_Upload_URL();
        // Retrieve the upload path
        $tmp_upload_path = $file_upload_helper->Get_Tmp_Upload_Path();
        // If the dir is not writable
        if (!is_writable($upload_path)) { 
            // Add the field message
            $this->Add_Alert('Upload Directory is not writable','danger');
            // End with an AJAX error response
            $this->_AJAX_Error();
        } 
        // Start the safe filename
        $raw_filename = pathinfo($file_name, PATHINFO_FILENAME);
        // Keep looking flag
        $search = true;
        // Loop through each 
        while($search == true) {
            // Create a possible filename
            $possible_filename = $file_upload_helper->Get_Prefix().'_'.$raw_filename.'.'.$extension; 
            // If the file does not exist, stop looking
            if (!is_file($upload_path.'/'.$possible_filename) && !is_file($tmp_upload_path.'/'.$possible_filename)) { $search = false; }
        }
        // Set the final filename
        $final_filename = sanitize_file_name($possible_filename);
        // Move the uploaded filename
        move_uploaded_file($_FILES['file_upload']['tmp_name'], $tmp_upload_path.'/'.$final_filename);
        // Encode the meta fields and return
        echo base64_encode(json_encode(array(
            'result' => 'success',
            'data' => array(
                'original' => $raw_filename,
                'filename' => $final_filename,
                'location' => $upload_path.'/'.$final_filename,
                'url' => $upload_url.'/'.$final_filename,
            )
        ))); wp_die();
    }
    
    protected function _AJAX_Error($data=array()) {
        // Encode the meta fields and return
        echo base64_encode(json_encode(array(
            'result' => 'failed',
            'alerts' => $this->Get_Alerts_HTML(),
            'data' => $data
        ))); wp_die();
    }
    
    public function _AJAX_Remove($ajax_code) {
        // Retrieve the form instance
        $this->_Build_Form_Instance();
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Retrieve the machine name
        $field_instance = $form_instance
            ->Get_Field($ajax_code);
        // If the form instance could not be created
        if (!$field_instance) {
            // Add the field message
            $this->Add_Alert('Internal Error (Could not retrieve the field)','danger');
            // End with an AJAX error response
            $this->_AJAX_Error();
        }
        // Create a new upload helper
        $file_upload_helper = new VCFF_Fields_Helper_Upload();
        // Setup the helper
        $file_upload_helper
            ->Set_Form_Instance($form_instance);
        // Retrieve the upload path
        $tmp_upload_path = $file_upload_helper->Get_Tmp_Upload_Path();
        // Retrieve the filename
        $filename = sanitize_file_name($_POST['filename']);
        // Check if the file exists
        if (!is_file($tmp_upload_path.'/'.$filename)) {
            // Encode the meta fields and return
            echo json_encode(array(
                'result' => 'failed',
                'data' => array()
            )); wp_die();
        }
        // Remove the file
        unlink($tmp_upload_path.'/'.$filename);
        // Encode the meta fields and return
        echo json_encode(array(
            'result' => 'success',
            'data' => array()
        )); wp_die();
    }
    
}

new VCFF_Fields_AJAX_Upload();