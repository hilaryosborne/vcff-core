<?php

class VCFF_Single_File_Field_Item extends VCFF_Field_Item {
    
    public $is_upload = true;
    
    public $allow_conditions = false;
    
    public function _Post_Form_Validation($form_instance) {
        // If the form is not valid
        if (!$form_instance->is_valid) { return; }
        // If the form is not in a submission state
        if (!$form_instance->is_submission) { return; }
        // Create a new field upload helper
        $upload_helper = new VCFF_Single_File_Field_Upload();
        // Set the form instance
        $upload_helper->form_instance = $form_instance;
        // Set the field instance
        $upload_helper->field_instance = $this;
        // Retrieve the field's posted value
        $posted_value = $this->posted_value;
        // Confirm the upload
        $upload_helper->Confirm_Upload($posted_value);
    } 
    
    public function Is_Required() { 
        
        $rule = $this->Get_Validation_Rule('file_upload_required');
        
        return $rule ? true : false;
    }
    
    public function _Val_Required() {
        // Retrieve the posted value
        if (!$this->Get_Actual_Location()) {
            // Set the validation flag to false
            $this->is_valid = false;
            // Create the error string
            $error_string = 'You must upload a file';
            // Update the post value with the sanitized version
            $this->result_validation = array(
                'result' => 'failed',
                'message' => $error_string,
            );
            // Add a danger alert for this field
            $this->Add_Alert($error_string,'danger');
        }
    }
    
    public function Form_Render() {  
        // Convert attrs to vars
        extract(shortcode_atts(array(
            'field_label'=>'',
            'hide_label'=>'',
            'machine_code' => '',
            'extra_class'=>'',
            'css'=>'',
        ), $this->attributes));
        // Compile the css class
        $css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts);
        // Start gathering content
        ob_start();
        // Include the template file
        include(vcff_get_file_dir(VCFF_FIELDS_DIR.'/context/'.get_class($this).".tpl.php"));
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Return the contents
        return $output;
    }
    
    public function Get_Original_Filename() {
        
        $posted_value = $this->posted_value;
        
        if (!isset($posted_value['original'])) { return ''; }
        
        return $posted_value['original'];
    }
    
    public function Get_Actual_Filename() {
        
        $posted_value = $this->posted_value;
        
        if (!isset($posted_value['name'])) { return ''; }
        
        return $posted_value['name'];
    }
    
    public function Get_Actual_Location() {
        
        $posted_value = $this->posted_value;
        
        if (!isset($posted_value['location'])) { return ''; }
        
        return $posted_value['location'];
    }
    
    public function Get_Allowed_Extensions() {
        
        $rule = $this->Get_Validation_Rule('file_upload_extensions');

        if (!$rule) { return array('txt'); }
        
        if (!isset($rule['param']['value'])) { return array('txt'); }
        
        if (!$rule['param']['value']) { return array('txt'); }

        $extensions = explode(',',str_replace(' ','',$rule['param']['value']));
        
        $extension_list = array();
        
        foreach ($extensions as $k => $extension) {
            
            $extension_list[] = $extension;
        }
        
        return $extension_list;
    }
    
    public function Get_Display_Filesize() {
        $allowed_filesize = $this->Get_Allowed_Filesize();
        
        if ($allowed_filesize < 1000) {
            return $allowed_filesize.'b';
        } elseif ($allowed_filesize < 1000000) {
            return floor($allowed_filesize/1000).'kb';
        } elseif ($allowed_filesize < 1000000000) {
            return number_format($allowed_filesize/1000000,2).'mb';
        } else {
            return number_format($allowed_filesize/1000000000,2).'gig';
        }
        
    }
    
    public function Get_Allowed_Filesize() {
       
        $rule = $this->Get_Validation_Rule('file_upload_max_size');

        if (!$rule) { return $this->_Get_INI_Max_Upload(); }
        
        if (!isset($rule['param']['value'])) { return $this->_Get_INI_Max_Upload(); }

        if (!$rule['param']['value']) { return $this->_Get_INI_Max_Upload(); }
        
        $supplied_size = $rule['param']['value'];
        
        $supplied_size_bytes = 0;
        
        $max_allowed = $this->_Get_INI_Max_Upload();
        
        $int_val = (int)filter_var($supplied_size, FILTER_SANITIZE_NUMBER_INT);
        
        if (stripos($supplied_size,'kb') !== false) {
            
            $supplied_size_bytes = round($int_val*1000);
        } 
        elseif (stripos($supplied_size,'mb') !== false) {
            
            $supplied_size_bytes = round($int_val*1000000);
        } 
        else { $supplied_size_bytes = $int_val; }
        
        return $supplied_size_bytes > $max_allowed ? $max_allowed : $supplied_size_bytes ;
    } 

    protected function _Get_INI_Max_Upload()  
    {  
        $To_Size = function($max_size) {
            $_suffix = substr($max_size, -1);  
            $_value = substr($max_size, 0, -1);  
            switch(strtoupper($_suffix)){  
                case 'P':  
                $_value *= 1024;  
                case 'T':  
                $_value *= 1024;  
                case 'G':  
                $_value *= 1024;  
                case 'M':  
                $_value *= 1024;  
                case 'K':  
                $_value *= 1024;  
                break;  
            }  
            return $_value;  
        };
        
        $max_post = $To_Size(ini_get('post_max_size'));
        $max_upload = $To_Size(ini_get('upload_max_filesize'));
        
        return min($max_post,$max_upload);
    }  
}

add_filter('vcff_settings_group_list',function($group_list, $form_instance){
    
    $group_list[] = array(
        'id' => 'field_upload_config',
        'title' => 'File Upload Configuration',
        'weight' => 5,
        'hint_html' => '<h4><strong>Instructions</strong></h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur cursus erat at lectus commodo tempor eget vel turpis. Praesent vitae eros semper, aliquet ipsum vel, porttitor tellus.</p>',
        'help_url' => 'http://vcff.theblockquote.com',
    );
    
    return $group_list;
    
},0,2);

add_filter('vcff_settings_field_list',function($field_list, $form_instance){
    
    $field_list[] = array(
        'machine_code' => 'field_upload_structure',
        'field_label' => 'Folder Structure',
        'field_group' => 'field_upload_config',
        'weight' => 6,
        'field_type' => 'select',
        'values' => array(
            '' => 'Select Folder Structure',
            'NONE' => 'None',
            'YYMMDD' => 'Year / Month / Day',
            'YYMM' => 'Year / Month',
            'YY' => 'Year'
        )
    );
    
    $field_list[] = array(
        'machine_code' => 'field_upload_dir',
        'field_label' => 'Upload Directory',
        'field_group' => 'field_upload_config',
        'weight' => 1,
        'field_type' => 'textfield',
        'field_dependancy' => false
    );
    
    $field_list[] = array(
        'machine_code' => 'field_upload_temp_dir',
        'field_label' => 'Temp Directory',
        'field_group' => 'field_upload_config',
        'weight' => 2,
        'field_type' => 'textfield',
        'field_dependancy' => false
    );
    
    return $field_list;
    
},0,2);