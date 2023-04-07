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

		$('.btn--simple').click(function() {
			var post_ids = [];
			$('.request-info-checkbox:input[type="checkbox"]:checked').each(function() {
				post_ids.push($(this).attr('id').split('-')[1]);
			});
			if (post_ids.length > 0) {
				var baseurl = window.location.origin+window.location.pathname;
				var url = baseurl+"/request-information?franchise="+post_ids.join(',');
				window.location.href = url;
			}
		});

		$('.remove_post').click(function() {
			var post_id = $(this).data('post-id');
			var post_ids = getParameterByName('franchise');
			post_ids = post_ids.split(',').filter(function(id) {
				return id != post_id;
			}).join(',');
			var url = window.location.href.split('?')[0];
			if (post_ids !== '') {
				url += '?franchise=' + post_ids;
			}
			window.location.href = url;
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
