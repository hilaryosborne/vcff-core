<?php

vcff_map_container(array(
    'type' => 'vcff_container',
    'title' => 'Standard Container',
    'class' => 'VCFF_Standard_Container_Item',
    'filter_logic' => array(),
    'conditional_logic' => array(),
    'validation_logic' => array(),
    'vc_map' => array(
        'admin_enqueue_js' => VCFF_CONTAINERS_URL.'/assets/admin/vcff_container.js',
        'admin_enqueue_css' => VCFF_CONTAINERS_URL.'/assets/admin/vcff_container.css',
        'params' => array(
            // add params same as with any other content element
            array (
                "type" => "vcff_heading",
                "heading" => false,
                "param_name" => "container_heading",
                'html_title' => 'VCFF Container',
                'html_description' => 'You can set this field to accept dynamic values from either POST, GET or REQUEST variables. This is useful if you have forms posting to each other or if you want to refill form fields via a URL link.',
                'help_url' => 'http://blah',
            ),
            array (
                "type" => "vcff_machine",
                "heading" => __ ( "Machine Code", VCFF_FORM ),
                "param_name" => "machine_code",
            ), 
            // CORE FIELD SETTINGS
            array (
                "type" => "vcff_heading",
                "heading" => false,
                "param_name" => "label_heading",
                'html_title' => 'Container Labels',
                'html_description' => 'You can set this field to accept dynamic values from either POST, GET or REQUEST variables. This is useful if you have forms posting to each other or if you want to refill form fields via a URL link.',
            ),
            array(
                "type" => "textfield",
                "heading" => __("Container Label", VCFF_NS),
                "param_name" => "label"
            ),
            array(
                "type" => "textfield",
                "heading" => __("Extra class name", VCFF_NS),
                "param_name" => "extra_class",
            ),
            array (
                'type' => 'vcff_conditional',
                'heading' => false,
                'param_name' => 'conditions',
                'group' => 'Adv. Logic'
            ),
            array(
                'type' => 'css_editor',
                'heading' => __( 'Css', 'my-text-domain' ),
                'param_name' => 'css',
                'group' => __( 'Design Options', 'my-text-domain' ),
            ),
        ),
        'js_view' => 'VCFFStandardContainerView',
    )
));

class WPBakeryShortCode_VCFF_Container extends WPBakeryShortCodesContainer {
    
    /**
		 * @param $controls
		 * @param string $extended_css
		 *
		 * @return string
		 */
    public function getCntrControls($extended_css = '' ) {
        $output = '<div class="vc_controls vc_controls-visible controls controls_cntr controls_column' . ( ! empty( $extended_css ) ? " {$extended_css}" : '' ) . '">';
        $output .= '<a class="vc_control column_move" data-vc-control="move" href="#" title="' . sprintf( __( 'Move this %s', 'js_composer' ), strtolower( $this->settings( 'name' ) ) ) . '"><span class="vc_icon"></span></a>';
        $output .= '<span class="cntr-lbl"><strong>Container Header</span>';
        $output .= '<span class="controls_column_lnks">';
        $output .= '    <a class="vc_control column_add" data-vc-control="add" href="#" title="' . $control_title . '"><span class="vc_icon"></span></a>';
        $output .= '    <a class="vc_control column_edit" data-vc-control="edit" href="#" title="' . sprintf( __( 'Edit this %s', 'js_composer' ), strtolower( $this->settings( 'name' ) ) ) . '"><span class="vc_icon"></span></a>';
        $output .= '    <a class="vc_control column_clone" data-vc-control="clone" href="#" title="' . sprintf( __( 'Clone this %s', 'js_composer' ), strtolower( $this->settings( 'name' ) ) ) . '"><span class="vc_icon"></span></a>';
        $output .= '    <a class="vc_control column_delete" data-vc-control="delete" href="#" title="' . sprintf( __( 'Delete this %s', 'js_composer' ), strtolower( $this->settings( 'name' ) ) ) . '"><span class="vc_icon"></span></a>';
        $output .= '</span>';
        $output .= '</div>';
        
        return $output;
    }
    
    /**
		 * @param $atts
		 * @param null $content
		 *
		 * @return string
		 */
    public function contentAdmin( $atts, $content = null ) { 
        $width = $el_class = ''; 
        $atts  = shortcode_atts( $this->predefined_atts, $atts );
        extract( $atts );
        $this->atts = $atts;
        $output = '';

        $column_controls = $this->getCntrControls();
        $column_controls_bottom = $this->getColumnControls( 'add', 'bottom-controls' );
        for ( $i = 0; $i < count( $width ); $i ++ ) {
            $output .= '<div ' . $this->mainHtmlBlockParams( $width, $i ) . '>';
            $output .= $column_controls;
            $output .= '<div class="wpb_element_wrapper">';
            $output .= $this->outputTitle( $this->settings['heading'] );
            $output .= '<div ' . $this->containerHtmlBlockParams( $width, $i ) . '>';
            $output .= do_shortcode( shortcode_unautop( $content ) );
            $output .= '</div>';
            if ( isset( $this->settings['params'] ) ) {
                $inner = '';
                foreach ( $this->settings['params'] as $param ) {
                    $param_value = isset( $$param['param_name'] ) ? $$param['param_name'] : '';
                    if ( is_array( $param_value ) ) {
                        // Get first element from the array
                        reset( $param_value );
                        $first_key   = key( $param_value );
                        $param_value = $param_value[ $first_key ];
                    }
                    $inner .= $this->singleParamHtmlHolder( $param, $param_value );
                }
                $output .= $inner;
            }
            $output .= '</div>';
            $output .= $column_controls_bottom;
            $output .= '</div>';
        }

        return $output;
    }

}
