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

    public function Init() {
        // Fire the shortcode init action
        do_action('vcff_forms_before_init',$this);
		// Include the admin class
        require_once(VCFF_FORMS_DIR.'/functions.php'); 
        // Load the custom post type
        $this->_Load_Post_Type();
        // Load helper classes
        $this->_Load_Helpers();
        // Load the core classes
        $this->_Load_Core();
        // Load the context classes
        $this->_Load_Context();
        // Load the pages
        $this->_Load_Pages();
        // Fire the shortcode init action
        do_action('vcff_forms_init',$this);
        // Include the admin class
        require_once(VCFF_FORMS_DIR.'/VCFF_Forms_Admin.php');
        // Otherwise if this is being viewed by the client 
        require_once(VCFF_FORMS_DIR.'/VCFF_Forms_Public.php');
        // Fire the shortcode init action
        do_action('vcff_forms_after_init',$this);
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
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_FORMS_DIR.'/helpers') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; } 
            // Include the file
            require_once(VCFF_FORMS_DIR.'/helpers/'.$FileInfo->getFilename());
        }
        // Fire the shortcode init action
        do_action('vcff_forms_helper_init',$this);
    }

    protected function _Load_Core() {
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_FORMS_DIR.'/core') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // If this is not false
            if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; }
            // Include the file
            require_once(VCFF_FORMS_DIR.'/core/'.$FileInfo->getFilename());
        }
        // Fire the shortcode init action
        do_action('vcff_forms_core_init',$this);
    }

    protected function _Load_Context() {
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_FORMS_DIR.'/context') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // If this is not false
            if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; }
            // Include the file
            require_once(VCFF_FORMS_DIR.'/context/'.$FileInfo->getFilename());
            // If this is not false
            if (stripos($FileInfo->getFilename(),'_Item') !== false) { continue; }
            // Retrieve the classname
            $context_classname = $FileInfo->getBasename('.php');
            
            vcff_map_form($context_classname);
        }
        // Fire the shortcode init action
        do_action('vcff_forms_context_init',$this);
    }
    
    protected function _Load_Pages() { 
        // Load each of the form shortcodes
        foreach (new DirectoryIterator(VCFF_FORMS_DIR.'/pages') as $FileInfo) {
            // If this is a directory dot
            if($FileInfo->isDot()) { continue; }
            // If this is a directory
            if($FileInfo->isDir()) { continue; }
            // If this is not false
            if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; }
            // Include the file
            require_once(VCFF_FORMS_DIR.'/pages/'.$FileInfo->getFilename());
        }
        // Fire the shortcode init action
        do_action('vcff_forms_pages_init',$this);
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

    public function Load_Public_Scripts() {
        // Retrieve the global vcff forms class
        $vcff_forms = vcff_get_library('vcff_forms');
        // Retrieve the list of contexts
        $contexts = $vcff_forms->contexts;
        // If a list of active fields were returned
        if (!$contexts || !is_array($contexts)) { return; }
        // Loop through each of the active fields
        foreach ($contexts as $_type => $_context) {
            // If this field has custom scripts which need registering
            if ($_context['params']['public_scripts'] 
                && is_array($_context['params']['public_scripts'])) {
                // Loop through each of the scripts
                $i=0; foreach ($_context['params']['public_scripts'] as $__k => $_script) {
                    // Retrieve the script url
                    $script_url = vcff_get_file_url($_script);
                    // Queue the custom script
                    vcff_front_enqueue_script( $_type.'_'.$i, $script_url, array('jquery')); $i++;
                }
            }
            // If this field has custom styles which need registering
            if ($_context['params']['public_styles'] 
                && is_array($_context['params']['public_styles'] )) {
                // Loop through each of the styles
                $i=0; foreach ($_context['params']['public_styles']  as $__k => $_style) {
                    // Retrieve the css url
                    $style_url = vcff_get_file_url($_style);
                    // Queue the custom script
                    vcff_front_enqueue_style( $_type.'_'.$i, $style_url); $i++;
                }
            }
        }
    }

    public function Load_Admin_Scripts() {
        // Retrieve the global vcff forms class
        $vcff_forms = vcff_get_library('vcff_forms');
        // Retrieve the list of contexts
        $contexts = $vcff_forms->contexts;
        // If a list of active fields were returned
        if (!$contexts || !is_array($contexts)) { return; }
        // Loop through each of the active fields
        foreach ($contexts as $_type => $_context) {
            // If this field has custom scripts which need registering
            if ($_context['params']['admin_scripts'] 
                && is_array($_context['params']['admin_scripts'])) {
                // Loop through each of the scripts
                $i=0; foreach ($_context['params']['admin_scripts'] as $__k => $_script) {
                    // Queue the custom script
                    vcff_admin_enqueue_script( $_type.'_'.$i, $_script, array('jquery')); $i++;
                }
            }
            // If this field has custom styles which need registering
            if ($_context['params']['admin_styles'] 
                && is_array($_context['params']['admin_styles'] )) {
                // Loop through each of the styles
                $i=0; foreach ($_context['params']['admin_styles']  as $__k => $_style) {
                    // Queue the custom script
                    vcff_admin_enqueue_style( $_type.'_'.$i, $_style); $i++;
                }
            }
        }
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
        // If no form unique id then return out
        if (!$form_obj || !is_object($form_obj)) { return; }
        // Retrieve a new form instance helper
        $form_instance_helper = new VCFF_Forms_Helper_Instance();
        // Generate a new form instance
        $form_instance = $form_instance_helper
            ->Set_Post_ID(get_the_ID() ? get_the_ID() : false)
            ->Set_Form_UUID($form_unique_id)
            ->Set_Form_Data(array())
            ->Set_Form_Attributes($attributes)
            ->Generate();
        // Create a new cache helper
        $form_cache_helper = new VCFF_Forms_Helper_Cache();
        // Cache the submitted form
        $form_instance = $form_cache_helper
            ->Set_Form_Instance($form_instance)
            ->Retrieve();    
        // Populate the focused form
        $this->vcff_focused_form = $form_instance;
        // Set the focused post id
        $this->vcff_focused_post_id = get_the_ID();
        // Set the focused post id
        $this->vcff_focused_form_uuid = $form_unique_id;
        // If the form instance could not be created
        if (!$form_instance) { die('could not create form instance'); }
        // Complete setting up the form instance
        $form_instance_helper
            ->Add_Fields()
            ->Filter()
            ->Add_Containers()
            ->Add_Meta()
            ->Add_Supports()
            ->Check_Conditions();
        // Fire the shortcode init action
        $form_instance = apply_filters('vcff_forms_render_init',$form_instance);
        // Render the form
        return $form_instance
            ->Render();
    }
}

$vcff_forms = new VCFF_Forms();

vcff_register_library('vcff_forms',$vcff_forms);

$vcff_forms->Init();