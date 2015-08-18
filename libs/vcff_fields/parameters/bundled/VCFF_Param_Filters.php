<?php

class VCFF_Param_Filters {

    public function __construct() {

        $this->init();
    }

    public function init() {

        add_shortcode_param( 'vcff_filters', array($this, 'render'), VCFF_FIELDS_URL.'/assets/admin/vcff_param_filter.js' );
        // Register the vcff admin css
        vcff_admin_enqueue_style('vcff_filters', VCFF_FIELDS_URL.'/assets/admin/vcff_param_filter.css', array(), '20120608', 'all');

    }
    
    public function Get_Filters() {
        // Retrieve the field shortcode
        $shortcode = $_POST['tag'];
        // Return the rule list
        return VCFF_PARAM_FILTER::Get_Rules($shortcode);
    }

    public function render($settings, $value) { 
    
        $stored_rules = json_decode(base64_decode($value),true); 
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

new VCFF_Param_Filters();

class VCFF_PARAM_FILTER {
    
    static function Get_Rules($shortcode) {
        // Default param rules
        $rules = array(
            'sanitize_string' => array(
                'label' => 'Sanitize String',
                'description' => 'Remove script tags and encode HTML entities',
                'is_gump' => true,
            ),
            'urlencode' => array(
                'label' => 'Encode URL',
                'description' => 'Encode url entities',
                'is_gump' => true,
            ),
            'htmlencode' => array(
                'label' => 'Encode HTML Entities',
                'description' => 'Encode HTML entities',
                'is_gump' => true,
            ),
            'sanitize_email' => array(
                'label' => 'Sanitize Email Address',
                'description' => 'Remove illegal characters from email addresses',
                'is_gump' => true,
            ),
            'sanitize_numbers' => array(
                'label' => 'Sanitize Numbers',
                'description' => 'Remove any non-numeric characters',
                'is_gump' => true,
            ),
            'trim' => array(
                'label' => 'Trim Spaces',
                'description' => 'Remove spaces from the beginning or end of strings',
                'is_gump' => true,
            ),
            'base64_encode' => array(
                'label' => 'Base64 Encode Value',
                'description' => 'Base64 encode the input',
                'is_gump' => true,
            ),
            'base64_decode' => array(
                'label' => 'Base64 Decode Value',
                'description' => 'Base64 decode the input',
                'is_gump' => true,
            ),
            'sha1' => array(
                'label' => 'SHA1 Encrypt Value',
                'description' => 'Encrypt the input with the secure sha1 algorithm',
                'is_gump' => true,
            ),
            'md5' => array(
                'label' => 'MD5 Encode Value',
                'description' => 'MD5 encode the input',
                'is_gump' => true,
            ),
            'noise_words' => array(
                'label' => 'Remove Noise Words',
                'description' => 'Remove noise words from string',
                'is_gump' => true,
            ),
            'json_encode' => array(
                'label' => 'JSON Encode Value',
                'description' => 'Create a json representation of the input',
                'is_gump' => true,
            ),
            'json_decode' => array(
                'label' => 'JSON Decode Value',
                'description' => 'Decode a json string',
                'is_gump' => true,
            ),
            'rmpunctuation' => array(
                'label' => 'Remove Punctuation',
                'description' => 'Remove all known punctuation characters from a string',
                'is_gump' => true,
            ),
            'basic_tags' => array(
                'label' => 'Only Basic HTML Tags',
                'description' => 'Remove all layout orientated HTML tags from text. Leaving only basic tags',
                'is_gump' => true,
            ),
            'whole_number' => array(
                'label' => 'Only Whole Number',
                'description' => 'Ensure that the provided numeric value is represented as a whole number',
                'is_gump' => true,
            ),
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
            if (isset($field_params['filter_rules'])) { 
                // Merge the two rulesets together
                $rules = array_merge($rules,$field_params['filter_rules']); 
            } 
        }

        $rules = apply_filters('vcff_param_filter_rules',$rules,$shortcode);

        return $rules;
    }
    
}

