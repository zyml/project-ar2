( function( $ ) {

$( document ).ready( function( $ ) {

	$( '.menu' ).tinyNav();
	
	<?php if ( is_singular() ) : ?>
	$( '.entry-photo a, .gallery a:has( img ), .entry-content a:has( img )' ).colorbox( { rel: 'gallery' } );
	<?php endif ?>
	
	<?php do_action( 'ar2_custom_scripts' ) ?>
	
} );

} )( jQuery );