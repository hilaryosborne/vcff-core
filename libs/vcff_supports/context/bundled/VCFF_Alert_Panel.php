<?php

class VCFF_Alert_Panel {
    
    static $type = 'vcff_alert_panel';
    
    static $title = 'Alert Panels';
    
    static $item_class = 'VCFF_Alert_Panel_Item';

    static function VC_Params() {
        // Return any visual composer params
        return array(
            'params' =>  array(
                array (
                    "type" => "vcff_machine",
                    "heading" => __ ( "Machine Code", VCFF_FORM ),
                    "param_name" => "machine_code",
                ), 
                array (
                    'type' => 'textfield',
                    'heading' => __ ( 'Extra Class', VCFF_FORM ),
                    'param_name' => 'extra_class',
                ),
                array(
                    'type' => 'css_editor',
                    'heading' => __('CSS',VCFF_FORM),
                    'param_name' => 'css',
                    'group' => __('Design Options',VCFF_FORM),
                ),
                // FIELD CONDITIONAL PARAMETERS
                array (
                    'type' => 'vcff_conditional',
                    'heading' => false,
                    'param_name' => 'conditions',
                    'group' => 'Adv. Logic'
                )
            )
        );
    }
    
    static function Params() {
        // Return any field params
        return array();
    }
}

vcff_map_support('VCFF_Alert_Panel');