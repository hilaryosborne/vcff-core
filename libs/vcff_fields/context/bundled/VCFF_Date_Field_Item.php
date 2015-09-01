<?php

class VCFF_Date_Field_Item extends VCFF_Field_Item {

    public function __construct() {
    
        $this->Add_Action('create',array($this,'_Create'));
    }

    public function Form_Render() {  
        // Convert attrs to vars
        extract(shortcode_atts(array(
            'field_label'=>'',
            'view_label'=>'',
            'machine_code'=>'',
            'default_value'=>'',
            'display_mode'=>'',
            'min_date' => '',
            'max_date' => '',
            'output_format' => '',
            'dynamically_populate'=>'',
            'validation'=>'',
            'conditions'=>'',
            'extra_class'=>'',
            'css'=>'',
        ), $this->attributes));
        // Compile the css class
        $css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $this->attributes);
        // Start gathering content
        ob_start();
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Include the template file
        include(vcff_get_file_dir($dir.'/'.get_class($this).".tpl.php"));
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Return the contents
        return $output;
    }
    
    public function _Create() {

        if (!$this->posted_value) { return; }
        
        if (!is_array($this->posted_value)) { return; }
        
        if (!$this->posted_value['yyyy'] || !$this->posted_value['mm'] || !$this->posted_value['dd']) {
            
            $this->posted_value = false;
            
        } else {
            
            // Build the date string
            $_date_string = $this->posted_value['yyyy'].'-'.$this->posted_value['mm'].'-'.$this->posted_value['dd'];

            $_date_obj = new DateTime($_date_string);

            $this->posted_value = $_date_obj->format('Y-m-d');
        }
    }
    
    public function Do_Validate() {
        
        $posted_value = $this->posted_value;
        
        if (!$posted_value) { return; }
        
        $posted_datetime = new DateTime($posted_value);
        
        $max_date = $this->_Get_Max_Date(false);

        if ($max_date && $max_date->getTimestamp() < $posted_datetime->getTimestamp()) {
            $this->Add_Alert('The date can be no more than '.$max_date->format('Y-m-d'),'danger');         
            $this->is_valid = false;
            return;
        }
        
        $min_date = $this->_Get_Min_Date(false);
        
        if ($min_date && $min_date->getTimestamp() > $min_date->getTimestamp()) {
            $this->Add_Alert('The date can be no less than '.$min_date->format('Y-m-d'),'danger');         
            $this->is_valid = false;
            return;
        }
    }
    
    public function _Get_Max_Date($default='+100 years') {
        
        if (!$default) { return $this->attributes['max_date'] ? new DateTime($this->attributes['max_date']) : false; }
         
        return $this->attributes['max_date'] ? new DateTime($this->attributes['max_date']) : new DateTime($default) ;
    }
    
    public function _Get_Min_Date($default='-100 years') {
        
        if (!$default) { return $this->attributes['min_date'] ? new DateTime($this->attributes['min_date']) : false; }
         
        return $this->attributes['min_date'] ? new DateTime($this->attributes['min_date']) : new DateTime($default) ;
    }
    
    public function _Get_Date() {
        
        return $this->posted_value ? new DateTime($this->posted_value) : false;
    }

    public function _Get_Date_Day() {
        
        $_date = $this->_Get_Date();
        
        if (!$_date) { return; }
        
        return $_date->format('d');
    }
    
    public function _Get_Date_Month() {
    
        $_date = $this->_Get_Date();
        
        if (!$_date) { return; }
        
        return $_date->format('m');
        
    }
    
    public function _Get_Date_Year() {
        
        $_date = $this->_Get_Date();
        
        if (!$_date) { return; }
        
        return $_date->format('Y');
    }
    
    /**
     * Select Mode Methods
     */
    
    protected function _Select_Get_Years() {    
        $max_timestamp = $this->_Get_Max_Date();
        $min_timestamp = $this->_Get_Min_Date();
        $_years_num = $max_timestamp->diff($min_timestamp)->y;
        // If less than or equal to a year
        if ($_years_num < 2) { return array(date('Y',$min_timestamp)); }
        // Add one year
        $_years_num++;
        // List to store the years in
        $_years = array(); 
        // Generate the years
        for ($i=0;$i<$_years_num;$i++) {
            // Generate the year
            $_years[] = date('Y',strtotime('+'.$i.' year',$min_timestamp->getTimestamp()));
        }
        return $_years;
    }

    /**
         * CONDITIONAL FUNCTIONS
         * 
         */        
    public function Check_Rule_IS($against) {
        // If no value was provided, return false
        if (!$this->posted_value) { return false; }
        // Generate datetime objects
        $posted_datetime = new DateTime($this->posted_value);
        $against_datetime = new DateTime($against);
        // If more or less than one value was posted through
        if ($posted_datetime->format('Y-m-d') != $against_datetime->format('Y-m-d')) { return false; } else { return true; }
    }

    public function Check_Rule_IS_NOT($against) {
        // If no value was provided, return false
        if (!$this->posted_value) { return false; }
        // Generate datetime objects
        $posted_datetime = new DateTime($this->posted_value);
        $against_datetime = new DateTime($against);
        // If the first value does not match the against
        if ($posted_datetime->format('Y-m-d') != $against_datetime->format('Y-m-d')) { return true; } else { return false; }
    }

    public function Check_Rule_GREATER_THAN($against) {
        // If no value was provided, return false
        if (!$this->posted_value) { return false; }
        // Generate datetime objects
        $posted_datetime = new DateTime($this->posted_value);
        $against_datetime = new DateTime($against);
        // If the number of posted values is higher
        if ($posted_datetime->getTimestamp() > $against_datetime->getTimestamp()) { return true; } else { return false; }
    }

    public function Check_Rule_LESS_THAN($against) {
        // If no value was provided, return false
        if (!$this->posted_value) { return false; }
        // Generate datetime objects
        $posted_datetime = new DateTime($this->posted_value);
        $against_datetime = new DateTime($against);
        // If the number of posted values is lower
        if ($posted_datetime->getTimestamp() < $against_datetime->getTimestamp()) { return true; } else { return false; }
    }
    
}