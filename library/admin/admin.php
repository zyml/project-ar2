<?php

/**
 * Default tabs for theme options.
 * @since 1.6
 */
function ar2_theme_options_default_tabs() {

	global $ar2_postviews;

	$_default_tabs = array (
		'general'	=> __( 'General', 'ar2' ),
	);
	
	$_default_tabs = apply_filters( '_ar2_builtin_theme_option_tabs', $_default_tabs );
	
	$_default_tabs = array_merge( $_default_tabs, array ( 
		'layout'		=> __( 'Layout', 'ar2' ),
		'design'		=> __( 'Design', 'ar2' ),
		'thumbnails'	=> __( 'Thumbnails', 'ar2' ),
		'tools'		=> __( 'Tools', 'ar2' )
	) );
	
	return apply_filters( 'ar2_theme_options_tabs', $_default_tabs );
	
}

/**
 * Retrieves default sections for theme options.
 * @since 1.6
 */
function ar2_theme_options_default_sections() {

	$_defaults = array ( 
		'ar2_general_site_info' => array (
			'name'	=> __( 'Theme Information', 'ar2' ),
			'page'	=> 'ar2_general'
		),
		'ar2_general_social' => array (
			'name'	=> __( 'Social Networks', 'ar2' ),
			'page'	=> 'ar2_general'
		),
		'ar2_general_footer' => array (
			'name'	=> __( 'Footer Information', 'ar2' ),
			'page'	=> 'ar2_general'
		),
		
		'ar2_layout_excerpts' => array (
			'name'	=> __( 'Excerpts', 'ar2' ),
			'page'	=> 'ar2_layout'
		),
		'ar2_layout_archive' => array (
			'name'	=> __( 'Archive / Search', 'ar2' ),
			'page'	=> 'ar2_layout'
		),
		'ar2_layout_single' => array (
			'name'	=> __( 'Posts', 'ar2' ),
			'page'	=> 'ar2_layout'
		),
		
		'ar2_design_overall' => array (
			'name'	=> __( 'Overall Design', 'ar2' ),
			'page'	=> 'ar2_design'
		),
		
		'ar2_thumbnails_options' => array (
			'name'	=> __( 'Thumbnail Options', 'ar2' ),
			'page'	=> 'ar2_thumbnails'
		),
		
		'ar2_thumbnails_sizes' => array (
			'name'	=> __( 'Thumbnail Sizes', 'ar2' ),
			'page'	=> 'ar2_thumbnails'
		),
		
		'ar2_tools_port' => array (
			'name'	=> __( 'Import / Export Options', 'ar2' ),
			'page'	=> 'ar2_tools'
		),
	);
	
	return apply_filters( 'ar2_theme_options_sections', $_defaults );
	
}

/**
 * Retrieves default setting fields for theme options.
 * @since 1.6
 */
function ar2_theme_options_default_fields() {

	global $ar2_image_sizes, $ar2_styles;

	$_defaults = array (
	
		/* Site Information */
		'theme_version' => array (
			'type'			=> 'static',
			'title'			=> __( 'Version', 'ar2' ),
			'section'		=> 'ar2_general_site_info',
			'content'		=> '<strong>' . wp_get_theme()->get( 'Version' ) . '</strong>',
			'description'	=> __( 'If you have recently upgraded Project AR2 to a new release, it is <span style="color: red">highly recommended</span> that you reset your theme options, clear your browser cache and restart your browser before proceeding.', 'ar2' )
		),
		
		/* Social Networks */
		'site_rss_feed' => array (
			'type'			=> 'static',
			'title'			=> __( 'RSS Feed', 'ar2' ),
			'section'		=> 'ar2_general_social',
			'content'		=> '<code>' . get_feed_link( 'rss2' ) . '</code>',
			'description'	=> __( 'Custom feed URLs are no longer allowed due to support for automatic feed links.', 'ar2' )
		),
		'social_twitter' => array (
			'type'			=> 'input',
			'title'			=> __( 'Twitter Username', 'ar2' ),
			'section'		=> 'ar2_general_social',
		),
		'social_facebook' => array (
			'type'			=> 'input',
			'title'			=> __( 'Facebook Username', 'ar2' ),
			'section'		=> 'ar2_general_social',
		),
		'social_flickr' => array (
			'type'			=> 'input',
			'title'			=> __( 'Flickr ID', 'ar2' ),
			'section'		=> 'ar2_general_social',
		),
		'social_gplus' => array (
			'type'			=> 'input',
			'title'			=> __( 'Google+ ID', 'ar2' ),
			'section'		=> 'ar2_general_social',
		),
		'social_youtube' => array (
			'type'			=> 'input',
			'title'			=> __( 'YouTube Channel ID', 'ar2' ),
			'section'		=> 'ar2_general_social',
		),
		
		/* Footer Information */
		'footer_message' => array (
			'type'			=> 'textarea_html',
			'title'			=> __( 'Footer Message', 'ar2' ),
			'section'		=> 'ar2_general_footer',
			'description'	=> __( "Usually your website's copyright information would go here.<br /> It would be great if you could include a link to WordPress or the theme website. :)", 'ar2' ),
			'extras'		=> 'class="code"'
		),

		/* Excerpts */
		'nodebased_show_excerpts' => array (
			'type'			=> 'switch',
			'title'			=> __( 'Show Excerpts', 'ar2' ),
			'section'		=> 'ar2_layout_excerpts'
		),
		'excerpt_limit' => array (
			'type'			=> 'input',
			'title'			=> __( 'Excerpt Limit', 'ar2' ),
			'section'		=> 'ar2_layout_excerpts',
			'extras'		=> 'style="width: 50px" maxlength="2"',
			'description'	=> __( 'Excerpts will only be trimmed to the limit if no excerpt is specified for the respective post.', 'ar2' )
		),
		
		/* Archives / Search */
		'archive_display' => array (
			'type'			=> 'dropdown',
			'title'			=> __( 'Display Type', 'ar2' ),
			'section'		=> 'ar2_layout_archive',
			'options'		=> ar2_get_archive_display_types(),
		),
		
		/* Posts */
		'single_posts_display' => array (
			'type'			=> 'custom',
			'title'			=> __( 'Display in Single Posts', 'ar2' ),
			'section'		=> 'ar2_layout_single',
			'callback'		=> 'ar2_render_single_form_field',
			'setting'		=> 'ar2_theme_options[post_display]',
		),
		'relative_dates' => array (
			'type'			=> 'checkbox',
			'title'			=> __( 'Display Relative Post Dates', 'ar2' ),
			'section'		=> 'ar2_layout_single',
			'description'	=> __( 'Check this to display post dates relative to current time (eg. 2 days ago ).', 'ar2' )
		),
		
		/* Thumbnail Options */
		'auto_thumbs' => array (
			'type'				=> 'checkbox',
			'title'				=> __( 'Auto Thumbnails', 'ar2' ),
			'section'			=> 'ar2_thumbnails_options',
			'description'		=> __( 'Check this to allow the theme to automatically retrieve the first attached image from the post as featured image when no image is specified.', 'ar2' )
		),
		
		/* Layouts */
		'layout' => array (
			'type'			=> 'dropdown',
			'title'			=> __( 'No of Columns', 'ar2' ),
			'section'		=> 'ar2_design_overall',
			'options'		=> $ar2_styles->get_layouts(),
		),
		
		
		/* Import / Export Options */
		'import_theme_options' => array (
			'type'			=> 'textarea',
			'title'			=> __( 'Import Theme Options', 'ar2' ),
			'section'		=> 'ar2_tools_port',
			'description'	=> __( 'Import your theme settings by pasting the exported code in the textbox above.', 'ar2' ),
			'extras'		=> 'class="code"'
		),
		'export_theme_options' => array (
			'type'			=> 'textarea',
			'title'			=> __( 'Export Theme Options', 'ar2' ),
			'section'		=> 'ar2_tools_port',
			'description'	=> __( 'You can save the code above into a text file and use it when you need to import them into another installation. Note that not all options (custom background, child theme settings, etc.) will be exported.', 'ar2' ),
			'extras'		=> 'class="code"',
			'value'			=> json_encode( ar2_flush_theme_options() )
		),
		
	);
	
	foreach ( $ar2_image_sizes as $id => $args ) {
		$_defaults[ $id ] = array (
			'type'			=> 'thumbnail-size',
			'title'			=> $args[ 'name' ],
			'setting'		=> 'ar2_theme_options[thumbnails][' . $id . ']',
			'section'		=> 'ar2_thumbnails_sizes',
			'width'			=> $args[ 'w' ],
			'height'		=> $args[ 'h' ],
			'd_width'		=> $args[ 'dw' ],
			'd_height'		=> $args[ 'dh' ],
		); 	
	}
	
	if ( AR2_ALLOW_CUSTOM_STYLES ) {
		$_defaults[ 'style' ] = array (
			'type'			=> 'dropdown',
			'title'			=> __( 'Custom Stylesheet', 'ar2' ),
			'section'		=> 'ar2_design_overall',
			'options'		=> ar2_get_custom_css_files(),
			'description'	=> sprintf( __( 'Stylesheets can be placed in %s.', 'ar2' ), '<code>wp-content/themes/' . get_stylesheet() . '/css/styles/</code>' )
		);
	}
	
	// Allow developers to add more fields
	$_defaults = apply_filters( 'ar2_theme_options_fields', $_defaults );
	
	// Process the fields
	$sections = ar2_theme_options_default_sections();
	$_default_args = array (
		'title'		=> '',
		'type'		=> 'static',
		'section'	=> 'ar2_general_site_info',
		'page'		=> 'ar2_general',
		'content'	=> '',
		'extras'	=> ''	
	);
	
	foreach( $_defaults as $id => &$args ) {
	
		if ( !isset( $args[ 'setting' ] ) )
			$args[ 'setting' ] = 'ar2_theme_options[' . $id . ']';
		
		// Parse the ID for array keys (adapted from WP_Customize_Setting class).
		$args[ '_id_data' ][ 'keys' ] = preg_split( '/\[/', str_replace( ']', '', $args[ 'setting' ] ) );
		$args[ '_id_data' ][ 'base' ] = array_shift( $args[ '_id_data' ][ 'keys' ] );
	
		if ( isset( $sections[ $args[ 'section' ] ] ) )
			$args[ 'page' ] = $sections[ $args[ 'section' ] ][ 'page' ];
		
		$args = wp_parse_args( $args, $_default_args );
		
	}
	
	return $_defaults;
	
}

/**
 * @todo
 * @since 1.6
 */
function ar2_taxonomy_blacklist() {
	$_defaults = array( 'post_format' );
	return apply_filters( 'ar2_taxonomy_blacklist', $_defaults );
}

/**
 * @todo
 * @since 1.6
 */
function ar2_posttype_blacklist() {
	$_defaults = array( 'revision', 'nav_menu_item' );
	return apply_filters( 'ar2_posttype_blacklist', $_defaults );
}

/**
 * @todo
 * @since 2.0
 */
function ar2_get_archive_display_types() {
	
	global $ar2_postviews;
	$_defaults = $ar2_postviews->list_display_types();
	
	if ( isset( $_defaults[ 'slideshow' ] ) )
		unset( $_defaults[ 'slideshow' ] );
	
	return apply_filters( 'ar2_archive_display_types', $_defaults );
	
}

/**
 * Setup theme options sections and fields.
 * @since 1.6
 */
function ar2_theme_options_setup() {

	$sections = ar2_theme_options_default_sections();
	
	foreach ( $sections as $id => $args ) {
		$callback = ( isset( $args['callback'] ) ) ? $args['callback'] : 'ar2_theme_options_render_section';	
		add_settings_section( $id, $args['name'], $callback, $args['page'] );
	}
	
	$fields = ar2_theme_options_default_fields();
	
	foreach ( $fields as $id => $args ) {
		$args[ 'id' ] = $id;
		add_settings_field( $id, $args[ 'title' ], 'ar2_theme_options_render_field', $args[ 'page' ], $args[ 'section' ], $args );
	}
		
}
add_action( 'admin_init', 'ar2_theme_options_setup' );
 
/**
 * Add theme options page to admin menu.
 * @since 1.6
 */
function ar2_theme_options_add_page() {
	$theme_page = add_theme_page(
		__( 'Theme Options', 'ar2' ), 
		__( 'Options', 'ar2' ), 
		'edit_theme_options', 
		'theme_options', 
		'ar2_theme_options_render_page' 
	);
	
	if ( !$theme_page ) return;
}
add_action( 'admin_menu', 'ar2_theme_options_add_page' );

/**
 * Enqueue scripts and styles to theme options page.
 * @since 1.6
 */
function ar2_theme_options_enqueue_scripts( $hook_suffix ) {

	global $wp_styles;

	$current = isset( $_GET[ 'opt_type' ] ) ? esc_attr( $_GET['opt_type'] ) : '';
	
	wp_enqueue_style( 'ar2-theme-options', get_template_directory_uri() . '/css/theme-options.css', null, '2011-07-29' );
	
	wp_register_style( 'ar2-theme-options-ie', get_template_directory_uri() . '/css/theme-options-ie.css' );
	$wp_styles->add_data( 'ar2-theme-options-ie', 'conditional', 'lt IE 9' );
	wp_enqueue_style( 'ar2-theme-options-ie' );
	
	if ( is_rtl() )
	wp_enqueue_style( 'ar2-theme-options-rtl', get_template_directory_uri() . '/css/theme-options-rtl.css', null, '2012-08-09' );
	
	wp_enqueue_script( 'jquery-tokeninput', get_template_directory_uri() . '/js/jquery.tokeninput.min.js', array( 'jquery' ), '2012-08-09' );
	wp_enqueue_script( 'ar2-theme-options', get_template_directory_uri() . '/js/theme-options.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-tabs' ), '2012-08-09' );
	
	wp_localize_script( 'ar2-theme-options', 'ar2Admin_l10n', ar2_theme_options_localize_vars() );
	
}
add_action( 'admin_print_styles-appearance_page_theme_options', 'ar2_theme_options_enqueue_scripts' );

/**
 * Stores the localization object to localize theme options' scripts.
 * @since 1.6
 */
function ar2_theme_options_localize_vars() {

	$current = isset( $_GET[ 'opt_type' ] ) ? esc_attr( $_GET['opt_type'] ) : '';
	
	$_vars = array (
		'changedConfirmation'	=> __( 'If you have made any changes in the fields without submitting, all changes will be lost.', 'ar2' )
	);
	
	$_vars = array_merge( $_vars, array (
		'ajaxurl'				=> admin_url( 'admin-ajax.php' ),
		'hintText'				=> __( 'Start by entering a term here.', 'ar2' ),
		'noResultsText'			=> __( 'No results.', 'ar2' ),
		'searchingText'			=> __( 'Searching...', 'ar2' ),
	) );
	
	$_fields = ar2_theme_options_default_fields();
	
	foreach ( $_fields as $id => $args ) {
		if ( $args[ 'type' ] == 'cat-dropdown' ) {
			$_taxonomy_id = isset( $args[ 'taxonomy_id' ] ) ? $args[ 'taxonomy_id' ] : str_replace( '[terms]', '[taxonomy]', $args[ 'setting' ] );
			$_vars[ $id ] = ar2_prep_term_js_vars( $args[ 'setting' ], $_taxonomy_id );
		}
	}
	
	return apply_filters( 'ar2_theme_options_localize_vars', $_vars );
	
}

/**
 * @todo
 * @since 1.6
 */
function ar2_prep_term_js_vars( $term_opt_id, $tax_opt_id ) {

	$array = ar2_get_theme_option( $term_opt_id );
	$taxonomy = ar2_get_theme_option( $tax_opt_id );
	
	$result = array();
	
	if ( is_array( $array ) ) {
		foreach ( $array as $term_id ) {
			$t = get_term_by( 'id', $term_id, $taxonomy );
			if ( $t ) {
				$result[] = array(
					'id'	=> $term_id,
					'name'	=> $t->name
				);
			}
		}
	}
	
	return $result;
	
}

/**
 * Render theme options page.
 * @since 1.6
 */
function ar2_theme_options_render_page() {
	global $ar2_options;

	// ar2_reset_theme_options(); // temporary
	?>
	<div id="ar2-theme-options" class="wrap">
		<?php screen_icon(); ?>
		<h2><?php printf( __( '%s Theme Options', 'ar2' ), wp_get_theme()->get( 'Name' ) ); ?></h2>
		
		<?php settings_errors(); ?>
		<?php ar2_theme_options_render_tabs(); ?>
		
		<!-- <pre><code><?php print_r( $ar2_options ); ?></code></pre> -->
	
		<form id="ar2-theme-options-form" method="post" action="options.php" enctype="multipart/form-data">
			<?php 
			settings_fields( 'ar2_options' ); 
			ar2_theme_options_render_form();
			?>
			
			<p class="submit">
				<?php 
				submit_button( __( 'Save Changes', 'ar2' ), 'primary', 'ar2_theme_options[submit]', false );
				submit_button( __( 'Reset Settings', 'ar2' ), 'secondary', 'ar2_theme_options[reset]', false );
				?>
			</p>
		</form>
		
		<div class="asides">
			<aside class="widget clearfix">
				<h3 class="widget-title"><?php _e( 'Need Support?', 'ar2' ) ?></h3>
				<p><?php _e( 'Ask questions and share tips and tricks with fellow theme users all over the world at the theme community forums.', 'ar2' ) ?></p>
				<p class="theme-link"><a href="http://forums.arrastheme.com/"><?php _e( 'Community Forums', 'ar2' ) ?></a></p>
			</aside>
			<aside class="widget clearfix">
				<h3 class="widget-title"><?php _e( 'Like this Theme?', 'ar2' ) ?></h3>
				<p><?php _e( 'Arras & Project AR2 are open-source projects developed in my free time since 2009 for the benefit of the WordPress community. Your donations will support the on-going development of these themes in many years to come.', 'ar2' ) ?></p>
				<p class="theme-link"><a href="http://www.arrastheme.com/donate/"><?php _e( 'Donate to Theme Author', 'ar2' ) ?></a></p>
				<p><?php _e( 'There are other ways you can support these themes too:', 'ar2' ) ?></p>
				<ul>
					<li><strong><a href="http://forums.arrastheme.com/"><?php _e( 'Forums', 'ar2' ) ?></a></strong><br /><?php _e( 'Ask questions and share tips and tricks with others.', 'ar2' ) ?></li>
					<li><strong><a href="https://www.transifex.net/projects/p/project-ar2/"><?php _e( 'Translations', 'ar2' ) ?></a></strong><br /><?php _e( 'Fluent in other languages? Translate the theme for other users around the world.', 'ar2' ) ?></li>
					<li><strong><a href="https://github.com/zyml/project-ar2"><?php _e( 'GitHub', 'ar2' ) ?></a></strong><br /><?php _e( 'Aid development by sending in bug reports and issuing patches.', 'ar2' ) ?></li>
				</ul>
			</aside>
		</div>
		
	</div>
	<?php
}

/**
 * Renders tabs to separate theme options into sections.
 * 
 * Referenced from: 
 * http://www.chipbennett.net/2011/02/17/incorporating-the-settings-api-in-wordpress-themes/3/
 * http://www.onedesigns.com/tutorials/separate-multiple-theme-options-pages-using-tabs
 * 
 * @since 1.6
 */
function ar2_theme_options_render_tabs() {

	$tabs = ar2_theme_options_default_tabs();
	$links = array();
	
	foreach ( $tabs as $tab => $name )
		$links[] = '<li><a class="nav-tab" href="#' . $tab . '">' . $name . '</a></li>';
		
	echo '<div class="nav-tab-wrapper"><ul class="clearfix">';
	foreach ( $links as $link ) echo $link;
	echo '</ul></div>';
	
}

/**
 * Renders individual sections' form output.
 * @since 1.6
 */
function ar2_theme_options_render_form() {

	$tabs = ar2_theme_options_default_tabs();
	
	foreach( $tabs as $tab => $name ) {
		echo '<div id="' . $tab . '" class="section-wrapper">';
		do_settings_sections( 'ar2_' . $tab );
		echo '</div>';
	}
	
}

/**
 * Renders section header in theme options. Does nothing at the moment.
 * @since 1.6
 */
function ar2_theme_options_render_section() {
	return false;
}

/**
 * Renders single post's checkboxes fields.
 * @since 1.6
 */
function ar2_render_single_form_field( $args ) {

	echo '<input type="hidden" value="0" name="ar2_theme_options[single_posts_display][post_author]" />';
	echo ar2_form_checkbox( 'ar2_theme_options[single_posts_display][post_author]', 1, ar2_get_theme_option( 'post_display[post_author]' ) ); ?>
	<label for="ar2_theme_options[single_posts_display][post_author]"><?php _e('Author & Publish Date', 'ar2') ?></label>
	<br />
	
	<?php echo '<input type="hidden" value="0" name="ar2_theme_options[single_posts_display][excerpt]" />'; ?>
	<?php echo ar2_form_checkbox( 'ar2_theme_options[single_posts_display][excerpt]', 1, ar2_get_theme_option( 'post_display[excerpt]' ) ); ?> 
	<label for="ar2_theme_options[single_posts_display][excerpt]"><?php _e('Post Excerpt (if available)', 'ar2') ?></label>
	<br />
	
	<?php echo '<input type="hidden" value="0" name="ar2_theme_options[single_posts_display][post_social]" />'; ?>
	<?php echo ar2_form_checkbox( 'ar2_theme_options[single_posts_display][post_social]', 1, ar2_get_theme_option( 'post_display[post_social]' ) ); ?> 
	<label for="ar2_theme_options[single_posts_display][post_social]"><?php _e('Facebook, Twitter, Google+ Buttons (English Only)', 'ar2') ?></label>
	<br />
	
	<?php echo '<input type="hidden" value="0" name="ar2_theme_options[single_posts_display][post_cats]" />'; ?>
	<?php echo ar2_form_checkbox( 'ar2_theme_options[single_posts_display][post_cats]', 1, ar2_get_theme_option( 'post_display[post_cats]' ) ); ?> 
	<label for="ar2_theme_options[single_posts_display][post_cats]"><?php _e('Categories', 'ar2') ?></label>
	<br />
	
	<?php echo '<input type="hidden" value="0" name="ar2_theme_options[single_posts_display][post_tags]" />'; ?>
	<?php echo ar2_form_checkbox( 'ar2_theme_options[single_posts_display][post_tags]', 1, ar2_get_theme_option( 'post_display[post_tags]' ) ); ?> 
	<label for="ar2_theme_options[single_posts_display][post_tags]"><?php _e('Tags', 'ar2') ?></label>
	<br />
	
	<?php echo '<input type="hidden" value="0" name="ar2_theme_options[single_posts_display][single_thumbs]" />'; ?>
	<?php echo ar2_form_checkbox( 'ar2_theme_options[single_posts_display][single_thumbs]', 1, ar2_get_theme_option( 'post_display[single_thumbs]' ) ); ?> 
	<label for="ar2_theme_options[single_posts_display][single_thumbs]"><?php _e('Post Thumbnail', 'ar2') ?></label>
	<br />
	
	<?php echo '<input type="hidden" value="0" name="ar2_theme_options[single_posts_display][display_author]" />'; ?>
	<?php echo ar2_form_checkbox( 'ar2_theme_options[single_posts_display][display_author]', 1, ar2_get_theme_option( 'post_display[display_author]' ) ); ?> 
	<label for="ar2_theme_options[single_posts_display][display_author]"><?php _e('Author Information', 'ar2') ?></label>	
	<?php
	
}

/**
 * Renders post types menus.
 * @since 1.6
 */
function ar2_form_posttype_dropdown( $id, $value = null ) {
	$post_types = ar2_get_post_types_list();
	return ar2_form_dropdown( $id, $post_types, $value, 'class="post-dropdown"' );
}

/**
 * Renders taxonomies menus.
 * @since 1.6
 */
function ar2_form_taxonomies_dropdown( $id, $posttype, $value = null ) {
	$taxonomies = ar2_get_taxonomies_list( $posttype );
	
	$extras = 'class="tax-dropdown"';
	if ( count( $taxonomies ) == 0 ) {
		$taxonomies[0] = __( 'No Taxonomies Available', 'ar2' );
		$extras .= ' disabled="disabled"';	
	}
	
	return ar2_form_dropdown( $id, $taxonomies, $value, $extras );
}

/**
 * Renders terms dropdown menus based on post type / taxonomy.
 * @since 1.6
 */
function ar2_form_terms_dropdown( $id, $setting, $tax_id, $value = null ) {
	return ar2_form_input( 'ar2_theme_options[' . $id . ']', null, 'class="tokeninput" id="' . $id .'"' );
}

/**
 * @todo
 * @since 1.6
 */
function ar2_get_taxonomy_name( $tax ) {
	$obj = get_taxonomy( $tax );
	
	if ( $obj )
		return $obj->labels->name;
	else
		return __( 'Taxonomy', 'ar2' );
}

/**
 * @todo
 * @since 1.6
 */
function ar2_get_post_types_list() {
	$post_types = get_post_types( array( 'public' => true ), 'object' );
	$output = array();
	
	foreach ( $post_types as $id => $obj ) {
		$output[$id] = $obj->labels->name;
	}
	
	return $output;
}

/**
 * @todo
 * @since 1.6
 */
function ar2_get_taxonomies_list( $object ) {
	$taxonomies = get_object_taxonomies( $object, 'objects' );

	$opt = array();
	
	foreach( $taxonomies as $id => $obj ) {
		if ( !in_array( $id, ar2_taxonomy_blacklist() ) ) {
			if ( $id == 'category' || $id == 'post_tag' || isset( $obj->query_var ) )
				$opt[$id] = $obj->labels->name;
		}
	}
	
	return $opt;
}

/**
 * @todo
 * @since 1.6
 */
function ar2_get_tapestries_list() {
	global $ar2_tapestries;
	
	$output = array();
	
	foreach($ar2_tapestries as $id => $args) {
		$output[$id] = $args->name;
	}
	
	ksort($output);
	return $output;
}

/**
 * @todo
 * @since 1.6
 */
function ar2_ajax_get_taxonomies_list() {
	$post_type	= esc_attr( $_POST['post_type'] );
	
	if ( isset( $post_type ) ) {
		$list = ar2_get_taxonomies_list( $post_type );
		echo json_encode( ( count( $list ) == 0 ) ? array( __( 'No Taxonomies Available', 'ar2' ) ) : $list );
	}
		
	die();
}
add_action( 'wp_ajax_ar2_load_taxonomies', 'ar2_ajax_get_taxonomies_list' );

/**
 * @todo
 * @since 1.6
 */
function ar2_ajax_get_terms_list() {

	global $wpdb;
	
	$limit		= esc_attr( $_POST['limit'] );
	$query		= esc_attr( $_POST['query'] );
	
	$results = $wpdb->get_results( $wpdb->prepare( "SELECT t.term_id, t.name FROM $wpdb->terms AS t, $wpdb->term_taxonomy AS tt WHERE ( t.term_id = tt.term_id ) AND tt.taxonomy = %s AND t.name LIKE %s", array( $limit, '%' . $query . '%' ) ) );
	
	$list = array();
	
	foreach ( $results as $result ) {
		$list[] = array( 
			'id'	=> $result->term_id,
			'name'	=> $result->name
		);
	}
	
	echo json_encode( $list );
		
	die();
	
}
add_action( 'wp_ajax_ar2_load_terms', 'ar2_ajax_get_terms_list' );

/**
 * @todo
 * @since 1.5
 */
function ar2_get_terms_list( $taxonomy ) {

	$terms = get_terms( $taxonomy, 'hide_empty=0' );
	$options = array();
	
	if ( !is_wp_error( $terms ) ) {
		foreach ( $terms as $term ) {
			if ( $taxonomy == 'category' || $taxonomy == 'post_tag' ) {
				$options[ $term->term_id ] = $term->name;
			} else {
				$options[ $term->slug ] = $term->name;
			}
		}
	}
	
	return $options;

}

/**
 * Renders individual field in theme options.
 * @since 1.6
 */
function ar2_theme_options_render_field( $args = array() ) {
	global $ar2_options;

	extract( $args, EXTR_SKIP );
	
	if ( !isset( $value ) ) {
		if ( !is_array( $ar2_options ) ) 
			ar2_flush_theme_options();
		
		$value = ar2_multidimensional_get( $ar2_options, $_id_data[ 'keys' ] );
	}
	
	switch ( $type ) :
		
		case 'thumbnail-size' :
			?>
			<div class="thumbnail-size-fields">
				<label for="'ar2_theme_options[<?php echo $id ?>][w]"><?php _e( 'Width', 'ar2' ) ?></label>
				<?php echo ar2_form_input( 'ar2_theme_options[' .  $id . '][w]', $width, 'class="thumb-width" style="width: 50px" maxlength="3"' ); ?>
				<span class="thumbnail_default_width" style="display: none"><?php echo $d_width ?></span>
				
				<label for="'ar2_theme_options[<?php echo $id ?>][h]"><?php _e( 'Height', 'ar2' ) ?></label>
				<?php echo ar2_form_input( 'ar2_theme_options[' . $id . '][h]', $height, 'class="thumb-height" style="width: 50px" maxlength="3"' ); ?>
				<span class="thumbnail_default_height" style="display: none"><?php echo $d_height ?></span>
				
				<a class="reset-thumbnail-sizes button-secondary"><?php _e( 'Reset to Defaults', 'ar2' ) ?></a>
			</div>
			<?php
		break;
		
		case 'cat-dropdown' :
			$_taxonomy_keys = array_slice( $_id_data[ 'keys' ], 0, count( $_id_data[ 'keys' ] ) - 1 );
			$_taxonomy_keys[] = 'taxonomy';
			?>
			<div class="cat-dropdown-container">
				<p class="cat-dropdown-multiselect">
					<?php echo ar2_form_terms_dropdown( $id, $setting, ar2_multidimensional_get( $ar2_options, $_taxonomy_keys ), $value ); ?>
					<?php 
					$taxonomy = ar2_multidimensional_get( $ar2_options, $_taxonomy_keys );
					echo ar2_form_dropdown( 'ar2_theme_options[' . $id . '][]', ar2_get_terms_list( $taxonomy ), $value, 'multiple="multiple" class="terms-multiselect"' );
					?>
				</p>
				<span style="display: none" class="choosetax"><?php _e( 'Please choose your taxonomy before proceeding.', 'ar2' ) ?></span>
			</div>
			<?php
		break;
		
		case 'taxonomies-dropdown' :
			$_posttype_keys = array_slice( $_id_data[ 'keys' ], 0, count( $_id_data[ 'keys' ] ) - 1 );
			$_posttype_keys[] = 'post_type';
			
			$_post_type = ( ar2_multidimensional_get( $ar2_options, $_posttype_keys ) ) == '' ? 'post' : ar2_multidimensional_get( $ar2_options, $_posttype_keys );
			
			$taxonomy = get_taxonomy( $value );
			echo '<span class="no-js-label">' . $taxonomy->labels->name . '</span>';
			?>
			<div class="tax-dropdown-container">
				<?php echo ar2_form_taxonomies_dropdown( 'ar2_theme_options[' . $id . ']', $_post_type, $value, $extras ); ?> 
				<img class="ajax-feedback" src="<?php echo get_template_directory_uri() ?>/images/admin/wpspin_light.gif" alt="<?php _e( 'Loading', 'ar2' ) ?>" />
			</div>
			<?php
		break;
		
		case 'posttype-dropdown' :
			$post_type = get_post_type_object( $value );
			echo '<span class="no-js-label">' . $post_type->labels->name . '</span>';
			echo ar2_form_posttype_dropdown( 'ar2_theme_options[' . $id . ']', $value, $extras );
		break;
		
		case 'switch' :
			echo '<input type="hidden" value="0" name="ar2_theme_options[' . $id . ']" />';
			
			echo '<div class="switch">';
			echo ar2_form_checkbox( 'ar2_theme_options[' . $id . ']', true, $value, 'id="ar2_theme_options[' . $id . ']"' );
			echo '<label for="ar2_theme_options[' . $id . ']"><div class="switch-inner">';
			echo '<span class="switch-active">' . __( 'On', 'ar2' ) . '</span>';
			echo '<span class="switch-inactive">' . __( 'Off', 'ar2' ) . '</span>';
			echo '</div><div class="switch-node"></div></label></div>';
		break;
		
		case 'color-switcher' :
			echo '<div class="color-switch">';
			foreach ( $options as $color_id => $color ) {
				if ( $color_id == 'default' ) {
					echo ar2_form_radio( 'ar2_theme_options[' . $id . ']', 'default', $value, 'id="' . $id . '-' . $color_id . '"' );
					echo '<label title="' . $color[ 'label' ] . '" class="default" for="' . $id . '-' . $color_id . '"><span>' . $color[ 'label' ] . '</span></label>';
				} else {
					echo ar2_form_radio( 'ar2_theme_options[' . $id . ']', $color[ 'hex' ], $value, 'id="' . $id . '-' . $color_id . '"' );
					echo '<label title="' . $color[ 'label' ] . '" for="' . $id . '-' . $color_id . '"><span style="background: ' . $color[ 'hex' ] . '">' . $color[ 'label' ] . '</span></label>';
				}
			}
			echo '</div>';
		break;
		
		case 'input' :
			echo ar2_form_input( 'ar2_theme_options[' . $id . ']', $value, $extras );
		break;
		
		case 'textarea' :
			echo ar2_form_textarea( 'ar2_theme_options[' . $id . ']', $value, $extras );
		break;
		
		case 'textarea_html' :
			echo ar2_form_textarea( 'ar2_theme_options[' . $id . ']', $value, $extras );
		break;
		
		case 'dropdown' :
			echo ar2_form_dropdown( 'ar2_theme_options[' . $id . ']', $options, $value, $extras );
		break;
		
		case 'checkbox' :
			echo '<input type="hidden" value="0" name="ar2_theme_options[' . $id . ']" />';
			echo ar2_form_checkbox( 'ar2_theme_options[' . $id . ']', true, $value, $extras );
		break;
		
		case 'custom' :
			if ( ( string ) $callback != '' )
				call_user_func( $callback, array_merge( $args, array ( 'value' => $value ) ) );
		break;
		
		default : // default is static
			echo ( ( false != $content ) ? $content : '' );
		
	endswitch;
	
	if ( isset( $description ) ) {
		if ( $type == 'checkbox' )
			echo '&nbsp;';
		else
			echo '<br />';
			
		echo '<span class="description">' . $description . '</span>';
	}
}

/**
 * Scans for any valid CSS file within /css/styles/
 * @since 1.6
 */
function ar2_get_custom_css_files() {

	$styles = array( '_default' => __( 'Default Stylesheet (style.css)', 'ar2' ) );
	
	$style_dir = dir( get_template_directory() . '/css/styles/' );
	if ( $style_dir ) {
		while ( ( $file = $style_dir->read() ) !== false )
			if ( is_valid_css_file( $file ) )
				$styles[ substr( $file, 0, -4 ) ] = $file;
	}
	
	return $styles;
	
}

/**
 * Update default Theme Customizer settings.
 * @since 2.0
 */
function ar2_customize_register( $wp_customize ) {

	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

}
add_action( 'customize_register', 'ar2_customize_register' );

/* End of file admin.php */
/* Location: ./admin/admin.php */