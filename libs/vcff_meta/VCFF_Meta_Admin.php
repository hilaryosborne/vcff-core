<?php

class VCFF_Meta_Admin {

    public function __construct() {
        
        add_action('edit_form_advanced',array($this,'Render_Meta_Container'));
        
        add_action('save_post',array($this,'Save_Post'),1,2);
        
        add_action('delete_post',array($this,'Delete_Post'),1,1);
        
        add_action('vcff_form_import_export_do',array($this,'_Hook_Export_Hook'));
        
        add_action('vcff_form_import_upload_do',array($this,'_Hook_Import_Hook'));
        
        add_action('edit_form_before_permalink',array($this,'_Hook_Edit_Title'),-100);
    }

    public function _Hook_Edit_Title($form) {
        // If this is not a post form
        if ($form->post_type != 'vcff_form') { return; }
        // Retrieve the form uuid
        $form_uuid = vcff_get_uuid_by_form($form->ID);
        // If no form uuid
        if (!$form_uuid) { return ''; }
        // PREPARE PHASE
        $form_prepare_helper = new VCFF_Forms_Helper_Prepare();
        // Get the form instance
        $form_instance = $form_prepare_helper
            ->Get_Form(array(
                'uuid' => $form_uuid,
            ));
        // If the form instance could not be created
        if (!$form_instance) { die('could not create form instance'); }
        // POPULATE PHASE
        $form_populate_helper = new VCFF_Forms_Helper_Populate();
        // Run the populate helper
        $form_populate_helper
            ->Set_Form_Instance($form_instance)
            ->Populate(array());
        // Create a new validation helper
        $meta_validation_helper = new VCFF_Meta_Helper_Validation();
        // Check the meta for validation
        $meta_validation_helper
            ->Set_Form_Instance($form_instance)
            ->Check(); 
        // Retrieve the failed meta fields
        $failed = $meta_validation_helper->Get_Failed(); 
        // If there are more than one failed fields
        if ($failed && is_array($failed) && count($failed) > 0) {
            // Start the html content
            $html = '<div class="vcff-bs">';
            $html .= '  <div class="alert alert-danger" role="alert">';
            $html .= '      This form has some meta fields requiring values';
            $html .= '  </div>';
            $html .= '</div>';
            // Echo the html
            echo $html;
        }
    }

    public function _Hook_Export_Hook($export_helper) {
        // If we want to export the settings
        if (!isset($export_helper->settings['export_forms'])) { return; }
        // Retrieve the selected form ids
        $forms = $export_helper->export['forms'];
        // If there are no forms, return out
        if (!$forms || !is_array($forms)) { return; }
        // Loop through each form
        foreach ($forms as $form_uuid => $export_data) {
            // Retrieve a new form instance helper
            $form_instance_helper = new VCFF_Forms_Helper_Instance();
            // Generate a new form instance
            $form_instance = $form_instance_helper
                ->Set_Form_UUID($form_uuid)
                ->Generate();
            // If the form instance could not be created
            if (!$form_instance) { continue; }
            // Complete setting up the form instance
            $form_instance_helper
                ->Add_Fields()
                ->Add_Containers()
                ->Add_Meta()
                ->Add_Events()
                ->Add_Supports();
            // If this form has no meta, continue on
            if (!$form_instance->meta || !is_array($form_instance->meta)) { continue; }
            // Loop through each meta instance
            foreach ($form_instance->meta as $k => $meta_instance) {
                // Add the fields to the form meta array
                $export_helper->export['forms'][$form_uuid]['form_meta'][] = array(
                    'name' => $meta_instance->Get_Machine_Code(),
                    'label' => $meta_instance->Get_Label(),
                    'data' => $meta_instance->Get_Data(),
                    'value' => $meta_instance->Get_Value()
                );
            }
        }
    }
    
    public function _Hook_Import_Hook($import_helper) {
        // Retrieve the selected form ids
        $forms = $import_helper->import['forms'];
        // If there are no forms, return out
        if (!$forms || !is_array($forms)) { return; }
        // Loop through each form
        foreach ($forms as $form_uuid => $import_data) {
            // Retrieve a new form instance helper
            $form_instance_helper = new VCFF_Forms_Helper_Instance();
            // Generate a new form instance
            $form_instance = $form_instance_helper
                ->Set_Form_UUID($form_uuid)
                ->Generate();
            // If the form instance could not be created
            if (!$form_instance) { continue; }
            // Complete setting up the form instance
            $form_instance_helper
                ->Add_Fields()
                ->Add_Containers()
                ->Add_Meta()
                ->Add_Events()
                ->Add_Supports();
            // If this form has no meta, continue on
            if (!$form_instance->meta || !is_array($form_instance->meta)) { continue; }
            // Retrieve the form id
            $form_id = vcff_get_form_id_by_uuid($form_uuid);
            // Loop through each meta instance
            foreach ($import_data['form_meta'] as $k => $meta_data) {
                // Retrieve the field name
                $machine_code = $meta_data['name'];
                // Retrieve the meta instance
                $meta_instance = $form_instance->Get_Meta($machine_code);
                // If there was no meta instance, move on
                if (!$meta_instance) { continue; }
                // Set the field instance
                $meta_instance->value = $meta_data['value'];
                // Update the post meta
                $meta_instance->Store_Value();
            }
        }
    }
    
    public function Render_Meta_Container($form) {
        // If this is not a post form
        if ($form->post_type != 'vcff_form') { return; }
        // Retrieve the form uuid
        $form_uuid = vcff_get_uuid_by_form($form->ID,true); 
        // Get the saved vcff form type
        $meta_form_type = vcff_get_type_by_form($form_uuid);
        // PREPARE PHASE
        $form_prepare_helper = new VCFF_Forms_Helper_Prepare();
        // Get the form instance
        $form_instance = $form_prepare_helper
            ->Get_Form(array(
                'uuid' => $form_uuid,
                'type' => $meta_form_type
            ));
        // If the form instance could not be created
        if (!$form_instance) { die('could not create form instance'); }
        // POPULATE PHASE
        $form_populate_helper = new VCFF_Forms_Helper_Populate();
        // Run the populate helper
        $form_populate_helper
            ->Set_Form_Instance($form_instance)
            ->Populate(array());
        // Create new meta helper
        $form_meta_helper = new VCFF_Meta_Helper_AJAX();
        // Retrieve the json data
        echo $form_meta_helper
            ->Set_Form_Instance($form_instance)
            ->Render_Meta_Container();
    }

    public function Save_Post($post_id, $post) {
        // If this is not the post type we are looking for
        if ($post->post_type != 'vcff_form') { return; }
        // If no post id or post is supplied
        if (!$post_id 
            || !$post) { return; }
        // Dont' save meta boxes for revisions or autosaves
        if (defined( 'DOING_AUTOSAVE') 
            || wp_is_post_revision($post) 
            || wp_is_post_autosave($post)) { return; }
        // Check the post being saved == the $post_id to prevent triggering this call for other save_post events
        if (!$_POST['post_ID'] 
            || $_POST['post_ID'] != $post_id ) { return; }
        // Check user has permission to edit
        if (!current_user_can('edit_post', $post_id)) { return; }
        // Retrieve the form uuid
        $form_uuid = vcff_get_uuid_by_form($_POST['post_ID']);
        
        if (!$form_uuid) { 
            // Generate a new uuid
            $form_uuid = uniqid(); 
            // Insert a new uuid
            update_post_meta($_POST['post_ID'], 'form_uuid', $form_uuid);
        }
        // PREPARE PHASE
        $form_prepare_helper = new VCFF_Forms_Helper_Prepare();
        // Get the form instance
        $form_instance = $form_prepare_helper
            ->Get_Form(array(
                'uuid' => $form_uuid,
                'contents' => $_POST['content'],
            ));
        // If the form instance could not be created
        if (!$form_instance) { die('could not create form instance'); }
        // Create a new cache helper
        $form_cache_helper = new VCFF_Forms_Helper_Cache();
        // Cache the submitted form
        $form_instance = $form_cache_helper
            ->Set_Form_Instance($form_instance)
            ->Retrieve();  
        // POPULATE PHASE
        $form_populate_helper = new VCFF_Forms_Helper_Populate();
        // Run the populate helper
        $form_populate_helper
            ->Set_Form_Instance($form_instance)
            ->Populate(array(
                'meta_values' => $_POST
            ));
        // CALCULATE PHASE
        $form_calculate_helper = new VCFF_Forms_Helper_Calculate();
        // Initiate the calculate helper
        $form_calculate_helper
            ->Set_Form_Instance($form_instance)
            ->Calculate(array(
                'validation' => false
            ));
        // Create a new meta store helper
        $form_store_helper = new VCFF_Meta_Helper_Store();
        // Save the updated meta
        $form_store_helper
            ->Set_Form_Instance($form_instance)
            ->Save();
    }

    public function Delete_Post($post_id) {
        // Retrieve the post object
        $post = get_post($post_id);
        // If this is not the post type we are looking for
        if ($post->post_type != 'vcff_form') { return; }
    }
}

global $vcff_meta_admin;

$vcff_meta_admin = new VCFF_Meta_Admin();