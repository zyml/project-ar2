<?php
/**
 * AR2 PostViews Class.
 * Adapted from WordPress Customize subpackage.
 *
 * @package AR2
 * @subpackage PostViews
 * @since 2.0
 */

final class AR2_PostViews {

	protected $zones = array();
	protected $sections = array();
	protected $display_types = array();
	
	/**
	 * Constructor.
	 * @since 2.0
	 */
	public function __construct() {
	
		require_once 'classes/postviews-section.php';
		require_once 'classes/postviews-zone.php';
		require_once 'classes/postviews-slideshow-section.php';
		
		add_action( 'wp_loaded', array( $this, 'wp_loaded' ), 5 );
		
		add_action( 'ar2_postviews_register', array( $this, 'register_zones' ) );
		add_action( 'ar2_postviews_register', array( $this, 'register_sections' ) );
		
		add_action( 'customize_register', array( $this, 'customize_register' ) );
		
	}
	
	/**
	 * Registers zones and sections when WordPress is loaded.
	 * @since 2.0
	 */
	public function wp_loaded() {
	
		do_action( 'ar2_postviews_register', $this );
		
	}
	
	/**
	 * Adds a new zone.
	 * @since 2.0
	 */
	public function add_zone( $id, $args = array() ) {
	
		if ( is_a( $id, 'AR2_PostViews_Zone' ) )
			$zone = $id;
		else
			$zone = new AR2_PostViews_Zone( $this, $id, $args );
		
		$this->zones[ $zone->id ] = $zone;
		
	}
	
	/**
	 * Retrieves an existing zone as an AR2_PostViews_Zone object.
	 * @since 2.0
	 */
	public function get_zone( $id ) {
	
		if ( isset( $this->zones[ $id ] ) )
			return $this->zones[ $id ];
			
	}
	
	/**
	 * Renders an existing zone in HTML.
	 * @since 2.0
	 */
	public function render_zone( $id ) {
		if ( is_a ( $id, 'AR2_PostViews_Section' ) ) {
		
			$id->render();
		} else {
			if ( isset( $this->zones[ $id ] ) )
				$this->zones[ $id ]->render();		
		}
		
	}
	
	/**
	 * Removes an existing zone.
	 * @since 2.0
	 */
	public function remove_zone( $id ) {
	
		unset( $this->zones[ $id ] );
		
	}
	
	/**
	 * Adds a new post section.
	 * @since 2.0
	 */
	public function add_section( $id, $zone = '_void', $args = array() ) {
	
		if ( is_a( $id, 'AR2_PostViews_Section' ) )
			$section = $id;
		else
			$section = new AR2_PostViews_Section( $this, $id, $zone, $args );
		
		$this->sections[ $section->id ] = $section;
		$this->get_zone( $zone )->sections[] = &$this->sections[ $section->id ];
		
	}
	
	/**
	 * Retrieves an existing post section as an AR2_PostViews_Section object.
	 * @since 2.0
	 */
	public function get_section( $id ) {
	
		if ( isset( $this->sections[ $id ] ) )
			return $this->sections[ $id ];
			
	}
	
	/**
	 * Retrives existing post sections from a specified zone.
	 * @since 2.0
	 */
	public function get_sections_from_zone( $zone_id ) {
	
		$result = array();
		
		foreach ( $this->sections as $id => $obj ) {
			if ( $obj->zone->id == $zone_id )
				$result[ $id ] = $obj;
		}
		
		// Sort the sections based on priority.
		uasort( $result, array( $this, '_cmp_priority' ) );
		
		return $result;
		
	}
	
	/**
	 * Removes an existing post section.
	 * @since 2.0
	 */
	public function remove_section( $id ) {
	
		unset( $this->sections[ $id ] );
		
	}
	
	/**
	 * Renders an existing post section in HTML.
	 * @since 2.0
	 */
	public function render_section( $id ) {
		
		if ( is_a( $id, 'AR2_PostViews_Section' ) )
			$id->render();
		else if ( isset( $this->sections[ $id ] ) )
			$this->sections[ $id ]->render();
			
	}
	
	
	/**
	 * Registers a display type as an option for users.
	 * @since 2.0
	 */
	public function register_display_type( $id, $label, $classname ) {
		
		if ( is_a( $classname, 'AR2_PostViews_Section' ) )
			$classname = get_class( $classname );
		
		$this->display_types[ $id ] = array (
			'label'		=> $label,
			'classname'	=> $classname,	
		);
		
	}
	
	
	/**
	 * Unregisters a display type as an option.
	 * @since 2.0
	 */
	public function unregister_display_type( $id ) {
		
		unset( $this->display_types[ $id ] );
		
	}
	
	/**
	 * Retrieves the classname asscociated with a display type.
	 * @since 2.0
	 */
	public function get_display_type_classname( $id ) {
		
		if ( isset( $this->display_types[ $id ] ) )
			return $this->display_types[ $id ][ 'classname' ];
		else
			return false;
		
	}
	
	
	/**
	 * Lists all registered display types as an array.
	 * @since 2.0
	 */
	public function list_display_types( $type = 'names' ) {
		
		if ( $type == 'names' ) {
		$display_types = array();
			foreach( $this->display_types as $id => $args )
				$display_types[ $id ] = $args[ 'label' ];
			return $display_types;	
		} else {
			return $this->display_types;
		}
		
	}
	
	/**
	 * Helper function to compare two objects by priority.
	 * Taken from WordPress' WP_Customize class.
	 *
	 * @since 3.4.0
	 *
	 * @param object $a Object A.
	 * @param object $b Object B.
	 */
	private function _cmp_priority( $a, $b ) {
	
		$ap = $a->priority;
		$bp = $b->priority;

		if ( $ap == $bp )
			return 0;
		return ( $ap < $bp ) ? 1 : -1;
		
	}
	
	/**
	 * Registers default post sections.
	 * @since 2.0
	 */
	public function register_sections() {
		

		/*$this->add_section( 'slideshow', 'home', array (
			'label'		=> __( 'Slideshow', 'ar2' ),
			'title'		=> __( 'Slideshow', 'ar2' ),
			'type'		=> 'slideshow',
			'count'		=> 3,
			'priority'	=> 5,
			'display_types' => array( 'slideshow' ),
			'enabled'	=> true,
		) );*/
		$this->add_section( new AR2_PostViews_Slideshow_Section( $this, 'slideshow', 'home', array (
			'label'		=> __( 'Slideshow', 'ar2' ),
			'title'		=> __( 'Slideshow', 'ar2' ),
			'type'		=> 'slideshow',
			'count'		=> 3,
			'priority'	=> 5,
			'display_types' => array( 'slideshow' ),
			'enabled'	=> true,
		) ) );
		
		$this->add_section( 'featured-posts-1', 'home', array (
			'label'		=> __( 'Featured Posts #1', 'ar2' ),
			'title'		=> __( 'Featured Posts', 'ar2' ),
			'type'		=> 'node',
			'count'		=> 3,
			'priority'	=> 4,
			'enabled'	=> true,
		) );
		
		$this->add_section( 'featured-posts-2', 'home', array (
			'label'		=> __( 'Featured Posts #2', 'ar2' ),
			'title'		=> __( "Editors' Picks", 'ar2' ),
			'type'		=> 'quick',
			'count'		=> 3,
			'priority'	=> 3,
			'enabled'	=> true,
		) );
		
		$this->add_section( 'news-posts', 'home', array (
			'label'				=> __( 'News Posts', 'ar2' ),
			'title'				=> __( 'Latest News', 'ar2' ),
			'type'				=> 'line',
			'use_query_posts'	=> true,
			'count'				=> get_option( 'posts_per_page' ),
			'priority'			=> 2,
			'enabled'			=> true,
		) );
		
	}
	 
	/**
	 * Register default zones.
	 * @since 2.0
	 */
	public function register_zones() {
		
		$this->add_zone( '_void', array (
			'show_in_theme_options' => false,
			'show_in_customize'		=> false,
			'_builtin'				=> false,
			'persistent'			=> null,
		) );
		
		$this->add_zone( 'home', array (
			'label'					=> __( 'Home', 'ar2' ),
			'description'			=> __( 'Handles all content being displayed in the front page (excluding the slideshow).', 'ar2' ),
			'show_in_theme_options'	=> true,
			'show_in_customize'		=> true,
			'_builtin'				=> true,
		) );
		
	}
	
	/**
	 * Registers settings, sections and fields to the Theme Customizer.
	 * @since 2.0
	 */
	public function customize_register( $wp_customize ) {
	
		if ( $wp_customize->is_preview() && !is_admin() )
			add_action( 'wp_head', array( $this, 'init_customize_preview' ) );
	
	}
	
	/**
	 * Tells the theme to go into 'customize preview' mode, somewhat.
	 * @since 2.0
	 */
	public function init_customize_preview() {
	
		global $ar2_is_customize_preview;
		$ar2_is_customize_preview = true;
		
		wp_enqueue_script( 'ar2-customize-preview', get_template_directory_uri() . '/js/customize-preview.js', array( 'jquery' ), '2012-07-29' );
		wp_localize_script( 'ar2-customize-preview', '_ar2Customize', $this->localize_customize_preview_js() );
		
		// Workaround for IE users: force the <iframe> to reload so that it could load jQuery.
		// Credits: http://stackoverflow.com/questions/8389261/ie9-throws-exceptions-when-loading-scripts-in-iframe-why
		?>
		<!--[if IE]>
		<script type="text/javascript">
		/* <![CDATA[ */
		if ( typeof jQuery == 'undefined' )
			window.location.reload();
		/* ]]> */	
		</script>
		<![endif]-->
		<?php
		
	}
	
	public function localize_customize_preview_js() {
		
		$_defaults =  array ( 
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		);
		
		return apply_filters( 'ar2_customize_preview_localize_vars', $_defaults );
	
	}
	
}

/**
 * Helper function to render zones.
 * @since 2.0
 */
function ar2_render_zone( $id ) {

	global $ar2_postviews;
	$ar2_postviews->render_zone( $id );
	
}

/**
 * Helper function to render sections.
 * @since 2.0
 */
function ar2_render_section( $id ) {
	
	global $ar2_postviews;
	$ar2_postviews->render_section( $id );
	
}

/**
 * Mega-helper function to render posts, preferably archives.
 * @since 2.0
 */
function ar2_render_posts( $query = null, $args = array(), $show_nav = false ) {

	$_defaults = array (
		'type'				=> 'traditional',
		'count'				=> get_option( 'posts_per_page' ),
		'title'				=> null,
		'use_query_posts'	=> true,
		'enabled'			=> true,
		'persistent'		=> false,
	);
	
	$args = wp_parse_args( $args, $_defaults );

	$section = new AR2_PostViews_Section( null, 'archive-posts', null, $args );
	ar2_render_section( $section );

	if ( $show_nav && $section->query->max_num_pages > 1 )
		ar2_post_navigation();

}

global $ar2_postviews;
$ar2_postviews = new AR2_PostViews();
 
/* End of file ar2-postviews.php */
/* Location: ./library/classes/ar2-postviews.php */