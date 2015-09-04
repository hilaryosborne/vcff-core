<?php

class VCFF_Single_File_Field_Upload extends VCFF_Helper {
    
    public $form_instance;

    public $field_instance;
    
    public function __construct() {
    
        add_action('wp_ajax_vcff_single_field_upload', array($this,'_AJAX_Upload'));
        
        add_action('wp_ajax_vcff_single_field_remove', array($this,'_AJAX_Remove'));
    }
    
    
}

new VCFF_Single_File_Field_Upload();