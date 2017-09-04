(function ($) {
	'use strict';

	/**
	 * Create the upload field functionality.
	 */

	if ($('.field-image').length || $('.field-file').length) {

		// Bind the upload button event.
		$(document).on('click', '.upload', function (event) {

			var attachment;
			var button = $(this);
			var settings = button.data('settings');
			var media = wp.media({
				title: settings['title'],
				library: {
					type: 'image'
				},
			}).on('select', function () {

				attachment = media.state().get('selection').first().toJSON();
				button.removeClass('button');

				if (settings['type'] == 'image') {

					if (typeof attachment.sizes[settings['size']] !== 'undefined') {
						attachment.sizes[settings['size']].id = attachment.id;
						attachment = attachment.sizes[settings['size']];
					}

					button.html('<img src="' + attachment.url + '" width="' + attachment.width + '" height="' + attachment.height + '" alt="' + attachment.alt + '">');

				} else if (settings['type'] == 'file') {

					button.html('<span class="selected">' + attachment.title + ' (' + attachment.filesizeHumanReadable + ') ' + '</span>');

				}

				button.prev().show();
				button.next().val(attachment.id);

			}).open();

			return false;

		});

		// Bind the remove button event.
		$(document).on('click', '.remove', function () {

			var settings = $(this).next().data('settings');

			$(this).hide().next().next().val('');
			$(this).next().addClass('button').html(settings['title']);

			return false;

		});

	}

	/**
	 * Create the color field functionality.
	 */

	if( $('.field-color').length ) {
		$('.input-color').each(function(){
			$(this).wpColorPicker();
		});
	}

	/**
	 * Create the repeater field functionality.
	 */

	if ($('.field-repeater').length) {
		
		// Bind the "add" and "remove" events to repeater buttons.
		$(document).on('click', '[data-repeater]', function (event) {

			var repeater = $(this).closest('.repeater').find('tbody');
			var item = $(this).closest('.repeater-item');
			var template = repeater.find('.repeater-template').html().replace('data-name', 'name');

			switch ($(this).data('repeater')) {
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

		});

		// Populate the repeaters with the current values.
		$('.repeater').each(function () {

			var repeater = $(this).find('tbody');
			var template = repeater.find('.repeater-template').html().replace('data-name', 'name');
			var data = JSON.parse($(this).find('.repeater-data').val().replace('"",', ''));

			for (var index in data) {
				repeater.append(
					'<tr class="repeater-item">' + template.replace('value=""', 'value="' + data[index] + '"') + '</tr>'
				);
			}

		});

	}

})(jQuery);