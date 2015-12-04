<?php

vcff_map_form(array(
    'type' => 'vcff_standard_form',
    'title' => 'Standard Form',
    'class' => 'VCFF_Standard_Form_Item',
    'meta' => array(
        'pages' => array(),
        'groups' => array(),
        'fields' => array(
            array(
                'machine_code' => 'form_attributes',
                'label' => 'Form Attributes',
                'weight' => 2,
                'type' => 'textfield',
                'dependancy' => false,
            ),
            array(
                'machine_code' => 'form_extra_class',
                'label' => 'Extra Form Classes',
                'type' => 'textfield',
                'weight' => 5,
                'dependancy' => array(
                    'outcome' => 'show',
                    'requires' => 'all',
                    'conditions' => array(
                        array('form_attributes','is','hello')
                    )
                )
            )
        )
    )
));

add_filter('vcff_meta_field_list',function($meta_fields,$form_instance){
    // Retrieve the global vcff forms class
    $vcff_forms = vcff_get_library('vcff_forms');
    // Retrieve the form class
    $form_context = $vcff_forms->contexts;
    // If no context could be found
    if (!$form_context || !is_array($form_context)) { return; }
    // Storage var
    $contexts_list = array();
    // Loop through each form context
    foreach ($form_context as $type => $context) {
        // Populate the context list
        $contexts_list[$type] = $context['title'];
    }
    // Create the form type field
    $meta_fields[] = array(
        'machine_code' => 'form_type',
        'label' => 'Form Type',
        'type' => 'select',
        'validation' => array(
            'required' => true
        ),
        'default_value' => 'vcff_standard_form',
        'weight' => 1,
        'values' => $contexts_list
    );

    return $meta_fields;
}, 15, 2);

add_filter('vcff_meta_field_list',function($meta_fields,$form_instance){
    // Create the form type field
    $meta_fields[] = array(
        'machine_code' => 'use_ajax',
        'label' => 'Submit Via AJAX',
        'type' => 'select',
        'validation' => array(
            'required' => true
        ),   
        'required' => true,
        'weight' => 2,
        'default_value' => 'yes',
        'values' => array(
            'yes' => 'Yes, Use AJAX Submission',
            'no' => 'No, Use Standard Submission'
        )
    );

    return $meta_fields;
}, 15, 2);

add_filter('vcff_settings_field_list',function($field_list, $form_instance){
    
    $field_list['form_attributes'] = array(
        'machine_code' => 'form_attributes',
        'label' => 'Form Attributes',
        'weight' => 2,
        'validation' => array(),
        'type' => 'textfield',
        'dependancy' => false,
    );
    
    $field_list['form_extra_class'] = array(
        'machine_code' => 'form_extra_class',
        'label' => 'Extra Form Classes',
        'type' => 'textfield',
        'weight' => 5,
        'dependancy' => array(
            'outcome' => 'show',
            'requires' => 'all',
            'conditions' => array(
                array('form_attributes','is','hello')
            )
        )
    );
    
    return $field_list;
},10,2);
