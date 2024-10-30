(function ($) {
	'use strict';

	$(document).ready(function () {
		//Initiate Color Picker
		$('.wp-color-picker-field').wpColorPicker();

        //add chooser

        $('.selecttwo-select').select2({
            placeholder: cbxscratingreview_setting.please_select,
            allowClear: false
        });

		// Switches option sections

		let activetab = '';
		if (typeof(localStorage) !== 'undefined') {
			//get
			activetab = localStorage.getItem('cbxscratingreviewactivetab');
		}

		//if url has section id as hash then set it as active or override the current local storage value
		if (window.location.hash) {
			if($(window.location.hash).hasClass('global_setting_group') ){
				activetab = window.location.hash;
				if (typeof(localStorage) !== 'undefined') {
					localStorage.setItem('cbxscratingreviewactivetab', activetab);
				}
			}
		}


		if (activetab !== '' && $(activetab).length  && $(activetab).hasClass('global_setting_group')) {
			$('.global_setting_group').hide();
			$(activetab).fadeIn();
		}

		if (activetab !== '' && $(activetab + '-tab').length) {
			$('.nav-tab-wrapper a.nav-tab').removeClass('nav-tab-active');
			$(activetab + '-tab').addClass('nav-tab-active');

			$('.nav-tab-wrapper li.nav-parent-active').removeClass('nav-parent-active');
			$(activetab + '-tab').closest('li').addClass('nav-parent-active');
		}


		$('.nav-tab-wrapper a').on('click', function (e) {
			e.preventDefault();

			let $this = $(this);

			$('.nav-tab-wrapper a.nav-tab').removeClass('nav-tab-active');
			$this.addClass('nav-tab-active').blur();

			$('.nav-tab-wrapper li.nav-parent-active').removeClass('nav-parent-active');
			$this.closest('li').addClass('nav-parent-active');

			let clicked_group = $(this).attr('href');

			if (typeof (localStorage) !== 'undefined') {
				localStorage.setItem('cbxscratingreviewactivetab', $(this).attr('href'));
			}
			$('.global_setting_group').hide();
			$(clicked_group).fadeIn();
		});


		$('.wpsa-browse').on('click', function (event) {
			event.preventDefault();

			let self = $(this);

			// Create the media frame.
			let file_frame = wp.media.frames.file_frame = wp.media({
				title: self.data('uploader_title'),
				button: {
					text: self.data('uploader_button_text')
				},
				multiple: false
			});

			file_frame.on('select', function () {
				let attachment = file_frame.state().get('selection').first().toJSON();
				self.prev('.wpsa-url').val(attachment.url);
			});

			// Finally, open the modal
			file_frame.open();
		});



		//make the subheading single row
		$('.setting_heading').each(function (index, element) {
			let $element = $(element);
			let $element_parent = $element.parent('td');
			$element_parent.attr('colspan', 2);
			$element_parent.prev('th').remove();
			$element_parent.parent('tr').removeAttr('class');
			$element_parent.parent('tr').addClass('global_setting_heading_section');
			//$element_parent.parent('tr').addClass('global_setting_heading_section_open');


		});


		$('.setting_subheading').each(function (index, element) {
			let $element = $(element);
			let $element_parent = $element.parent('td');
			$element_parent.attr('colspan', 2);
			$element_parent.prev('th').remove();
			$element_parent.parent('tr').removeAttr('class');
			$element_parent.parent('tr').addClass('global_setting_subheading_section');
		});


		$('.global_setting_group').each(function (index, element) {
			let $element = $(element);
			let $form_table = $element.find('.form-table');
			$form_table.prev('h2').remove();

			let $i = 0;
			$form_table.find('tr').each(function (index2, element){
				let $tr = $(element);

				if(!$tr.hasClass('global_setting_heading_section')){
					$tr.addClass('global_setting_common_section');
					$tr.addClass('global_setting_common_section_'+$i);
				}
				else{
					$i++;
					$tr.addClass('global_setting_heading_section_'+$i);
					$tr.attr('data-counter',  $i);
					$tr.attr('data-is-closed',  0);

				}

			});

			$form_table.on('click', 'a.setting_heading_toggle', function (evt){
				evt.preventDefault();

				let $this 		= $(this);
				let $parent 	= $this.closest('.global_setting_heading_section');
				let $counter 	= Number($parent.data('counter'));
				let $is_closed 	= Number($parent.data('is-closed'));
				if($is_closed === 0){
					$parent.data('is-closed', 1);
					$parent.addClass('global_setting_heading_section_closed');
					$('.global_setting_common_section_'+$counter).hide();
				}
				else{
					$parent.data('is-closed', 0);
					$parent.removeClass('global_setting_heading_section_closed');
					$('.global_setting_common_section_'+$counter).show();
				}
			});
		});

		$('#cbxscratingreview_info_trig').on('click', function (e) {
			e.preventDefault();

			$('#cbxscratingreview_resetinfo').toggle();

		});

        $('.global_setting_group').on('click', '.checkbox', function() {
            let mainParent = $(this).closest('.checkbox-toggle-btn');
            if($(mainParent).find('input.checkbox').is(':checked')) {
                $(mainParent).addClass('active');
            } else {
                $(mainParent).removeClass('active');
            }

        });

		//one click save setting for the current tab
		$('#save_settings').on('click', function (e) {
			e.preventDefault();
			let $current_tab = $('.nav-tab.nav-tab-active');
			let $tab_id      = $current_tab.data('tabid');
			$('#' + $tab_id).find('.submit_cbxscratingreview').trigger('click');
		});

	});

})(jQuery);
