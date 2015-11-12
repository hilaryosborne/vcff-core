<?php

function vcff_map_context($classname) {
    // Retrieve the global vcff forms class
    $vcff_curly = vcff_get_library('vcff_curly');
    
    $vcff_curly->context[] = $classname;
}


function vcff_curly_editor_textarea($form_instance,$machine_code,$field_value) {
    
    $curly_helper = new VCFF_Curly_Helper_Builder();
    
    return $curly_helper
        ->Set_Form_Instance($form_instance)
        ->Get_Textarea_Field($machine_code,$field_value);
}

function vcff_curly_editor_textfield($form_instance,$machine_code,$field_value) {
    
    $curly_helper = new VCFF_Curly_Helper_Builder();
    
    return $curly_helper
        ->Set_Form_Instance($form_instance)
        ->Get_Textfield_Field($machine_code,$field_value);
}

function vcff_curly_compile($form_instance,$content) {

    $curly_helper = new VCFF_Curly_Helper_Builder();
    
    return $curly_helper
        ->Set_Form_Instance($form_instance)
        ->Set_Content($content)
        ->Compile();
}