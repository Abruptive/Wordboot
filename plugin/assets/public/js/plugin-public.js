(function( $ ) {
	'use strict';

	/**
	 * Notice
	 * 
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 */

	/**
	 * AJAX
	 * 
	 * Calls an AJAX callback for the site front-end.
	 * Add a button with class ".ajax" to test.
	 */
	$(document).on( 'click', '.ajax', function() {

		$.ajax( {

			// Set the call parameters.
			url    : plugin.ajax_url,
			type   : 'POST',
			data   : {
				action  : 'callback',
				nonce   : plugin.nonce
			},
			dataType: 'json',

			// When an error is encountered.
			error : function( MLHttpRequest, textStatus, errorThrown ) {
				console.error(errorThrown);
			},

			// When the call succeeds.
			success : function( response, textStatus, XMLHttpRequest ) {
				console.log( response );
			},

			// When the call is complete.
			complete : function( reply, textStatus ) {
				console.log( textStatus );
			}

		} );

	} );

})( jQuery );
