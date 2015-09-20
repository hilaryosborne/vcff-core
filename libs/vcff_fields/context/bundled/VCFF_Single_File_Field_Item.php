<?php

class VCFF_Single_File_Field_Item extends VCFF_Field_Item {
    
    public $is_upload = true;
    
    public $allow_conditions = false;
    
    public function After_Validation() {
        // Return the form instance
        $form_instance = $this->form_instance;
        // If the form is not valid
        if (!$form_instance->is_valid) { return; }
        // If the form is not in a submission state
        if (!$form_instance->is_submission) { return; }
        // Create a new upload helper
        $file_upload_helper = new VCFF_Fields_Helper_Upload();
        // Setup the helper
        $file_upload_helper
            ->Set_Form_Instance($form_instance)
            ->Confirm_Upload($this->posted_value);
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
        $css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $this->attributes);
        // Add css classes
        $css_class = apply_filters('vcff_el_css',$css_class,$this->attributes,$this);
        // Start gathering content
        ob_start();
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Include the template file
        include(vcff_get_file_dir($dir.'/'.get_class($this).".tpl.php"));
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Return the contents
        return $output;
    }
    
    public function _MAX_UPLOAD_SIZE($_config) {
        
    }
    
    public function _ALLOWED_EXTENSIONS($_config) {
        
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
    
    public function Get_Actual_URL() {
        
        $posted_value = $this->posted_value;
        
        if (!isset($posted_value['url'])) { return ''; }
        
        return $posted_value['url'];
    }
    
    public function Get_Allowed_Extensions() {
        
        $validation = $this->Get_Validation();

        if (isset($validation['ALLOWED_EXTENSIONS']) && $validation['ALLOWED_EXTENSIONS']['value']) {
            
            return explode(',',str_replace(' ','',$validation['ALLOWED_EXTENSIONS']['value']));    
        } 
        else { return array('txt'); }
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
        
        $validation = $this->Get_Validation();

        if (isset($validation['MAX_UPLOAD_SIZE']) && $validation['MAX_UPLOAD_SIZE']['value']) {
            
            $supplied_size = $validation['MAX_UPLOAD_SIZE']['value'];
            
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
        else { return $this->_Get_INI_Max_Upload(); }
    } 

    protected function _Get_INI_Max_Upload() {  
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