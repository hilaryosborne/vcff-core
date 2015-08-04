<?php

function vcff_parse_fragment($text) {  
    // Allow plugins/themes to override the default caption template.
    $text = apply_filters('vcff_fragment_pre_parse', $text); 
    // Create a new parser
    $blq_parser = new BLQ_Parser($text);
    // Retrieve a list of shortcodes
    $_shortcodes = $blq_parser
        ->Set_Ends('[',']')
        ->Parse()
        ->Get_Flattened();
    // If no shortcodes were returned
    if (!$_shortcodes || !is_array($_shortcodes)) { return; }
    // Loop through each shortcode
    foreach ($_shortcodes as $k => $el) {
        // If this is not a tag
        if (!$el->is_tag || !$el->tag) { continue; }
        // If this is not a tag
        $_shortcode = $el->tag;
        // If this is not a fragment
        if ($_shortcode != 'vcff_fragment') { continue; }
        // Retrieve the attributes
        $_attributes = $el->attributes;
        // If no fragment id was found
        if (!isset($_attributes['fragment_uuid'])) { continue; }
        // Retrieve the fragment id
        $fragment_uuid = $_attributes['fragment_uuid'];
        // Retrieve the fragment post            
        $post = vcff_get_fragment_by_uuid($fragment_uuid);
        // If no fragment id was found
        if (!$post) { continue; }
        // Retrieve the post content
        $post_content = $post->post_content;
        // Create the new text string
        $text = str_replace('['.$el->string.']','['.$el->string.']'.stripslashes($post_content).'[/'.$_shortcode.']',$text);
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