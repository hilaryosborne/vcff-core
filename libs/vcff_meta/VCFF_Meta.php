<?php

if(!defined('VCFF_META_DIR'))
{ define('VCFF_META_DIR',untrailingslashit( plugin_dir_path(__FILE__ ) )); }

if (!defined('VCFF_META_URL'))
{ define('VCFF_META_URL',untrailingslashit( plugins_url( '/', __FILE__ ) )); }


class VCFF_Meta {

    public $contexts = array();

    public function Init() {
        // Fire the shortcode init action
        do_action('vcff_meta_before_init',$this);
        // Include the admin class
        require_once(VCFF_META_DIR.'/functions.php');
        // Initalize core logic
        add_action('vcff_init_core',array($this,'__Init_Core'),20);
        // Initalize context logic
        add_action('vcff_init_context',array($this,'__Init_Context'),20);
        // Initalize misc logic
        add_action('vcff_init_misc',array($this,'__Init_Misc'),20);
        // Fire the shortcode init action
        do_action('vcff_meta_init',$this);
        // Fire the shortcode init action
        do_action('vcff_meta_after_init',$this);
    }
    
    public function __Init_Core() {
        // Load helper classes
        $this->_Load_Helpers();
        // Load the core classes
        $this->_Load_Core(); 
        // Fire the shortcode init action
        do_action('vcff_meta_init_core',$this);
    }

    public function __Init_Context() {
        // Load the context classes
        $this->_Load_Context();
        // Fire the shortcode init action
        do_action('vcff_meta_init_context',$this);
    }
    
    public function __Init_Misc() {
        // Load the pages
        $this->_Load_Pages();
        // Load AJAX
        $this->_Load_AJAX();
        // Load Hooks
        $this->_Load_Hooks();
        // Fire the shortcode init action
        do_action('vcff_meta_init_misc',$this);
    }

    protected function _Load_Helpers() {
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Recurssively load the directory
        $this->_Recusive_Load_Dir($dir.'/helpers');
        // Fire the shortcode init action
        do_action('vcff_meta_helper_init',$this);
    }

    protected function _Load_Core() {
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Recurssively load the directory
        $this->_Recusive_Load_Dir($dir.'/core');
        // Fire the shortcode init action
        do_action('vcff_meta_core_init',$this);
    }

    protected function _Load_Context() {
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Recurssively load the directory
        $this->_Recusive_Load_Dir($dir.'/context');
        // Fire the shortcode init action
        do_action('vcff_meta_context_init',$this);
    }
    
    protected function _Load_Pages() { 
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Recurssively load the directory
        $this->_Recusive_Load_Dir($dir.'/pages');
        // Fire the shortcode init action
        do_action('vcff_meta_pages_init',$this);
    }
    
    protected function _Load_AJAX() {
        // Retrieve the context director
        $dir = untrailingslashit( plugin_dir_path(__FILE__ ) );
        // Recurssively load the directory
        $this->_Recusive_Load_Dir($dir.'/ajax');
        // Fire the shortcode init action
        do_action('vcff_meta_ajax_init',$this);
    }
    
    protected function _Load_Hooks() {
        // Assign required hooks
        add_action('edit_form_advanced',array($this,'_Render_Container'));
        add_action('save_post',array($this,'_Save_Post'),1,2);
    }
    
    protected function _Recusive_Load_Dir($dir) {
        // If the directory doesn't exist
        if (!is_dir($dir)) { return; }
        // Load each of the field shortcodes
        foreach (new DirectoryIterator($dir) as $FileInfo) {
            // If this is a directory dot
            if ($FileInfo->isDot()) { continue; }
            // If this is a directory
            if ($FileInfo->isDir()) { 
                // Load the directory
                $this->_Recusive_Load_Dir($FileInfo->getPathname());
            } // Otherwise load the file
            else {
                // If this is not false
                if (stripos($FileInfo->getFilename(),'.tpl') !== false) { continue; } 
                // If this is not false
                if (stripos($FileInfo->getFilename(),'.php') === false) { continue; } 
                // Include the file
                require_once($FileInfo->getPathname());
            }
        }
    }
    
    public function _Render_Container($form) {
        // If this is not a post form
        if ($form->post_type != 'vcff_form') { return; }
        // Retrieve the form uuid
        $form_uuid = vcff_get_uuid_by_form($form->ID,true); 
        // Get the saved vcff form type
        $meta_form_type = vcff_get_type_by_form($form_uuid);
        // PREPARE PHASE
        $form_prepare_helper = new VCFF_Forms_Helper_Prepare();
        // Get the form instance
        $form_instance = $form_prepare_helper
            ->Get_Form(array(
                'uuid' => $form_uuid,
                'type' => $meta_form_type
            ));
        // If the form instance could not be created
        if (!$form_instance) { die('could not create form instance'); }
        // POPULATE PHASE
        $form_populate_helper = new VCFF_Forms_Helper_Populate();
        // Run the populate helper
        $form_populate_helper
            ->Set_Form_Instance($form_instance)
            ->Populate(array());
        // Create new meta helper
        $form_meta_helper = new VCFF_Meta_Helper_AJAX();
        // Retrieve the json data
        echo $form_meta_helper
            ->Set_Form_Instance($form_instance)
            ->Render_Meta_Container();
    }

    public function _Save_Post($post_id, $post) {
        // If this is not the post type we are looking for
        if ($post->post_type != 'vcff_form') { return; }
        // If no post id or post is supplied
        if (!$post_id 
            || !$post) { return; }
        // Dont' save meta boxes for revisions or autosaves
        if (defined( 'DOING_AUTOSAVE') 
            || wp_is_post_revision($post) 
            || wp_is_post_autosave($post)) { return; }
        // Check the post being saved == the $post_id to prevent triggering this call for other save_post events
        if (!$_POST['post_ID'] 
            || $_POST['post_ID'] != $post_id ) { return; }
        // Check user has permission to edit
        if (!current_user_can('edit_post', $post_id)) { return; }
        // Retrieve the form uuid
        $form_uuid = vcff_get_uuid_by_form($_POST['post_ID']);
        
        if (!$form_uuid) { 
            // Generate a new uuid
            $form_uuid = uniqid(); 
            // Insert a new uuid
            update_post_meta($_POST['post_ID'], 'form_uuid', $form_uuid);
        }
        // PREPARE PHASE
        $form_prepare_helper = new VCFF_Forms_Helper_Prepare();
        // Get the form instance
        $form_instance = $form_prepare_helper
            ->Get_Form(array(
                'uuid' => $form_uuid,
                'contents' => $_POST['content'],
            ));
        // If the form instance could not be created
        if (!$form_instance) { die('could not create form instance'); }
        // Create a new cache helper
        $form_cache_helper = new VCFF_Forms_Helper_Cache();
        // Cache the submitted form
        $form_instance = $form_cache_helper
            ->Set_Form_Instance($form_instance)
            ->Retrieve();  
        // POPULATE PHASE
        $form_populate_helper = new VCFF_Forms_Helper_Populate();
        // Run the populate helper
        $form_populate_helper
            ->Set_Form_Instance($form_instance)
            ->Populate(array(
                'meta_values' => $_POST
            ));
        // CALCULATE PHASE
        $form_calculate_helper = new VCFF_Forms_Helper_Calculate();
        // Initiate the calculate helper
        $form_calculate_helper
            ->Set_Form_Instance($form_instance)
            ->Calculate(array(
                'validation' => false
            ));
        // Create a new meta store helper
        $form_store_helper = new VCFF_Meta_Helper_Store();
        // Save the updated meta
        $form_store_helper
            ->Set_Form_Instance($form_instance)
            ->Save();
    }
}

$vcff_meta = new VCFF_Meta();

vcff_register_library('vcff_meta',$vcff_meta);

$vcff_meta->Init();

// Register the vcff admin css
vcff_admin_enqueue_script('vcff-admin-meta', VCFF_META_URL . '/assets/admin/vcff.admin.meta.js', array('jquery'), '20120608', 'all');
// Register the vcff admin css
vcff_admin_enqueue_style('vcff-admin-meta', VCFF_META_URL . '/assets/admin/vcff.admin.meta.css', array(), '20120608', 'all');