<?php
/**
 * AR2 Customize Terms Control Class.
 *
 * @package AR2
 * @subpackage PostViews
 * @since 2.0
 */

class AR2_Customize_Color_Switcher_Control extends WP_Customize_Control {

	/**
	 * Constructor.
	 * @since 2.0
	 */
	public function __construct( $manager, $id, $args = array() ) {
		
		
		parent::__construct( $manager, $id, $args );
		
	}
	
	/**
	 * Enqueue control related scripts/styles.
	 * @since 2.0
	 */
	public function enqueue() {
	
		wp_enqueue_style( 'ar2-theme-options', get_template_directory_uri() . '/css/theme-options.css', null, '2011-07-29' );
		wp_enqueue_script( 'ar2-customize-controls', get_template_directory_uri() . '/js/customize.js', array( 'jquery' ), time() );
		
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
		<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<div class="color-switch">
		<?php foreach ( $this->choices as $id => $color ) : ?>
		
		<?php if ( $id == 'default' ) : ?>
		<input type="radio" value="<?php echo $id ?>" name="<?php echo esc_attr( $this->id ) ?>" id="<?php echo esc_attr( $this->id  ) . '-' . $id ?>" <?php $this->link(); checked( $this->value(), $id ); ?> />
		<label title="<?php echo $color[ 'label' ] ?>" class="default" for="<?php echo esc_attr( $this->id  ) . '-' . $id ?>"><span><?php echo $color[ 'label' ] ?></span></label>
		<?php else : ?>
		<input type="radio" value="<?php echo $color[ 'hex' ] ?>" name="<?php echo esc_attr( $this->id ) ?>" id="<?php echo esc_attr( $this->id ) . '-' . $id ?>" <?php $this->link(); checked( $this->value(), $color[ 'hex' ] ); ?> />
		<label title="<?php echo $color[ 'label' ] ?>" for="<?php echo esc_attr( $this->id  ) . '-' . $id ?>"><span style="background-color: <?php echo $color[ 'hex' ] ?>"><?php echo $color[ 'label' ] ?></span></label>		
		<?php endif ?>
		
		<?php endforeach; ?>
		</div>
		
		<p><?php _e( 'Previewing hovered colors will not work at the moment.', 'ar2' ) ?></p>
		<?php
		
	}


}

/* End of file ar2-customize-terms-control.php */
/* Location: ./library/classes/ar2-customize-terms-control.php */