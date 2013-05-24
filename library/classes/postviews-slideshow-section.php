<?php
/**
 * AR2 PostViews Slideshow Section Class.
 *
 * @package AR2
 * @subpackage PostViews
 * @since 2.0
 */
 
class AR2_PostViews_Slideshow_Section extends AR2_PostViews_Section {

	/**
	 * Constructor.
	 * @since 2.0
	 */
	function __construct( $manager = null, $id, $zone = '_void', $args = array() ) {
		
		parent::__construct( $manager, $id, $zone, $args );
		
		$this->manager->register_display_type( 'slideshow', __( 'Slideshow', 'ar2' ), $this );
		
		if ( $this->settings[ '_preview' ] || $this->settings[ 'enabled' ] )
			$this->init_slideshow_scripts();
		
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
		
		echo '<div class="hfeed posts-' . $this->settings[ 'type' ] . '">';
				
		echo '<ul class="slides clearfix">';
	
		while ( $this->query->have_posts() ) :
		
			$this->query->the_post();
		
			// hack for plugin authors who love to use $post = $wp_query->post
			$wp_query->post = $this->query->post;
			setup_postdata( $post );
		
			get_template_part( 'section', $this->settings[ 'type' ] );
			
			// Update the post blacklist.
			$this->zone->blacklist[] = $post->ID;			
			
		endwhile;
		
		echo '</ul><!-- .slides -->';
		

		echo '</div><!-- .posts-' . $this->settings[ 'type' ] . '-->';
		
		if ( $this->settings[ 'container' ] )
			echo '</div><!-- #section-' . $this->id . '-->';
			
		// For developers to place code after the post section.
		do_action( 'ar2_after_section-' . $this->id );
			
	}
	
	/**
	 * @todo
	 * @since 2.0
	 */
	public function init_slideshow_scripts() {
	
		wp_enqueue_script( 'flexslider', get_template_directory_uri() . '/js/jquery.flexslider.min.js', array( 'jquery' ), '2012-07-08' );
		add_action( 'ar2_custom_scripts', array( $this, 'do_slideshow_js' ) );
		
	}

	/**
	 * @todo
	 * @since 2.0
	 */
	public function do_slideshow_js() {
	
		?>
		$( '.posts-slideshow' ).flexslider( {
			useCSS: false,
			animation: 'slide'
	    } );
		<?php
		
	}
		
}

/* End of file ar2-postviews-section.php */
/* Location: ./library/classes/ar2-postviews-section.php */