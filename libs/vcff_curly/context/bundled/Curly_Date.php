<?php

class Curly_Date extends VCFF_Item {
    
    public $form_instance;
    
    public $tag = 'date';
    
    public $name = 'Date';
    
    public $category = 'Date/Time';
    
    public $hint = 'date:format';
    
    /**
     * Returns a single tag array
     */
    public function Get_Tag() {
        // Populate the curly tags
        $tag = array(
            'code' => $this->tag,
            'category' => $this->category,
            'hint' => $this->hint,
            'name' => $this->name,
            'method' => array($this,'_Render')
        );
        // Run through a filter for hints
        $tag = apply_filters('vcff_curly_tag_date_data',$tag,$this);
        // Return the tag
        return $tag;
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
            'hint' => $this->tag.':mm_dd_yyyy',
            'name' => 'MM/DD/YYYY '.$this->name,
            'method' => array($this,'_Render')
        );
        // Populate the curly tags
        $hints[] = array(
            'code' => $this->tag,
            'category' => $this->category,
            'hint' => $this->tag.':dd_mm_yyyy',
            'name' => 'DD/MM/YYYY '.$this->name,
            'method' => array($this,'_Render')
        );
        // Run through a filter for hints
        $hints = apply_filters('vcff_curly_tag_date_hints',$hints,$this);
        // Return the hint list
        return $hints;
    }
    
    public function _Render($format) {
        // The value var
        $value = '';
        // Determine the date render value
        switch ($format) {
            case 'mm_dd_yyyy' : $value = date('m/d/Y'); break;
            case 'dd_mm_yyyy' : $value = date('d/m/Y'); break;
        }
        // Run through a filter for hints
        $value = apply_filters('vcff_curly_tag_date_value',$value,$format,$this);
        // Return the value
        return $value; 
    }
}

vcff_map_context('Curly_Date');
