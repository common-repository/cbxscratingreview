	'use strict';

	//dom ready
	jQuery(document).ready(function ($) {

		//apply rating edit form
		$('.cbxscratingreview_mainwrap').each(function (index, element) {
			let $wrapper = $(element);
			let $form    = $wrapper.find('.cbxscratingreview-form');
			let $log_id = parseInt($form.find('#cbxscratingreview-review-id').val());

			//jquery ui date picker
			/*$form.find(".cbxscratingreview_review_date_flatpicker").datepicker({
				dateFormat: "yy-mm-dd"
			});*/

			//apply raty plugin to all star rating
			$form.find('.cbxscratingreview_rating_trigger').cbxscratingreview_raty({
				cancelHint : cbxscratingreview_ratingeditform.rating.cancelHint,
				hints      : cbxscratingreview_ratingeditform.rating.hints,
				noRatedMsg : cbxscratingreview_ratingeditform.rating.noRatedMsg,
				starType   : 'img',
				starHalf   : cbxscratingreview_ratingeditform.rating.img_path + 'star-half.png',                                // The name of the half star image.
				starOff    : cbxscratingreview_ratingeditform.rating.img_path + 'star-off.png',                                 // Name of the star image off.
				starOn     : cbxscratingreview_ratingeditform.rating.img_path + 'star-on.png',                                  // Name of the star image on.
				half       : cbxscratingreview_ratingeditform.rating.half_rating,
				halfShow   : cbxscratingreview_ratingeditform.rating.half_rating,
				targetScore: $form.find('.cbxscratingreview_rating_score')
			});

			$.validator.setDefaults({
				ignore: ":hidden:not(select)",
			}); //for all select

			$.extend($.validator.messages, {
				required   : cbxscratingreview_ratingeditform.validation.required,
				remote     : cbxscratingreview_ratingeditform.validation.remote,
				email      : cbxscratingreview_ratingeditform.validation.email,
				url        : cbxscratingreview_ratingeditform.validation.url,
				date       : cbxscratingreview_ratingeditform.validation.date,
				dateISO    : cbxscratingreview_ratingeditform.validation.dateISO,
				number     : cbxscratingreview_ratingeditform.validation.number,
				digits     : cbxscratingreview_ratingeditform.validation.digits,
				creditcard : cbxscratingreview_ratingeditform.validation.creditcard,
				equalTo    : cbxscratingreview_ratingeditform.validation.equalTo,
				maxlength  : $.validator.format(cbxscratingreview_ratingeditform.validation.maxlength),
				minlength  : $.validator.format(cbxscratingreview_ratingeditform.validation.minlength),
				rangelength: $.validator.format(cbxscratingreview_ratingeditform.validation.rangelength),
				range      : $.validator.format(cbxscratingreview_ratingeditform.validation.range),
				max        : $.validator.format(cbxscratingreview_ratingeditform.validation.max),
				min        : $.validator.format(cbxscratingreview_ratingeditform.validation.min)
			});

			let $require_headline = (cbxscratingreview_ratingeditform.review_common_config.require_headline == 1);
			let $require_comment  = (cbxscratingreview_ratingeditform.review_common_config.require_comment == 1);

			let $formvalidator = $form.validate({
				ignore        : [],
				errorPlacement: function (error, element) {
					error.appendTo(element.closest('.cbxscratingreview-form-field'));
				},
				errorElement  : 'p',
				rules         : {
					'cbxscratingreview_ratingForm[score]'   : {
						required: true
					},
					'cbxscratingreview_ratingForm[headline]': {
						required : $require_headline,
						minlength: 2
					},
					'cbxscratingreview_ratingForm[comment]' : {
						required : $require_comment,
						minlength: 10
					}
				},
				messages      : {}
			});


			$formvalidator.focusInvalid = function () {
				// put focus on tinymce on submit validation
				if (this.settings.focusInvalid) {
					try {
						let toFocus = $(this.findLastActive() || this.errorList.length && this.errorList[0].element || []);
						if (toFocus.is('textarea')) {
							if(typeof tinyMCE !== 'undefined') {
								tinyMCE.get(toFocus.attr('id')).focus();
							}
						} else {
							toFocus.filter(':visible').focus();
						}
					} catch (e) {
						// ignore IE throwing errors when focusing hidden elements
					}
				}
			};


			$form.on('keypress', ":input:not(textarea):not([type=submit])", function (e) {
				if (e.keyCode === 13) {
					return false; // prevent the button click from happening
				}
			});



			//validation done
			$form.submit(function (e) {
				e.preventDefault();

				if(typeof tinyMCE !== 'undefined') {
					tinyMCE.triggerSave();
				}

				let $busy = parseInt($form.data('busy'));

				if ($formvalidator.valid() && $busy === 0) {

					$form.find('.btn-cbxscratingreview-submit').prop('disabled', true);
					$form.find('.label-cbxscratingreview-submit-processing').show();

					$.ajax({
						type    : 'post',
						dataType: 'json',
						url     : cbxscratingreview_ratingeditform.ajaxurl,
						data    : $form.serialize() + '&action=cbxscratingreview_review_rating_front_edit' + '&security=' + cbxscratingreview_ratingeditform.nonce,// our data object
						success : function (data) {
							if (data.ok_to_process === 1) {
								$.each(data.success, function (key, valueObj) {
									if (key !== '' && valueObj !== '') {
										let $exp_all_msg = '<p class="cbxscratingreview-alert alert-' + key + '">' + valueObj + '</p>';
										$form.prev('.cbxscratingreview_global_msg').html($exp_all_msg);
									}
								});

								$form.data('busy', 0);
								$form.find('.btn-cbxscratingreview-submit').prop('disabled', false);
								$form.find('.label-cbxscratingreview-submit-processing').hide();

								let $scroll_to = $('#cbxscratingreview_mainwrap').offset().top - 150;

								$('html, body').animate({
									scrollTop: $scroll_to
								}, 1000);

								CBXscRatingReviewEvents_do_action('cbxscratingreview_review_frontedit_success', $);

							} else {
								$form.data('busy', 0);
								$form.find('.btn-cbxscratingreview-submit').prop('disabled', false);
								$form.find('.label-cbxscratingreview-submit-processing').hide();

                                $.each(data.error, function (key, valueObj) {
                                    $.each(valueObj, function (key2, valueObj2) {
                                        if(key === 'cbxscratingreview_questions_error'){
                                            //key2 = question id
                                            let $field_parent = $form.find('#cbxscratingreview_review_custom_question_'+ key2).closest('.cbxscratingreview-form-field');

                                            if($field_parent.find('p.error').length > 0){
                                                $field_parent.find('p.error').html(valueObj2).show();
                                            }
                                            else{
                                                $('<p for="cbxscratingreview_q_field_'+key2+'" class="error">' + valueObj2 + '</p>').appendTo($field_parent);
                                            }

                                        }
                                        else if (key === 'top_errors') {
                                            $.each(valueObj2, function (key3, valueObj3) {
                                                let $exp_all_msg = '<p class="cbxscratingreview-alert cbxscratingreview-alert-warning">' + valueObj3 + '</p>';
                                                $form.prev('.cbxscratingreview_global_msg').html($exp_all_msg);
                                            });
                                        }
                                        else {

                                            $form.find( + key).addClass('error');
                                            $form.find('#' + key).remove('valid');

                                            let $field_parent = $form.find('#' + key).closest('.cbxscratingreview-form-field');
                                            if($field_parent.find('p.error').length > 0){
                                                $field_parent.find('p.error').html(valueObj2).show();
                                            }
                                            else{
                                                $('<p for="'+key+'" class="error">' + valueObj2 + '</p>').appendTo($field_parent);
                                            }

                                        }
                                    });
                                });
							}
						},
						complete: function () {
							//$form.find('.btn-cbxscratingreview-submit').prop('disabled', false);
						}
					});
				}
				else {
					return false;
				}
			});
		}); //form submit ends
	});

