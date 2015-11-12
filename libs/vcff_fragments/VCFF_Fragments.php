<?php 

if(!defined('VCFF_FRAGMENTS_DIR'))
{ define('VCFF_FRAGMENTS_DIR',untrailingslashit(plugin_dir_path(__FILE__ ))); }

if (!defined('VCFF_FRAGMENTS_URL'))
{ define('VCFF_FRAGMENTS_URL',untrailingslashit(plugins_url('/', __FILE__ ))); }
 
class VCFF_Fragments {

    // The list of contexts
    public $contexts = array();

    public function Init() {
        // Fire the shortcode init action
        do_action('vcff_fragments_before_init',$this);
		// Include the admin class
        require_once(VCFF_FRAGMENTS_DIR.'/functions.php'); 
        // Initalize core logic
        add_action('vcff_init_core',array($this,'__Init_Core'),15);
        // Action to register the page
        add_action('admin_menu', array($this,'Register_Pages'));
        // Initalize core logic
        add_action('vcff_init_core',array($this,'__Init_Core'));
        // Initalize context logic
        add_action('vcff_init_context',array($this,'__Init_Context'));
        // Initalize misc logic
        add_action('vcff_init_misc',array($this,'__Init_Misc'));
        // Include the admin class
        add_action('save_post', array($this,'_Hook_Save_Post'));
        // Fire the shortcode init action
        do_action('vcff_fragments_after_init',$this);
        add_filter('vcff_field_pre_parse', array($this, '_Hook_Field_Pre_Parse'));
        add_filter('vcff_container_pre_parse', array($this, '_Hook_Container_Pre_Parse'));
        // Add the css to scripts
        add_action('wp_print_scripts', array($this,'Inject_CSS'));
    }
    
    public function __Init_Core() {
        // Load the custom post type
        $this->_Load_Post_Type();
        // Fire the shortcode init action
        do_action('vcff_fragments_init_core',$this);
    }
    
    public function __Init_Context() {
        // Fire the shortcode init action
        do_action('vcff_fragments_init_context',$this);
    }
    
    public function __Init_Misc() {
        // Fire the shortcode init action
        do_action('vcff_fragments_init_misc',$this);
    }
 
    public function Register_Pages() {
        // Add the page sub menu item
        add_submenu_page('edit.php?post_type=vcff_form', 'Fragments', 'Fragments', 'edit_posts', 'edit.php?post_type=vcff_fragment');
        // Add the page sub menu item
        add_submenu_page('edit.php?post_type=vcff_form', 'Add Fragment', 'Add Fragment', 'edit_posts', 'post-new.php?post_type=vcff_fragment');
        // Fire the shortcode init action
        do_action('vcff_fragments_pages_init',$this);
    }

    protected function _Load_Post_Type() { 
        // If the post type already exists
        if (post_type_exists('vcff_fragment')) { return; }
        // The filter data
        $filter = array(
            'labels' => array(
                'name' => __( 'Fragment', VCFF_FORM ),
                'singular_name' => __( 'Fragment', VCFF_FORM ),
                'menu_name' => __( 'Fragment', 'Admin menu name', VCFF_FORM ),
                'add_new' => __( 'Add Fragment', VCFF_FORM ),
                'add_new_item' => __( 'Add New Fragment', VCFF_FORM ),
                'edit' => __( 'Edit', VCFF_FORM ),
                'edit_item' => __( 'Edit Fragment', VCFF_FORM ),
                'new_item' => __( 'New Fragment', VCFF_FORM ),
                'view' => __( 'View Fragment', VCFF_FORM ),
                'view_item' => __( 'View Fragment', VCFF_FORM ),
                'search_items' => __( 'Search Fragments', VCFF_FORM ),
                'not_found' => __( 'No Fragments found', VCFF_FORM ),
                'not_found_in_trash' => __( 'No Fragments found in trash', VCFF_FORM ),
                'parent' => __( 'Parent Fragment', VCFF_FORM )
            ),
            'description' => __( 'This is where you can add new fragment.', VCFF_FORM ),
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => false,
            'map_meta_cap' => true,
            'publicly_queryable' => false,
            'exclude_from_search' => false,
            'hierarchical' => false,
            'rewrite' => false,
            'query_var' => false,
            'supports' => array('title', 'editor'),
            'show_in_nav_menus' => false,
            'show_in_admin_bar' => false,
            'menu_icon' => 'dashicons-menu'
        );
        // Register the custom post type for the vcff forms
        register_post_type("vcff_fragment",apply_filters('vcff_fragment_register_post_type',$filter));
        // Update the form framework option
        $this->_Form_Framework_Option_Update();
        // Update visual composer if required
        $this->_Visual_Composer_Option_Update();
    }

    protected function _Form_Framework_Option_Update() {
        // Retrieve the current content types option
        $vcff_shortcode_allowed = get_option('vcff_shortcode_content_types');
        // If there are currently no content type options
        if (!is_array($vcff_shortcode_allowed)) { $vcff_shortcode_allowed = array('vcff_form'); }
        // If custom type is not present
        if (!in_array('vcff_fragment', $vcff_shortcode_allowed)) {
            // Update the content types with the vcff fragment
            $vcff_shortcode_allowed[] = 'vcff_fragment';
            // Update vcff
            update_option('vcff_shortcode_content_types', $vcff_shortcode_allowed);
        }
    }

    protected function _Visual_Composer_Option_Update() {
        // The option type
        $wpb_js_content_types = '';
        // Determine the option type
        if (class_exists('WPBakeryVisualComposer') 
            && method_exists('WPBakeryVisualComposer','isTheme') 
            && WPBakeryVisualComposer::getInstance()->isTheme()) { $wpb_js_content_types = 'wpb_js_theme_content_types'; } 
        else { $wpb_js_content_types = 'wpb_js_content_types'; }
        // Retrieve the current content types option
        $vc_content_types = get_option($wpb_js_content_types);
        // If there are currently no content type options
        if (!is_array($vc_content_types)) { $vc_content_types = array('page'); }
        // If custom type is not present
        if (!in_array('vcff_fragment', $vc_content_types)) {
            // Update the content types with the vcff fragment
            $vc_content_types[] = 'vcff_fragment';
            // Update vcff
            update_option($wpb_js_content_types, $vc_content_types);
        }
    }

    public function Load_Shortcodes() { 
        // Add the render function
        add_shortcode('vcff_fragment', function($attr,$content,$shortcode) {
            // Retrieve the fragment post            
            $post = vcff_get_fragment_by_uuid($attr['fragment_uuid']);
            // If no fragment id was found
            if (!$post) { ''; }
            // Retrieve the post content
            $post_content = $post->post_content;
            // Build the fragment content
            $html = '<div class="vff-fragment">';
            $html .= '   <div class="fragment-inner">'.do_shortcode($post_content).'</div>';
            $html .= '</div>';
            // Fragments are actually inserted via hook vcff_form_contents
            return $html;
        });
        // Fire the shortcode init action
        do_action('vcff_fragments_shortcode_init',$this);
    }
    
    public function Map_Visual_Composer() { 
        // If this is not the form edit page
        if (!vcff_allow_field_vc_shortcodes()) { return; }
        // Retrieve the global wordpress database layer
        global $wpdb;
        // Check the vcff_form post type exists
        if (!post_type_exists('vcff_fragment')){ return; } 
        // Retrieve a list of all the published vv forms
        $published = $wpdb->get_results("SELECT ID, post_title 
	        FROM $wpdb->posts
	        WHERE post_status = 'publish'
            AND post_type = 'vcff_fragment'"); 
        // If no published posts were returned
        if (!$published) { return; }
        
        $fragment_list = array();
        
        $fragment_list['Please select a fragment'] = '';

        // Loop through each published post
        foreach ($published as $k => $_post) { 
            // Retrieve the post object
            $_post = get_post($_post->ID);
            // Retrieve the fragment id
            $fragment_uuid = vcff_get_uuid_by_fragment($_post->ID);
            // Build the visual select list
            $fragment_list[$_post->post_title] = $fragment_uuid;
        }
        // Run the params through a filter
        $params = apply_filters('vcff_fragment_vc_params',array(
            array (
                "type" => "dropdown",
                "heading" => __ ("Form Fragment", VCFF_FORM ),
                "param_name" => "fragment_uuid",
                "admin_label" => true,
                "value" => $fragment_list
            ),
            array (
                'type' => 'textfield',
                'heading' => __ ('Extra Class', VCFF_FORM ),
                'param_name' => 'extra_class',
            ),
        ),$_context_data);
        // Map the form to visual composer
        vc_map(array(
            "name" => __('Form Fragment'),
            "icon" => "icon-ui-splitter-horizontal",
            "base" => 'vcff_fragment',
            "class" => "",
            "category" => isset($_context_data['vc_category']) ? $_context_data['vc_category'] : __('Form Controls', VCFF_NS),
            "params" => $params
        ));
        // Fire the vc init action
        do_action('vcff_fragments_vc_init',$this);
    }
    
    public function Inject_CSS() {
        // Retrieve all of the fragments
        $fragments = get_posts(array(
            'post_type' => 'vcff_fragment',
            'numberposts' => - 1
        ));
        // If no fragments were returned, return out
        if (!$fragments || !is_array($fragments) || count($fragments) == 0) { return; }
        // Return a new inline style block
        echo '<style type="text/css" data-type="vc_shortcodes-custom-css">';
        // Loop through each block
        foreach ($fragments as $k => $post) {
            // Retrieve the custom css
            $custom_css = get_post_meta($post->ID,'_wpb_shortcodes_custom_css',true);
            // If no custom css, move on
            if (!$custom_css) { continue; }
            // Return the custom css
            echo $custom_css;
        }
        // Echo the style
        echo '</style>';
    }
    
    public function _Hook_Save_Post($post_id) {
        // Attempt to retrieve the uuid
        $fragment_uuid = get_post_meta($post_id, 'fragment_uuid', true); 
        // If the post does not have a uuid
        if (!$fragment_uuid) {
            // Update the fragment with a new uuid
            update_post_meta($post_id, 'fragment_uuid', uniqid(), true);
        }
    }
    
    public function _Hook_Field_Pre_Parse($text) {
    
        return vcff_parse_fragment($text);
    }
    
    public function _Hook_Container_Pre_Parse($text) {
    
        return vcff_parse_fragment($text);
    }

}

$vcff_fragments = new VCFF_Fragments();

vcff_register_library('vcff_fragments',$vcff_fragments);

$vcff_fragments->Init();