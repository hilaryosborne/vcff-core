<?php

class VCFF_Fragments_Admin {
    
    public function __construct() {
        
        add_action('save_post', array($this,'_Hook_Save_Post'));
        add_filter('vcff_field_pre_parse', array($this, '_Hook_Field_Pre_Parse'));
        add_filter('vcff_container_pre_parse', array($this, '_Hook_Container_Pre_Parse'));
        add_action('vcff_form_import_export_do',array($this,'_Hook_Export_Hook'));
        add_action('vcff_form_import_upload_do',array($this,'_Hook_Import_Hook'));
        add_action('vcff_form_import_form_inputs',array($this,'_Hook_Import_Form_Fields'));
        add_action('vcff_form_export_form_inputs',array($this,'_Hook_Export_Form_Inputs'));
    }
    
    public function _Hook_Field_Pre_Parse($text) {
    
        return vcff_parse_fragment($text);
    }
    
    public function _Hook_Container_Pre_Parse($text) {
    
        return vcff_parse_fragment($text);
    }
    
    public function _Hook_Import_Hook($import_helper) {
        // If we want to export form data
        if (!isset($import_helper->settings['import_fragments'])) { return; }
        // Retrieve the list of forms to be imported
        $imported_fragments = $import_helper->import['fragments'];  
        // If there are no forms to be uploaded, return out
        if (!$imported_fragments || !is_array($imported_fragments)) { return; }
        // Loop through each of the forms to be imported
        $c=0; $u=0; foreach ($imported_fragments as $uuid => $fragment) {
            // Attempt top load a form using that form uuid 
            $post = vcff_get_fragment_by_uuid($uuid);
            // If a post already exists
            if ($post && is_object($post)) {
                // Create the update array
                $update = array(
                    'ID' => $post->ID,
                    'post_title' => $fragment['post_title'],
                    'post_content' => base64_decode($fragment['post_content'])
                );
                // Update the post into the database
                wp_update_post($update); $u++;
                // Do any other actions
                do_action('vcff_fragment_update',$post);
            } // Otherwise if we have to create a new post
            else { 
                // Create the update array
                $create = array(
                    'post_title' => $fragment['post_title'],
                    'post_type' => 'vcff_fragment',
                    'post_content' => base64_decode($fragment['post_content']),
                    'post_status' => 'publish',
                );
                // Update the post into the database
                $fragment_id = wp_insert_post($create); 
                // If no form id then the form was not created
                if (!$fragment_id) { die('Fragment failed to create'); }
                // Update the fragment with a new uuid
                update_post_meta($fragment_id, 'fragment_uuid', $uuid); $c++;
                // Load the post
                $post = get_post($fragment_id);
                // Do any other actions
                do_action('vcff_fragment_update',$post);
            }  
        }
        
        if ($c > 0) { $import_helper->Add_Alert('<strong>Success!</strong> ... '.$c.' Fragments Imported','success'); }
        
        if ($u > 0) { $import_helper->Add_Alert('<strong>Success!</strong> ... '.$u.' Fragments Updated','success'); }
    }
    
    public function _Hook_Export_Form_Inputs($page){
        // Compile the setting html
        $html = '<div class="checkbox">';
        $html .= '  <label>';
        $html .= '      <input type="checkbox" name="settings[export_fragments]" value="y" checked="checked"> Export Fragments';
        $html .= '  </label>';
        $html .= '</div>';
        // Echo the html
        echo $html;
    }
    
    public function _Hook_Import_Form_Fields($page) {
        // Compile the setting html
        $html = '<div class="checkbox">';
        $html .= '  <label>';
        $html .= '      <input type="checkbox" name="settings[import_fragments]" value="y" checked="checked"> Import Fragments';
        $html .= '  </label>';
        $html .= '</div>';
        // Echo the html
        echo $html;
    }

    public function _Hook_Export_Hook($export_helper) {
        // If we want to export the settings
        if (!isset($export_helper->settings['export_fragments'])) { return; }
        // Retrieve the global wordpress database layer
        global $wpdb; 
        // Check the vcff_form post type exists
        if (!post_type_exists('vcff_fragment')){ return; } 
        // Retrieve a list of all the published vv forms
        $fragments = $wpdb->get_results("SELECT ID, post_title 
	        FROM $wpdb->posts
	        WHERE post_status = 'publish'
            AND post_type = 'vcff_fragment'"); 
        // If no published posts were returned
        if (!$fragments) { return; } 
        // Loop through each published post
        foreach ($fragments as $k => $row) {
            // Retrieve the post object
            $post = get_post($row->ID);
            // If no post could be found then continue
            if (!$post || !is_object($post)) { continue; }
            // Retrieve the fragment id
            $fragment_uuid = vcff_get_uuid_by_fragment($post->ID);
            // If no fragment uuid was found, move on
            if (!$fragment_uuid) { continue; }
            // Build the export array
            $export_fragment = array(
                'uuid' => $fragment_uuid,
                'post_title' => $post->post_title,
                'post_content' => base64_encode($post->post_content),
                'post_author' => $post->post_author,
                'post_date' => $post->post_date,
                'post_date_gmt' => $post->post_date_gmt,
            );
            // Pass the data array through the setup filter
            $export_fragment = apply_filters('vcff_forms_export_fragment_data', $export_fragment);
            // Add the form to the forms list
            $export_helper->export['fragments'][$fragment_uuid] = $export_fragment; 
        }  
    }

    public function _Hook_Save_Post($post_id) {
        // Attempt to retrieve the uuid
        $fragment_uuid = get_post_meta($post_id, 'fragment_uuid', true); 
        // If the post does not have a uuid
        if (!$fragment_uuid) {
            // Update the fragment with a new uuid
            update_post_meta($post_id, 'fragment_uuid', uniqid(), true);
        }
    }
}

new VCFF_Fragments_Admin();
