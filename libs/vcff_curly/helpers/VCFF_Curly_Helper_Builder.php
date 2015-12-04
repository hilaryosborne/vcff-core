<?php

class VCFF_Curly_Helper_Builder {

    protected $form_instance;
    
    protected $content;
    
	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
        
		return $this;
	}
    
    public function Set_Content($content) {
        
        $this->content = $content;
        
        return $this;
    }
    
    public function Get_Hints() {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Retrieve the global vcff forms class
        $vcff_curly = vcff_get_library('vcff_curly');
        // Retrieve the curly tags list
        $context = $vcff_curly->context;
        // The hints list
        $hints = array();
        // Loop through each context item
        foreach ($context as $code => $classname) {
            // If no classname then continue
            if (!class_exists($classname)) { continue; }
            // Create a new instance
            $instance = new $classname();
            // Retrieve the tag data
            $tag_data = $instance->Get_Tag();
            // If this tag has additional conditions
            if (isset($tag_data['available_if'])) {
                // Store the available if rules
                $check_result = $this->_Check_Requirements($tag_data['available_if']);
                // If the check result fails
                if (!$check_result) { continue; }
            }
            // Populate the form instance
            $instance->form_instance = $form_instance; 
            // Retrieve the hint list
            $context_hints = $instance->Get_Hints();
            // If no hints were returned
            if (!is_array($context_hints)) { continue; }
            // Merge with the existing hints
            $hints = array_merge($hints,$context_hints);
        }
        // Return the list of hints
        return $hints;
    }
    
    public function Get_Tags() {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Retrieve the global vcff forms class
        $vcff_curly = vcff_get_library('vcff_curly');
        // Retrieve the curly tags list
        $context = $vcff_curly->context;
        // The hints list
        $tags = array();
        // Loop through each context item
        foreach ($context as $code => $classname) {
            // If no classname then continue
            if (!class_exists($classname)) { continue; }
            // Create a new instance
            $instance = new $classname();
            // Retrieve the tag data
            $tag_data = $instance->Get_Tag();
            // If this tag has additional conditions
            if (isset($tag_data['available_if'])) {
                // Store the available if rules
                $check_result = $this->_Check_Requirements($tag_data['available_if']);
                // If the check result fails
                if (!$check_result) { continue; }
            }
            // Populate the form instance
            $instance->form_instance = $form_instance; 
            // Retrieve the hint list
            $context_tag = $instance->Get_Tag();
            // If no context tag
            if (!$context_tag || !is_array($context_tag)) { continue; }
            // Merge with the existing hints
            $tags[$instance->tag] = $context_tag;
        }
        // Return the list of hints
        return $tags;
    }
    
    protected function _Check_Requirements($requirements) {
        // If we need to check the field types
        if (isset($requirements['form_types'])) {
            // Calculate the check result
            $_check_result = $this->_Check_Form_Types($requirements['form_types']);
            // If the check result failed, move on
            if (!$_check_result) { return false; }
        }
        // If we need to check the field types
        if (isset($requirements['field_types'])) {
            // Calculate the check result
            $_check_result = $this->_Check_Field_Types($requirements['field_types']);
            // If the check result failed, move on
            if (!$_check_result) { return false; }
        }
        // If we need to check the field types
        if (isset($requirements['field_codes'])) {
            // Calculate the check result
            $_check_result = $this->_Check_Field_Codes($requirements['field_codes']);
            // If the check result failed, move on
            if (!$_check_result) { return false; }
        }
        // Otherwise return true
        return true;
    }
    
    protected function _Check_Form_Types($types) {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Return if this form is of type
        return in_array($form_instance->Get_Type(),$types);
    }
    
    protected function _Check_Field_Types($types) {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Retrieve the form fields
        $fields = $form_instance->fields;
        // If no fields, return out
        if (!$fields || !is_array($fields)) { return true; }
        // Loop through each field
        foreach ($fields as $machine_code => $field_instance) {
            // Retrieve the field type
            $field_type = $field_instance->Get_Type();
            // If this is the type we are looking for, return true
            if (in_array($field_type,$types)) { return true; }
        }
        // Otherwise return false
        return false;
    }
    
    protected function _Check_Field_Codes($codes) {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Retrieve the form fields
        $fields = $form_instance->fields;
        // If no fields, return out
        if (!$fields || !is_array($fields)) { return true; }
        // Loop through each field
        foreach ($fields as $machine_code => $field_instance) {
            // If this is the type we are looking for, return true
            if (in_array($machine_code,$codes)) { return true; }
        }
        // Otherwise return false
        return false;
    }
    
    public function Get_Hints_List() {
        // Retrieve the hints
        $hints = $this->Get_Hints();
        // If no hints
        if (!$hints || !is_array($hints)) { return array(); }
        // The hint list
        $hint_list = array();
        // Loop through each hint
        foreach ($hints as $k => $hint) {
            // Retrieve the category
            $hint_category = $hint['category'];
            // Populate the hint list
            $hint_list[$hint_category][] = $hint;
        }
        // Return the hint list
        return $hint_list;
    }

    public function Compile() {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Retrieve the set content
        $content = $this->content;
        // Retrieve the tag matches
        preg_match_all('/{([^}]*)}/', $content, $tag_matches);
        // If no tag matches are returned
        if (!$tag_matches || !is_array($tag_matches) || !is_array($tag_matches[1])) { return $content; }
        // Retrieve the global vcff forms class
        $vcff_curly = vcff_get_library('vcff_curly');
        // Retrieve the curly tags list
        $tags = $this->Get_Tags();
        // Loop through each found tag
        foreach ($tag_matches[1] as $k => $tag_string) {
            // Explode the tag arguments
            $arguments = explode(':',$tag_string);
            // If there is no curly tags
            if (!isset($tags[$arguments[0]])) { continue; }
            // Retrieve the tag data
            $tag_context = $tags[$arguments[0]]; 
            // Retrieve the tag
            unset($arguments[0]);
            // Retrieve the tag contents
            $tag_contents = call_user_func_array($tag_context['method'],$arguments);
            // Replace the contents
            $content = str_replace("{".$tag_string."}",$tag_contents,$content);
        }
        // Return the compiled content
        return $content;
    }
    
    
    public function Get_Textarea_Field($machine_code,$field_value) {     
        // Start gathering content
        ob_start();
        // Include the template file
        include(VCFF_CURLY_DIR.'/templates/VCFF_Curly_Editor_Textarea.tpl.php');
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Return the contents
        return $output;
    }
    
    public function Get_Textfield_Field($machine_code,$field_value) {
        // Start gathering content
        ob_start();
        // Include the template file
        include(VCFF_CURLY_DIR.'/templates/VCFF_Curly_Editor_Textfield.tpl.php');
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Return the contents
        return $output;
    }
    
}