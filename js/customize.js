var ar2Customize;
( function( $ ) {

ar2Customize = {
	
	init : function() {
	
		$( 'input.tokeninput' ).each( function( index ) {
			ar2Customize.initTokenInput( this );
		} );
		
	},
	
	initTokenInput : function( input ) {
	
		$( input ).tokenInput( ar2Admin_l10n.ajaxurl, {
			method		: 'POST',
			queryAction	: 'ar2_load_terms',
			queryParam	: 'query',
			minChars	: 2,
			preventDuplicates: true,
			prePopulate : ar2Admin_l10n[ $( input ).attr( 'id' ) ],
			limit		: $( input ).attr( 'data-taxonomy' )
		} );
		
	}
		
};

$( document ).ready( function() { ar2Customize.init(); } );

} )( jQuery );

