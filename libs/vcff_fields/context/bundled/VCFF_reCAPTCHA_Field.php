<?php

vcff_map_field(array(
    'type' => 'vcff_recaptcha_field',
    'title' => 'reCAPTCHA',
    'class' => 'VCFF_reCAPTCHA_Field_Item',
    'conditional_logic' => array(),
    'validation_logic' => array(),
    'vc_map' => array(
        'params' => array(
            array (
                "type" => "vcff_heading",
                "heading" => false,
                "param_name" => "field_heading",
                'html_title' => 'VCFF Fields',
                'html_description' => 'You can set this field to accept dynamic values from either POST, GET or REQUEST variables. This is useful if you have forms posting to each other or if you want to refill form fields via a URL link.',
                'help_url' => 'http://blah',
            ),
            array (
                "type" => "vcff_machine",
                "heading" => __ ( "Machine Code", VCFF_FORM ),
                "param_name" => "machine_code",
            ), 
            // VC CSS EDITOR
            array(
                'type' => 'css_editor',
                'heading' => __('CSS',VCFF_FORM),
                'param_name' => 'css',
                'group' => __('Design Options',VCFF_FORM),
            ),
        )
    )
));

// Register the vcff admin css
add_action('wp_footer', function(){
    echo '<script src="//www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit" async defer></script>';
},100);

add_filter('vcff_settings_group_list',function($group_list, $form_instance){
    
    $group_list[] = array(
        'id' => 'recaptcha_config',
        'title' => 'reCAPTCHA Configuration',
        'weight' => 5,
        'hint_html' => '<h4><strong>Instructions</strong></h4><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur cursus erat at lectus commodo tempor eget vel turpis. Praesent vitae eros semper, aliquet ipsum vel, porttitor tellus.</p>',
        'help_url' => 'http://vcff.theblockquote.com',
    );
    
    return $group_list;
},0,2);

add_filter('vcff_settings_field_list',function($field_list, $form_instance){
    
    $field_list[] = array(
        'machine_code' => 'recaptcha_site_key',
        'label' => 'Site Key',
        'group' => 'recaptcha_config',
        'weight' => 1,
        'type' => 'textfield',
        'dependancy' => false
    );
    
    $field_list[] = array(
        'machine_code' => 'recaptcha_secret_key',
        'label' => 'Secret Key',
        'group' => 'recaptcha_config',
        'weight' => 2,
        'type' => 'textfield',
        'dependancy' => false
    );
    
    return $field_list;
},0,2);