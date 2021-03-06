<?php

class VCFF_Standard_Form_Item extends VCFF_Form_Item {
    
    public function Render() {
        // Convert attrs to vars
        extract(shortcode_atts(array(
            'form_attributes'=>'',
            'extra_class'=>'',
            'css'=>'',
        ), $this->form_attributes));
        // Compile the css class
        $css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts);
        // Retrieve the form contents
        $form_content = $this->form_render;
        // Retrieve the output html
        $form_content = apply_filters('vcff_form_item_content',$form_content,$this); 
        // Load the form content
        $form_content = do_shortcode($form_content);
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
        // Retrieve the output html
        $output_html = apply_filters('vcff_form_render_html',$output,$this);
        // Retrieve the output html
        $output_html = $this->Apply_Filters('render',$output_html,array());
        // Return the contents
        return $output_html;
    }

}
