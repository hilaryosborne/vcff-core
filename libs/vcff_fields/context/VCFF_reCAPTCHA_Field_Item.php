<?php

class VCFF_reCAPTCHA_Field_Item extends VCFF_Field_Item {

    /**
         * RENDER FORM FIELD FOR INPUT (Required)
         * Use shortcode logic, attributes and template files
         * to display the form field shortcode within a form context
         */
    public function Form_Render() { 
        // Convert attrs to vars
        extract(shortcode_atts(array(
            'field_label'=>'',
            'hide_label'=>'',
            'machine_code' => '',
            'placeholder'=>'',
            'default_value'=>'',
            'attributes'=>'',
            'is_disabled'=>'',
            'extra_class'=>'',
            'css'=>'',
        ), $this->attributes));
        // Retrieve the site key
        $recaptcha_site_key = vcff_get_setting_value('recaptcha_site_key');
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
    
    public function Post_Value() { 
        // Retrieve the form data
        $form_data = $this->form_instance->post_data; 
        // Retrieve the posted value
        $this->posted_value = $form_data['g-recaptcha-response'];
    }
    
    public function Do_Validation() { 
        // Retrieve the posted value
        $posted_value = $this->posted_value;
        // If posted value is not an array
        if (!$posted_value) { 
            // Add an alert for this field
            $this->Add_Alert('Please complete reCAPTCHA check','danger');
            // Set the field validation to false
            $this->is_valid = false; 
            // Return out    
            return; 
        }
        // Retrieve the site key
        $recaptcha_secret_key = vcff_get_setting_value('recaptcha_secret_key');
        // Determine the target service url
        $api_url = 'https://www.google.com/recaptcha/api/siteverify';
        // Start the curl instance
        $curl = curl_init($api_url);
        // Populate the section
        $params = array(
            'secret' => $recaptcha_secret_key,
            'response' => $posted_value,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        ); 
        // Set the curl parameters
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, count($params));
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
        // Make the api call
        $curl_response = curl_exec($curl);
        // Decode into a json object
        $curl_json = json_decode($curl_response);
        // If the validation failed to validate
        if (!$curl_json || !is_object($curl_json) || !$curl_json->success) {
            // Set the field validation to false
            $this->is_valid = false;
            // Add an alert for this field
            $this->Add_Alert('Invalid reCAPTCHA Response','danger');
        }
    }

}