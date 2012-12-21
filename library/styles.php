<?php
/**
 * AR2 Styles Class.
 *
 * @package AR2
 * @subpackage Styles
 * @since 2.0
 */
final class AR2_Styles {
	
	protected $layouts = array();
	
	protected $font_sets = array();
	protected $fonts = array();
	
	protected $color_sets = array();
	
	/**
	 * Constructor.
	 * @since 2.0
	 */
	public function __construct() {
	
		add_action( 'after_setup_theme', array( $this, 'default_layouts' ), 5 );
		add_action( 'after_setup_theme', array( $this, 'default_fonts' ), 5 );
		add_action( 'after_setup_theme', array( $this, 'default_colors' ), 5 );
		
		if ( !is_admin() ) {
			add_action( 'wp_print_styles', array( $this, 'load_styles' ), 1 );
			add_action( 'wp_print_styles', array( $this, 'remove_styles' ), 100 );
		}

		add_action( 'wp_head', array( $this, 'override_styles' ) );
		
		// Theme Options.
		add_filter( 'ar2_default_theme_options', array( $this, 'add_default_theme_option' ) );
		add_filter( 'ar2_theme_options_sections', array( $this, 'add_theme_option_section' ) );
		add_filter( 'ar2_theme_options_fields', array( $this, 'add_theme_option_fields' ) );	
		
		// Theme Customize (BUGGY).
		// add_action( 'customize_register', array( $this, 'customize_register' ) );
		// add_filter( 'ar2_theme_customize_validate', array( $this, 'validate' ), 15, 3 );
	
	}
	
	/**
	 * Adds a new layout.
	 * @since 2.0
	 */
	public function add_layout( $id, $name ) {
	
		$this->layouts[ $id ] = $name;
		
	}
	
	/**
	 * Removes a layout from the list.
	 * @since 2.0
	 */
	public function remove_layout( $id ) {
	
		unset( $this->layouts[ $id ] );
		
	}
	
	/**
	 * Retrieves all layouts from the list.
	 * @since 2.0
	 */
	public function get_layouts() {
	
		return $this->layouts;
		
	}
	
	/**
	 * Adds a new font set.
	 * @since 2.0
	 */
	public function add_font_set( $id, $name, $css = 'body' ) {
	
		$this->font_sets[ $id ] = array (
			'label'		=> $name,
			'fonts'		=> array(),
			'selectors'	=> $css,
		);
		
		$this->add_font( 'default', $id, array (
			'label'		=> __( 'Default', 'ar2' ),
		) );
		
	}
	
	/**
	 * Removes a font set.
	 * @since 2.0
	 */
	public function remove_font_set( $id ) {
	
		unset( $this->font_sets[ $id ] );
		
	}
	
	/**
	 * Adds a new font to a font set.
	 * @since 2.0
	 */
	public function add_font( $id, $sets = null, $args ) {
	
		$_defaults = array (
			'label'		=> __( 'Unknown Font', 'ar2' ),
			'web_font'	=> false,
			'link'		=> null,
		);
		$args = wp_parse_args( $args, $_defaults );
		
		$this->fonts[ $id ] = $args;
		
		if ( !is_array( $sets ) )
			$sets = array( $sets );
		
		foreach( $sets as $set_id ) {
			if ( isset( $this->font_sets[ $set_id ] ) )
				$this->font_sets[ $set_id ][ 'fonts' ][] = $id;
		}
		
		return $args;
		
	}
	
	/**
	 * Removes a font from a font set.
	 * @since 2.0
	 */
	public function remove_font( $id ) {
		
		if ( isset( $this->fonts[ $id ] ) )
			unset( $this->fonts[ $id ] );
		
		foreach( $this->font_sets as $set_id => $set ) {
			$key = array_search( $id, $set[ 'fonts' ] );
			unset( $set[ 'fonts' ][ $key ] );
		}
		
	}
	
	/**
	 * Adds a new color set.
	 * @since 2.0
	 */
	public function add_color_set( $id, $name, $args ) {
		
		$_defaults = array (
			'label'		=> $name,
			'colors'	=> array(),	
		);
		
		$args = wp_parse_args( $args, $_defaults );
		
		$this->color_sets[ $id ] = $args;
		
		$this->add_color( 'default', $id, array (
			'label'		=> __( 'Default', 'ar2' ),
		) );
		
	}
	
	/**
	 * Removes a color set.
	 * @since 2.0
	 */
	public function remove_color_set( $id ) {
	
		unset( $this->color_sets[ $id ] );
		
	}
	
	/**
	 * Adds a new color to a color set.
	 * @since 2.0
	 */
	public function add_color( $id, $set, $args ) {
	
		if ( isset( $this->color_sets[ $set ] ) ) {
			$_defaults = array (
				'label'		=> __( 'Unknown Color', 'ar2' ),
				'hex'		=> '#86C140',
			);
			$args = wp_parse_args( $args, $_defaults );
			
			$this->color_sets[ $set ][ 'colors' ][ $id ] = $args;
		}
		
	}
	
	/**
	 * Removes a color from a color set.
	 * @since 2.0
	 */
	public function remove_color( $id, $set ) {
		
		if ( isset( $this->color_sets[ $set ] ) )
			unset( $this->color_sets[ $set ][ 'colors' ][ $id ] );
	
	}
	
	/**
	 * Retrieves all available fonts from the font set as an option array.
	 * @since 2.0
	 */
	public function list_fonts( $set ) {
	
		$result = array();
		if ( isset( $this->font_sets[ $set ] ) ) {
			
			foreach( $this->font_sets[ $set ][ 'fonts' ] as $id ) {

				$result[ $id ] = $this->fonts[ $id ][ 'label' ];;
				
			}
			
		}
		return $result;
		
	}
	
	/**
	 * Retrieves all available colors from the color set as an option array.
	 * @since 2.0
	 */
	public function list_colors( $set ) {
	
		$result = array();
		
		if ( isset( $this->color_sets[ $set ] ) )
			$result = $this->color_sets[ $set ][ 'colors' ];

		return $result;
		
	}

	/**
	 * Register default layouts.
	 * @since 2.0
	 */
	public function default_layouts() {
	
		$this->add_layout( 'twocol-r', __( '2 Column Layout (Right Sidebar)', 'ar2' ) );
		$this->add_layout( 'twocol-l', __( '2 Column Layout (Left Sidebar)', 'ar2' ) );
		
		do_action( 'ar2_styles_layouts_register', $this );
		
	}
	
	/**
	 * Register default font sets.
	 * @since 2.0
	 */
	public function default_fonts() {
	
		$this->add_font_set( 'primary', __( 'Primary Font', 'ar2' ), 'body, input, textarea' );
		$this->add_font_set( 'secondary', __( 'Secondary Font', 'ar2' ), 'h1, h2, h3, h4, h5, .entry-title, .home-title, .archive-title, .logo, .menu, .multi-sidebar .tabs' );
		
		$this->add_font( 'lato', array( 'primary', 'secondary' ), array (
			'label'		=> 'Lato',
			'family'	=> 'Lato',
			'web_font'	=> true,
			'link'		=> 'http://fonts.googleapis.com/css?family=Lato:400,700',
		) );
		$this->add_font( 'droid-sans', array( 'primary', 'secondary' ), array (
			'label'		=> 'Droid Sans',
			'family'	=> 'Droid Sans',
			'web_font'	=> true,
			'link'		=> 'http://fonts.googleapis.com/css?family=Droid+Sans:400,700',
		) );
		$this->add_font( 'open-sans', array( 'primary', 'secondary' ), array (
			'label'		=> 'Open Sans',
			'family'	=> 'Open Sans',
			'web_font'	=> true,
			'link'		=> 'http://fonts.googleapis.com/css?family=Open+Sans:400,700',
		) );
		$this->add_font( 'ubuntu', array( 'primary', 'secondary' ), array (
			'label'		=> 'Ubuntu',
			'family'	=> 'Ubuntu',
			'web_font'	=> true,
			'link'		=> 'http://fonts.googleapis.com/css?family=Ubuntu:400,700',
		) );
		
		$this->add_font( 'arimo', 'secondary', array (
			'label'		=> 'Arimo',
			'family'	=> 'Arimo',
			'web_font'	=> true,
			'link'		=> 'http://fonts.googleapis.com/css?family=Arimo:400,700',
		) );
		
		do_action( 'ar2_styles_fonts_register', $this );
		
	}
	
	/**
	 * Register default font sets.
	 * @since 2.0
	 */
	public function default_colors() {
	
		$this->add_color_set( 'primary', __( 'Primary Color', 'ar2' ), array (
			'bg_selectors'		=> 'input[type=submit]:hover, a.more-link:hover, .navigation a:hover, .comment-controls a:hover, a.post-edit-link:hover, .wp-pagenavi a:hover, .tags a:hover, .tagcloud a:hover, .navigation .current, .wp-pagenavi .current, #main-nav ul.menu ul a:hover, #main-nav ul.menu ul ul a:hover, .multi-sidebar .tabs a:hover, .multi-sidebar .tabs .ui-state-active a, .posts-node .entry-comments, .posts-quick .entry-comments, .posts-slideshow ul.flex-direction-nav li a:hover, .post-navigation span, .post-navigation a:hover span, .social-nav a:hover',
			'text_selectors'	=> 'a:hover',
			'border_selectors'	=> '#main-nav ul.menu li a:hover',
		) );
		
		$this->add_color( 'orange', 'primary', array (
			'label'		=> __( 'Orange', 'ar2' ),
			'hex'		=> '#F25C05',
		) );
		$this->add_color( 'yellow', 'primary', array (
			'label'		=> __( 'Yellow', 'ar2' ),
			'hex'		=> '#FFE300',
		) );
		$this->add_color( 'red', 'primary', array (
			'label'		=> __( 'Red', 'ar2' ),
			'hex'		=> '#C51C30',
		) );
		$this->add_color( 'blue', 'primary', array (
			'label'		=> __( 'Blue', 'ar2' ),
			'hex'		=> '#05AFF2',
		) );
		$this->add_color( 'pink', 'primary', array (
			'label'		=> __( 'Pink', 'ar2' ),
			'hex'		=> '#FF00A9',
		) );

		do_action( 'ar2_styles_colors_register', $this );
		
	}
	
	/**
	 * Filter for adding default theme options for fonts.
	 * @since 2.0
	 */
	public function add_default_theme_option( $options ) {
	
		if ( !isset( $options[ 'fonts' ] ) )
			$options[ 'fonts' ] = array();
		
		foreach( $this->font_sets as $id => $args )
			$options[ 'fonts' ][ $id ] = 'default';
			
		if ( !isset( $options[ 'colors' ] ) )
			$options[ 'colors' ] = array();
		
		foreach( $this->color_sets as $id => $args )
			$options[ 'colors' ][ $id ] = 'default';
		
		return $options;
		
	}
	
	/**
	 * Filter for adding the 'Fonts' section in the 'Design' tab.
	 * @since 2.0
	 */
	public function add_theme_option_section( $sections ) {
		
		if ( count( $this->font_sets ) > 0 ) {
			$sections[ 'ar2_fonts' ] = array (
				'name' => __( 'Custom Fonts', 'arras' ),
				'page' => 'ar2_design',
			);
		}	
		
		if ( count( $this->color_sets ) > 0 ) {
			$sections[ 'ar2_colors' ] = array (
				'name' => __( 'Color Schemes', 'arras' ),
				'page' => 'ar2_design',
			);
		}	
		
		return $sections;
		
	}
	
	/**
	 * Filter for adding theme option fields for custom fonts.
	 * @since 2.0
	 */
	public function add_theme_option_fields( $fields ) {
		
		foreach( $this->font_sets as $id => $args ) {
		
			$fields[ 'fonts-' . $id ] = array (
				'setting'		=> 'ar2_theme_options[fonts][' . $id . ']',
				'type'			=> 'dropdown',
				'title'			=> $args[ 'label' ],
				'section'		=> 'ar2_fonts',
				'options'		=> $this->list_fonts( $id ),
			);
			
		}
		
		foreach( $this->color_sets as $id => $args ) {
		
			$fields[ 'colors-' . $id ] = array (
				'setting'		=> 'ar2_theme_options[colors][' . $id . ']',
				'type'			=> 'color-switcher',
				'title'			=> $args[ 'label' ],
				'section'		=> 'ar2_colors',
				'options'		=> $this->list_colors( $id ),
			);
			
		}
		
		return $fields;
		
	}
	
	/**
	 * Loads the default stylesheet at <head>.
	 * Uses wp_enqueue_style() function so that it could be minified.
	 * @since 1.6
	 */
	public function load_styles() {

		if ( !AR2_ALLOW_CUSTOM_STYLES || ( 
			!ar2_get_theme_option( 'style' ) || 
			ar2_get_theme_option( 'style' ) == '_default' || 
			!file_exists( get_template_directory() . '/css/styles/' . ar2_get_theme_option( 'style' ) . '.css' )
		) ) {
			wp_enqueue_style( 'ar2', get_stylesheet_uri(), false, '2011-12-05', 'screen' );
		} else {
			wp_enqueue_style( 'ar2', get_template_directory_uri() . '/css/styles/' . ar2_get_theme_option( 'style' ) . '.css', false, '2012-08-07', 'screen' );
		}
		
		wp_enqueue_style( 'ar2-user', get_template_directory_uri() . '/user.css', false, '2011-12-05', 'screen' ); 
		
		// custom fonts
		foreach ( $this->font_sets as $id => $args ) {
			$font_id = ar2_get_theme_option( 'fonts[' . $id . ']' );
			
			if ( isset( $this->fonts[ $font_id ] ) && $this->fonts[ $font_id ][ 'web_font' ] ) 
				wp_enqueue_style( 'ar2-font-' . $id, $this->fonts[ $font_id ][ 'link' ], false, null, 'all' );
		
		}
		
		// load other custom styles
		do_action( 'ar2_load_styles' );
		
	}

	/**
	 * Removes any unwanted styles.
	 * @since 1.6
	 */
	public function remove_styles() {

		wp_deregister_style( 'wp-pagenavi' );

	}
	
	/**
	 * Overrides certain styles from the theme.
	 * @since 1.6
	 */
	public function override_styles() {
	
		?>
		<!-- Generated by AR2 Theme -->
		<style type="text/css">
		<?php 
		foreach ( $this->font_sets as $id => $args ) {
		
			$font_id = ar2_get_theme_option( 'fonts[' . $id . ']' );
			
			if ( isset( $this->fonts[ $font_id ] ) && $font_id != 'default' )
				echo $this->font_sets[ $id ][ 'selectors' ] . ' { font-family: "' .  $this->fonts[ $font_id ][ 'family' ] . '", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif; }' . "\n";
		
		}
		
		foreach ( $this->color_sets as $id => $args ) {

			$color = ar2_get_theme_option( 'colors[' . $id . ']' );
			
			if ( isset( $color ) && $color != 'default' ) {
				if ( isset( $args[ 'bg_selectors' ] ) )
					echo $args[ 'bg_selectors' ] . ' { background-color: ' .  $color . '; }' . "\n";
				
				if ( isset( $args[ 'text_selectors' ] ) )	
					echo $args[ 'text_selectors' ] . ' { color: ' .  $color . '; }' . "\n";
				
				if ( isset( $args[ 'border_selectors' ] ) )
					echo $args[ 'border_selectors' ] . ' { border-color: ' .  $color . '; }' . "\n";
			}
			
		}
		
		// override other styles
		do_action( 'ar2_custom_styles' ); 
		?>	
		</style>
		<?php
	
	}
	
	/**
	 * Registers settings, sections and fields to the Theme Customizer.
	 * @since 2.0
	 */
	public function customize_register( $wp_customize ) {
		
		require_once 'classes/customize/color-switcher-control.php';
		
		// Layouts
		$wp_customize->add_section( 'ar2_layout', array (
			'title'		=> __( 'Layout', 'ar2' ),
			'priority'	=> 100,
		) );
		
		$wp_customize->add_setting( 'ar2_theme_options[layout]', array (
			'default'	=> 'twocol-r',
			'type'		=> 'option',
			'transport'	=> 'postMessage',
		) );
		
		$wp_customize->add_control( 'ar2_layout', array ( 
			'settings'	=> 'ar2_theme_options[layout]',
			'label'		=> __( 'No. of Columns', 'ar2' ),
			'type'		=> 'select',
			'section'	=> 'ar2_layout',
			'choices'	=> $this->get_layouts(),
		) );
		
		// Fonts
		$wp_customize->add_section( 'ar2_fonts', array (
			'title'		=> __( 'Fonts', 'ar2' ),
			'priority'	=> 100,
		) );
		
		foreach( $this->font_sets as $id => $args ) :
		
		$wp_customize->add_setting( 'ar2_theme_options[fonts][' . $id . ']', array ( 
			'default'	=> 'default',
			'type'		=> 'option',
			'transport'	=> 'postMessage',
		) );
		
		$wp_customize->add_control( 'ar2_fonts_' . $id, array ( 
			'settings'	=> 'ar2_theme_options[fonts][' . $id . ']',
			'label'		=> $args[ 'label' ],
			'type'		=> 'select',
			'section'	=> 'ar2_fonts',
			'choices'	=> $this->list_fonts( $id ),
		) );
			
		endforeach;
		
		// Colors
		foreach( $this->color_sets as $id => $args ) :
		
		$wp_customize->add_setting( 'ar2_theme_options[colors][' . $id . ']', array ( 
			'default'	=> 'default',
			'type'		=> 'option',
			'transport'	=> 'postMessage',
		) );
		
		$wp_customize->add_control( new AR2_Customize_Color_Switcher_Control( $wp_customize, 'ar2_colors_' . $id, array (
			'settings'	=> 'ar2_theme_options[colors][' . $id . ']',
			'label'		=> $args[ 'label' ],
			'type'		=> 'select',
			'section'	=> 'colors',
			'choices'	=> $this->list_colors( $id ),
		) ) );
		
		endforeach;
		
		if ( $wp_customize->is_preview() && !is_admin() )
			add_action( 'wp_footer', array( $this, 'do_customize_preview_js' ), 21 );			

	}
	
	/**
	 * Handles all Javascript for Customize preview.
	 * @since 2.0
	 */
	public function do_customize_preview_js() {
	
		?>
		<script type="text/javascript">
		/* <![CDATA[ */
		( function( $ ) {
			
			var fonts = <?php echo json_encode( $this->fonts ) ?>;
		
			wp.customize( 'ar2_theme_options[layout]', function( value ) {
				value.bind( function( to ) {
					$( 'body' ).removeClass( '<?php echo implode( array_keys( $this->get_layouts() ), ' ' ) ?>' );
					$( 'body' ).addClass( to );
				} );
			} );
		
			<?php foreach ( $this->font_sets as $id => $args ) : ?>
			wp.customize( '<?php echo 'ar2_theme_options[fonts][' . $id . ']' ?>', function( value ) {
				value.bind( function( to ) {

					if ( fonts[ to ][ 'web_font' ] )
						$( 'head' ).append( '<link href="' + fonts[ to ][ 'link' ] + '" rel="stylesheet" type="text/css">' );
					
					$( '<?php echo $args[ 'selectors' ] ?>' ).css( 'font-family', fonts[ to ][ 'family' ] + ', "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif' );
				
				} );
			} );
			<?php endforeach ?>
			
			<?php foreach ( $this->color_sets as $id => $args ) : ?>
			wp.customize( '<?php echo 'ar2_theme_options[colors][' . $id . ']' ?>', function( value ) {
				value.bind( function( to ) {

					if ( to == 'default' ) {
						<?php if ( isset( $args[ 'bg_selectors' ] ) ) : ?>
							$( '<?php echo $args[ 'bg_selectors' ] ?>' ).css( 'background-color', '#86C140' );
						<?php endif ?>
						<?php if ( isset( $args[ 'text_selectors' ] ) ) : ?>
							$( '<?php echo $args[ 'text_selectors' ] ?>' ).css( 'color', '#86C140' );
						<?php endif ?>
						<?php if ( isset( $args[ 'border_selectors' ] ) ) : ?>
							$( '<?php echo $args[ 'border_selectors' ] ?>' ).css( 'border-color', '#86C140' );
						<?php endif ?>
					} else {
						<?php if ( isset( $args[ 'bg_selectors' ] ) ) : ?>
							$( '<?php echo $args[ 'bg_selectors' ] ?>' ).css( 'background-color', to );
						<?php endif ?>
						<?php if ( isset( $args[ 'text_selectors' ] ) ) : ?>
							$( '<?php echo $args[ 'text_selectors' ] ?>' ).css( 'color', to );
						<?php endif ?>
						<?php if ( isset( $args[ 'border_selectors' ] ) ) : ?>
							$( '<?php echo $args[ 'border_selectors' ] ?>' ).css( 'border-color', to );
						<?php endif ?>
					}
				
				} );
			} );
			<?php endforeach ?>
			
		} )( jQuery )
		/* ]]> */
		</script>
		<?php
	
	}
	
	/**
	 * Validates user input when options have been saved.
	 * @since 2.0
	 */
	public function validate( $output, $input, $defaults ) {
	
		if ( in_array( $input[ 'layout' ], array_keys( $this->get_layouts() ) ) ) $output[ 'layout' ] = $input[ 'layout' ];
		
		foreach ( $this->font_sets as $id => $args ) :
		
		if ( isset( $this->fonts[ $input[ 'fonts' ][ $id ] ] ) )
		$output[ 'fonts' ][ $id ] = $input[ 'fonts' ][ $id ];
		
		endforeach;
		
		foreach( $this->color_sets as $id => $color ) :
		$output[ 'colors' ][ $id ] = sanitize_hex_color( $input[ 'colors' ][ $id ] );
		endforeach;
	
		return $output;
		
	}
	
} 

global $ar2_styles;
$ar2_styles = new AR2_Styles();
 
/**
 * @todo
 * @since 1.6
 */
function is_valid_css_file( $file ) {

	return ( bool )( !preg_match( '/^\.+$/', $file ) && preg_match( '/^[A-Za-z][A-Za-z0-9\-]*.css$/', $file ) );
	
}

/**
 * @todo
 * @since 1.6
 */
function ar2_add_custom_logo() {

	$ar2_logo_id = ar2_get_theme_option( 'logo' );
	if ( $ar2_logo_id != 0 ) {
		$ar2_logo = wp_get_attachment_image_src( $ar2_logo_id, 'full' );

		echo '.blog-name a { background: url(' . $ar2_logo[0] . ') no-repeat; text-indent: -9000px; width: ' . $ar2_logo[1] . 'px; height: ' . $ar2_logo[2] . 'px; display: block; }' . "\n";
	}
	
}

/**
 * Custom background header callback. Based on:
 * http://devpress.com/blog/custom-background-fix-for-theme-developers/
 * @since 1.6
 */
function ar2_custom_bg_header_callback() {

	$image = get_background_image();
	
	if ( !empty( $image ) ) {
		_custom_background_cb();
		return;
	}
	
	$color = get_background_color();
	
	if ( empty( $color ) )
		return;
	
	$style = "background: #{$color};";
	?>
	<style type="text/css">
		body { 
			<?php echo trim( $style ); ?> 
		}
		#wrapper {
			box-shadow: none;
		}
	</style>
	<?php
	
}

/**
 * Styles the header image and text displayed on the blog.
 * @since 2.0
 */
function ar2_header_style() {

	$image = get_header_image();
	$text_color = get_header_textcolor();
	?>
	<style type="text/css">
	<?php if ( $image ) : ?>
	<?php if ( 'blank' == $text_color ) : ?>
	.logo {
		float: none;
		padding: 0;
		text-indent: -9000px;
	}
	.blog-name a:link, .blog-name a:visited {
		display: block;
		width: 100%;
		height: 120px;
		background: url( <?php echo $image ?> ) no-repeat;
	}
	.blog-description {
		display: none;
	}
	<?php else : ?>
	#branding {
		height: <?php echo get_custom_header()->height; ?>px;
		background: url( <?php echo $image ?> ) no-repeat;
	}
	.blog-name a:link, .blog-name a:visited, .blog-description {
		color: #<?php echo $text_color ?>;
	}
	<?php endif ?>
	<?php endif ?>
	</style>
	<?php

}

/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 * @since 2.0
 */
function ar2_admin_header_style() {
	
	$image = get_header_image();
	$text_color = get_header_textcolor();
	?>
	<style type="text/css">
	#headimg {
		width: 960px;
		margin: 0 auto;
		
		<?php if ( $image ) : ?>
		background: url( <?php echo $image ?> ) no-repeat;
		height: <?php echo get_custom_header()->height; ?>px;
		<?php else : ?>
		background: #FFF;
		<?php endif ?>
		max-height: 120px;
	}
	.logo {
		margin: 4% 1.3em;
		font-weight: 700;
		text-transform: uppercase;
		<?php if ( 'blank' == $text_color ) : ?>
		text-indent: -9000px;
		<?php endif ?>
	}
	.blog-name {
		margin: 0 0 0.2em;
		line-height: 1em;
		display: block;
		font-size: 190%;
	}
	
	.blog-name a:link, .blog-name a:visited {
		color: #<?php echo $text_color ?>;
		text-decoration: none;
	}
	.blog-description {
		line-height: 1em;
		display: block;
		margin: 0;
		padding: 0 0 0;
		font-size: 90%;
		color: #<?php echo $text_color ?>;
	}
	
	.clearfix:before, .clearfix:after {
		content: '';
		display: table;
	}
	.clearfix:after {
		clear: both;
	}
	.clearfix { 
		zoom: 1; /* for IE6/IE7 (trigger hasLayout) */
	} 
	</style>
	<?php

}

/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 * @since 2.0
 */
function ar2_admin_header_image() {
	
	$color = get_header_textcolor();
	if ( $color && $color != 'blank' )
			$style = ' style="color:#' . $color . '"';
		else
			$style = ' style="display:none"';
	?>
	<div id="headimg" role="banner" class="clearfix">
		<hgroup class="logo displaying-header-text">
			<h1 class="blog-name"><a id="name" onclick="return false;"<?php echo $style ?> href="<?php echo home_url() ?>"><?php bloginfo(' name' ); ?></a></h1>
			<div id="desc" class="blog-description"<?php echo $style ?>><?php bloginfo('description'); ?></div>
		</hgroup>
	</div><!-- #header -->
	<?php

}

/* End of file styles.php */
/* Location: ./library/styles.php */
