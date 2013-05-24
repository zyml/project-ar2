<?php
/**
 * AR2 Customize Terms Control Class.
 *
 * @package AR2
 * @subpackage PostViews
 * @since 2.0
 */

class AR2_Customize_Terms_Control extends WP_Customize_Control {

	public $section_id;
	public $taxonomy;

	/**
	 * Constructor.
	 * @since 2.0
	 */
	public function __construct( $manager, $id, $args = array() ) {
	
		$this->section_id = $args[ 'post_section' ];
		$this->taxonomy = $args[ 'taxonomy' ];
		
		parent::__construct( $manager, $id, $args );
		
	}
	
	/**
	 * Enqueue control related scripts/styles.
	 * @since 2.0
	 */
	public function enqueue() {
	
		wp_enqueue_style( 'ar2-theme-options', get_template_directory_uri() . '/css/theme-options.css', null, '2011-07-29' );
		
		wp_enqueue_script( 'jquery-tokeninput', get_template_directory_uri() . '/js/jquery.tokeninput.min.js', array( 'jquery' ), '2012-08-10' );
		wp_enqueue_script( 'ar2-customize-controls', get_template_directory_uri() . '/js/customize.js', array( 'jquery' ), '2012-08-10' );
		
		if ( !defined( 'AR2_CUSTOMIZE_TERMS_LOCALIZED' ) )
			wp_localize_script( 'ar2-customize-controls', 'ar2Admin_l10n', $this->localize_vars() );
		
	}
	
	
	/**
	 * Stores the localization object to localize scripts.
	 * @since 2.0
	 */
	protected function localize_vars() {
		
		$_vars = ar2_theme_options_localize_vars();
		define ( 'AR2_CUSTOMIZE_TERMS_LOCALIZED', true );
		
		return $_vars;
		
	}
	
	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 * @since 2.0
	 */
	public function to_json() {
	
		parent::to_json();
		
	}

	/**
	 * Render the control's content.
	 * @since 2.0
	 */
	public function render_content() {

		?>
		<label>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<input id="section-<?php echo $this->section_id ?>-terms" class="tokeninput" type="text" data-taxonomy="<?php echo $this->taxonomy ?>" <?php $this->link() ?> />
			<p><em><?php _e( "The section's post type and taxonomy can be changed via the theme options page.", 'ar2' ) ?></em></p>
		</label>
		<?php
		
	}


}

/* End of file ar2-customize-terms-control.php */
/* Location: ./library/classes/ar2-customize-terms-control.php */