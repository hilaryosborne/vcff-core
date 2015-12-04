<?php

class VCFF_Settings_Helper_AJAX extends VCFF_Helper {

    protected $form_instance;	

    protected $default_page = 'general_settings';
    
    protected $default_group = 'form_settings';

	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}

    public function Get_JSON_Data() {
		// Start the json data array
        $json_data = array();
        
        $form_instance = $this->form_instance;
        // Retrieve the settings container data
        $settings_container = $this->_Build_Settings_Container();
        // Retrieve the list of pages
        $settings_container_pages = $settings_container['pages'];
        // Loop through each of the container pages
        foreach ($settings_container_pages as $page_name => $page_data) {
            // Create the json page data
            $json_page = array(
                'id' => $page_data['id'],
                'title' => $page_data['title'],
                'html' => $page_data['html'],
                'groups' => array()
            );
            // If a list of page groups was returned
            if (!isset($page_data['groups']) || !is_array($page_data['groups'])) { continue; }
			// Retrieve the page groups
            $page_groups = $page_data['groups'];
            // Loop through each page group
            foreach ($page_groups as $_k => $group_data) {
                // Create the json page data
                $json_group = array(
                    'id' => $group_data['id'],
                    'title' => $group_data['title'],
                    'html' => $group_data['html'],
                    'fields' => array()
                );
                // If there are no group fields
                if (!isset($group_data['fields']) || !is_array($group_data['fields'])) { continue; }
				// Retrieve the group fields
                $group_fields = $group_data['fields'];
                // Loop through each of the group fields
                foreach ($group_fields as $__k => $field_data) {
                    // Retrieve the field intance
                    $field_instance = $field_data['instance'];
                    // If the field is not visible
                    if ($field_instance->Is_Hidden()) { continue; }
                    // Build the json data
                    $json_field = array(
                        'machine_code' => $field_data['machine_code'],
                        'label' => $field_data['label'],
                        'html' => $field_data['html'],
                    );
                    // Retrieve the field instance context
                    $field_instance_context = $field_instance->context;
                    //If the instance has admin javascript files
                    if ($field_instance_context['params']['js']) {
                        // Add to the json data
                        $json_field['js'] = $field_instance_context['params']['js'];
                    }
                    // If the instance has admin css styles
                    if ($field_instance_context['params']['css']) {
                        // Add to the json data
                        $json_field['css'] = $field_instance_context['params']['css'];
                    }
                    // Add the field data to the json group array
                    $json_group['fields'][] = $json_field;
                }
                // Add the json group
                $json_page['groups'][] = $json_group;
            }
            // Add the json page data to the pages array
            $json_data['pages'][] = $json_page;
        }
        // Return the json data
        return $json_data;
	}
	
	protected function _Build_Settings_Container() { 
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// The var holding the settings container
        $settings_field_container = array(
            'pages' => array()    
        );
        // Retrieve the form settings pages
        $settings_pages = $form_instance->built['pages'];
        // If no settings pages have been returned
        // There should be no situation this happens
        if (!$settings_pages || !is_array($settings_pages)) { return array(); }
        // Reorder the pages based on page weight
        usort($settings_pages, function($a,$b){
            // If no weight was provided
            if (!$a["weight"]) { $a["weight"] = 100; }
            // If no weight was provided
            if (!$b["weight"]) { $b["weight"] = 100; }
            // Return the result of the comparison
            return $a["weight"] > $b["weight"] ? 1 : -1;
        });
        // Loop through each settings page
        foreach ($settings_pages as $k => $settings_page) {
            // Populate the build settings page var
            $built_settings_page = $settings_page;
            // Retrieve the page groups
            $built_settings_page['groups'] = $this->_Build_Settings_Groups($settings_page['id']);
            // Add the html
            $built_settings_page['html'] = $this->_Render_Page($built_settings_page);
            // Store in the container var
            $settings_field_container['pages'][$settings_page['id']] = $built_settings_page;
        } 
        // Return the field groups
        return $settings_field_container;
	}
	
	protected function _Build_Settings_Groups($page_id) { 
		// Retrieve the form instance
		$form_instance = $this->form_instance; 
		// Retrieve the form settings groups
        $settings_groups = $form_instance->built['groups'];  
        // Retrieve the form settings groups
        $settings_fields = $form_instance->built['fields'];
        // If no settings groups have been returned
        // There should be no situation this happens
        if (!$settings_groups || !is_array($settings_groups)) { return; }
        // The var to store the found groups
        $settings_field_groups = array();
        // Loop through each settings groups
        foreach ($settings_groups as $k => $settings_group) { 
            // If the group does not belong to the page
            if ($settings_group['page_id'] && $settings_group['page_id'] != $page_id) { continue; }
            // If the group has no page id, use the default
            if (!$settings_group['page_id'] && $page_id != $this->default_page) { continue; }
            // Populate the built settings group
            $built_settings_group = $settings_group;
            // Get the fields list ready
            $built_settings_group['fields'] = array();
            // List var for built fields
            $build_settings_field_list = array();   
            // Loop through each of the fields
            foreach ($settings_fields as $k => $settings_field_data) { 
                // If this is not the field we are looking for
                if ($settings_field_data['group'] && $settings_group['id'] != $settings_field_data['group']) { continue; } 
                // If the group has no page id, use the default
                if (!$settings_field_data['group'] && $settings_group['id'] != $this->default_group) { continue; } 
                // Retrieve the field name
                $settings_machine_code = $settings_field_data['machine_code'];
                // Retrieve the settings field instance
                $settings_field_instance = $form_instance->fields[$settings_machine_code];
                
                if (!is_object($settings_field_instance)) { continue; }
                // Retrieve the settings instance
                $settings_field_data['instance'] = $settings_field_instance;
                // Add the html
                $settings_field_data['html'] = $settings_field_instance->Render();
                // Store in the container var
                $build_settings_field_list[$settings_machine_code] = $settings_field_data; 
            }
            // Reorder the groups based on page weight
            usort($build_settings_field_list, function($a,$b){
                // If no weight was provided
                if (!$a["weight"]) { $a["weight"] = 100; }
                // If no weight was provided
                if (!$b["weight"]) { $b["weight"] = 100; }
                // Return the result of the comparison
                return $a["weight"] > $b["weight"] ? 1 : -1;
            });  
            // Populate the settings field list
            $built_settings_group['fields'] = $build_settings_field_list;
            // Add the html
            $built_settings_group['html'] = $this->_Render_Group($built_settings_group);
            // Add the built group to the field groups
            $settings_field_groups[$settings_group['id']] = $built_settings_group;
        }
        // Reorder the groups based on page weight
        usort($settings_field_groups, function($a,$b){
            // If no weight was provided
            if (!$a["weight"]) { $a["weight"] = 100; }
            // If no weight was provided
            if (!$b["weight"]) { $b["weight"] = 100; }
            // Return the result of the comparison
            return $a["weight"] > $b["weight"] ? 1 : -1;
        });
        // Return the settings field page
        return $settings_field_groups;
	}

    public function Render_Container() {
        // Start gathering content
        ob_start();
        // Include the template file
        include(VCFF_SETTINGS_DIR.'/templates/VCFF_Settings_Container.tpl.php');
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Return the contents
        return $output;
    }
    
    protected function _Render_Page($page) {
        // Start gathering content
        ob_start();
        // Include the template file
        include(VCFF_SETTINGS_DIR.'/templates/VCFF_Settings_Page.tpl.php');
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Return the contents
        return $output;
    }

    protected function _Render_Group($group) {
        // Start gathering content
        ob_start();
        // Include the template file
        include(VCFF_SETTINGS_DIR.'/templates/VCFF_Settings_Group.tpl.php');
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Return the contents
        return $output;
    }
}