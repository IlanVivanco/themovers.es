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

	// Process the cart total
	gform.addFilter('gform_product_total', function (total, formId) {
		//only apply to quote form
		if (formId != 1) return total;

		let $type = $('.transport_type input');
		let $service = $('.transport_service input');

		if ($type.is(':checked') && $service.is(':checked')) {
			let typePrice = parseFloat($type.parent().find(':checked').val());
			let servicePrice = parseFloat($service.parent().find(':checked').val().split('|')[1]);
			total = servicePrice + typePrice;

			console.log({ typePrice, servicePrice, total });
		}

		return total;
	});
});
