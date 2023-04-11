(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
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

	jQuery(document).ready(function($) {

		$('.tabs__nav li').click(function(e) {
			e.preventDefault();
			var tab_id = $(this).children('a').attr('href');

			$('.tabs__nav li').removeClass('current');
			$('.tab').removeClass('current');

			$(this).addClass('current');
			$(tab_id).addClass('current');
		});

		$('.btn--special').click(function() {
			var count = $('.request-info-checkbox:checked').length;
			$(document).find('.bar__number span').text(count);
			if (count > 0) {
				$('#show-shell').show();
			} else {
				$('#show-shell').hide();
			}
		});

		function getCookie(c_name) {
			var c_value = document.cookie,
				c_start = c_value.indexOf(" " + c_name + "=");
			if (c_start == -1) {
				c_start = c_value.indexOf(c_name + "=");
			}
			if (c_start == -1) {
				c_value = null;
			} else {
				c_start = c_value.indexOf("=", c_start) + 1;
				var c_end = c_value.indexOf(";", c_start);
				if (c_end == -1) {
					c_end = c_value.length;
				}
				c_value = decodeURIComponent(c_value.substring(c_start, c_end));
			}
			return c_value;
		}

		$('#show-shell').hide();
		
		var posts = getCookie("post_ids");
		if (posts) {
			var count = posts.split(',').length; // count the number of items in the cookie
			$('#show-shell').show();
			$(document).find('.bar__number span').text(count);
		} else {
			$('#show-shell').hide();
		}



		$('.btn--special').click(function() {
			// $('#show-shell').hide();
			var count = $('.request-info-checkbox:checked').length;
			if(count > 0) {
				// $(document).find('#sb_ajax_details').val(2);
				$(document).find('.bar__number span').text(count);
				$(this).addClass('sb_ajax');
				$(document).find('.bar__number span').text(count);
				var post_ids = $(this).find('input').attr('id').split('-')[1];
				jQuery.ajax({
					type: "POST",
					url: URL.ajax_url,
					data: {
						action: "add_post_in_session",
						post_ids: post_ids,
					},
					dataType: "json",
					cache: false,
					success: function (res) {
						$('#show-shell').show();
					}
				});
			}
			else {
				$('#show-shell').hide();
			}
		});

		$('.btn--loog').click(function() {
			window.location.href = URL.redirect+'/request-information';
		});
		$('.btn--single-post').click(function() {
			var post_ids = $(this).data('id');
			jQuery.ajax({
				type: "POST",
				url: URL.ajax_url,
				data: {
					action: "add_post_in_session",
					post_ids: post_ids,
				},
				dataType: "json",
				cache: false,
				success: function (res) {
					window.location.href = URL.redirect+'/request-information';
				}
			});
		});

		$('.remove_post').click(function() {
			var post_id = $(this).data('post-id');
			jQuery.ajax({
				type: "POST",
				url: URL.ajax_url,
				data: {
					action: "remove_post_in_session",
					post_id: post_id,
				},
				dataType: "json",
				cache: false,
				success: function (res) {
					window.location.reload();
				}
			});
		});

		function getParameterByName(name, url = window.location.href) {
			name = name.replace(/[\[\]]/g, '\\$&');
			var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
				results = regex.exec(url);
			if (!results) {
				return null;
			}
			if (!results[2]) {
				return '';
			}
			return decodeURIComponent(results[2].replace(/\+/g, ' '));
		}

		$(document).find('.popup-open').click(function (e){
			e.preventDefault();
			var target = $(this).attr('data-target');
			$(document).find('#'+target).show();
		});
		$(document).find('.popup__close').click(function (e){
			e.preventDefault();
			$(document).find('.popup').hide();
		});

		$('.select_posts').click(function() {
			var post_ids = [];
			$('.select_posts:checked').each(function() {
				post_ids.push($(this).data('id'));
			});
			$('#information_form input[name="recommended_franchise"]').val(post_ids);
		});

		$('.uncheck_selected').click(function() {
			$('.select_posts:checked').prop('checked', false);
			var post_ids = [];
			$('.select_posts:checked').each(function() {
				post_ids.push($(this).data('id'));
			});
			$('#information_form input[name="recommended_franchise"]').val(post_ids);
		});

		// Search Form JS

		$('#search_post_form_btn').on('click', function(e) {
			var category = $('select[name="category"]').val();
			var investment = $('select[name="investment"]').val();
			if (category === '' && investment === '') {
				e.preventDefault();
				$('.invalid-form-message').show();
			} else {
				$('.invalid-form-message').hide();
			}
		});

	});

})( jQuery );
