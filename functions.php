<?php
define ( 'AR2_CHILD', is_child_theme() );
define ( 'AR2_VERSION' , wp_get_theme()->get( 'Version' ) );
define ( 'AR2_LIB', get_template_directory() . '/library' );

// Set this to true if you wish to use custom stylesheets.
// CSS files have to be placed in /css/styles/ folder.
define( 'AR2_ALLOW_CUSTOM_STYLES', false );

do_action( 'ar2_init' );

/**
 * Theme setup function - to be run during 'after_setup_theme' action hook.
 * @since 1.6
 */
add_action( 'after_setup_theme', 'ar2_setup', 10 );

if ( !function_exists('ar2_setup') ) :

function ar2_setup() {

	/* Load theme options */
	require_once AR2_LIB . '/options.php';
	
	/* Post Views API */
	require_once AR2_LIB . '/postviews.php';
	
	/* Load theme library files */
	require_once AR2_LIB . '/actions.php';
	require_once AR2_LIB . '/filters.php';
	require_once AR2_LIB . '/template.php';
	require_once AR2_LIB . '/thumbnails.php';
	require_once AR2_LIB . '/styles.php';
	require_once AR2_LIB . '/widgets.php';
	
	//require_once AR2_LIB . '/shortcodes.php';

	require_once AR2_LIB . '/admin/form.php';
	require_once AR2_LIB . '/admin/admin.php';

	/* Langauge support */
	load_theme_textdomain( 'ar2', get_template_directory() . '/language' );
	
	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );
	
	/* Theme support */
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'nav-menus' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'custom-background', array ( 
		'default-color'		=> 'F0F0F0',
		'wp-head-callback'	=> 'ar2_custom_bg_header_callback',
	) );
	add_theme_support( 'custom-header', array (
		'width'						=> 960,
		'height'					=> 120,
		'default-text-color'		=> '333',
		'wp-head-callback'			=> 'ar2_header_style',
		'admin-head-callback'		=> 'ar2_admin_header_style',
		'admin-preview-callback'	=> 'ar2_admin_header_image',
	) );
	add_theme_support( 'post-formats', array ( 
		'gallery',  
		'image', 
		'video', 
		'audio', 
	) );
	
	/* Menus locations */
	register_nav_menus( array (
		'main-menu'		=> __( 'Main Menu', 'ar2' ),
		'top-menu'		=> __( 'Top Menu', 'ar2' ),
		'footer-nav'	=> __( 'Footer Navigation', 'ar2' )
	));
	
	/* Register sidebars */
	ar2_add_sidebars();
	
	/* Header actions */
	remove_action( 'wp_head', 'pagenavi_css' );
	
	add_action( 'wp_footer', 'ar2_add_header_js', 100 );
	
	// Editor Style
	add_editor_style();

	/* Thumbnail sizes */
	ar2_add_theme_thumbnails();
	
	/* Max image size */
	$max_image_size = ar2_post_thumbnail_size();
	$content_width = $max_image_size[ 0 ];
	
	set_post_thumbnail_size( $max_image_size[ 0 ], $max_image_size[ 1 ] );
	
	// print_r($ar2_options);
	
}

endif;

/**
 * Sidebar setup function.
 * @since 1.6
 */
function ar2_add_sidebars() {
	
	/* Default sidebars */
	register_sidebar( array(
		'name' => __( 'Primary Sidebar', 'ar2' ),
		'id' => 'primary-sidebar',
		'before_widget' => '<aside id="%1$s" class="widget clearfix">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	) );
	register_sidebar( array(
		'name' => __( 'Bottom Content #1', 'ar2' ),
		'id' => 'bottom-content-1',
		'before_widget' => '<aside id="%1$s" class="widget clearfix">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	) );
	register_sidebar( array(
		'name' => __( 'Bottom Content #2', 'ar2' ),
		'id' => 'bottom-content-2',
		'before_widget' => '<aside id="%1$s" class="widget clearfix">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>'
	) );
	
	/* Footer sidebars (4 sidebars) */
	$footer_sidebars = 4;
	
	for( $i = 1; $i < $footer_sidebars + 1; $i++ ) {
		register_sidebar( array(
			'name' => sprintf( __( 'Footer Sidebar #%s', 'ar2' ), $i ),
			'id' => 'footer-sidebar-' . $i,
			'before_widget' => '<aside id="%1$s" class="widget clearfix">',
			'after_widget' => '</aside>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		) );
	}
			
}
 
/* End of file functions.php */
/* Location: ./functions.php */