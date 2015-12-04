<?php

class Curly_User_IP extends VCFF_Item {
    
    public $form_instance;
    
    public $tag = 'user_ip';
    
    public $name = 'User IP Address';
    
    public $category = 'User';
    
    public $hint = 'user_ip';
    
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
        $tag = apply_filters('vcff_curly_tag_userip_data',$tag,$this);
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
            'hint' => $this->hint,
            'name' => $this->name,
            'method' => array($this,'_Render')
        );
        // Run through a filter for hints
        $hints = apply_filters('vcff_curly_tag_userip_hints',$hints,$this);
        // Return the hint list
        return $hints;
    }
    
    public function _Render() {
    
        $ipaddress = '';

        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        }
        else if(getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        }
        else if(getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        }
        else if(getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        }
        else if(getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        }
        else if(getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        }
        else { $ipaddress = 'UNKNOWN'; }

        return $ipaddress;
    }
}

vcff_map_context('Curly_User_IP');
