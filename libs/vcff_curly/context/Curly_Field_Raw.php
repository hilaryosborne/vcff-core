<?php

class Curly_Field_Raw extends VCFF_Item {
    
    public $form_instance;
    
    public $tag = 'field_raw';
    
    public $name = '(Raw Field Value)';
    
    public $category = 'Fields as Raw';
    
    public $hint = 'field_raw:field_name';
    
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
        // Retrieve the form fields
        $form_fields = $form_instance->Get_Fields();
        // The list for all code hints
        $hints = array();
        // Loop through each of the found curly tags
        foreach ($form_fields as $machine_code => $field_instance) {
            // Populate the curly tags
            $hints[] = array(
                'code' => $this->tag,
                'category' => $this->category,
                'hint' => $this->tag.':'.$machine_code,
                'name' => $machine_code.' '.$this->name,
                'method' => array($this,'_Render')
            );
        }
        // Return the hint list
        return $hints;
    }
    
    public function _Render($machine_code) {
        // Retrieve the form instance
        $form_instance = $this->form_instance;
        // Retrieve the field instance
        $field_instance = $form_instance->Get_Field($machine_code);
        // If no container instance was returned
        if (!$field_instance || !is_object($field_instance)) { return; }
        // If no container instance was returned
        if (isset($field_instance->no_curly) && $field_instance->no_curly) { return; }
        // Return the raw value
        return $field_instance->Get_RAW_Value();
    }
}

vcff_map_context('Curly_Field_Raw');
