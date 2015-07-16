<?php

class VCFF_Fields_Helper_Download {

    protected $reference;
    
    public function Set_File_Reference($reference) {
        
        $this->reference = explode(':',$reference);
        
        return $this; 
    }
}