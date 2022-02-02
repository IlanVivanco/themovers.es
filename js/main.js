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
		// Only apply to quote form
		if (formId != 1) return optionsObj;

		if (fieldId == 7) {
			optionsObj.minDate = '+2';
			optionsObj.yearRange = '-1:+1';
		}

		return optionsObj;
	});

	// Process the cart total
	gform.addFilter('gform_product_total', function (total, formId) {
		// Only apply to quote form
		if (formId != 1) return total;

		// Fields
		let $transport = $('.transport_type input');
		let $service = $('.service_type input');

		if ($transport.is(':checked') && $service.is(':checked')) {
			const transportPrice = parseFloat($transport.parent().find(':checked').val());
			const servicePrice = parseFloat($service.parent().find(':checked').val().split('|')[1]);
			const transportSelected = $transport.parent().find(':checked + label .option-card').data('item');
			const serviceSelected = $service.parent().find(':checked + label .option-card').data('item');
			let satirwaysSubTotal = 0;

			if (transportSelected != 'mini' && (serviceSelected == 'standard' || serviceSelected == 'deluxe')) {
				const satirwayFromPrice =
					parseInt($('.stairway_from input:checked').val()) * parseInt($('.floor_from input').val() | 0);
				const satirwayToPrice =
					parseInt($('.stairway_to input:checked').val()) * parseInt($('.floor_to input').val() | 0);

				satirwaysSubTotal = satirwayFromPrice + satirwayToPrice;
			}

			const services = transportPrice * (1 + servicePrice / 100);
			const fee = services * 0.05;
			const subtotal = services - fee;
			total = parseFloat((services + satirwaysSubTotal).toFixed(2));

			// Show the data on the cart
			$('.cart-total__price-title').text(`Movers ${transportSelected} - Servicio ${serviceSelected}`);
			$('.cart-total__price-amount').text(total.toLocaleString('es-ES'));

			$('.cart-total__row.service .cart-total__row-amount').text(
				subtotal.toLocaleString('es-ES', { style: 'currency', currency: 'EUR' })
			);
			$('.cart-total__row.fee .cart-total__row-amount').text(
				fee.toLocaleString('es-ES', { style: 'currency', currency: 'EUR' })
			);
		}

		return total;
	});

	// Track the form submission events
	$(document).on('gform_confirmation_loaded', function (event, formId) {
		if (formId == 1) {
			if (typeof window.dataLayer != 'undefined') {
				window.dataLayer.push({
					event: 'GFTrackSubmission',
					GFTrackCategory: 'form',
					GFTrackAction: 'submission',
					GFTrackLabel: `form_${form_id}`,
				});
			}
		}
	});

	// Track the form pagination events
	$(document).on('gform_page_loaded', function (event, formId, currentPage) {
		if (formId == 1) {
			if (typeof window.dataLayer != 'undefined') {
				window.dataLayer.push({
					event: 'GFTrackPagination',
					GFTrackCategory: 'form',
					GFTrackAction: 'pagination',
					GFTrackLabel: `form_${formId}`,
					GFTrackValue: `page_${currentPage}`,
				});
			}
		}
	});
});
