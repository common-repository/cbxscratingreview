	'use strict';



	jQuery(document).ready(function ($) {
		//apply rating form
		$('.cbxscratingreview_mainwrap').each(function (index, element) {
			let $wrapper = $(element);
			let $form    = $wrapper.find('.cbxscratingreview-form');
			let $log_id = Number($form.find('#cbxscratingreview-review-id').val());

			//jquery ui date picker
			/*$form.find(".cbxscratingreview_review_date_flatpicker").datepicker({
				dateFormat: "yy-mm-dd"
			});*/


			/*if (cbxscratingreview_ratingform.enable_location == 1) {
				let current_lat = $form.find(".cbxscratingreview_review_location_lat").val();
				let current_lng = $form.find(".cbxscratingreview_review_location_lon").val();
				let $zoom       = Number(cbxscratingreview_ratingform.googlemap_api_zoom);

				if (cbxscratingreview_ratingform.googlemap_api_key != '') {

					$form.find(".cbxscratingreview-form-field-map-canvas").locationpicker({
						location          : {
							latitude : [current_lat],
							longitude: [current_lng],
						},
						radius            : 0,
						zoom              : $zoom,
						scrollwheel       : true,
						inputBinding      : {
							latitudeInput    : $form.find(".cbxscratingreview_review_location_lat"),
							longitudeInput   : $form.find(".cbxscratingreview_review_location_lon"),
							locationNameInput: $form.find(".cbxscratingreview_review_location")
						},
						enableAutocomplete: true,
						markerInCenter    : true,
						onchanged         : function (currentLocation, radius, isMarkerDropped) {
							//console.log("Location changed. New location (" + currentLocation.latitude + ", " + currentLocation.longitude + ")");
						},
						onzoomed          : function (currentZoom) {
							//console.log(currentZoom);
							$form.find(".cbxscratingreview_review_location_zoom").val(currentZoom);
						}

					});
				}
			}*/


			//apply raty plugin to all star rating
			$form.find('.cbxscratingreview_rating_trigger').cbxscratingreview_raty({
				cancelHint : cbxscratingreview_ratingform.rating.cancelHint,
				hints      : cbxscratingreview_ratingform.rating.hints,
				noRatedMsg : cbxscratingreview_ratingform.rating.noRatedMsg,
				starType   : 'img',
				starHalf   : cbxscratingreview_ratingform.rating.img_path + 'star-half.png',                                // The name of the half star image.
				starOff    : cbxscratingreview_ratingform.rating.img_path + 'star-off.png',                                 // Name of the star image off.
				starOn     : cbxscratingreview_ratingform.rating.img_path + 'star-on.png',                                  // Name of the star image on.
				half       : cbxscratingreview_ratingform.rating.half_rating,
				halfShow   : cbxscratingreview_ratingform.rating.half_rating,
				targetScore: $form.find('.cbxscratingreview_rating_score')
			});


			$.validator.setDefaults({
				ignore: ':hidden:not(select)',
			}); //for all select

			$.extend($.validator.messages, {
				required   : cbxscratingreview_ratingform.validation.required,
				remote     : cbxscratingreview_ratingform.validation.remote,
				email      : cbxscratingreview_ratingform.validation.email,
				url        : cbxscratingreview_ratingform.validation.url,
				date       : cbxscratingreview_ratingform.validation.date,
				dateISO    : cbxscratingreview_ratingform.validation.dateISO,
				number     : cbxscratingreview_ratingform.validation.number,
				digits     : cbxscratingreview_ratingform.validation.digits,
				creditcard : cbxscratingreview_ratingform.validation.creditcard,
				equalTo    : cbxscratingreview_ratingform.validation.equalTo,
				maxlength  : $.validator.format(cbxscratingreview_ratingform.validation.maxlength),
				minlength  : $.validator.format(cbxscratingreview_ratingform.validation.minlength),
				rangelength: $.validator.format(cbxscratingreview_ratingform.validation.rangelength),
				range      : $.validator.format(cbxscratingreview_ratingform.validation.range),
				max        : $.validator.format(cbxscratingreview_ratingform.validation.max),
				min        : $.validator.format(cbxscratingreview_ratingform.validation.min)
			});

			let $require_headline = (cbxscratingreview_ratingform.review_common_config.require_headline == 1);
			let $require_comment  = (cbxscratingreview_ratingform.review_common_config.require_comment == 1);

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
					'cbxscratingreview_ratingForm[comment]': {
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


			$form.on('keypress', ':input:not(textarea):not([type=submit])', function (e) {
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

				let $busy = Number($form.data('busy'));

				if ($formvalidator.valid() && $busy === 0) {

					$form.find('.btn-cbxscratingreview-submit').prop("disabled", true);
					$form.find('.label-cbxscratingreview-submit-processing').show();

					$.ajax({
						type    : 'post',
						dataType: 'json',
						url     : cbxscratingreview_ratingform.ajaxurl,
						data    : $form.serialize() + '&action=cbxscratingreview_review_rating_admin_edit' + '&security=' + cbxscratingreview_ratingform.nonce,// our data object
						success : function (data) {
							if (data.ok_to_process == 1) {
								$.each(data.success, function (key, valueObj) {
									if (key != '' && valueObj !== '') {
										let $exp_all_msg = '<p class="cbxscratingreview-alert alert-' + key + '">' + valueObj + '</p>';
										$form.prev('.cbxscratingreview_global_msg').html($exp_all_msg);
									}
								});

								$form.data('busy', 0);
								$form.find('.btn-cbxscratingreview-submit').prop("disabled", false);
								$form.find('.label-cbxscratingreview-submit-processing').hide();

								let $scroll_to = $("#cbxscratingreview_mainwrap").offset().top - 50;

								$('html, body').animate({
									scrollTop: $scroll_to
								}, 1000);

								CBXscRatingReviewEvents_do_action('cbxscratingreview_review_adminedit_success', $);

							} else {
								$form.data('busy', 0);
								$form.find('.btn-cbxscratingreview-submit').prop("disabled", false);
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

                                            $form.find('#' + key).addClass('error');
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
							//$form.find('.btn-cbxscratingreview-submit').prop("disabled", false);
						}
					});
				}
				else {
					return false;
				}
			});

		}); //form submit ends
	});

