<?php

class VCFF_Date_Field_Item extends VCFF_Field_Item {

    /**
         * RENDER FORM FIELD FOR INPUT (Required)
         * Use shortcode logic, attributes and template files
         * to display the form field shortcode within a form context
         */
    public function Form_Render() {  
        // Convert attrs to vars
        extract(shortcode_atts(array(
            'field_label'=>'',
            'view_label'=>'',
            'machine_code'=>'',
            'default_value'=>'',
            'allowed_dates'=>'',
            'date_format'=>'',
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
        // Include the template file
        include(vcff_get_file_dir(VCFF_FIELDS_DIR.'/context/'.get_class($this).".tpl.php"));
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Return the contents
        return $output;
    }
    
    public function Get_Years() {
        
        $allowed_dates = $this->attributes['allowed_dates'];
        
        $year_list = array();
        
        if (!$allowed_dates) {
            
            for ($y=0;$y<=100;$y++) {
                
                $year_list[] = date('Y',strtotime('-'.$y.' years'));
            }
            
        } else { 
            
            $date_items = explode('|',$allowed_dates); 
            
            $start_date = $this->_Parse_Date($date_items[0]);
            $end_date = $this->_Parse_Date($date_items[1]);

            $to_year = $end_date['year'];
            $num_years = $end_date['year']-$start_date['year'];
            for ($y=0;$y<=$num_years;$y++) {
                
                $year_list[] = $to_year-$y;
            }
        }
        
        return $year_list;
    }
    
    protected function _Parse_Date($date) {
    
        $date_day = '';
        $date_month = '';
        $date_year = '';
        // Explode the date
        $date_exploded = explode(':',$date);
        // Either a year or command was provided
        if (count($date_exploded) == 1) {
        
            if (is_numeric($date_exploded[0])) {
                $date_day = '01';
                $date_month = '01';
                $date_year = $date_exploded[0];
            } else { 
                $command_date = strtotime($date_exploded[0]);
                
                $date_day = date('d',$command_date);
                $date_month = date('m',$command_date);
                $date_year = date('Y',$command_date);
            }
        
        } // Otherwise a year and a month was provided 
        elseif (count($date_exploded) == 2) {
            $date_day = '01';
            $date_month = $date_exploded[0];
            $date_year = $date_exploded[1];
        } // Otherwise a full date was provided
        elseif (count($date_exploded) == 3) {
            $date_day = $date_exploded[0];
            $date_month = $date_exploded[1];
            $date_year = $date_exploded[2];
        }
        // Return the date values
        return array(
            'day' => $date_day,
            'month' => $date_month,
            'year' => $date_year
        );
    }
    
    
    public function Do_Validate() { return true;
        // Retrieve the posted day
        $day = $this->posted_value['day'];
        // Retrieve the posted month
        $month = $this->posted_value['month'];
        // Retrieve the posted year
        $year = $this->posted_value['year'];
        // If none of the date fields are filled out
        // If the date is required or any other validation, let gump handle that!
        if (!$day && !$month && !$year) { return true; }
        // If the date fails to validate
        if (!checkdate ($month , $day , $year)) { return 'The date is not a valid date'; }
        // Retrieve the allowed dates option
        $allowed_dates = $this->attributes['allowed_dates'];
        // If there are allowed dates specified
        if ($allowed_dates) {
            // Explode the allowed dates
            $date_items = explode('|',$allowed_dates); 
            // Retrieve the start date
            $date_start = $this->_Parse_Date($date_items[0]);
            // Retrieve the start date timestamp
            $date_start_stamp = strtotime($date_start['year'].'-'.$date_start['month'].'-'.$date_start['day']);
            // Retrieve the end date
            $date_end = $this->_Parse_Date($date_items[1]);
            // Retrieve the end date stamp
            $date_end_stamp = strtotime($date_end['year'].'-'.$date_end['month'].'-'.$date_end['day']);
            // Retrieve the end date stamp
            $posted_date_stamp = strtotime($year.'-'.$month.'-'.$day);
            
            if ($posted_date_stamp > $date_end_stamp || $posted_date_stamp < $date_start_stamp) {
                // Return an error
                return 'The date is not valid';
            }
        }
        // Return true
        return true;
    }
    
    public function Get_Stringified_Value() {
        // If no value was provided, return false
        if (!is_array($this->posted_value)) { return; }
        // Retrieve the posted day
        $day = $this->posted_value['day'];
        // Retrieve the posted month
        $month = $this->posted_value['month'];
        // Retrieve the posted year
        $year = $this->posted_value['year'];
        // Some of the fields are not filled out
        if (!$day || !$month || !$year) { return; }
        // Create a date string
        return $year.'-'.$month.'-'.$day;
    }
    
    /**
         * CONDITIONAL FUNCTIONS
         * 
         */        
    public function Check_Rule_IS($against) {
        // If no value was provided, return false
        if (!is_array($this->posted_value)) { return false; }
        // Retrieve the posted day
        $day = $this->posted_value['day'];
        // Retrieve the posted month
        $month = $this->posted_value['month'];
        // Retrieve the posted year
        $year = $this->posted_value['year'];
        // Some of the fields are not filled out
        if (!$day || !$month || !$year) { return true; }
        // Create a date string
        $date_string = $year.'-'.$month.'-'.$day;
        // If more or less than one value was posted through
        if ($date_string != $against) { return false; } else { return true; }
    }

    public function Check_Rule_IS_NOT($against) {
        // If no value was provided, return false
        if (!is_array($this->posted_value)) { return false; }
        // Retrieve the posted day
        $day = $this->posted_value['day'];
        // Retrieve the posted month
        $month = $this->posted_value['month'];
        // Retrieve the posted year
        $year = $this->posted_value['year'];
        // Some of the fields are not filled out
        if (!$day || !$month || !$year) { return true; }
        // Create a date string
        $date_string = $year.'-'.$month.'-'.$day;
        // If the first value does not match the against
        if ($date_string != $against) { return true; } else { return false; }
    }

    public function Check_Rule_GREATER_THAN($against) {
        // If no value was provided, return false
        if (!is_array($this->posted_value)) { return false; }
        // Retrieve the posted day
        $day = $this->posted_value['day'];
        // Retrieve the posted month
        $month = $this->posted_value['month'];
        // Retrieve the posted year
        $year = $this->posted_value['year'];
        // Some of the fields are not filled out
        if (!$day || !$month || !$year) { return true; }
        // Create a date string
        $date_string = $year.'-'.$month.'-'.$day;
        // Retrieve the end date stamp
        $date_string_stamp = strtotime($year.'-'.$month.'-'.$day);
        // Retrieve the end date stamp
        $against_stamp = strtotime($against);
        // If the number of posted values is higher
        if ($date_string_stamp > $against_stamp) { return true; } else { return false; }
    }

    public function Check_Rule_LESS_THAN($against) {
        // If no value was provided, return false
        if (!is_array($this->posted_value)) { return false; }
        // Retrieve the posted day
        $day = $this->posted_value['day'];
        // Retrieve the posted month
        $month = $this->posted_value['month'];
        // Retrieve the posted year
        $year = $this->posted_value['year'];
        // Some of the fields are not filled out
        if (!$day || !$month || !$year) { return true; }
        // Create a date string
        $date_string = $year.'-'.$month.'-'.$day;
        // Retrieve the end date stamp
        $date_string_stamp = strtotime($year.'-'.$month.'-'.$day);
        // Retrieve the end date stamp
        $against_stamp = strtotime($against);
        // If the number of posted values is lower
        if ($date_string_stamp < $against_stamp) { return true; } else { return false; }
    }

    public function Check_Rule_CONTAINS($against) {
        // No useful check
        return true;
    }

    public function Check_Rule_STARTS_WITH($against) {
        // No useful check
        return true;
    }

    public function Check_Rule_ENDS_WITH($against) {
        // No useful check
        return true;
    }
}