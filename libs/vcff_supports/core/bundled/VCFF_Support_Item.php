<?php

class VCFF_Support_Item extends VCFF_Item {

    /**
         * ATTRIBUTES
         * Attributes for this field instance
         */
    public $attributes;
    
    /**
     * THE FORM INSTANCE
     */
    public $form_instance;
    
    public $container_instance;
    
    public $is_hidden = false;

    public $is_valid = true;

    public function Is_Hidden() {

        return $this->is_hidden;
    }

    public function Is_Visible() {

        return $this->is_hidden ? false : true;
    }

    public function Is_Valid() {

        return $this->is_valid;
    }
    
    /**
    * CONTEXT DATA
    * The class which handles vc integration
    */
    public $context;
    
    public function Check_Conditions() { 
		// Retrieve the field's attributes
        $_attributes = $this->attributes;
        // If there are no conditions set
        if (!isset($_attributes['conditions'])) { return; }
        // Retrieve the conditions data
        $_conditions = json_decode(base64_decode($_attributes['conditions']),true);
        // If there are no rules
        if (!isset($_conditions['rules']) || count($_conditions['rules']) == 0) { return; }
        // Create a new conditions item
        $conditions_item = new VCFF_Conditions_Item($this);
        // Check the conditions item
        $conditions_item
            ->Set_Form_Instance($this->form_instance)
            ->Set_Rules($_conditions['rules'])
            ->Prepare()
            ->Check_Rules(); 
        // Retrieve the result settings
        $_settings_result = $_conditions['result'];
        // Retrieve the matching settings
        $_settings_match = $_conditions['match'];
        // Retrieve the number of triggered rules
        $_triggered = count($conditions_item->Get_Triggered());
        // Retrieve the number of non triggered rules
        $_non_triggered = count($conditions_item->Get_Non_Triggered());
        // If the container is to be show on passing conditions
        if ($_settings_result == 'show') {
            // If we require all fields to pass
            if ($_settings_match == 'all') {
                // The container will be visible if no conditions failed
                $this->is_hidden = $_non_triggered == 0 ? false : true; 
            } // Otherwise if we only require some conditions to pass 
            elseif ($_settings_match == 'any') {
                // The container will be visible if at least one conditions passed
                $this->is_hidden = $_triggered != 0 ? false : true;
            }
        } // Otherwise if the container is to be hidden on passing conditions 
        elseif ($_settings_result == 'hide') {
            // If we require all fields to pass
            if ($_settings_match == 'all') {
                // The container will not be visible if no conditions failed
                $this->is_hidden = $_non_triggered == 0 ? true : false; 
            } // Otherwise if we only require some conditions to pass 
            elseif ($_settings_match == 'any') {
                // The container will not be visible if at least one conditions passed
                $this->is_hidden = $_triggered != 0 ? true : false;
            }
        }
	}
}