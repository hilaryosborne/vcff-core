<?php

class Curly_Fields_Text extends VCFF_Item {
    
    public $form_instance;
    
    public $tag = 'fields_text';
    
    public $name = 'All Fields Text Value';
    
    public $category = 'Display All';
    
    public $hint = 'fields_text';
    
    /**
     * Returns a single tag array
     */
    public function Get_Tag() {
        // Populate the curly tags
        return array(
            'code' => $this->tag,
            'category' => $this->category,
            'hint' => $this->hint,
            'name' => $this->name,
            'method' => array($this,'_Render')
        );
    }
    
    /**
     * Returns multiple hints for this curly tag
     */
    public function Get_Hints() {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // The list for all code hints
        $hints = array();
        // Populate the curly tags
        $hints[] = array(
            'code' => $this->tag,
            'category' => $this->category,
            'hint' => $this->hint,
            'name' => $this->name,
            'method' => array($this,'_Render')
        );
        // Return the hint list
        return $hints;
    }
    
    public function _Render() {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Retrieve the form fields
        $form_fields = $form_instance->Get_Fields();
        // If there are no form fields
        if (!$form_fields || !is_array($form_fields) || count($form_fields) == 0) { return ''; }
        // The text string
        $text = ''; $containers = array();
        // Loop through each of the found curly tags
        foreach ($form_fields as $machine_code => $field_instance) {
            // If the field is hidden
            if ($field_instance->Is_Hidden()) { continue; }
            // If the field is hidden
            if (!$field_instance->Get_Value()) { continue; }
            // If the field is part of a container
            if ($field_instance->container_instance) {
                // Retrieve the container instance
                $container_instance = $field_instance->container_instance;
                // Retrieve the machine code
                $_machine_code = $container_instance->Get_Machine_Code();
                // If the container has already been rendered
                if (isset($containers[$_machine_code])) { continue; }
                // Add to the text
                $text .= $container_instance->Get_TEXT_Value()."\n\r";
                // Flag this container as rendered
                $containers[$_machine_code] = true;
            } // Otherwise retrieve the text field
            else { $text .= $field_instance->Get_TEXT_Value()."\n\r"; }
        }
        
        return $text;
    }
}

vcff_map_context('Curly_Fields_Text');
