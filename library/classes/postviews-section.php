<?php
/**
 * AR2 PostViews Section Class.
 * Adapted from WordPress Customize subpackage.
 *
 * @package AR2
 * @subpackage PostViews
 * @since 2.0
 */
 
class AR2_PostViews_Section {
	public $manager;
	
	public $settings 	= array();
	
	public $id;
	public $enabled		= true;
	public $priority	= 10;
	public $zone;

	public $query;
	
	/**
	 * Constructor.
	 * @since 2.0
	 */
	function __construct( $manager = null, $id, $zone = '_void', $args = array() ) {
		
		global $ar2_postviews;
		
		if ( null === $manager )
			$manager = $ar2_postviews;
		
		$this->manager = $manager;
		$this->id = $id;
		
		$this->zone = $this->manager->get_zone( $zone );
		
		$this->flush_settings( $args );
		
		// Register the default display types.
		$this->manager->register_display_type( 'node', __( 'Node Based', 'ar2' ), $this );
		$this->manager->register_display_type( 'quick', __( 'Quick Preview', 'ar2' ), $this );
		$this->manager->register_display_type( 'line', __( 'Per Line', 'ar2' ), $this );
		$this->manager->register_display_type( 'traditional', __( 'Traditional', 'ar2' ), $this );
		
		if ( isset( $this->zone->settings[ 'show_in_theme_options' ] ) && $this->zone->settings[ 'show_in_theme_options' ] )
			$this->init_section_theme_options();
			
		if ( isset( $this->zone->settings[ 'show_in_customize' ] ) && $this->zone->settings[ 'show_in_customize' ] )
			$this->init_section_theme_customize();
		
		return $this;
		
	}
	
	/**
	 * Prepares WP_Query based on settings. An upgrade from Arras' arras_prep_query().
	 * @since 2.0
	 */
	protected function prepare_query() {
	
		global $wp_query, $paged;
	
		// Convert Term setting into an array.
		if ( !is_array( $this->settings[ 'terms' ] ) )
			$this->settings[ 'terms' ] = array( $this->settings[ 'terms' ] );
		
		// Default settings for WP_Query
		$_query_args = array (
			'post_type'			=> 'post',
			'posts_per_page'	=> get_option( 'posts_per_page' ),
			'orderby'			=> 'date',
			'order'				=> 'DESC',
			'post_status'		=> 'publish',
		);
		
		if ( isset( $this->settings[ 'query_args' ] ) )
			$_query_args = wp_parse_args( $this->settings[ 'query_args' ], $_query_args );
		
		// Post Type
		$_query_args[ 'post_type' ] = $this->settings[ 'post_type' ];
		
		// Sticky Posts
		if ( isset( $this->zone ) && $this->zone->settings[ 'ignore_sticky' ] )
			$_query_args[ 'ignore_sticky_posts' ] = 1;
			
		
		if ( in_array( '-5', $this->settings[ 'terms' ] ) ) {
			$stickies = get_option( 'sticky_posts' );
			rsort( $stickies );
			
			if ( count( $stickies ) > 0 )
				$_query_args[ 'post__in' ] =  $stickies;
			
			$key = array_search( '-5', $this->settings[ 'terms' ] );
			unset( $this->settings[ 'terms' ][ $key ] );
		}
		
		// Taxonomies
		switch( $this->settings[ 'taxonomy' ] ) :
		
		case 'category' :
			if ( isset( $this->settings[ 'terms' ][ 0 ] ) && $this->settings[ 'terms' ][ 0 ] == null )
				unset ( $this->settings[ 'terms' ][ 0 ] );
			$_query_args[ 'category__in' ] = $this->settings[ 'terms' ];
			break;
			
		case 'post_tag' :
			$_query_args[ 'tag__in' ] = $this->settings[ 'terms' ];
			break;
		
		default :
			$_query_args[ 'tax_query' ][] = array (
				'taxonomy'	=> $this->settings[ 'taxonomy' ],
				'field'		=> 'id',
				'terms'		=> $this->settings[ 'terms' ],
				'operator'	=> 'AND',
			);	
			
		endswitch;

		// Post Duplicates
		if ( isset( $this->zone->settings[ 'hide_duplicates' ] ) && $this->zone->settings[ 'hide_duplicates' ] )
			$_query_args[ 'post__not_in' ] = array_unique( $this->zone->blacklist );
			
		// Attachment-specific
		if ( $_query_args[ 'post_type' ] == 'attachment' )
			$_query_args[ 'post_status' ] = 'inherit';
		
		// Post Count
		$_query_args[ 'posts_per_page' ] = $this->settings[ 'count' ];	

		// Done! Now let's create a WP_Query object.
		if ( !$this->settings[ '_preview' ] && $this->settings[ 'use_query_posts' ] ) {	
		
			$_query_args[ 'paged' ] = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
		
			query_posts( $_query_args );
			$this->query = &$wp_query;
			
		} else
			$this->query = new WP_Query( $_query_args );
		
		return $this->query;
		
	}
	
	/**
	 * Updates Post Section settings from the database.
	 * @since 2.0
	 */
	public function flush_settings( $override = array() ) {
		
		global $ar2_options;
		
		$_defaults = array (
			'enabled'			=> true,
			'label'				=> sprintf( __( 'Post Section ID: %s', 'ar2' ), $this->id ),
			'terms'				=> array(),
			'count'				=> get_option( 'posts_per_page' ),
			'taxonomy'			=> 'category',
			'post_type'			=> 'post',
			'use_query_posts'	=> false,
			'priority'			=> 10,
			'container'			=> true,
			'persistent'		=> array( 'enabled', 'terms', 'count', 'taxonomy', 'post_type', 'type', 'title' ),
			'display_types'		=> array( 'node', 'quick', 'line', 'traditional' ),
			'_preview'			=> false,
		);
		
		$args = wp_parse_args( $override, $_defaults );

		if ( is_array( $args[ 'persistent' ] ) && !is_array( $ar2_options ) ) 
			ar2_flush_theme_options();
		
		if ( is_array( $args[ 'persistent' ] ) && isset( $ar2_options[ 'sections' ][ $this->id ] ) )
			$this->settings = wp_parse_args( $ar2_options[ 'sections' ][ $this->id ], $args );
		else
			$this->settings = $args;
			
		$this->priority = &$this->settings[ 'priority' ];
		
		/*if ( is_array( $this->settings[ 'persistent' ] ) && !isset( $ar2_options[ 'sections' ][ $this->id ] ) ) {
		
			foreach( $this->settings[ 'persistent' ] as $id ) {
				if ( isset( $this->settings[ $id ] ) )
					$ar2_options[ 'sections' ][ $this->id ][ $id ] = $this->settings[ $id ];
			}
			
			update_option( 'ar2_theme_options', $ar2_options );
			
		}*/
	}
	
	/**
	 * Retrieves the field ID for a specific setting.
	 * @since 2.0
	 */
	public function get_field_name( $name ) {
	
		return apply_filters( $this->id . '_field_name', 'ar2_theme_options[sections][' . $this->id . '][' . $name . ']', $name );
	
	}
	
	/**
	 * Renders the zone in HTML.
	 * @since 2.0
	 */
	public function render() {
	
		global $wp_query, $post, $ar2_is_customize_preview;
		
		if ( $ar2_is_customize_preview ) $this->settings[ '_preview' ] = true;

		if ( !$this->settings[ '_preview' ] && !$this->settings[ 'enabled' ] ) return false;
		
		// For developers to place code before the post section.
		do_action( 'ar2_before_section-' . $this->id );
		
		if ( !is_a( $this->query, 'WP_Query' ) )
			$this->prepare_query();
		
		if ( $this->settings[ 'container' ] ) {
			echo '<div id="section-' . $this->id . '" class="clearfix"';
		
			if ( !$this->settings[ 'enabled' ] )
				echo ' style="display: none"';
			
			echo '>';
		}
		
		if ( isset( $this->settings[ 'title' ] ) )
			echo '<h4 class="home-title">' . $this->settings[ 'title' ] . '</h4>';
		
		if ( $this->settings[ 'type' ] == 'line' || $this->settings[ 'type' ] == 'quick' )
			echo '<ul class="hfeed posts-' . $this->settings[ 'type' ] . '">';
		else
			echo '<div class="hfeed posts-' . $this->settings[ 'type' ] . '">';
		
		if ( $this->settings[ 'type' ] == 'node' ) {

			for ( $i = 0; $this->query->have_posts(); $i++ ) :
			
				if ( $i % 3 == 0 ) echo '<div class="clearfix">';
			
				$this->query->the_post();
			
				// hack for plugin authors who love to use $post = $wp_query->post
				$wp_query->post = $this->query->post;
				setup_postdata( $post );
			
				get_template_part( 'section', $this->settings[ 'type' ] );
				
				if ( $i % 3 == 2 ) echo '</div>';
				
				// Update the post blacklist.
				$this->zone->blacklist[] = $post->ID;
				
			endfor;
			
			if ( $i % 3 != 0 ) echo '</div>';
			
		} else {		
		
			while ( $this->query->have_posts() ) :
			
				$this->query->the_post();
			
				// hack for plugin authors who love to use $post = $wp_query->post
				$wp_query->post = $this->query->post;
				setup_postdata( $post );
			
				get_template_part( 'section', $this->settings[ 'type' ] );
				
				// Update the post blacklist.
				$this->zone->blacklist[] = $post->ID;			
				
			endwhile;
			
		}
		
		if ( $this->settings[ 'type' ] == 'line' || $this->settings[ 'type' ] == 'quick' )
			echo '</ul><!-- .posts-' . $this->settings[ 'type' ] . '-->';
		else
			echo '</div><!-- .posts-' . $this->settings[ 'type' ] . '-->';
		
		/*
		if ( $this->settings[ 'use_query_posts' ] && $wp_query->max_num_pages > 1 )
			ar2_post_navigation();
		*/
		
		if ( $this->settings[ 'container' ] )
			echo '</div><!-- #section-' . $this->id . '-->';
			
		// For developers to place code after the post section.
		do_action( 'ar2_after_section-' . $this->id );
			
	}
	
	/**
	 * Initialise theme options functionality.
	 * @since 2.0
	 */
	public function init_section_theme_options() {

		add_filter( 'ar2_default_theme_options', array( $this, 'add_default_theme_option' ) );
		add_filter( 'ar2_theme_customize_validate', array( $this, 'validate' ), 15, 3 );
		
		add_filter( 'ar2_theme_options_sections', array( $this, 'add_theme_option_section' ) );
		add_filter( 'ar2_theme_options_fields', array( $this, 'add_theme_option_fields' ) );
		
	}
	
	/**
	 * Initialise theme options functionality.
	 * @since 2.0
	 */
	public function init_section_theme_customize() {

		add_action( 'customize_register', array( $this, 'customize_register' ) );
		add_filter( 'ar2_theme_customize_validate', array( $this, 'validate' ), 15, 3 );
		
		if( is_admin() )
			add_action( 'wp_ajax_ar2_customize_preview_section', array( $this, 'ajax_customize_preview_section' ) );
			
	}
	
	/**
	 * Filter for adding default theme options for the zone.
	 * @since 2.0
	 */
	public function add_default_theme_option( $options ) {
	
		if ( !isset( $options[ 'sections' ] ) )
			$options[ 'sections' ] = array();
		
		$options[ 'sections' ][ $this->id ] = array (
			'enabled'	=> $this->settings[ 'enabled' ],
			'title'		=> $this->settings[ 'title' ],
			'type'		=> $this->settings[ 'type' ],
			'terms'		=> $this->settings[ 'terms' ],
			'post_type' => $this->settings[ 'post_type' ],
			'taxonomy'	=> $this->settings[ 'taxonomy' ],
			'count'		=> $this->settings[ 'count' ],
		);
		
		return $options;
		
	}
	
	/**
	 * Filter for adding a section in the zone's theme option tab.
	 * @since 2.0
	 */
	public function add_theme_option_section( $sections ) {
		
		$sections[ 'ar2_zone_' . $this->zone->id . '_' . $this->id ] = array (
			'name' => $this->settings[ 'label' ],
			'page' => 'ar2_zone-' . $this->zone->id,
		);
		return $sections;
		
	}
	
	/**
	 * Generate a list of available display types in an array.
	 * @since 2.0
	 */
	public function list_display_types() {
		
		$_display_types = $this->manager->list_display_types();
		$list = array();
		
		foreach( $_display_types as $id => $name ) {
			if ( in_array( $id, $this->settings[ 'display_types' ] ) )
				$list[ $id ] = $name;
		}
		
		return $list;
		
	}
	
	/**
	 * Filter for adding new theme option fields for the zone.
	 * @since 2.0
	 */
	public function add_theme_option_fields( $fields ) {
		
		$fields[ 'section-' . $this->id . '-enabled' ] = array (
			'setting'		=> $this->get_field_name( 'enabled' ),
			'type'			=> 'switch',
			'title'			=> sprintf( __( 'Enable %s', 'ar2' ), $this->settings[ 'label' ] ),
			'section'		=> 'ar2_zone_' . $this->zone->id . '_' . $this->id,
			//'description'	=> sprintf( __( 'Check this to allow %s to appear in this zone.', 'ar2' ), $this->settings[ 'label' ] ),
		);
		
		$fields[ 'section-' . $this->id . '-type' ] = array (
			'setting'		=> $this->get_field_name( 'type' ),
			'type'			=> 'dropdown',
			'title'			=> __( 'Type', 'ar2' ),
			'section'		=> 'ar2_zone_' . $this->zone->id . '_' . $this->id,
			'options'		=> $this->list_display_types(),
		);
		
		$fields[ 'section-' . $this->id . '-title' ] = array (
			'setting'		=> $this->get_field_name( 'title' ),
			'type'			=> 'input',
			'title'			=> __( 'Title', 'ar2' ),
			'section'		=> 'ar2_zone_' . $this->zone->id . '_' . $this->id,
		);
		
		$fields[ 'section-' . $this->id . '-post_type' ] = array (
			'setting'		=> $this->get_field_name( 'post_type' ),
			'type'			=> 'posttype-dropdown',
			'title'			=> __( 'Post Type', 'ar2' ),
			'section'		=> 'ar2_zone_' . $this->zone->id . '_' . $this->id,
		);
		
		$fields[ 'section-' . $this->id . '-taxonomy' ] = array (
			'setting'		=> $this->get_field_name( 'taxonomy' ),
			'type'			=> 'taxonomies-dropdown',
			'title'			=> __( 'Taxonomy', 'ar2' ),
			'section'		=> 'ar2_zone_' . $this->zone->id . '_' . $this->id,
		);
		
		$fields[ 'section-' . $this->id . '-terms' ] = array (
			'setting'		=> $this->get_field_name( 'terms' ),
			'type'			=> 'cat-dropdown',
			'title'			=> __( 'Terms', 'ar2' ),
			'section'		=> 'ar2_zone_' . $this->zone->id . '_' . $this->id,
		);
		
		$fields[ 'section-' . $this->id . '-count' ] = array (
			'setting'		=> $this->get_field_name( 'count' ),
			'type'			=> 'dropdown',
			'title'			=> __( 'Post Count', 'ar2' ),
			'section'		=> 'ar2_zone_' . $this->zone->id . '_' . $this->id,
			'options'		=> apply_filters( 'ar2_post_count_options', array ( 1 => 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12 ) ),
		);
		
		return $fields;
		
	}
	
	/**
	 * Registers settings, sections and fields to the Theme Customizer.
	 * @since 2.0
	 */
	public function customize_register( $wp_customize ) {
		
		require_once 'customize/terms-control.php';
		
		if ( $wp_customize->is_theme_active() ) {

			$wp_customize->add_section( 'ar2_section_' . $this->id, array (
				'title'		=> $this->settings[ 'label' ],
				'priority'	=> 210 + ( 10 - $this->priority ),
			) );
			
			// Settings
			$wp_customize->add_setting( $this->get_field_name( 'enabled' ), array ( 
				'default'	=> $this->settings[ 'enabled' ],
				'type'		=> 'option',
				'transport'	=> 'postMessage',
			) );
			$wp_customize->add_setting( $this->get_field_name( 'title' ), array ( 
				'default'	=> $this->settings[ 'title' ],
				'type'		=> 'option',
				'transport'	=> 'postMessage',
			) );
			$wp_customize->add_setting( $this->get_field_name( 'type' ), array ( 
				'default'	=> $this->settings[ 'type' ],
				'type'		=> 'option',
				'transport'	=> 'postMessage',
			) );
			$wp_customize->add_setting( $this->get_field_name( 'terms' ), array ( 
				'default'	=> $this->settings[ 'terms' ],
				'type'		=> 'option',
				'transport'	=> 'postMessage',
			) );
			$wp_customize->add_setting( $this->get_field_name( 'count' ), array ( 
				'default'	=> $this->settings[ 'count' ],
				'type'		=> 'option',
				'transport'	=> 'postMessage',
			) );
			
			// Controls
			$wp_customize->add_control( 'ar2_section-' . $this->id . '-enabled', array ( 
				'settings'	=> $this->get_field_name( 'enabled' ),
				'label'		=> sprintf( __( 'Enable %s', 'ar2' ), $this->settings[ 'label' ] ),
				'type'		=> 'checkbox',
				'section'	=> 'ar2_section_' . $this->id,
			) );
			$wp_customize->add_control( 'ar2_section-' . $this->id . '-title', array ( 
				'settings'	=> $this->get_field_name( 'title' ),
				'label'		=> __( 'Title', 'ar2' ),
				'type'		=> 'text',
				'section'	=> 'ar2_section_' . $this->id,
			) );
			$wp_customize->add_control( 'ar2_section-' . $this->id . '-type', array ( 
				'settings'	=> $this->get_field_name( 'type' ),
				'label'		=> __( 'Type', 'ar2' ),
				'type'		=> 'select',
				'section'	=> 'ar2_section_' . $this->id,
				'choices'	=> $this->list_display_types(),
			) );
			$wp_customize->add_control( new AR2_Customize_Terms_Control( $wp_customize, 'ar2_section-' . $this->id . '-terms', array (
				'settings'		=> $this->get_field_name( 'terms' ),
				'label'			=> __( 'Terms', 'ar2' ),
				'section'		=> 'ar2_section_' . $this->id,
				'post_section'	=> $this->id,
				'taxonomy'		=> $this->settings[ 'taxonomy' ],
			) ) );
			$wp_customize->add_control( 'ar2_section-' . $this->id . '-count', array ( 
				'settings'	=> $this->get_field_name( 'count' ),
				'label'		=> __( 'Post Count', 'ar2' ),
				'type'		=> 'select',
				'section'	=> 'ar2_section_' . $this->id,
				'choices'	=> apply_filters( 'ar2_post_count_options', array ( 1 => 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12 ) ),
			) );
			
			if ( $wp_customize->is_preview() && !is_admin() ) {
				add_action( 'wp_footer', array( $this, 'do_customize_preview_js' ), 30 );
				add_action( 'ar2_customize_preview_localize_vars', array( $this, 'customize_localize_vars' ) );
			}
			
		}
		
	}
	
	/**
	 * customize_localize_vars function.
	 * @since 2.0
	 */
	public function customize_localize_vars( $vars ) {
	
		$_persistent_arr = array();
		foreach( $this->settings[ 'persistent' ] as $id )
			$_persistent_arr[ $id ] = $this->settings[ $id ];
			
		$vars[ $this->js_friendly_id() . 'Settings' ] = $_persistent_arr;
		
		return $vars;
	
	}
	
	/**
	 * ajax_customize_preview_section function.
	 * @since 2.0
	 */
	public function ajax_customize_preview_section() {
	
		$_section_id = esc_attr( $_REQUEST[ 'section' ] );
		$_section = $this->manager->get_section( $_section_id );
		
		if ( !isset( $_section ) )
			return 0;
		
		$_input_settings = $_REQUEST[ 'settings' ];
		
		$_input_settings[ 'persistent' ] = null;
		$_input_settings[ 'container' ] = false;
		$_input_settings[ '_preview' ] = true;
		
		// Validate the JSON variables
		$_input_settings[ 'title' ] = esc_attr( $_input_settings[ 'title' ] );
		$_input_settings[ 'type' ] = esc_attr( $_input_settings[ 'type' ] );
		$_input_settings[ 'count' ] = absint( $_input_settings[ 'count' ] );
		$_input_settings[ 'enabled' ] = ( boolean ) $_input_settings[ 'enabled' ];
		
		if ( empty( $_input_settings[ 'terms' ] ) )
			$_input_settings[ 'terms' ] = array();
			
		$_temp_settings = wp_parse_args( $_input_settings, $_section->settings );
		
		$_classname = $this->manager->get_display_type_classname( $_input_settings[ 'type' ] );
		
		if ( $_classname )
		$this->manager->render_section( new $_classname( $this->manager, $_section->id, null, $_temp_settings ) );
		
		exit;
		
	}
	
	
	/**
	 * Converts dashes to camelCase for Javascript-friendly variable naming.
	 * @since 2.0
	 */
	public function js_friendly_id() {
		
		return preg_replace( "/\-(.)/e", "strtoupper('\\1')", $this->id );
		
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
			wp.customize( '<?php echo $this->get_field_name( 'enabled' ) ?>', function( value ) {
				value.bind( function( to ) {
					_ar2Customize.<?php echo $this->js_friendly_id() ?>Settings.enabled = to;
					$( '#section-<?php echo $this->id ?>' ).toggle( to );
				} );
			} );
			
			wp.customize( '<?php echo $this->get_field_name( 'title' ) ?>', function( value ) {
				value.bind( function( to ) {
					_ar2Customize.<?php echo $this->js_friendly_id() ?>Settings.title = to;
					$( '#section-<?php echo $this->id ?> .home-title' ).html( to );
				} );
			} );
			
			wp.customize( '<?php echo $this->get_field_name( 'count' ) ?>', function( value ) {
				value.bind( function( to ) {				
					_ar2Customize.<?php echo $this->js_friendly_id() ?>Settings.count = to;
					ar2Customize.refreshSection( '<?php echo $this->id ?>', _ar2Customize.<?php echo $this->js_friendly_id() ?>Settings );	
				} );
			} );
			
			wp.customize( '<?php echo $this->get_field_name( 'terms' ) ?>', function( value ) {
				value.bind( function( to ) {				
					_ar2Customize.<?php echo $this->js_friendly_id() ?>Settings.terms = to;
					ar2Customize.refreshSection( '<?php echo $this->id ?>', _ar2Customize.<?php echo $this->js_friendly_id() ?>Settings );	
				} );
			} );
			
			wp.customize( '<?php echo $this->get_field_name( 'type' ) ?>', function( value ) {
				value.bind( function( to ) {			
					_ar2Customize.<?php echo $this->js_friendly_id() ?>Settings.type = to;
					ar2Customize.refreshSection( '<?php echo $this->id ?>', _ar2Customize.<?php echo $this->js_friendly_id() ?>Settings );			
				} );
			} );
			
		} )( jQuery )
		/* ]]> */
		</script>
		<?php
	
	}
	
	/**
	 * Validates user input when section-specific options have been saved.
	 * @since 2.0
	 */
	public function validate( $output, $input, $defaults ) {
		
		if ( isset( $input[ 'opt_type' ] ) && $input[ 'opt_type' ] != 'zone-' . $this->zone->id )
			return $output;
		
		$output[ 'sections' ][ $this->id ][ 'enabled' ]	= ar2_theme_options_validate_checkbox( $input[ 'sections' ][ $this->id ][ 'enabled' ] );
		$output[ 'sections' ][ $this->id ][ 'title' ] = isset( $input[ 'sections' ][ $this->id ][ 'title' ] ) ? esc_attr( $input[ 'sections' ][ $this->id ][ 'title' ] ) : $defaults[ 'sections' ][ $this->id ][ 'title' ];
		$output[ 'sections' ][ $this->id ][ 'post_type' ] = ( isset( $input[ 'sections' ][ $this->id ][ 'post_type' ] ) && post_type_exists( $input[ 'sections' ][ $this->id ][ 'post_type' ] ) ? $input[ 'sections' ][ $this->id ][ 'post_type' ] : $defaults[ 'sections' ][ $this->id ][ 'post_type' ] );
		$output[ 'sections' ][ $this->id ][ 'taxonomy' ] = ( isset( $input[ 'sections' ][ $this->id ][ 'taxonomy' ] ) && taxonomy_exists( $input[ 'sections' ][ $this->id ][ 'taxonomy' ] ) ? $input[ 'sections' ][ $this->id ][ 'taxonomy' ] : $defaults[ 'sections' ][ $this->id ][ 'taxonomy' ] );
		$output[ 'sections' ][ $this->id ][ 'terms' ] = isset( $input[ 'sections' ][ $this->id ][ 'terms' ] ) ? ar2_theme_options_validate_terms_input( $input[ 'sections' ][ $this->id ][ 'terms' ] ) : $defaults[ 'sections' ][ $this->id ][ 'terms' ];
		$output[ 'sections' ][ $this->id ][ 'type' ] = ( isset( $input[ 'sections' ][ $this->id ][ 'type' ] ) && in_array( $input[ 'sections' ][ $this->id ][ 'type' ], array_keys( $this->list_display_types() ) ) ? $input[ 'sections' ][ $this->id ][ 'type' ] : $defaults[ 'sections' ][ $this->id ][ 'type' ] );
		$output[ 'sections' ][ $this->id ][ 'count' ] = ( isset( $input[ 'sections' ][ $this->id ][ 'count' ] ) && is_numeric( $input[ 'sections' ][ $this->id ][ 'count' ] ) ? absint( $input[ 'sections' ][ $this->id ][ 'count' ] ) : $defaults[ 'sections' ][ $this->id ][ 'count' ] );
		
		return $output;
		
	}
	
}

/* End of file ar2-postviews-section.php */
/* Location: ./library/classes/ar2-postviews-section.php */