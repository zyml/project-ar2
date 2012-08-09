var ar2Customize;
( function( $ ) {

wp.customize( 'blogname', function( value ) {
	value.bind( function( to ) {
		$( '.blog-name a' ).html( to );
	} );
} );
wp.customize( 'blogdescription', function( value ) {
	value.bind( function( to ) {
		$( '.blog-description' ).html( to );
	} );
} );
wp.customize( 'header_textcolor', function( value ) {
	value.bind( function( to ) {
		if ( to == 'blank' ) {
			$( '.blog-name a, .blog-description' ).hide();
		} else {
			$( '.blog-name a, .blog-description' ).show();
			$( '.blog-name a, .blog-description' ).css( 'color', to );
		}
	} );
} );

ar2Customize = {
	
	refreshSection : function( id, settings ) {
		
		$( '#section-' + id ).css( 'opacity', 0.5 );
		
		var data = {
			action: 'ar2_customize_preview_section',
			section: id,
			settings: settings
		};
		
		$.post( _ar2Customize.ajaxurl, data, function( response ) {
		
			if ( response != 0 ) $( '#section-' + id ).html( response );
			$( '#section-' + id ).css( 'opacity', 1.0 );
			
			if ( id == 'slideshow' ) {
				$( '#section-' + id + ' .posts-slideshow' ).flexslider( {
			          animation: 'slide'
			    } );
			}
							
		} );
		
	}
	
};

} )( jQuery );

