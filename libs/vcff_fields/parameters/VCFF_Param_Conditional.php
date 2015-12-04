<?php

class VCFF_Param_Conditional {

    public $field_data;

    public $form_instance;
    
    public $conditions_item;

    public $value;

    public function __construct() {

        add_action('init',function(){ 
        
            add_shortcode_param('vcff_conditional', array($this, 'render'), VCFF_FIELDS_URL.'/assets/admin/vcff_param_conditional.js');
            // Register the vcff admin css
            vcff_admin_enqueue_style('vcff_conditional', VCFF_FIELDS_URL.'/assets/admin/vcff_param_conditional.css', array(), '20120608', 'all');
        },100);
    }
    
    protected function _Build_Form_Instance() {
        // Parse the form data
        parse_str(base64_decode($_POST['post_contents']),$output);
        // Retrieve the form uuid
        $form_uuid = vcff_get_uuid_by_form($output['post_ID']);
        // If there is no form type and form id
        if (!$output['form_type'] && $output['post_ID']) {
            // Get the saved vcff form type
            $meta_form_type = get_post_meta($output['post_ID'], 'form_type',true);
        } // Otherwise use the passed form type 
        else { $meta_form_type = $output['form_type']; }
        // PREPARE PHASE
        $form_prepare_helper = new VCFF_Forms_Helper_Prepare();
        // Get the form instance
        $form_instance = $form_prepare_helper
            ->Get_Form(array(
                'uuid' => $form_uuid,
                'contents' => $output['content'],
                'type' => $meta_form_type ? $meta_form_type : 'vcff_standard_form',
            ));
        // If the form instance could not be created
        if (!$form_instance) { die('could not create form instance'); }
        // POPULATE PHASE
        $form_populate_helper = new VCFF_Forms_Helper_Populate();
        // Run the populate helper
        $form_populate_helper
            ->Set_Form_Instance($form_instance)
            ->Populate(array(
                'meta_values' => $output
            ));
        // Populate the form instance
        $this->form_instance = $form_instance;
    }
    
   
    protected function _Prepare() {
        
        $this->_Build_Form_Instance();
        
        $form_instance = $this->form_instance;
        
        $this->conditions_item = new VCFF_Conditions_Item(false);
        
        $this->conditions_item
            ->Set_Form_Instance($form_instance)
            ->Prepare();
        
    }
    
    protected function _Els() {
        
        $conditions_item = $this->conditions_item;
        
        $_els = $conditions_item->els;
        
        $_json = array();
        
        if (!$_els || !is_array($_els)) { return $_json;  }
        
        foreach ($_els as $k => $_el) {
            
            $_json[$k] = array(
                'machine_code' => $_el['machine_code'],
                'logic_rules' => $_el['logic_rules'],
            );
        }
        
        return $_json;
    }
    
    public function render($settings,$value) { 
    
        $this->_Prepare();
        
        $_decoded = json_decode(base64_decode($value),true);
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

new VCFF_Param_Conditional();