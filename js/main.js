'use strict';

jQuery(function ($) {
	// Init tooltips
	$('.tooltip').powerTip();

	// Init tooltips after gravity form is loaded
	$(document).on('gform_post_render', function (event, form_id, current_page) {
		$('.tooltip').powerTip();
	});
});
