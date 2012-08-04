<?php

/**
 * Removes the invalid inline CSS styles in the gallery (ticket #10734).
 * @since 2.0
 */
add_filter( 'use_default_gallery_style', '__return_false' );

function ar2_add_embed_container( $html ) {

	return '<div class="entry-embed">' . $html . '</div>';

}
add_filter( 'embed_oembed_html', 'ar2_add_embed_container' );

/**
 * Displays when the specified post/archive requested by the user is not found.
 * @since 1.2.2
 */
function ar2_post_notfound() {

	get_template_part( 'section', '404' );
	
}

/**
 * Adds a paragraph to the 'Read More' link.
 * @since 1.0
 */
function ar2_more_link( $link ) {

	return '<p>' . $link . '</p>';
	
}
add_filter( 'the_content_more_link', 'ar2_more_link' );

/**
 * Generates additional classes for BODY element.
 * @since 1.3
 */
function ar2_body_class( $classes ) {

	global $override_layout;
	
	if ( isset( $override_layout ) )
		$layout = $override_layout;
	else
		$layout = ar2_get_theme_option( 'layout' );
	
	$classes[] = $layout;
	
	return $classes;
	
}
add_filter( 'body_class', 'ar2_body_class' );

/**
 * Generates semantic classes for single posts.
 * @since 1.3
 */
function ar2_post_class( $classes ) {
	
	$classes[] = 'clearfix';
	return $classes;
	
}
add_filter( 'post_class', 'ar2_post_class' );

/**
 * ar2_excerpt_more function.
 * @since 1.3
 */
function ar2_excerpt_more( $excerpt ) {

	return str_replace( ' [...]', '...', $excerpt );
	
}
add_filter( 'excerpt_more', 'ar2_excerpt_more' );

/**
 * ar2_excerpt_length function.
 * @since 1.3
 */
function ar2_excerpt_length( $length ) {

	if ( !ar2_get_theme_option( 'excerpt_limit' ) ) $limit = 30;
	else $limit = ar2_get_theme_option( 'excerpt_limit' );
	
	return $limit;
	
}
add_filter( 'excerpt_length', 'ar2_excerpt_length' );

/* End of file filters.php */
/* Location: ./library/filters.php */
