'use strict';

jQuery(function ($) {
	if (typeof gform !== 'undefined') {
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

		// Track the submission form events
		$(document).on('gform_confirmation_loaded', function (event, formId) {
			if (formId == 1) {
				const service = $('.cart-total__price-title').text();
				const total = parseFloat($('.cart-total__price-amount').text().replace(',', '.'));

				dataLayer.push({ ecommerce: null });
				dataLayer.push({
					event: 'purchase',
					ecommerce: {
						transaction_id: uuid(),
						value: 9.99,
						currency: 'EUR',
						coupon: '',
						items: [
							{
								item_name: 'TheMovers',
								price: 9.99,
								quantity: 1,
							},
						],
					},
				});
			}
		});

		// Track the quote form events
		$(document).on('gform_page_loaded', function (event, formId, currentPage) {
			if (formId == 1) {
				dataLayer.push({
					event: 'form',
					step: currentPage,
				});

				if (currentPage == 3) {
					setTimeout(() => {
						const service = $('.cart-total__price-title').text();
						const total = parseFloat($('.cart-total__price-amount').text().replace(',', '.'));

						dataLayer.push({ ecommerce: null });
						dataLayer.push({
							event: 'add_to_cart',
							ecommerce: {
								value: total,
								currency: 'EUR',
								items: [
									{
										item_name: service,
										price: total,
										quantity: 1,
									},
								],
							},
						});
					}, 1000);
				}
			}
		});
	}

	function uuid() {
		var dt = new Date().getTime();
		var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
			var r = (dt + Math.random() * 16) % 16 | 0;
			dt = Math.floor(dt / 16);
			return (c == 'x' ? r : (r & 0x3) | 0x8).toString(16);
		});
		return uuid;
	}
});
