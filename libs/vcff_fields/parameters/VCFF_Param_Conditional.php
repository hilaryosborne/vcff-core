<?php

class VCFF_Param_Conditional {

    public $field_data;

    public $value;

    public function __construct() {

        add_action('init',function(){ 
        
            add_shortcode_param('vcff_conditional', array($this, 'render'), VCFF_FIELDS_URL.'/assets/admin/vcff_param_conditional.js');
            // Register the vcff admin css
            vcff_admin_enqueue_style('vcff_conditional', VCFF_FIELDS_URL.'/assets/admin/vcff_param_conditional.css', array(), '20120608', 'all');
        },100);
    }
    
    protected function _Get_Current_Rules() {
        // Retrieve the stored value
        $value = json_decode(base64_decode($this->value),true);
        // If the data is not what we need
        if (!is_array($value) || !isset($value['conditions'])) { return; }
        // Retrieve the conditions
        $rules = $value['conditions'];
        // If no rules were present
        if (!$rules || !is_array($rules)) { return; }
        // Retrieve the form instance fields
        $form_fields = $this->field_data;
        // Loop through each of the rules
        foreach ($rules as $k => $rule) {
            // The field list var
            $field_list = array();
            // The field conditions
            $condition_list = false;
            // Loop through each field instance
            foreach ($form_fields as $k => $_field) {
                // Retrieve the field context
                $context = $_field['context'];
                // Retrieve the allowed conditions
                $allowed_conditions = isset($context['params']['allowed_conditions']) ? $context['params']['allowed_conditions'] : array() ;
                // If the field does not allow conditions
                if (!$allowed_conditions) { continue; }
                // Retrieve the field name
                $machine_code = $_field['name'];
                // Populate the field list
                $field_list[$machine_code] = array(
                    'machine_code' => $_field['name'],
                    'field_label' => $_field['label'],
                    'selected' => $rule['check_field'] == $machine_code ? true : false
                );
                // If this is the selected field
                if ($rule['check_field'] == $machine_code) {
                    // Loop through each rule
                    foreach ($allowed_conditions as $rule_name => $rule_label) {
                        // Populate the condition data
                        $condition_list[$rule_name] = array(
                            'rule_name' => $rule_name,
                            'rule_label' => $rule_label,
                            'selected' => $rule['check_condition'] == $rule_name ? true : false
                        );
                    }
                }
            }
            // Add to the rule date
            $rule_data[] = array(
                'field_list' => $field_list,
                'field_conditions' => $condition_list,
                'field_value' => $rule['check_value']
            );
        } 
        // Return the rule data
        return $rule_data;
    }
    
    protected function _Get_Field_List() {
        // Retrieve the form instance fields
        $form_fields = $this->field_data;
        // The array for the form fields
        $field_list = array();
        // If a list of field instances was returned
        if (!$form_fields || !is_array($form_fields)) { return array(); }
        // Loop through each field instance
        foreach ($form_fields as $k => $_field) {
            // Retrieve the field context
            $context = $_field['context'];
            // Retrieve the allowed conditions
            $allowed_conditions = isset($context['params']['allowed_conditions']) ? $context['params']['allowed_conditions'] : array() ;
            // If the field does not allow conditions
            if (!$allowed_conditions) { continue; }
            // Retrieve the field name
            $machine_code = $_field['name'];
            // Populate the field list
            $field_list[$machine_code] = array(
                'machine_code' => $_field['name'],
                'field_label' => $_field['label'],
                'field_conditions' => $allowed_conditions
            );
        }
        // Return the field list
        return $field_list;
    }
    
    protected function _Get_Field_JSON() {
        // Return the field list
        return json_encode($this->_Get_Field_List());
    }

    public function render($settings,$value) { 
        // Retrieve the post object
        $post = get_post($_POST['post_id']);
        // If no post could be found
        if (!$post) { return; }
        // Populate the field data
        $this->field_data = vcff_parse_field_data($post->post_content);
        // Populate the current value
        $this->value = $value;
        // Retrieve the current rules
        $current_rules = $this->_Get_Current_Rules(); 
        // Retrieve the current form fields
        $current_fields = $this->_Get_Field_List();
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