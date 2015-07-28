<?php

class VCFF_Standard_Form_Item extends VCFF_Form_Item {

    public function Render() {
        // Convert attrs to vars
        extract(shortcode_atts(array(
            'form_attributes'=>'',
            'extra_class'=>'',
        ), $this->form_attributes));

        $form_content = $this->form_content;
        // Retrieve the output html
        $form_content = apply_filters('vcff_form_item_content',$form_content,$this); 
        // Load the form content
        $form_content = do_shortcode($form_content);
        // Start gathering content
        ob_start();
        // Include the template file
        include(vcff_get_file_dir(VCFF_FORMS_DIR.'/context/'.get_class($this).".tpl.php"));
        // Get contents
        $output = ob_get_contents();
        // Clean up
        ob_end_clean();
        // Retrieve the output html
        $output_html = apply_filters('vcff_form_render_html',$output,$this);
        // Return the contents
        return $output_html;
    }
    
    public function Post_Validation() {
    
        if ($this->is_valid) { return; }
        
        $this->Add_Alert('The form failed to valid','danger');
    }
    
}

add_filter('vcff_form_render_html', function($output,$form_instance){
    
    if (isset($form_instance->standard['html'])) { 
        
        return $form_instance->standard['html']; 
    }
    
    return $output;
    
},false,2);