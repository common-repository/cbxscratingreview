(function ($) {
	'use strict';

	let cbxscratingreview_video_filetypes = ['mp4', 'm4v', 'webm', 'ogv', 'wmv', 'flv'];
	let cbxscratingreview_audio_filetypes = ['mp3', 'm4a', 'ogg', 'wav', 'wma'];



	/**
	 * Get file extension from a file name
	 *
	 * @param $file_url
	 * @returns string
	 */
	function cbxscratingreview_getFileExtension(file_url) {
		return file_url.split('.').pop();
	}

	/**
	 * Check if the string is valid url
	 * https://stackoverflow.com/a/14582229/341955
	 *
	 * @param str
	 * @returns {boolean}
	 */
	function cbxscratingreview_isURL(str) {
		let pattern = new RegExp('^(https?:\\/\\/)?' + // protocol
			'((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|' + // domain name
			'((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
			'(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
			'(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
			'(\\#[-a-z\\d_]*)?$', 'i'); // fragment locator
		return pattern.test(str);
	}



	$(document).ready(function () {
		//any where read only rating
		$('.cbxscratingreview_readonlyrating_score_js').each(function (index, element) {
			let $element = $(element);

			$(element).cbxscratingreview_raty({
				hints     : cbxscratingreview_admin.rating.hints,
				noRatedMsg: cbxscratingreview_admin.rating.noRatedMsg,
				//starType  : 'img',
				starType  : 'img',
				starHalf  : cbxscratingreview_admin.rating.img_path + 'star-half.png',                                // The name of the half star image.
				starOff   : cbxscratingreview_admin.rating.img_path + 'star-off.png',                                 // Name of the star image off.
				starOn    : cbxscratingreview_admin.rating.img_path + 'star-on.png',                                  // Name of the star image on.
				halfShow  : true,
				readOnly  : true
			});
		});

		//review listing comment details popup using sweetalert
		$('.cbxscratingreview_review_text_expand').on('click', function (e) {
			e.preventDefault();

			let $this       = $(this);
			let $review_id  = $this.data('reviewid');
			let $headline   = $this.data('headline');
			let reviews_arr = cbxscratingreview_admin.reviews_arr;

			swal({
				title            : $headline,
				html             : reviews_arr[$review_id],
				//type: "info",
				confirmButtonText: cbxscratingreview_admin.button_text_ok,
				cancelButtonText : cbxscratingreview_admin.button_text_cancel
			});
		});
		//end review listing comment details popup using sweetalert

		// in review edit view trigger rating
		/*let $form = $('.cbxscratingreview-review-edit-form');
		$form.find('.cbxscratingreview_rating_trigger').cbxscratingreview_raty({
			cancelHint : cbxscratingreview_admin.rating.cancelHint,
			hints      : cbxscratingreview_admin.rating.hints,
			noRatedMsg : cbxscratingreview_admin.rating.noRatedMsg,
			//starType   : 'img',
			starType   : 'img',
			starHalf  : cbxscratingreview_admin.rating.img_path + 'star-half.png',                                // The name of the half star image.
			starOff   : cbxscratingreview_admin.rating.img_path + 'star-off.png',                                 // Name of the star image off.
			starOn    : cbxscratingreview_admin.rating.img_path + 'star-on.png',                                  // Name of the star image on.
			half       : true,
			halfShow   : true,
			targetScore: $form.find('.cbxscratingreview_rating_score')
		});
*/


		/*let $cbxscratingreview_review_readonly_section = $('.cbxscratingreview-review-readonly-section');
		$cbxscratingreview_review_readonly_section.find('.cbxscratingreview-btn-review-delete').on('click', function (e) {
			e.preventDefault();

			let $this = $(this);
			let $review_id = $this.data('review_id');

			$.ajax({
				type    : "post",
				dataType: 'json',
				url     : cbxscratingreview_admin.ajaxurl,
				data    : {
					action   : "cbxscratingreview_review_delete_process",
					security : cbxscratingreview_admin.nonce,
					review_id: $review_id,
				},
				success : function (data) {
					if (data.process_status == 1) {
						alert(data.success_msg);
						window.location = data.redirect_url;
					} else {
						let $err_msg = '<p class="cbxscratingreview-alert cbxscratingreview-alert-warning">' + data.error_msg + '</p>';
						$cbxscratingreview_review_readonly_section.find('.cbxscratingreview_review_readonly_global_msg').html($err_msg);
					}
				},
			});
		});*/

		//flatpickr enable for review submission
		/*$form.find(".cbxscratingreview_review_date_flatpicker").flatpickr({
			dateFormat: 'Y-m-d',
		});*/


		// delete single photo
		/*$cbxscratingreview_review_readonly_section.on('click', 'a.cbxscratingreview_review_photo_delete', function (e) {
			e.preventDefault();
			let $this = $(this);

			let $review_id = $this.data('review_id');
			let $filename = $this.data('name');

			let request = $.ajax({
				url     : cbxscratingreview_admin.ajaxurl,
				type    : 'post',
				data    : {
					action   : "file_delete_process_admin",
					security : cbxscratingreview_admin.nonce,
					filename : $filename, //only input
					review_id: $review_id, //only input
				},
				dataType: 'json',
			});

			request.done(function (data) {
				if (data.ok_to_progress == 1) {
					$this.parent('.cbxscratingreview_review_photo').fadeOut("slow", function () {
						$this.parent('.cbxscratingreview_review_photo').remove();
					});
				} else {
					let $err_msg = '<p class="cbxscratingreview-alert cbxscratingreview-alert-warning">' + data.error_msg + '</p>';
					$cbxscratingreview_review_readonly_section.find('.cbxscratingreview_review_readonly_global_msg').html($err_msg);
				}
			});

			request.fail(function () {
				alert(cbxscratingreview_admin.delete_error);
			});

			return false;
		});*/


	});

})(jQuery);
