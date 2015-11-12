<?php

class VCFF_Meta_Helper_AJAX {
	
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
        // Retrieve the meta container data
        $meta_container = $this->_Build_Meta_Container();
        // Retrieve the list of pages
        $meta_container_pages = $meta_container['pages'];
        // Loop through each of the container pages
        foreach ($meta_container_pages as $page_name => $page_data) {
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
	
	protected function _Build_Meta_Container() { 
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// The var holding the meta container
        $meta_field_container = array(
            'pages' => array()    
        );
        // Retrieve the form meta pages
        $meta_pages = $form_instance->context['meta']['pages'];
        // If no meta pages have been returned
        // There should be no situation this happens
        if (!$meta_pages || !is_array($meta_pages)) { return; }
        // Reorder the pages based on page weight
        usort($meta_pages, function($a,$b){
            // If no weight was provided
            if (!$a["weight"]) { $a["weight"] = 100; }
            // If no weight was provided
            if (!$b["weight"]) { $b["weight"] = 100; }
            // Return the result of the comparison
            return $a["weight"] > $b["weight"] ? 1 : -1;
        });
        // Loop through each meta page
        foreach ($meta_pages as $k => $meta_page) {
            // Populate the build meta page var
            $built_meta_page = $meta_page;
            // Retrieve the page groups
            $built_meta_page['groups'] = $this->_Build_Meta_Groups($meta_page['id']);
            // Add the html
            $built_meta_page['html'] = $this->_Render_Meta_Page($built_meta_page);
            // Store in the container var
            $meta_field_container['pages'][$meta_page['id']] = $built_meta_page;
        } 
        // Return the field groups
        return $meta_field_container;
	}
	
	protected function _Build_Meta_Groups($page_id) { 
		// Retrieve the form instance
		$form_instance = $this->form_instance;
		// Retrieve the form meta groups
        $meta_groups = $form_instance->context['meta']['groups'];  
        // Retrieve the form meta groups
        $meta_fields = $form_instance->context['meta']['fields'];
        // If no meta groups have been returned
        // There should be no situation this happens
        if (!$meta_groups || !is_array($meta_groups)) { return; }
        // The var to store the found groups
        $meta_field_groups = array();
        // Loop through each meta groups
        foreach ($meta_groups as $k => $meta_group) {
            // If the group does not belong to the page
            if ($meta_group['page_id'] && $meta_group['page_id'] != $page_id) { continue; }
            // If the group has no page id, use the default
            if (!$meta_group['page_id'] && $page_id != $this->default_page) { continue; }
            // Populate the built meta group
            $built_meta_group = $meta_group;
            // Get the fields list ready
            $built_meta_group['fields'] = array();
            // List var for built fields
            $build_meta_field_list = array();   
            // Loop through each of the fields
            foreach ($meta_fields as $k => $meta_field_data) { 
                // If this is not the field we are looking for
                if ($meta_field_data['field_group'] && $meta_group['id'] != $meta_field_data['field_group']) { continue; } 
                // If the group has no page id, use the default
                if (!$meta_field_data['field_group'] && $meta_group['id'] != $this->default_group) { continue; } 
                // Retrieve the field name
                $meta_machine_code = $meta_field_data['machine_code'];
                // Retrieve the meta field instance
                $meta_field_instance = $form_instance->meta[$meta_machine_code];
                
                if (!is_object($meta_field_instance)) { continue; }
                // Retrieve the meta instance
                $meta_field_data['instance'] = $meta_field_instance;
                // Add the html
                $meta_field_data['html'] = $meta_field_instance->Render();
                // Store in the container var
                $build_meta_field_list[$meta_machine_code] = $meta_field_data;
            }
            // Reorder the groups based on page weight
            usort($build_meta_field_list, function($a,$b){
                // If no weight was provided
                if (!$a["weight"]) { $a["weight"] = 100; }
                // If no weight was provided
                if (!$b["weight"]) { $b["weight"] = 100; }
                // Return the result of the comparison
                return $a["weight"] > $b["weight"] ? 1 : -1;
            });
            // Populate the meta field list
            $built_meta_group['fields'] = $build_meta_field_list;
            // Add the html
            $built_meta_group['html'] = $this->_Render_Meta_Group($built_meta_group);
            // Add the built group to the field groups
            $meta_field_groups[$meta_group['id']] = $built_meta_group;
        }
        // Reorder the groups based on page weight
        usort($meta_field_groups, function($a,$b){
            // If no weight was provided
            if (!$a["weight"]) { $a["weight"] = 100; }
            // If no weight was provided
            if (!$b["weight"]) { $b["weight"] = 100; }
            // Return the result of the comparison
            return $a["weight"] > $b["weight"] ? 1 : -1;
        });
        // Return the meta field page
        return $meta_field_groups;
	}
	
    public function Render_Warning() {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Start gathering content
        ob_start(); 
        // Include the template file
        include(VCFF_META_DIR.'/templates/VCFF_Meta_Warning.tpl.php');
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Retrieve the output html
        $output_html = apply_filters('vcff_meta_render_warning',$output,$this);
        // Return the contents
        return $output_html;
    }
    
	public function Render_Meta_Container() {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Start gathering content
        ob_start(); 
        // Include the template file
        include(VCFF_META_DIR.'/templates/VCFF_Meta_Container.tpl.php');
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Retrieve the output html
        $output_html = apply_filters('vcff_meta_render_container',$output,$this);
        // Return the contents
        return $output_html;
    }
    
    protected function _Render_Meta_Page($meta_page) {
        // Start gathering content
        ob_start();
        // Include the template file
        include(VCFF_META_DIR.'/templates/VCFF_Meta_Page.tpl.php');
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Retrieve the output html
        $output_html = apply_filters('vcff_meta_render_page',$output,$meta_page,$this);
        // Return the contents
        return $output_html;
    }

    protected function _Render_Meta_Group($meta_group) {
        // Start gathering content
        ob_start();
        // Include the template file
        include(VCFF_META_DIR.'/templates/VCFF_Meta_Group.tpl.php');
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Retrieve the output html
        $output_html = apply_filters('vcff_meta_render_group',$output,$meta_group,$this);
        // Return the contents
        return $output_html;
    }
	
}