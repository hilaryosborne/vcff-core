<?php

class VCFF_Fields_Helper_Upload {

    protected $form_instance;	

    protected $machine_code;
    
    protected $form_key;
    
	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
    
    public function Set_Form_Key($form_key) {
    
        $this->form_key = $form_key;
		
		return $this;
    }
    
    public function Set_Field_Name($machine_code) {
    
        $this->machine_code = $machine_code;
		
		return $this;
    }
    
    public function Upload() {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Retrieve the field name
        $machine_code = $this->machine_code;
        // Retrieve the form key
        $form_key = $this->form_key;
        // If no field instance returned
        if (!$machine_code) { return 'no field name provided'; }
        // Return the field instance attached to this field
        $field_instance = $form_instance->Get_Field($machine_code);
        // If no field instance returned
        if (!$field_instance || !is_object($field_instance)) { return 'field not found'; }
        // Check to make sure this field accepts form uploads
        if (!isset($field_instance->is_upload) || !$field_instance->is_upload) { return 'field is not upload enabled'; }
        // If no files information can be found
        if (!isset($_FILES['file_upload']['tmp_name'])) { return  'no file upload found'; }
        // Create a new file info resource
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        // Retrieve the file mimetype
        $file_mime_type = finfo_file($finfo, $_FILES['file_upload']['tmp_name']);
        // Retrieve the allowed mimetypes
        $allowed_extensions = $field_instance->Get_Allowed_Extensions();
        // Retrieve the mimetype list
        $mimetypes = $field_instance->Get_Mime_Types();
        // If the file type could not be found
        if (!in_array($file_mime_type,$mimetypes)) { return 'file type not allowed'; }
        // Retrieve the extension
        $extension = array_search($file_mime_type,$mimetypes);
        // If the file type could not be found
        if (!in_array($extension,$allowed_extensions)) { return 'file type not allowed'; }
        // Retrieve the file object
        $file_obj = new SplFileInfo($_FILES['file_upload']['tmp_name']);
        // Retrieve the kb size
        $file_size = $file_obj->getSize()/1000; 
        // Retrieve the max file size
        $allowed_file_size = $field_instance->Get_Allowed_Size();
        // If the file type could not be found
        if ($file_size > $allowed_file_size) { return 'file is too large'; }
        // Retrieve the max file size
        $prepare_dir = $field_instance->Prepare_Upload($form_key,$file_obj,$_FILES['file_upload']['name'],$extension);
        // If the directory is not writable
        if (!$prepare_dir) { return 'could not create writable upload directory'; }
        
        $upload = $field_instance->Upload_File($_FILES['file_upload']['tmp_name']);
        // If the directory is not writable
        if (!$upload) { return 'could not upload file'; }
        
        return $upload;
    }
    
    
}