<?php

class VCFF_Param_Validation {

    public function __construct() {

        add_shortcode_param('vcff_validation', array($this, 'render'), VCFF_FIELDS_URL.'/assets/admin/vcff_param_validation.js' );
        // Register the vcff admin css
        vcff_admin_enqueue_style('vcff_param_validation', VCFF_FIELDS_URL.'/assets/admin/vcff_param_validation.css', array(), '20120608', 'all');
    }

    protected function Get_Validation_Rules() {
        // Retrieve the field shortcode
        $shortcode = $_POST['tag'];
        // Return the rule list
        return VCFF_PARAM_VAL::Get_Rules($shortcode);
    }

    public function render($settings, $value) {  
        // Set the instance settings
        $this->settings = $settings;
        // Set the instance value
        $this->value = $value;
        // Parse the stored vars
        $stored_rules = json_decode(base64_decode($this->value),true); 
        // Start gathering content
        ob_start();
        // Retrieve the context director
        $_dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Include the template file
        include($_dir.'/'.get_class($this).'.tpl.php');
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Return the contents
        return $output;
    }

}

class VCFF_PARAM_VAL {
    
    static function Get_Rules($shortcode) {
        // Default param rules
        $rules = array(
            'required' => array(
                'label' => 'Required Field',
                'description' => 'Insures the specified key value exists and is not empty',
                'has_value' => false,
                'is_gump' => true,
            ),
            'valid_email' => array(
                'label' => 'Valid Email',
                'description' => 'Checks for a valid email address',
                'has_value' => false,
                'is_gump' => true,
            ),
            'max_len' => array(
                'label' => 'Maximum Length',
                'description' => 'Checks key value length, makes sure it\'s not longer than the specified length.',
                'has_value' => true,
                'is_gump' => true,
            ),
            'exact_len' => array(
                'label' => 'Exact Length',
                'description' => 'Ensures that the key value length precisely matches the specified length.',
                'has_value' => true,
            ),
            'alpha' => array(
                'label' => 'Only Alpha',
                'description' => 'Ensure only alpha characters are present in the key value (a-z, A-Z)',
                'has_value' => false,
                'is_gump' => true,
            ),
            'alpha_numeric' => array(
                'label' => 'Only Alpha Numeric',
                'description' => 'Ensure only alpha-numeric characters are present in the key value (a-z, A-Z, 0-9)',
                'has_value' => false,
                'is_gump' => true,
            ),
            'alpha_dash' => array(
                'label' => 'Only Alpha, Dash, Underscore',
                'description' => 'Ensure only alpha-numeric characters + dashes and underscores are present in the key value (a-z, A-Z, 0-9, _-)',
                'has_value' => false,
                'is_gump' => true,
            ),
            'alpha_space' => array(
                'label' => 'Only Alpha and Spaces',
                'description' => 'Ensure only alpha-numeric characters + spaces are present in the key value (a-z, A-Z, 0-9, \s)',
                'has_value' => false,
                'is_gump' => true,
            ),
            'numeric' => array(
                'label' => 'Only Numeric',
                'description' => 'Ensure only numeric key values',
                'has_value' => false,
                'is_gump' => true,
            ),
            'integer' => array(
                'label' => 'Only integer numbers',
                'description' => 'Ensure only integer key values',
                'has_value' => false,
                'is_gump' => true,
            ),
            'boolean' => array(
                'label' => 'True/False',
                'description' => 'Checks for PHP accepted boolean values, returns TRUE for "1", "true", "on" and "yes"',
                'has_value' => false,
                'is_gump' => true,
            ),
            'float' => array(
                'label' => 'Only float values',
                'description' => 'Checks for float values',
                'has_value' => false,
                'is_gump' => true,
            ),
            'valid_url' => array(
                'label' => 'Is valid URL',
                'description' => 'Check for valid URL or subdomain',
                'has_value' => false,
                'is_gump' => true,
            ),
            'url_exists' => array(
                'label' => 'Does URL exist',
                'description' => 'Check to see if the url exists and is accessible',
                'has_value' => false,
                'is_gump' => true,
            ),
            'valid_ip' => array(
                'label' => 'Is valid IP address',
                'description' => 'Check for valid generic IP address',
                'has_value' => false,
                'is_gump' => true,
            ),
            'valid_ipv4' => array(
                'label' => 'Is valid IP4 address',
                'description' => 'Check for valid IPv4 address',
                'has_value' => false,
                'is_gump' => true,
            ),
            'valid_ipv6' => array(
                'label' => 'Is valid IP6 address',
                'description' => 'Check for valid IPv6 address',
                'has_value' => false,
                'is_gump' => true,
            ),
            'valid_cc' => array(
                'label' => 'Is valid credit card number',
                'description' => 'Check for a valid credit card number (Uses the MOD10 Checksum Algorithm)',
                'has_value' => false,
                'is_gump' => true,
            ),
            'valid_name' => array(
                'label' => 'Is valid name',
                'description' => 'Check for a valid format human name',
                'has_value' => false,
                'is_gump' => true,
            ),
            'contains' => array(
                'label' => 'Contains value',
                'description' => 'Verify that a value is contained within the pre-defined value set',
                'has_value' => true,
                'is_gump' => true,
            ),
            'containsList' => array(
                'label' => 'Contains list',
                'description' => 'Verify that a value is contained within the pre-defined value set. Comma separated, list not outputted.',
                'has_value' => true,
                'is_gump' => true,
            ),
            'doesNotcontainList' => array(
                'label' => 'Does not contain list',
                'description' => 'Verify that a value is not contained within the pre-defined value set. Comma separated, list not outputted.',
                'has_value' => true,
                'is_gump' => true,
            ),
            'street_address' => array(
                'label' => 'Valid street address',
                'description' => 'Checks that the provided string is a likely street address. 1 number, 1 or more space, 1 or more letters',
                'has_value' => false,
                'is_gump' => true,
            ),
            'iban' => array(
                'label' => 'Valid iban',
                'description' => 'Check for a valid IBAN',
                'has_value' => false,
                'is_gump' => true,
            ),
            'min_numeric' => array(
                'label' => 'Minimum Number',
                'description' => 'Determine if the provided numeric value is higher or equal to a specific value',
                'has_value' => true,
                'is_gump' => true,
            ),
            'max_numeric' => array(
                'label' => 'Maximum Numeric',
                'description' => 'Determine if the provided numeric value is lower or equal to a specific value',
                'has_value' => true,
                'is_gump' => true,
            ),
            'date' => array(
                'label' => 'Valid date',
                'description' => 'Determine if the provided input is a valid date (ISO 8601)',
                'has_value' => false,
                'is_gump' => true,
            ),
            'starts' => array(
                'label' => 'Starts with',
                'description' => 'Ensures the value starts with a certain character / set of character',
                'has_value' => true,
                'is_gump' => true,
            )
        );
        // Retrieve the global vcff forms class
        $vcff_fields = vcff_get_library('vcff_fields');
        // Retrieve the list of contexts
        $contexts = $vcff_fields->contexts; 
        // If this is a field tag
        if (is_array($contexts) && isset($contexts[$shortcode])) {
            // Retrieve the field context
            $field_context = $contexts[$shortcode];
            // Retrieve the field params
            $field_params = $field_context['params'];
            // If the field has custom validation rules
            if (isset($field_params['validation_rules'])) { 
                // Merge the two rulesets together
                $rules = array_merge($rules,$field_params['validation_rules']); 
            } 
        }

        $rules = apply_filters('vcff_param_validation_rules',$rules,$shortcode);

        return $rules;
    }
    
}
