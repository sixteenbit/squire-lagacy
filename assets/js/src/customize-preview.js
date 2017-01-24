/**
 * Live-update changed settings in real time in the Customizer preview.
 */

(function ( $ ) {
	var style = $( '#squire-color-scheme-css' ),
		api = wp.customize;

	if ( ! style.length ) {
		style = $( 'head' ).append( '<style type="text/css" id="squire-color-scheme-css" />' )
			.find( '#squire-color-scheme-css' );
	}

	// Site title.
	api( 'blogname', function ( value ) {
		value.bind( function ( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );

	// Site tagline.
	api( 'blogdescription', function ( value ) {
		value.bind( function ( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Site footer text.
	api( 'footer_text', function ( value ) {
		value.bind( function ( to ) {
			$( '.sq-info' ).text( to );
		} );
	} );

	// Add custom-background-image body class when background image is added.
	api( 'background_image', function ( value ) {
		value.bind( function ( to ) {
			$( 'body' ).toggleClass( 'custom-background-image', '' !== to );
		} );
	} );

	// Color Scheme CSS.
	api.bind( 'preview-ready', function () {
		api.preview.bind( 'update-color-scheme-css', function ( css ) {
			style.html( css );
		} );
	} );
})( jQuery );
