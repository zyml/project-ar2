<?php

/**
 * Called after the <body> tag is declared
 * @since 1.2.2 
 */
function ar2_body() {
	do_action('ar2_body');	
}

/**
 * Called before any content on the main container
 * @since 1.4.3
 */
function ar2_above_main() {
	do_action('ar2_above_main');
}

/**
 * Called before any content on the main column
 * @since 1.2.2
 */
function ar2_above_content() {
	do_action('ar2_above_content');
}

/**
 * Called before any content on the main column
 * @since 1.2.2
 */
function ar2_below_content() {
	do_action('ar2_below_content');
}

/**
 * Called before the top menus
 * @since 1.5 
 */
function ar2_above_top_menu() {
	do_action('ar2_above_top_menu');
}

/**
 * Called after the top menus
 * @since 1.5 
 */
function ar2_below_top_menu() {
	do_action('ar2_below_top_menu');
}

/**
 * Called before the main navigation
 * @since 1.2.1 
 */
function ar2_above_nav() {
	do_action('ar2_above_nav');
}

/**
 * Called after the main navigation
 * @since 1.2.1 
 */
function ar2_below_nav() {
	do_action('ar2_below_nav');
}

/**
 * Called before the main sidebar
 * @since 1.2.1 
 */
function ar2_above_sidebar() {
	do_action('ar2_above_sidebar');
}

/**
 * Called after the main sidebar
 * @since 1.2.1 
 */
function ar2_below_sidebar() {
	do_action('ar2_below_sidebar');
}

/**
 * Called before the post content, before the title
 * @since 1.2.1 
 */
function ar2_above_post() {
	do_action('ar2_above_post');
}

/**
 * Called after the post content, before the comments
 * @since 1.2.1 
 */
function ar2_below_post() {
	do_action('ar2_below_post');
}

/**
 * Called after the comments (form)
 * @since 1.2.1 
 */
function ar2_below_comments() {
	do_action('ar2_below_comments');
}

/**
 * Called before the footer
 * @since 1.2.1 
 */
function ar2_before_footer() {
	do_action( 'ar2_before_footer' );
}

/* End of file actions.php */
/* Location: ./library/actions.php */
