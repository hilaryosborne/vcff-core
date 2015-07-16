<?php

function vcff_is_admin() {

    return current_user_can('manage_options');
}

function vcff_register_library($code,$instance) {

    global $vcff;
    
    $vcff->Register_Lib($code,$instance);
}

function vcff_get_library($code) {

    global $vcff;
    
    return $vcff->Get_Lib($code);
}

function vcff_front_enqueue_style($handle, $src=false, $deps=false, $ver=false, $media=false) {
    // Retrieve the vcff library
    $vcff = vcff_get_library('vcff');
    // Add the style to the style array 
    $vcff->frontend_scripts['styles'][] = array($handle, $src, $deps, $ver, $media);
}

function vcff_front_enqueue_script($handle, $src=false, $deps=false, $ver=false, $in_footer=false) {
    // Retrieve the vcff library
    $vcff = vcff_get_library('vcff');
    // Add the script to the script array
    $vcff->frontend_scripts['scripts'][] = array($handle, $src, $deps, $ver, $in_footer);
}

function vcff_admin_enqueue_style($handle, $src=false, $deps=false, $ver=false, $media=false) {
    // Retrieve the vcff library
    $vcff = vcff_get_library('vcff');
    // Add the style to the style array 
    $vcff->admin_scripts['styles'][] = array($handle, $src, $deps, $ver, $media);
}

function vcff_admin_enqueue_script($handle, $src=false, $deps=false, $ver=false, $in_footer=false) {
    // Retrieve the vcff library
    $vcff = vcff_get_library('vcff');
    // Add the script to the script array
    $vcff->admin_scripts['scripts'][] = array($handle, $src, $deps, $ver, $in_footer);
}

function vcff_custom_css_fix($post_id) {

    $shortcodes_custom_css = get_post_meta($post_id, '_wpb_shortcodes_custom_css', true);

    if ($shortcodes_custom_css) {

        $custom_styles = '<style type="text/css" data-type="vc_shortcodes-custom-css">';
        $custom_styles .= $shortcodes_custom_css;
        $custom_styles .= '</style>';

        add_action('wp_print_scripts',function() use ($custom_styles) {
            echo $custom_styles;
        });
    }
}

function vcff_allow_field_vc_shortcodes() {
    // The post type var
    $post_type = false;
    // If this is an ajax visual composer request
    if (isset($_POST['action']) && isset($_POST['post_id'])) { 
        // Load the post object
        $post = get_post($_REQUEST['post_id']);
        // If the post type is not the correct one
        $post_type = $post->post_type;
    } // If post type is present and 
    elseif (isset($_REQUEST['post_type'])) { 
        // Retrieve the post type
        $post_type = $_REQUEST['post_type']; 
    } // If we are editing a post
    elseif (isset($_REQUEST['post']) && $_REQUEST['action'] == 'edit') {
        // Load the post object
        $post = get_post($_REQUEST['post']);
        // If the post type is not the correct one
        $post_type = $post->post_type;
    }
    // If we cannot find the post type, return out
    if (!$post_type) { return false; }
    // Retrieve the current content types option
    $vcff_shortcode_allowed = get_option('vcff_shortcode_content_types');
    // If there are no allowed shortcodes
    if (!$vcff_shortcode_allowed || !is_array($vcff_shortcode_allowed)) { return false; }
    // If the post type is not allowed
    if (!in_array($post_type,$vcff_shortcode_allowed)) { return false; }
    // Return true
    return true;
}

function vcff_allow_form_vc_shortcodes() {
    // The post type var
    $post_type = false;
    // If this is an ajax visual composer request
    if (isset($_POST['action']) && isset($_POST['post_id'])) { 
        // Load the post object
        $post = get_post($_REQUEST['post_id']);
        // If the post type is not the correct one
        $post_type = $post->post_type;
    } // If post type is present and 
    elseif (isset($_REQUEST['post_type'])) { 
        // Retrieve the post type
        $post_type = $_REQUEST['post_type']; 
    } // If we are editing a post
    elseif (isset($_REQUEST['post']) && $_REQUEST['action'] == 'edit') {
        // Load the post object
        $post = get_post($_REQUEST['post']);
        // If the post type is not the correct one
        $post_type = $post->post_type;
    }
    // If we cannot find the post type, return out
    if (!$post_type) { return true; }
    // Retrieve the current content types option
    $vcff_shortcode_allowed = get_option('vcff_shortcode_content_types');
    // If there are no allowed shortcodes
    if (!$vcff_shortcode_allowed || !is_array($vcff_shortcode_allowed)) { return true; }
    // If the post type is not allowed
    if (!in_array($post_type,$vcff_shortcode_allowed)) { return true; }
    // Return true
    return false;
}

function is_form_edit_page() {
    // If post type is present and 
    if (isset($_REQUEST['post_type']) && $_REQUEST['post_type'] != 'vcff_form') { $allow_vc = true; } 
    // If post type is present and 
    if ($_REQUEST['action'] == 'vc_edit_form' && $_REQUEST['post_id']) {  
        // Load the post object
        $post = get_post($_REQUEST['post_id']);
        // If the post type is not the correct one
        if ($post->post_type != 'vcff_form') { $allow_vc = true; }
    } 
    // If we are editing a post
    if (isset($_REQUEST['post']) && $_REQUEST['action'] == 'edit') {
        // Load the post object
        $post = get_post($_REQUEST['post']);
        // If the post type is not the correct one
        if ($post->post_type != 'vcff_form') { $allow_vc = true; }
    }
    // If we are not allowing vc
    return $allow_vc;
}

function vcff_get_file_dir($filepath) {
    
    $theme_dir = get_template_directory();
    
    $vcff_dir = VCFF_DIR;
    
    $relative_dir = str_replace($vcff_dir.'/libs','',$filepath);
    
    $theme_pathway = $theme_dir.'/vcff'.$relative_dir;
    
    if (file_exists($theme_pathway)) {
        return $theme_pathway;
    } else {
        return $filepath;
    }
    
}

function vcff_get_file_url($urlpath) {
    
    $theme_dir = get_template_directory();
    
    $vcff_url = VCFF_URL;
    
    $relative_dir = str_replace($vcff_url.'/libs','',$urlpath);
    
    $theme_pathway = $theme_dir.'/vcff'.$relative_dir;
    
    if (file_exists($theme_pathway)) {
        return get_template_directory_uri().'/vcff'.$relative_dir;
    } else {
        return $urlpath;
    }
    
}

function array_key_check($needle_key, $needle_value, $haystack, $strict = false) {
    
	foreach ($haystack as $k => $value) {
		
		if (!isset($value[$needle_key])) { continue; }
		
		if ($value[$needle_key] == $needle_value) { return true; }
	}

    return false;
}
