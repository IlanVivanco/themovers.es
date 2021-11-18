'use strict';

jQuery(function ($) {
	// Init tooltips
	$('.tooltip').powerTip();

	// Init tooltips after gravity form is loaded
	$(document).on('gform_post_render', function (event, form_id, current_page) {
		$('.tooltip').powerTip();
	});

	// Limit the dates from today to one year to the future
	gform.addFilter('gform_datepicker_options_pre_init', function (optionsObj, formId, fieldId) {
		//only apply to quote form
		if (formId != 1) return optionsObj;

		if (fieldId == 7) {
			optionsObj.minDate = 0;
			optionsObj.yearRange = '-1:+1';
		}

		return optionsObj;
	});
});
