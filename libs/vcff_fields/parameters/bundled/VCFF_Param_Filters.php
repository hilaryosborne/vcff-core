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
    

    public function render($settings, $value) { 
        // Retrieve the global vcff forms class
        $vcff_fields = vcff_get_library('vcff_fields');
        // If there is no context for this then report error
        if (!isset($vcff_fields->contexts[$_POST['tag']])) { die('No field found for shortcode '.$_POST['tag']); }
        // Retrieve the element context
        $field_context = $vcff_fields->contexts[$_POST['tag']];
        // Retrieve the validation logic
        $filter_logic = $field_context['filter_logic'];
        // If no validation logic could be found for this element, return out
        if (!$filter_logic || !is_array($filter_logic)) { return ''; }
        
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


