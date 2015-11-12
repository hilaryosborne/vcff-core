<?php

class Curly_Container_HTML extends VCFF_Item {
    
    public $form_instance;
    
    public $tag = 'container_html';
    
    public $name = '(HTML Field Value)';
    
    public $category = 'Containers as HTML';
    
    public $hint = 'container_html:machine_name';
    
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
        $form_containers = $form_instance->Get_Containers();
        // If there are no form containers
        if (!$form_containers || !is_array($form_containers) || count($form_containers) == 0) { return array(); }
        // The list for all code hints
        $hints = array();
        // Populate the curly tags
        $hints[] = array(
            'code' => $this->tag,
            'category' => $this->category,
            'hint' => $this->tag.':machine_code',
            'name' => 'Default '.$this->name,
            'method' => array($this,'_Render')
        );
        // Loop through each of the found curly tags
        foreach ($form_containers as $machine_code => $container_instance) {
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
        $container_instance = $form_instance->Get_Container($machine_code);
        // If no container instance was returned
        if (!$container_instance || !is_object($container_instance)) { return; }
        // If no container instance was returned
        if (isset($container_instance->no_curly) && $container_instance->no_curly) { return; }
        // Return the html value
        return $container_instance->Get_HTML_Value();
    }
}

vcff_map_context('Curly_Container_HTML');
