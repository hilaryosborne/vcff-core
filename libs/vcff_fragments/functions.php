<?php

function vcff_parse_fragment($text) {  
    // Allow plugins/themes to override the default caption template.
    $text = apply_filters('vcff_fragment_pre_parse', $text);  
    // Extract all of the shortcodes from the content 
    preg_match_all('/\[vcff_fragment (.*?)\]/s', $text, $_matches);
    // Loop through each of the field matches
    foreach ($_matches[0] as $k => $string) {
        // Look for all attribute matches
        preg_match_all('/(\w+)\s*=\s*"(.*?)"/', $_matches[1][$k], $attr_matches);
        // Start the attribute list
        $attributes = array();
        // Loop through each attribute match
        foreach ($attr_matches[1] as $_k => $_attr) {
            // Populate the attributes list
            $attributes[$_attr] = $attr_matches[2][$_k];
        } 
        // If no fragment id was found
        if (!isset($attributes['fragment_uuid'])) { continue; }
        // Retrieve the fragment id
        $fragment_uuid = $attributes['fragment_uuid'];
        // Retrieve the fragment post            
        $post = vcff_get_fragment_by_uuid($fragment_uuid);
        // If no fragment id was found
        if (!$post) { continue; }
        // Retrieve the post content
        $post_content = $post->post_content;
        
        $text = str_replace($string,$string.stripslashes($post_content).'[/vcff_fragment]',$text); 
    } 
    // Allow plugins/themes to override the default caption template.
    $text = apply_filters('vcff_fragment_post_parse', $text);
    // Return the contents
    return $text;
}

function vcff_get_uuid_by_fragment($fragment_id) { 
    // Retrieve the fragment uuid from post meta
    $meta_fragment_uuid = get_post_meta($fragment_id, 'fragment_uuid', true );
    // Return the fragment uuid
    return $meta_fragment_uuid;
}

function vcff_get_fragment_by_uuid($uuid) {
    // Attempt to find the form post
    $fragments = get_posts(array(
        'meta_query' => array(
            array('key' => 'fragment_uuid', 'value' => $uuid)
        ),
        'post_type' => 'vcff_fragment',
        'posts_per_page' => -1,
        'post_status' => 'any'
    )); 
    // If no form posts were returned
    if (!$fragments || !is_array($fragments)) { return; }
    
    return $fragments[0];
}