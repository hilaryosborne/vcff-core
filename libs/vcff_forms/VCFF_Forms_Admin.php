<?php

class VCFF_Forms_Admin {

    public function __construct() {
        add_filter('vcff_meta_field_list',array($this,'_Filter_Add_Meta_Field_Type'), 15, 2);
        add_filter('vcff_meta_field_list',array($this,'_Filter_Add_Meta_Field_AJAX'), 15, 2);
    }
    
    public function _Filter_Add_Meta_Field_Type($meta_fields, $form_instance) {
		
    }
    
    public function _Filter_Add_Meta_Field_AJAX($meta_fields, $form_instance) {
        // If the form allows for ajax submission
        if (!$form_instance->use_ajax) { return; }
		
    }
    
}

global $vcff_forms_admin;

$vcff_forms_admin = new VCFF_Forms_Admin();