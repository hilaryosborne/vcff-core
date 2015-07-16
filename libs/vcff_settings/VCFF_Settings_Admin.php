<?php

class VCFF_Settings_Admin {

    public function __construct() {
        
        $this->_Load_Pages();
        
        add_action('vcff_form_export_form_inputs',array($this,'_Hook_Export_Fields'));
        add_action('vcff_form_import_form_inputs',array($this,'_Hook_Import_Fields'));
        add_action('vcff_form_import_export_do',array($this,'_Hook_Export_Hook'));
        add_action('vcff_form_import_upload_do',array($this,'_Hook_Import_Hook'));
    }
    
    protected function _Load_Pages() {
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_SETTINGS_DIR.'/pages') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // If this is not false
            if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; }
            // Include the file
            require_once(VCFF_SETTINGS_DIR.'/pages/'.$FileInfo->getFilename());
        }
    }
    
    public function _Hook_Export_Fields($page) {
        // Compile the setting html
        $html = '<div class="checkbox">';
        $html .= '  <label>';
        $html .= '      <input type="checkbox" name="settings[export_settings]" value="y" checked="checked"> Export Settings';
        $html .= '  </label>';
        $html .= '</div>';
        // Echo the html
        echo $html;
    }
    
    public function _Hook_Import_Fields($page) {
        // Compile the setting html
        $html = '<div class="checkbox">';
        $html .= '  <label>';
        $html .= '      <input type="checkbox" name="settings[import_settings]" value="y" checked="checked"> Import Settings';
        $html .= '  </label>';
        $html .= '</div>';
        // Echo the html
        echo $html;
    }
    
    public function _Hook_Export_Hook($export_helper) {
        // If we want to export the settings
        if (!isset($export_helper->settings['export_settings'])) { return; }
        // Create a new populator helper
        $settings_helper_populator = new VCFF_Settings_Helper_Populator();
        // Create a new form instance
        $form_instance = new VCFF_Settings_Form();
        // Setup the helper populator
        $settings_helper_populator
            ->Set_Form_Instance($form_instance)
            ->Populate(); 
        // Retrieve the built field instances
        $field_instances = $form_instance->fields; 
        // If there are no field instances, return out
        if (!$field_instances || !is_array($field_instances)) { return; }
        // Loop through each setting field instance
        foreach ($field_instances as $k => $field_instance) {
            // Add the field instance data to the export data
            $export_helper->export['settings'][]  = array(
                'name' => $field_instance->machine_code,
                'label' => $field_instance->field_label,
                'data' => $field_instance->data,
                'value' => $field_instance->value,
            );
        }
    }
    
    public function _Hook_Import_Hook($import_helper) {
        // If we want to export form data
        if (!isset($import_helper->settings['import_settings'])) { return; }
        // Retrieve the list of forms to be imported
        $imported_settings = $import_helper->import['settings'];  
        // If there are no forms to be uploaded, return out
        if (!$imported_settings || !is_array($imported_settings)) { return; }
        // Set the settings options prefix
        $prefix = 'vcff_setting_';
        // Loop through each setting field instance
        $u=0; $c=0; foreach ($imported_settings as $k => $field_data) {
            // Attempt to retrieve the current value
            $current_value = get_option($prefix.$field_data['name']);
            
            if ($current_value) {
                // Update the global option 
                update_option($prefix.$field_data['name'],$field_data['value']); $u++;
            } // Update the global option 
            else { update_option($prefix.$field_data['name'],$field_data['value']); $c++; }
        }
        // Add a success note
        if ($c > 0) { $import_helper->Add_Alert('<strong>Success!</strong> ... '.$c.' Settings Imported','success'); }
        
        if ($u > 0) { $import_helper->Add_Alert('<strong>Success!</strong> ... '.$u.' Settings Updated','success'); }
    }
    
}

global $vcff_settings_admin;

$vcff_settings_admin = new VCFF_Settings_Admin();
