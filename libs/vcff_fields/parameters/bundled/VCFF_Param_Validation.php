<?php

class VCFF_Param_Validation {
    
    public $form_instance;

    public function __construct() {

        add_shortcode_param('vcff_validation', array($this, 'render'), VCFF_FIELDS_URL.'/assets/admin/vcff_param_validation.js' );
        // Register the vcff admin css
        vcff_admin_enqueue_style('vcff_param_validation', VCFF_FIELDS_URL.'/assets/admin/vcff_param_validation.css', array(), '20120608', 'all');
    }

    public function render($settings, $value) {
        // Retrieve the global vcff forms class
        $vcff_fields = vcff_get_library('vcff_fields');
        // If there is no context for this then report error
        if (!isset($vcff_fields->contexts[$_POST['tag']])) { die('No field found for shortcode '.$_POST['tag']); }
        // Retrieve the element context
        $field_context = $vcff_fields->contexts[$_POST['tag']];
        // Retrieve the validation logic
        $validation_logic = $field_context['validation_logic'];
        // If no validation logic could be found for this element, return out
        if (!$validation_logic || !is_array($validation_logic)) { return ''; }
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

new VCFF_Param_Validation();

