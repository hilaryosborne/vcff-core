<?php

class VCFF_Trigger_Item extends VCFF_Item {

    public $code;
    
    public $title;
    
    public $context;
    
    public $value;

    public $form_instance;
    
    public $action_instance;

    public $is_valid = false;
    
    public $validation_errors = array();
    
    public function Is_Valid() {
    
        return $this->is_valid;
    }

    public function Is_Update() {
        
        $action_instance = $this->action_instance;
        
        return $action_instance->Is_Update();
    }

    public function Check_Validation() {
    
    }

    public function Get_Name() {
        
        $context = $this->context;
        
        return $context['title'];
    }

    public function Get_Value() {
    
        return $this->value;
    }
}