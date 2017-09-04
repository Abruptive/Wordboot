(function( $ ) {
	'use strict';

	/**
	 * Create the repeater setting functionality.
	 */

	if( $('.repeater').length ) {

		// Bind the add and remove events to repeater buttons.
		$(document).on( 'click', '[data-repeater]', function(event) {

			var repeater = $(this).closest( '.repeater' ).find('tbody');
			var item     = $(this).closest( '.repeater-item' );
			var template = repeater.find( '.repeater-template' ).html().replace( 'data-name', 'name' );

			switch( $(this).data('repeater') ) {
				case 'add':
					repeater.append( 
						'<tr class="repeater-item">' + template + '</tr>'
					);
				break;
				case 'remove':
					item.remove();
				break;
			}

			event.preventDefault();

		} );

		// Populate the repeaters with the current values.
		$('.repeater').each(function() {

			var repeater = $(this).find('tbody');
			var template = repeater.find( '.repeater-template' ).html().replace( 'data-name', 'name' );
			var data = JSON.parse( $(this).find( '.repeater-data' ).val().replace( '"",', '' ) );

			for( var index in data ) {
				repeater.append(
					'<tr class="repeater-item">' + template.replace( 'value=""', 'value="' + data[index] + '"' ) + '</tr>'
				)
			}

		});

	}

})( jQuery );
