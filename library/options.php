<?php
/**
 * Initialise theme options.
 * @since 1.6
 */
function ar2_theme_options_init() {
	if ( false === ar2_flush_theme_options() )
		add_option( 'ar2_theme_options', ar2_get_default_theme_options() );
	
	register_setting( 'ar2_options', 'ar2_theme_options', 'ar2_theme_options_validate' );
}
add_action( 'admin_init', 'ar2_theme_options_init' );

/**
 * Change the capability required to save the 'ar2_options' options group. Adapted from TwentyEleven theme.
 * @since 2.0
 */
function ar2_option_page_capability( $capability ) {

	return 'edit_theme_options';
	
}
add_filter( 'option_page_capability_ar2_options', 'ar2_option_page_capability' );

/**
 * Generate default theme options.
 * @since 1.6
 */
function ar2_get_default_theme_options() {
	$_default_theme_options = array (
	
		'version' => AR2_VERSION,
		'footer_message' => '<p>' . sprintf( __('Copyright %s. All Rights Reserved', 'ar2'), get_bloginfo('name') ) . '</p>',
		
		'archive_display' => 'quick',
		
		'post_display' => array (
			'display_author'	=> true,
			'post_author'		=> true,
			'excerpt'			=> false,
			'post_social'		=> true,
			'post_cats'			=> true,
			'post_tags'			=> true,
			'single_thumbs'		=> false,
		),
		
		'relative_dates' => true,		
		'excerpt_limit'	=> 30,	
		'nodebased_show_excerpts' => false,	
		'layout' => 'twocol-r',	
		'auto_thumbs' => true,
		
	);
	
	return apply_filters( 'ar2_default_theme_options', $_default_theme_options );	
}

/**
 * Get theme options from database and cache it for further use.
 * @since 1.6
 */
function ar2_flush_theme_options() {

	global $ar2_options;
	$ar2_options = get_option( 'ar2_theme_options', ar2_get_default_theme_options() );

	return $ar2_options;
	
}

/**
 * Get individual theme option.
 * @since 1.6
 */
function ar2_get_theme_option( $name ) {

	global $ar2_options;
	
	if ( !is_array( $ar2_options ) )
		ar2_flush_theme_options();
		
	// Parse the ID for array keys (adapted from WP_Customize_Setting class).
	$_keys = preg_split( '/\[/', str_replace( ']', '', $name ) );
	
	if ( $_keys[ 0 ] == 'ar2_theme_options' )
		array_shift( $_keys );
	
	return ar2_multidimensional_get( $ar2_options, $_keys );
	
}

/**
 * Updates individual theme option.
 * @since 1.6
 */
function ar2_update_theme_option( $name, $val, $update_db = true ) {

	global $ar2_options;
	
	if ( !is_array( $ar2_options ) )
		ar2_flush_theme_options();
	
	// Parse the ID for array keys (adapted from WP_Customize_Setting class).
	$_keys = preg_split( '/\[/', str_replace( ']', '', $name ) );
	
	if ( $_keys[ 0 ] == 'ar2_theme_options' )
		array_shift( $_keys );
	
	$ar2_options = ar2_multidimensional_replace( $ar2_options, $_keys, $val );

	if ( $update_db === true )
		update_option( 'ar2_theme_options', $ar2_options );
		
}

/**
 * Resets theme options.
 * @since 1.6
 */
function ar2_reset_theme_options() {

	global $ar2_options;
	
	delete_option( 'ar2_theme_options' );
	$ar2_options = ar2_get_default_theme_options();
	ar2_flush_theme_options();	
	
}
 
/**
 * Sanitize and validate form input. Accepts an array, return a sanitized array.
 * @todo: Needs cleaning up.
 * @since 1.6
 */
function ar2_theme_options_validate( $input ) {

	$output = ar2_flush_theme_options();
	$defaults = ar2_get_default_theme_options();
	
	if ( isset( $input[ 'reset' ] ) ) {

		// Reset theme options to default settings.
		ar2_reset_theme_options();
		add_settings_error( 'reset', 'ar2-theme-options-reset', __( 'Your settings have been reverted to the defaults.', 'ar2' ), 'updated' );
		
		return $defaults;
		
	} else if ( isset( $input[ 'import_theme_options' ] ) && $input[ 'import_theme_options' ] != '' ) {
		
		// Import theme options from user input.
		$output = json_decode( $input[ 'import_theme_options' ], true );
		add_settings_error( 'import_theme_options', 'ar2-theme-options-import', __( 'Your settings have been successfully imported.', 'ar2' ), 'updated' );
		
		return $output;
	
	} else if ( isset( $input[ 'submit' ] ) ) {
	
		/* Validation for theme options page. Refer to WordPress Codex on Data Validation: 
		 * http://codex.wordpress.org/Data_Validation */
		$setting_fields = ar2_theme_options_default_fields();
		
		unset( $input[ 'export_theme_options' ] );
		unset( $input[ 'import_theme_options' ] );
		
		foreach ( $input as $id => $value ) {
			
			if ( isset( $setting_fields[ $id ] ) ) {

				switch( $setting_fields[ $id ][ 'type' ] ) :

					case 'thumbnail-size' :
						$sanitized_val = array (
							'w'	=> is_numeric( $value[ 'w' ] ) ? absint( $value[ 'w' ] ) : 0,
							'h'	=> is_numeric( $value[ 'h' ] ) ? absint( $value[ 'h' ] ) : 0,
						);
					break;
					
					case 'cat-dropdown' :
						$sanitized_val = ar2_theme_options_validate_terms_input( $value );
					break;
					
					case 'taxonomies-dropdown' :
						if ( taxonomy_exists( $value ) ) $sanitized_val = $value;
					break;
					
					case 'posttype-dropdown' :
						if ( post_type_exists( $value ) ) $sanitized_val = $value;
					break;
					
					case 'color-switcher' :
						$sanitized_val = $value; // do nothing
					break;
					
					case 'textarea_html' :
						$sanitized_val = esc_html( $value );
					break;
					
					case 'wp_editor' :
						$sanitized_val = esc_html( $value );
					break;
					
					case 'dropdown' :
						if ( in_array( $value, array_keys( $setting_fields[ $id ][ 'options' ] ) ) ) $sanitized_val = $value;
					break;
					
					case 'checkbox' :
						$sanitized_val = ( 1 == $value ) ? true : false;
					break;
					
					case 'switch' :
						$sanitized_val = ( 1 == $value ) ? true : false;
					break;
					
					case 'custom' :
						$sanitized_val = $value; // do nothing
					break;
					
					default :
						$sanitized_val = esc_attr( $value );
						
				endswitch;
				
				$sanitized_val = apply_filters( 'ar2_theme_options_validate_setting-' . $id, $sanitized_val, $value, $output );
				$output = ar2_multidimensional_replace( $output, $setting_fields[ $id ][ '_id_data' ][ 'keys' ], $sanitized_val );
				
			}
		
		}
		
		add_settings_error( 'submit', 'ar2-theme-options-submit', __( 'Your settings have been successfully saved.', 'ar2'), 'updated' );
		
	} else {
		
		// Input from WP Customize
		$output = apply_filters( 'ar2_theme_customize_validate', $output, $input, $defaults );
		
	}
	
	
	// Leave for debugging purposes.
	/*
	echo '<pre><code>';
	print_r( $input );
	echo '</code></pre>';
	
	echo '<pre><code>';
	print_r( $output );
	echo '</code></pre>';
	*/
	
	return apply_filters( 'ar2_theme_options_validate', $output, $input, $defaults );
	
}

/**
 * @todo
 * @since 2.0
 */
function ar2_theme_options_validate_single_posts_display( $sanitized, $value, $output ) {
	
	$single_post_opts = $output[ 'post_display' ];
	foreach( $single_post_opts as $id => $val )
		$single_post_opts[ $id ] = ( isset( $value[ $id ] ) && 1 == $value[ $id ] ) ? true : false;

	return $single_post_opts;

}
add_filter( 'ar2_theme_options_validate_setting-single_posts_display', 'ar2_theme_options_validate_single_posts_display', 1, 3 );

/**
 * @todo
 * @since 1.6
 */
function ar2_theme_options_validate_terms_input( $input_cats ) {

	if ( isset( $input_cats ) ) {
		if ( $input_cats != '' && is_array( $input_cats ) )
			return $input_cats;
	
		$cat_array = explode( ',', $input_cats );
	
		foreach ( $cat_array as $id => $cat )
			if ( !is_numeric( $cat ) ) unset( $cat_array[ $id ] );
	
		return $cat_array;
	} else
		return 0;

}

/**
 * Helper function for validating checkboxes.
 * @since 2.0
 */
function ar2_theme_options_validate_checkbox( &$input ) {
	
	return ( isset( $input ) && '1' == $input ) ? true : false;
	
}

/**
 * Multidimensional helper function. Taken from WordPress' WP_Customize_Setting class.
 *
 * @since 2.0
 *
 * @param $root
 * @param $keys
 * @param bool $create Default is false.
 * @return null|array
 */
function ar2_multidimensional( &$root, $keys, $create = false ) {

	if ( $create && empty( $root ) )
		$root = array();

	if ( ! isset( $root ) || empty( $keys ) )
		return;

	$last = array_pop( $keys );
	$node = &$root;

	foreach ( $keys as $key ) {
		if ( $create && ! isset( $node[ $key ] ) )
			$node[ $key ] = array();

		if ( ! is_array( $node ) || ! isset( $node[ $key ] ) )
			return;

		$node = &$node[ $key ];
	}

	if ( $create && ! isset( $node[ $last ] ) )
		$node[ $last ] = array();
	
	if ( ! isset( $node[ $last ] ) )
		return;

	return array(
		'root' => &$root,
		'node' => &$node,
		'key'  => $last,
	);
	
}

/**
 * Will attempt to replace a specific value in a multidimensional array. 
 * Taken from WordPress' WP_Customize_Setting class.
 *
 * @since 2.0
 *
 * @param $root
 * @param $keys
 * @param mixed $value The value to update.
 * @return
 */
function ar2_multidimensional_replace( $root, $keys, $value ) {

	if ( ! isset( $value ) )
		return $root;
	elseif ( empty( $keys ) ) // If there are no keys, we're replacing the root.
		return $value;

	$result = ar2_multidimensional( $root, $keys, true );

	if ( isset( $result ) )
		$result['node'][ $result['key'] ] = $value;

	return $root;
	
}

/**
 * Will attempt to fetch a specific value from a multidimensional array. 
 * Taken from WordPress' WP_Customize_Setting class.
 *
 * @since 2.0
 *
 * @param $root
 * @param $keys
 * @param $default A default value which is used as a fallback. Default is null.
 * @return mixed The requested value or the default value.
 */
function ar2_multidimensional_get( $root, $keys, $default = null ) {

	if ( empty( $keys ) ) // If there are no keys, test the root.
		return isset( $root ) ? $root : $default;

	$result = ar2_multidimensional( $root, $keys );
	return isset( $result ) ? $result['node'][ $result['key'] ] : $default;
	
}

/**
 * Will attempt to check if a specific value in a multidimensional array is set. 
 * Taken from WordPress' WP_Customize_Setting class.
 *
 * @since 2.0
 *
 * @param $root
 * @param $keys
 * @return bool True if value is set, false if not.
 */
function ar2_multidimensional_isset( $root, $keys ) {

	$result = ar2_multidimensional_get( $root, $keys );
	return isset( $result );
	
}

/* End of file options.php */
/* Location: ./library/options.php */