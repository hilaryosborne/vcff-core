<?php

class VCFF_Settings_Form extends VCFF_Item {

    public $built = array();
    
    public $fields = array();
    
    public $is_update = false;
    
    public $is_valid = true;
    
    public $validation_errors = array();
    
    public function Is_Valid() {
    
        return $this->is_valid;
    }
    
    public function Is_Update() {
    
        return $this->is_update;
    }
    
    public function Check_Validation() {
    
    }
    
    public function Do_Validation() {
        
        if (!$this->Is_Valid()) {
        
            $this->Add_Alert('Some fields failed to validate','danger');
        }
        
    }

    public function Get_Field($code) {
        
        return $this->fields[$code];
    }
}