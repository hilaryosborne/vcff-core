<?php

class VCFF_Fields_Admin {

    public function __construct() {
 
        $this->_Load_Parameters();
    }
    
    protected function _Load_Parameters() {
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_FIELDS_DIR.'/parameters') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // If this is not false
            if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; }
            // Include the file
            require_once(VCFF_FIELDS_DIR.'/parameters/'.$FileInfo->getFilename());
            // Retrieve the classname
            $param_classname = $FileInfo->getBasename('.php');
            // Create a new instance of the param
            new $param_classname();
        }
    }
}

global $vcff_fields_admin;

$vcff_fields_admin = new VCFF_Fields_Admin();