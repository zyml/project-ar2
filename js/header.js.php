( function( $ ) {

$( document ).ready( function( $ ) {
	<?php do_action( 'ar2_custom_scripts' ) ?>
	$( '.js #main-nav ul.menu' ).before( '<span class="toggle">☰</span>' );
	$( '#main-nav .toggle' ).click( function() {
		$( this ).toggleClass( 'toggle-selected' );
		$( '#main-nav ul.menu' ).slideToggle( 'fast' );
	} );
	$( window ).resize( function() { 
		if ( window.innerWidth > 768 )
			$( '#main-nav ul.menu' ).removeAttr( 'style' );
	} );
} );

} )( jQuery );