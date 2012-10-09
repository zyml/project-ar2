( function( $ ) {

$( document ).ready( function( $ ) {

	$( '.menu' ).tinyNav();
	
	<?php do_action( 'ar2_custom_scripts' ) ?>
	
} );

} )( jQuery );