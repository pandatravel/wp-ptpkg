(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	$(function(){
		// Uploading files
		var file_frame;

		$.fn.upload_banner_image = function( button ) {
			var button_id = button.attr('id');
			var field_id = button_id.replace('_button', '');

			// If the media frame already exists, reopen it.
			if ( file_frame ) {
			  file_frame.open();
			  return;
			}

			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
			  title: $(this).data('uploader_title'),
			  button: {
			    text: $(this).data('uploader_button_text'),
			  },
			  multiple: false
			});

			// When an image is selected, run a callback.
			file_frame.on( 'select', function() {
			  var attachment = file_frame.state().get('selection').first().toJSON();
			  $("#"+field_id).val(attachment.id);
			  $("#package-meta img").attr('src',attachment.url);
			  $('#package-meta img').show();
			  $('#' + button_id).attr( 'id', 'remove_banner_image_button');
			  $('#remove_banner_image_button').text('Remove banner image');
			});

			// Finally, open the modal
			file_frame.open();
		};

		$('#package-meta').on('click', '#upload_banner_image_button', function(event) {
			event.preventDefault();
			$.fn.upload_banner_image( $(this) );
		});

		$('#package-meta').on('click', '#remove_banner_image_button', function(event) {
			event.preventDefault();
			$('#upload_banner_image').val('');
			$('#package-meta img').attr('src', '');
			$('#package-meta img').hide();
			$(this).attr( 'id', 'upload_banner_image_button');
			$('#upload_banner_image_button' ).text('Set banner image');
		});
	});


})( jQuery );
