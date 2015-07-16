<?php

class VCFF_Templater_Tags {

    static $tags = array();
    
    static function Add_Tag($tag_name,$tag_code,$tag_hint,$tag_method) {
    
        self::$tags[$tag_code] = array(
            'code' => $tag_code,
            'hint' => $tag_hint,
            'name' => $tag_name,
            'method' => $tag_method
        );
        
    }
}

class VCFF_Events_Helper_Templater {

    protected $form_instance;	
	
	public function Set_Form_Instance($form_instance) {
		
		$this->form_instance = $form_instance;
		
		return $this;
	}
    
    public function Render($text) { 
        
        $form_instance = $this->form_instance;
        
        preg_match_all('/{([^}]*)}/', $text, $matches);
        
        if (!$matches || !is_array($matches) || !is_array($matches[1])) { return $text; }
        
        $tags_found = $matches[1];
        
        $tags_rules = $this->Get_Tag_List();
        
        foreach ($tags_found as $k => $tag_string) {
            
            $tag_args = explode(':',$tag_string);
            
            $tag_code = $tag_args[0];
            
            if (!isset($tags_rules[$tag_code])) { continue; }
            
            $tag_rule = $tags_rules[$tag_code];
            
            $tag_rule_args = array($form_instance);
            
            if ($tag_args && is_array($tag_args)) {
            $i=0; foreach ($tag_args as $_k => $value) { 
            
                if ($i != 0) { $tag_rule_args[] = $value; }
                
                $i++;
            }
            }
            
            $tag_contents = call_user_func_array($tag_rule['method'],$tag_rule_args);
            
            $text = str_replace("{".$tag_string."}",$tag_contents,$text);
        }

        return $text;
    }
    
    public function Get_Tag_List() {
        
        $tag_list = VCFF_Templater_Tags::$tags;
        
        $form_instance = $this->form_instance;
        
        $form_params = $form_instance->context['params'];
        
        if (isset($form_params['tags']) && is_array($form_params['tags'])) {
        
            foreach ($form_params['tags'] as $k => $tag) {
            
                $tag_list[$tag[0]] = array(
                    'code' => $tag[0],
                    'hint' => $tag[1],
                    'name' => $tag[2],
                    'method' => $tag[3]
                );    
            }
        
        }
        
        return $tag_list;
    }
    
    protected function _Get_Field_Tags() {
    
    }
}

VCFF_Templater_Tags::Add_Tag('User IP Address','user_ip','user_ip',function($form_instance){ 
    
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
});

VCFF_Templater_Tags::Add_Tag('Date DD/MM/YYYY','date_dd_mm_yyyy','date_dd_mm_yyyy',function($form_instance){ 
    
    return date('d/m/Y'); 
});

VCFF_Templater_Tags::Add_Tag('Date MM/DD/YYYY','date_mm_dd_yyyy','date_mm_dd_yyyy',function($form_instance){ 
    
    return date('m/d/Y'); 
});

VCFF_Templater_Tags::Add_Tag('Field Value (Text Version)','field_text','field_text:name',function($form_instance,$field_name){ 
    
    $field_instance = $form_instance->Get_Field($field_name);
    
    if (!$field_instance) { return; }
    
    return $field_instance->Get_TEXT_Value(); 
});

VCFF_Templater_Tags::Add_Tag('Field Value (HTML Version)','field_html','field_html:name',function($form_instance,$field_name){ 
    
    $field_instance = $form_instance->Get_Field($field_name);
    
    if (!$field_instance) { return; }
    
    return $field_instance->Get_HTML_Value(); 
});
