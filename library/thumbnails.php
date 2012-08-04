<?php

/**
 * Containers for storing thumbnail types and its default sizes.
 * @since 1.4.4
 */
$ar2_image_sizes = array();

/**
 * Adds theme thumbnails on load.
 * @since 1.6
 */
function ar2_add_theme_thumbnails() {
	
	$post_thumbnail_size = ar2_post_thumbnail_size();
	ar2_add_image_size( 'single-thumb', __( 'Single Post', 'ar2' ), $post_thumbnail_size[0], $post_thumbnail_size[1] );
	
	$sidebar_thumbnail_size = ar2_sidebar_thumbnail_size();
	ar2_add_image_size( 'sidebar-thumb', __( 'Sidebar Widgets', 'ar2' ), $sidebar_thumbnail_size[0], $sidebar_thumbnail_size[1] );
	
	$section_thumbnail_size = ar2_section_thumbnail_size();
	ar2_add_image_size( 'section-thumb', __( 'Post Sections', 'ar2' ), $section_thumbnail_size[0], $section_thumbnail_size[1] );
	
	do_action( 'ar2_add_theme_thumbnails' );
	
}

/**
 * Function to add image size into both theme system.
 * @since 1.4.4
 */
function ar2_add_image_size( $id, $name, $default_width, $default_height ) {

	global $ar2_image_sizes;
	
	$ar2_custom_image_sizes = ar2_get_theme_option( 'thumbnails' );
	
	// Check from options if a custom width and height has been specified, else use defaults
	if ( isset( $ar2_custom_image_sizes[ $id ] ) ) {
		$width = $ar2_custom_image_sizes[ $id ][ 'w' ];
		$height = $ar2_custom_image_sizes[ $id ][ 'h' ];
	} else {
		$width = $default_width;
		$height = $default_height;
	}
	
	$ar2_image_sizes[$id] = array(
		'name' 	=> $name, 
		'w' 	=> $width, 
		'h' 	=> $height,
		'dw' 	=> $default_width,
		'dh' 	=> $default_height
	);
	
	add_image_size( $id, $width, $height, true );
	
}

/**
 * Function to remove image size into both theme system.
 * @since 1.4.4
 */
function ar2_remove_image_size($id) {

	global $ar2_image_sizes, $_wp_additional_image_sizes;
	
	unset($ar2_images_sizes[$id]);
	unset($_wp_additional_image_sizes[$id]);
	
}

/**
 * Function to get image size's name, width and height, default or custom.
 * @since 1.4.4
 */
function ar2_get_image_size( $id ) {

	global $ar2_image_sizes;
	return ( isset( $ar2_image_sizes[$id] ) ) ? $ar2_image_sizes[$id] : false;
	
}


/**
 * Helper function to grab and display thumbnail from specified post
 * @since 1.4.0
 */
function ar2_get_thumbnail( $size = 'thumbnail', $id = NULL, $attr = array() ) {

	global $post, $ar2_image_sizes;
	
	if ( $post ) $id = $post->ID;
	
	if ( !key_exists( 'alt', $attr ) )
		$attr['alt'] = esc_attr( get_the_excerpt() );
	
	if ( !key_exists( 'title', $attr ) )
		$attr['title'] = esc_attr( get_the_title() );
	

	if ( has_post_thumbnail( $id ) ) {
		return get_the_post_thumbnail( $id, $size, $attr );
	} else {
		// Could it be an attachment?
		if ( $post->post_type == 'attachment' ) {
			return wp_get_attachment_image( $id, $size, false, $attr );
		}		
		// Use first thumbnail if auto thumbs is enabled.
		if ( ar2_get_theme_option( 'auto_thumbs' ) ) {
			$img_id = ar2_get_first_post_image_id();
			if ( $img_id ) return wp_get_attachment_image( $img_id, $size, false, $attr );
		}
	}
	
	// Return empty thumbnail if all else fails.
	return '<img src="' . get_template_directory_uri() . '/images/empty_thumbnail.gif" alt="' . $attr['alt'] . '" title="' . $attr['title'] . '" />'; 
	
}

/**
 * Function to retrieve the first image ID from post.
 * @since 1.5.0
 */
function ar2_get_first_post_image_id( $id = NULL ) {

	global $post;
	if (!$id) $id = $post->ID;
	
	$attachments = get_children( 'post_parent=' . $id . '&post_type=attachment&post_mime_type=image' );
	if (!$attachments) return false;
	
	$keys = array_reverse( array_keys( $attachments ) );
	return $keys[0];
	
}

/**
 * @todo
 * @since 1.6
 */
function ar2_post_thumbnail_size() {

	return apply_filters('ar2_content_width', array( 620, 350 ) );
	
}

/**
 * @todo
 * @since 1.6
 */
function ar2_sidebar_thumbnail_size() {

	return apply_filters( 'ar2_sidebar_thumbnail_size', array( 36, 36 ) );
	
}

/**
 * @todo
 * @since 1.6
 */
function ar2_section_thumbnail_size() {

	return apply_filters( 'ar2_section_thumbnail_size', array( 200, 110 ) );
	
}

/**
 * @todo
 * @since 1.6
 */
function ar2_full_width_thumbnail_size() {

	return apply_filters( 'ar2_full_width_thumbnail_size', array( 980, 540 ) );
	
}

/* End of file thumbnails.php */
/* Location: ./library/thumbnails.php */
