<?php
/**
 * AR2 PostViews Zone Class.
 * Adapted from WordPress Customize subpackage.
 *
 * @package AR2
 * @subpackage PostViews
 * @since 2.0
 */
 
class AR2_PostViews_Zone {
	public $manager;
	public $id;
	
	public $blacklist = array();
	public $sections = array();
	
	public $settings = array();
	
	/**
	 * Constructor.
	 * @since 2.0
	 */
	function __construct( $manager, $id, $args = array() ) {
		global $wp_customize;
		
		$this->manager = $manager;
		$this->id = $id;

		$this->flush_settings( $args );

		if ( $this->settings[ 'show_in_theme_options' ] ) {
			add_filter( 'ar2_default_theme_options', array( $this, 'add_default_theme_option' ) );
			
			if ( $this->settings[ '_builtin' ] )
				add_filter( '_ar2_builtin_theme_option_tabs', array( $this, 'add_theme_option_tab' ) );
			else
				add_filter( 'ar2_theme_options_tabs', array( $this, 'add_theme_option_tab' ) );
				
			add_filter( 'ar2_theme_options_sections', array( $this, 'add_theme_option_section' ) );
			add_filter( 'ar2_theme_options_fields', array( $this, 'add_theme_option_fields' ) );
			
			$this->chain_init_section_theme_options();
		}
		
		if ( $this->settings[ 'show_in_customize' ] ) {
		
			// Disable for now.
			//add_action( 'customize_register', array( $this, 'customize_register' ) );	
			//add_filter( 'ar2_theme_options_validate', array( $this, 'validate' ), 10, 3 );
			
			$this->chain_init_section_customize_register();
		}
		
		return $this;
	}
	
	/**
	 * Updates settings from the database.
	 * @since 2.0
	 */
	public function flush_settings( $override = array() ) {
		
		global $ar2_options;
		
		$_defaults = array (
			'label'					=> sprintf( __( 'ID: %s', 'ar2' ), $this->id ),
			'description'			=> '',
			'priority'				=> 10,
			'show_in_theme_options'	=> false,
			'show_in_customize'		=> false,
			'_builtin'				=> false,
			'hide_duplicates'		=> false,
			'ignore_sticky'			=> false,
			'persistent'			=> array( 'hide_duplicates', 'ignore_sticky' ),
		);
		
		$args = wp_parse_args( $override, $_defaults );

		if ( is_array( $args[ 'persistent' ] ) && !is_array( $ar2_options ) ) 
			ar2_flush_theme_options();
		
		if ( is_array( $args[ 'persistent' ] ) && isset( $ar2_options[ 'zones' ][ $this->id ] ) )
			$this->settings = wp_parse_args( $ar2_options[ 'zones' ][ $this->id ], $args );
		else
			$this->settings = $args;
		
		if ( is_array( $this->settings[ 'persistent' ] ) ) {
		
			foreach( $this->settings[ 'persistent' ] as $id ) {
				if ( isset( $this->settings[ $id ] ) )
					$ar2_options[ 'zones' ][ $this->id ][ $id ] = $this->settings[ $id ];
			}
			
			update_option( 'ar2_theme_options', $ar2_options );
			
		}
		
	}
	
	/**
	 * Retrieves the field ID for a specific setting.
	 * @since 2.0
	 */
	public function get_field_name( $name ) {
	
		return apply_filters( $this->id . '_field_name', 'ar2_theme_options[zones][' . $this->id . '][' . $name . ']', $name );
	
	}

	
	/**
	 * Renders the zone in HTML.
	 * @since 2.0
	 */
	public function render() {
		
		$this->sections = $this->manager->get_sections_from_zone( $this->id );
		
		if ( empty( $this->sections ) )
			return false;
		
		echo '<div id="zone-' . $this->id . '">';
			
		foreach ( $this->sections as $section )
			$section->render();
			
		echo '</div><!-- #zone-' . $this->id . '-->';
			
	}
	
	/**
	 * Validates user input when zone-specific options have been saved.
	 * @since 2.0
	 */
	public function validate( $output, $input, $defaults ) {

		$output[ 'zones' ][ $this->id ][ 'hide_duplicates' ]	= ar2_theme_options_validate_checkbox( $input[ 'zones' ][ $this->id ][ 'hide_duplicates' ] );
		$output[ 'zones' ][ $this->id ][ 'ignore_sticky' ]		= ar2_theme_options_validate_checkbox( $input[ 'zones' ][ $this->id ][ 'ignore_sticky' ] );
		
		return $output;
		
	}
	
	/**
	 * Enable all theme option functionality for the zone's sections.
	 * @since 2.0
	 */
	protected function chain_init_section_theme_options() {
	
		$this->manager->get_sections_from_zone( $this->id );
		foreach ( $this->sections as $section )
			$section->init_section_theme_options();
		
	}
	
	/**
	 * Enable all customizer functionality for the zone's sections.
	 * @since 2.0
	 */
	protected function chain_init_section_customize_register() {
		
		$this->manager->get_sections_from_zone( $this->id );
		foreach ( $this->sections as $section )
			$section->init_section_theme_customize();
		
	}
	
	
	/**
	 * Registers settings, sections and fields to the Theme Customizer.
	 * @since 2.0
	 */
	public function customize_register( $wp_customize ) {

		// Post Options Section
		if ( $this->settings[ '_builtin' ] ) {
			$wp_customize->add_section( 'ar2_zone_' . $this->id, array (
				'title'		=> sprintf( __( '%s: Post Options', 'ar2' ), $this->settings[ 'label' ] ),
				'priority'	=> 200
			) );
		} else {
			$wp_customize->add_section( 'ar2_zone_' . $this->id, array (
				'title'		=> sprintf( __( 'Zone %s: Post Options', 'ar2' ), $this->label ),
				'priority'	=> 200
			) );	
		}
		
		// Settings
		$wp_customize->add_setting( $this->get_field_name( 'hide_duplicates' ), array ( 
			'default'	=> false,
			'type'		=> 'option',
		) );
		$wp_customize->add_setting( $this->get_field_name( 'ignore_sticky' ), array ( 
			'default'	=> true,
			'type'		=> 'option',
		) );
		
		// Controls
		$wp_customize->add_control( 'ar2_zone-' . $this->id . '-hide_duplicates', array ( 
			'settings'	=> $this->get_field_name( 'hide_duplicates' ),
			'label'		=> __( 'Hide Duplicate Posts', 'ar2' ),
			'type'		=> 'checkbox',
			'section'	=> 'ar2_zone_' . $this->id,
		) );
		$wp_customize->add_control( 'ar2_zone-' . $this->id . '-ignore_sticky', array ( 
			'settings'	=> $this->get_field_name( 'ignore_sticky' ),
			'label'		=> __( 'Ignore Sticky Posts', 'ar2' ),
			'type'		=> 'checkbox',
			'section'	=> 'ar2_zone_' . $this->id,
		) );
		
	}
	
	/**
	 * Filter for adding default theme options for the zone.
	 * @since 2.0
	 */
	public function add_default_theme_option( $options ) {
	
		if ( isset( $options[ 'zones' ] ) )
			$options[ 'zones' ] = array();
		
		$options[ 'zones' ][ $this->id ] = array (
			'hide_duplicates'	=> false,
			'ignore_sticky'		=> true,
		);
		
		return $options;
		
	}
	
	
	
	/**
	 * Filter for adding a new theme option tab for the zone.
	 * @since 2.0
	 */
	public function add_theme_option_tab( $tabs ) {
		
		if ( $this->settings [ '_builtin' ] )
			$tab_name = $this->settings[ 'label' ];
		else
			$tab_name = sprintf( __( '%s (Zone)', 'ar2' ), $this->settings[ 'label' ] );
	
		$tabs[ 'zone-' . $this->id ] = $tab_name;
		return $tabs;
		
	}
	
	/**
	 * Filter for adding a new theme option section for the zone.
	 * @since 2.0
	 */
	public function add_theme_option_section( $sections ) {
		
		$sections[ 'ar2_zone_' . $this->id . '_posts' ] = array (
			'name' => __( 'Post Options', 'ar2' ),
			'page' => 'ar2_zone-' . $this->id,
		);
		return $sections;
		
	}
	
	/**
	 * Filter for adding new theme option fields for the zone.
	 * @since 2.0
	 */
	public function add_theme_option_fields( $fields ) {
	
		$fields[ 'zone-' . $this->id . '-hide_duplicates' ] = array (
			'setting'		=> $this->get_field_name( 'hide_duplicates' ),
			'type'			=> 'checkbox',
			'title'			=> __( 'Hide Duplicate Posts', 'ar2' ),
			'section'		=> 'ar2_zone_' . $this->id . '_posts',
			'description'	=> __( 'Check this to prevent duplicate posts from displaying within sections from this zone. This may increase your server load.', 'ar2' )
		);
		
		$fields[ 'zone-' . $this->id . '-ignore_sticky' ] = array (
			'setting'		=> $this->get_field_name( 'ignore_sticky' ),
			'type'			=> 'checkbox',
			'title'			=> __( 'Ignore Sticky Posts', 'ar2' ),
			'section'		=> 'ar2_zone_' . $this->id . '_posts',
			'description'	=> __( 'Check this to ignore sticky posts from being displayed on every top of each section in this zone.', 'ar2' )
		);
		
		return $fields;
		
	}
	
}

/* End of file ar2-postviews-zone.php */
/* Location: ./library/classes/ar2-postviews-zone.php */