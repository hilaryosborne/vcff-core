<?php

class VCFF_Settings_Page_Settings extends VCFF_Settings_Page {
    
    public function __construct() {
        // Action to register the page
        add_action('admin_menu', array($this,'Register_Page'));
        // Add the view event css
        add_action('admin_enqueue_scripts',function(){
			// Register the vcff admin css
        	wp_enqueue_script('vcff_settings_page', VCFF_SETTINGS_URL.'/assets/admin/vcff_settings_page.js');
            // Register the vcff admin css
        	wp_enqueue_style('vcff_settings_page', VCFF_SETTINGS_URL.'/assets/admin/vcff_settings_page.css');
        });
        
        add_action('wp_ajax_settings_refresh', array($this,'AJAX_Refresh')); 
    }
    
    public function Register_Page() {
        // Add the page sub menu item
        add_submenu_page('edit.php?post_type=vcff_form', 'Settings', 'Settings', 'edit_posts', 'vcff_settings_page_settings', array($this,'Render'));
    }
    
    public function Render() {
        // Run the update settings
        $this->_Update_Settings();
        // Retrieve the context director
        $tmp_dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Start gathering content
        ob_start();
        // Include the template file
        include(vcff_get_file_dir($tmp_dir.'/'.get_class($this).".tpl.php"));
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Return the contents
        echo $output;
    }

    protected function _Update_Settings() {
        // If no form data, return out
        if (!isset($_POST['vcff_settings_update'])) { return; }
        // Create a new populator helper
        $settings_helper_populator = new VCFF_Settings_Helper_Populator();
        // Create a new form instance
        $form_instance = new VCFF_Settings_Form();
        // Flag the form as updating
        $form_instance->is_update = true;
        // Setup the helper populator
        $settings_helper_populator
            ->Set_Form_Instance($form_instance)
            ->Set_Data($_POST)
            ->Populate()
            ->Check_Conditions()
            ->Check_Validation(); 
        // Retrieve the form instance
        $form_instance = $settings_helper_populator->form_instance;
        // If the form did not validate
        if (!$form_instance->Is_Valid()) { return; }
        // Add a success message
        $this->Add_Alert('<strong>Success!</strong> Settings successfully updated!','success');
        // Create the submit helper
        $settings_helper_submit = new VCFF_Settings_Helper_Submit();
        // Submit the form
        $settings_helper_submit
            ->Set_Form_Instance($form_instance)
            ->Submit();
    }
    
    public function AJAX_Refresh() {
        // Parse the form data
        parse_str($_POST['form_data'],$output);
        // Create a new populator helper
        $settings_helper_populator = new VCFF_Settings_Helper_Populator();
        // Setup the helper populator
        $settings_helper_populator
            ->Set_Form_Instance(new VCFF_Settings_Form())
            ->Set_Data($output)
            ->Populate()
            ->Check_Conditions();
        // Retrieve the form instance
        $form_instance = $settings_helper_populator->form_instance;
        // Create a new settings helper
        $settings_helper_ajax = new VCFF_Settings_Helper_AJAX();
        // Retrieve the json array
        $json = $settings_helper_ajax
            ->Set_Form_Instance($form_instance)
            ->Get_JSON_Data();
        // Encode the meta fields and return
        echo json_encode(array(
            'result' => 'success',
            'data' => $json
        )); wp_die();
    }
}

new VCFF_Settings_Page_Settings();