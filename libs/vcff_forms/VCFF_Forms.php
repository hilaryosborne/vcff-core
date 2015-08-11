<?php 

if(!defined('VCFF_FORMS_DIR'))
{ define('VCFF_FORMS_DIR',untrailingslashit(plugin_dir_path(__FILE__ ))); }

if (!defined('VCFF_FORMS_URL'))
{ define('VCFF_FORMS_URL',untrailingslashit(plugins_url('/', __FILE__ ))); }
 
class VCFF_Forms {
    
    // Set the focused post id
    public $vcff_focused_form;
    // Set the focused post id
    public $vcff_focused_post_id;
    // Set the focused post id
    public $vcff_focused_form_id;
    // The list of contexts
    public $contexts = array();

    public $cached_forms = array();

    public function Init() {
        // Fire the shortcode init action
        do_action('vcff_forms_before_init',$this);
		// Include the admin class
        require_once(VCFF_FORMS_DIR.'/functions.php'); 
        // Initalize core logic
        add_action('vcff_init_core',array($this,'__Init_Core'));
        // Initalize context logic
        add_action('vcff_init_context',array($this,'__Init_Context'));
        // Initalize misc logic
        add_action('vcff_init_misc',array($this,'__Init_Misc'));
        // Fire the shortcode init action
        do_action('vcff_forms_init',$this);
        // Include the admin class
        require_once(VCFF_FORMS_DIR.'/VCFF_Forms_Admin.php');
        // Otherwise if this is being viewed by the client 
        require_once(VCFF_FORMS_DIR.'/VCFF_Forms_Public.php');
        // Fire the shortcode init action
        do_action('vcff_forms_after_init',$this);
    }
    
    public function __Init_Core() {
        // Load the custom post type
        $this->_Load_Post_Type();
        // Load helper classes
        $this->_Load_Helpers();
        // Load the core classes
        $this->_Load_Core(); 
        // Fire the shortcode init action
        do_action('vcff_forms_init_core',$this);
    }

    public function __Init_Context() {
        // Load the context classes
        $this->_Load_Context();
        // Fire the shortcode init action
        do_action('vcff_forms_init_context',$this);
    }
    
    public function __Init_Misc() {
        // Load the pages
        $this->_Load_Pages();
        // Load AJAX
        $this->_Load_AJAX();
        // Fire the shortcode init action
        do_action('vcff_forms_init_misc',$this);
    }

    protected function _Load_Post_Type() {
        // If the post type already exists
        if (post_type_exists('vcff_form')) { return; }
        // The filter data
        $filter = array(
            'labels' => array(
                'name' => __( 'Forms', VCFF_FORM ),
                'singular_name' => __( 'Form', VCFF_FORM ),
                'menu_name' => __( 'Forms', 'Admin menu name', VCFF_FORM ),
                'add_new' => __( 'Add Form', VCFF_FORM ),
                'add_new_item' => __( 'Add New Form', VCFF_FORM ),
                'edit' => __( 'Edit', VCFF_FORM ),
                'edit_item' => __( 'Edit Form', VCFF_FORM ),
                'new_item' => __( 'New Form', VCFF_FORM ),
                'view' => __( 'View Form', VCFF_FORM ),
                'view_item' => __( 'View Form', VCFF_FORM ),
                'search_items' => __( 'Search Forms', VCFF_FORM ),
                'not_found' => __( 'No Forms found', VCFF_FORM ),
                'not_found_in_trash' => __( 'No Forms found in trash', VCFF_FORM ),
                'parent' => __( 'Parent Form', VCFF_FORM )
            ),
            'description' => __( 'This is where you can add new form.', VCFF_FORM ),
            'public' => false,
            'show_ui' => true,
            'map_meta_cap' => true,
            'publicly_queryable' => false,
            'exclude_from_search' => false,
            'hierarchical' => false,
            'rewrite' => false,
            'query_var' => false,
            'supports' => array( 'title', 'editor'),
            'show_in_nav_menus' => false,
            'show_in_admin_bar' => true,
            'menu_icon' => 'dashicons-menu'
        );
        // Register the custom post type for the vcff forms
        register_post_type("vcff_form",apply_filters('vcff_form_register_post_type',$filter));
        // Update the form framework option
        $this->_Form_Framework_Option_Update();
        // Update visual composer if required
        $this->_Visual_Composer_Option_Update();
    }

    protected function _Form_Framework_Option_Update() {
        // Retrieve the current content types option
        $vcff_shortcode_allowed = get_option('vcff_shortcode_content_types');
        // If custom type is not present
        if (!is_array($vcff_shortcode_allowed) 
            || !in_array('vcff_form', $vcff_shortcode_allowed)) {
            // If they are not a shortcode
            if (!is_array($vcff_shortcode_allowed)) { $vcff_shortcode_allowed = array(); }
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
        if (!in_array('vcff_form', $vc_content_types)) {
            // Update the content types with the vcff fragment
            $vc_content_types[] = 'vcff_form';
            // Update vcff
            update_option($wpb_js_content_types, $vc_content_types);
        }
    }

    protected function _Load_Helpers() {
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Load each of the form shortcodes
        foreach (new DirectoryIterator($dir.'/helpers') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // If this is not false
            if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; } 
            // Include the file
            require_once($FileInfo->getPathname());
        }
        // Fire the shortcode init action
        do_action('vcff_forms_helper_init',$this);
    }

    protected function _Load_Core() {
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Load each of the form shortcodes
        foreach (new DirectoryIterator($dir.'/core') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // If this is not false
            if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; } 
            // Include the file
            require_once($FileInfo->getPathname());
        }
        // Fire the shortcode init action
        do_action('vcff_forms_core_init',$this);
    }

    protected function _Load_Context() {
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Load each of the field shortcodes
        foreach (new DirectoryIterator($dir.'/context') as $FileInfo) { 
            // If this is a directory dot
            if ($FileInfo->isDot()) { continue; }
            // If this is a directory
            if ($FileInfo->isDir()) { 
                // Load each of the field shortcodes
                foreach (new DirectoryIterator($FileInfo->getPathname()) as $_FileInfo) {
                    // If this is a directory dot
                    if ($_FileInfo->isDot()) { continue; }
                    // If this is a directory
                    if ($_FileInfo->isDir()) { continue; }
                    // If this is not false
                    if (stripos($_FileInfo->getFilename(),'.tpl') !== false) { continue; } 
                    // Include the file
                    require_once($_FileInfo->getPathname());
                }
            } // Otherwise this is just a file
            else {
                // If this is not false
                if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; } 
                // Include the file
                require_once($FileInfo->getPathname());
            }
        }
        // Fire the shortcode init action
        do_action('vcff_forms_context_init',$this);
    }
    
    protected function _Load_Pages() { 
        // Retrieve the context director
        $dir = untrailingslashit(plugin_dir_path(__FILE__));
        // Load each of the field shortcodes
        foreach (new DirectoryIterator($dir.'/pages') as $FileInfo) { 
            // If this is a directory dot
            if ($FileInfo->isDot()) { continue; }
            // If this is a directory
            if ($FileInfo->isDir()) { 
                // Load each of the field shortcodes
                foreach (new DirectoryIterator($FileInfo->getPathname()) as $_FileInfo) {
                    // If this is a directory dot
                    if ($_FileInfo->isDot()) { continue; }
                    // If this is a directory
                    if ($_FileInfo->isDir()) { continue; }
                    // If this is not false
                    if (stripos($_FileInfo->getFilename(),'.tpl') !== false || stripos($FileInfo->getFilename(),'.txt') !== false) { continue; } 
                    // Include the file
                    require_once($_FileInfo->getPathname());
                }
            } // Otherwise this is just a file
            else {
                // If this is not false
                if (stripos($FileInfo->getFilename(),'.tpl') !== false || stripos($FileInfo->getFilename(),'.txt') !== false) { continue; } 
                // Include the file
                require_once($FileInfo->getPathname());
            }
        }
        // Fire the shortcode init action
        do_action('vcff_forms_pages_init',$this);
    }
    
    protected function _Load_AJAX() {
        // Retrieve the context director
        $dir = untrailingslashit(plugin_dir_path(__FILE__));
        // Load each of the field shortcodes
        foreach (new DirectoryIterator($dir.'/ajax') as $FileInfo) { 
            // If this is a directory dot
            if ($FileInfo->isDot()) { continue; }
            // If this is a directory
            if ($FileInfo->isDir()) { 
                // Load each of the field shortcodes
                foreach (new DirectoryIterator($FileInfo->getPathname()) as $_FileInfo) {
                    // If this is a directory dot
                    if ($_FileInfo->isDot()) { continue; }
                    // If this is a directory
                    if ($_FileInfo->isDir()) { continue; }
                    // If this is not false
                    if (stripos($_FileInfo->getFilename(),'.tpl') !== false || stripos($FileInfo->getFilename(),'.txt') !== false) { continue; } 
                    // Include the file
                    require_once($_FileInfo->getPathname());
                }
            } // Otherwise this is just a file
            else {
                // If this is not false
                if (stripos($FileInfo->getFilename(),'.tpl') !== false || stripos($FileInfo->getFilename(),'.txt') !== false) { continue; } 
                // Include the file
                require_once($FileInfo->getPathname());
            }
        }
        // Fire the shortcode init action
        do_action('vcff_forms_ajax_init',$this);
    }
    
    public function Map_Visual_Composer() {
        // If this is not the form edit page
        if (!vcff_allow_form_vc_shortcodes()) { return; }
        // Retrieve the global wordpress database layer
        global $wpdb;
        // Retrieve the global vcff forms class
        $vcff_forms = vcff_get_library('vcff_forms');
        // Check the vcff_form post type exists
        if (!post_type_exists('vcff_form')){ return; } 
        // Retrieve a list of all the published vv forms
        $published = $wpdb->get_results("SELECT ID, post_title 
	        FROM $wpdb->posts
	        WHERE post_status = 'publish'
            AND post_type = 'vcff_form'"); 
        // If no published posts were returned
        if (!$published) { return; }
        // Loop through each published post
        foreach ($published as $k => $_post_id) { 
            // Retrieve the post object
            $_post = get_post($_post_id);
            // Retrieve the form type from meta
            $meta_form_type = get_post_meta($_post->ID, 'form_type', true);
            // If the form does not have a meta form type value
            if (!$meta_form_type) { continue; }
            // Retrieve the form class
            $form_context = $vcff_forms->contexts[$meta_form_type];
            // If the form does not have a meta form type value
            if (!$form_context) { continue; } 
            // Retrieve the form type from meta
            $meta_form_uuid = get_post_meta($_post->ID, 'form_uuid', true);
            // If the form does not have a meta form type value
            if (!$meta_form_uuid) {  continue; } 
            // Create the form shortcode
            $form_short_code = 'vcff_form_'.$meta_form_uuid;  
            // Run the params through a filter
            $params = apply_filters('vcff_form_vc_params',array(
                array (
                    "type" => "vcff_heading",
                    "heading" => false,
                    "param_name" => "field_heading",
                    'html_title' => 'VCFF Form',
                    'html_description' => 'A form field provides a page element which users can import data into or make selections using to provide information for your form. Each field element requires at least a machine code and a set of labels explaining what the field is for to the user and administrator.',
                    'help_url' => 'http://blah',
                ),
                array(
                    'type' => 'textfield',
                    'heading' => __('Extra Class', VCFF_FORM ),
                    'param_name' => 'extra_class',
                ),
            ),$_post,$form_context);
            // Map the form to visual composer
            vc_map(array(
                "name" => $_post->post_title,
                "icon" => "icon-ui-splitter-horizontal",
                "base" => $form_short_code,
                "class" => "",
                "category" => __('Forms', VCFF_NS),
                "params" => $params
            ));
        }
        // Fire the vc init action
        do_action('vcff_forms_vc_init',$this);
    }

    public function Load_Shortcodes() {
        // Retrieve the global wordpress database layer
        global $wpdb;
        // Get the list of form contexts
        $contexts = $this->contexts;
        // Retrieve a list of all the published vv forms
        $published = $wpdb->get_results("SELECT ID, post_title 
                FROM $wpdb->posts
                WHERE post_status = 'publish'
                AND post_type = 'vcff_form'");
        // If no published posts were returned
        if (!$published) { return; }
        // Loop through each published post
        foreach ($published as $k => $_post) {
            // Retrieve the post object
            vcff_custom_css_fix($_post->ID);
            // Retrieve the form type from meta
            $meta_form_type = get_post_meta( $_post->ID, 'form_type', true );
            // If the form does not have a meta form type value
            if (!$meta_form_type) { continue; }
            // Retrieve the form type from meta
            $meta_form_uuid = vcff_get_uuid_by_form($_post->ID);
            // If the form does not have a meta form type value
            if (!$meta_form_uuid) { continue; }
            // Create the form shortcode
            $form_short_code = 'vcff_form_'.$meta_form_uuid; 
            // Add the render function
            add_shortcode($form_short_code, array($this,'Render_Load_Shortcodes'));
        } 
        // Fire the shortcode init action
        do_action('vcff_forms_shortcode_init',$this);
    }
    
    public function Render_Load_Shortcodes($attributes,$content,$shortcode) {
        // Retrieve the unique id
        $form_unique_id = str_replace('vcff_form_','',$shortcode);
        // If no form unique id then return out
        if (!$form_unique_id) { return; }
        // Retrieve the form object
        $form_obj = vcff_get_form_by_uuid($form_unique_id);
        // Retrieve the page id
        $page_id = get_the_ID() ? get_the_ID() : false;
        // If no form unique id then return out
        if (!$form_obj || !is_object($form_obj)) { return; }
        // Create a simple form cache id
        $form_cache_id = $page_id ? $page_id.'_'.$form_unique_id : $form_unique_id ;
        // If there is a cached form
        if (isset($this->cached_forms[$form_cache_id])) {
            // Retrieve the form instance
            $form_instance = $this->cached_forms[$form_cache_id];
        } // Otherwise create a new one 
        else {
            // PREPARE PHASE
            $form_prepare_helper = new VCFF_Forms_Helper_Prepare();
            // Get the form instance
            $form_instance = $form_prepare_helper
                ->Get_Form(array(
                    'post_id' => $page_id,
                    'uuid' => $form_unique_id,
                    'attributes' => $attributes,
                ));
            // If the form instance could not be created
            if (!$form_instance) { die('could not create form instance'); } 
            // POPULATE PHASE
            $form_populate_helper = new VCFF_Forms_Helper_Populate();
            // Run the populate helper
            $form_populate_helper
                ->Set_Form_Instance($form_instance)
                ->Populate(array());
            // CALCULATE PHASE
            $form_calculate_helper = new VCFF_Forms_Helper_Calculate();
            // Initiate the calculate helper
            $form_calculate_helper
                ->Set_Form_Instance($form_instance)
                ->Calculate(array(
                    'validation' => false
                ));
            // REVIEW PHASE
            $form_review_helper = new VCFF_Forms_Helper_Review();
            // Initiate the calculate helper
            $form_review_helper
                ->Set_Form_Instance($form_instance)
                ->Review(array());
            // FINALIZE PHASE
            $form_finalize_helper = new VCFF_Forms_Helper_Finalize();
            // Initiate the calculate helper
            $form_finalize_helper
                ->Set_Form_Instance($form_instance)
                ->Finalize(array());
        }
        // Populate the focused form
        $this->vcff_focused_form = $form_instance;
        // Set the focused post id
        $this->vcff_focused_post_id = get_the_ID();
        // Set the focused post id
        $this->vcff_focused_form_uuid = $form_unique_id;
        // Fire the shortcode init action
        $form_instance = apply_filters('vcff_forms_render_init',$form_instance);
        // Render the form
        $html = $form_instance->Render();
        // Return the rendered form
        return $html;
    }
}

$vcff_forms = new VCFF_Forms();

vcff_register_library('vcff_forms',$vcff_forms);

$vcff_forms->Init();