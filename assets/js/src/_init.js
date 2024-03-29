/* ========================================================================
 * DOM-based Routing
 * Based on http://goo.gl/EUTi53 by Paul Irish
 *
 * Only fires on body classes that match. If a body class contains a dash,
 * replace the dash with an underscore when adding it to the object below.
 *
 * .noConflict()
 * The routing is enclosed within an anonymous function so that you can
 * always reference jQuery with $, even when in .noConflict() mode.
 *
 * Squire
 * https://bitbucket.org/sixteenbit/squire
 *
 * Copyright (c) 2016 Sixteenbit
 * Licensed under the GPLv2+ license.
 * ======================================================================== */

(function ( $ ) {

	// Use this variable to set up the common and page specific functions. If you
	// rename this variable, you will also need to rename the namespace below.
	var SQUIRE = {
		// All pages
		common: {
			init: function () {
				// JavaScript to be fired on all pages

				// Foundation JavaScript
				// @link http://foundation.zurb.com/docs
				$( document ).foundation();

				var backToTopSel = '.js-back-to-top', backtoTopActiveClass = 'back-to-top--active';

				function jumpToTop () {
					$( 'html,body' ).scrollTop( 0 );
				}

				$( window ).scroll( function () {
					if ( $( this ).scrollTop() > 500 ) {
						$( backToTopSel ).addClass( backtoTopActiveClass );
					} else {
						$( backToTopSel ).removeClass( backtoTopActiveClass );
					}
				} );
				$( backToTopSel ).on( 'click', jumpToTop );

			}
		},
		// Home page
		home: {
			init: function () {
				// JavaScript to be fired on the home page

			}
		}
	};

	// The routing fires all common scripts, followed by the page specific scripts.
	// Add additional events for more control over timing e.g. a finalize event
	var UTIL = {
		fire: function ( func, funcname, args ) {
			var namespace = SQUIRE;
			funcname = (funcname === undefined) ? 'init' : funcname;
			if ( func !== '' && namespace[func] && typeof namespace[func][funcname] === 'function' ) {
				namespace[func][funcname]( args );
			}
		},
		loadEvents: function () {
			UTIL.fire( 'common' );

			$.each( document.body.className.replace( /-/g, '_' ).split( /\s+/ ), function ( i, classnm ) {
				UTIL.fire( classnm );
			} );
		}
	};

	$( document ).ready( UTIL.loadEvents );

})( jQuery ); // Fully reference jQuery after this point.
