<?php

/**
 * Render shortcodes on option labels
 *
 * @param Array $form  Form data.
 *
 * @return Array $form  Form data.
 */
function mv_process_shortcodes_on_gravity_fields( $form ) {
	// Process shortcodes on labels
	foreach ( $form['fields'] as &$field ) {
		$field_ids = array( 6, 23 );

		if ( ! in_array( $field->id, $field_ids, true ) ) {
			continue;
		}

		if ( count( $field->choices ) ) {
			foreach ( $field->choices as &$choice ) {
				$choice['text'] = do_shortcode( $choice['text'] );
			}
		}
	}

	return $form;
}

add_filter( 'gform_pre_render_1', 'mv_process_shortcodes_on_gravity_fields' );


/**
 * Render shortcodes on option labels
 *
 * @param Array $form  Form data.
 *
 * @return Array $form  Form data.
 */
function mv_process_shortcodes_before_save_on_gravity_fields( $form ) {
	// Process shortcodes on labels
	foreach ( $form['fields'] as &$field ) {
		$field_ids = array( 6, 23 );

		if ( ! in_array( $field->id, $field_ids, true ) ) {
			continue;
		}

		if ( count( $field->choices ) ) {
			foreach ( $field->choices as &$choice ) {
				$choice['text'] = mv_get_value_from_param( $choice['text'], 'title' );
			}
		}
	}

	return $form;
}

add_filter( 'gform_pre_submission_filter_1', 'mv_process_shortcodes_before_save_on_gravity_fields' );


/**
 * Process the product info on the entry view
 *
 * @param String $field_content Current info.
 * @param Object $field Fielf data.
 * @param String $value Output HTML.
 *
 * @return String Product info procceessed.
 */
function mv_process_product_info_on_entry_view( $field_content, $field, $value ) {
	if ( 6 === $field->id ) {
		if ( $field->is_entry_detail() ) {
			$chosen = array_filter(
				$field['choices'],
				function( $choice ) use ( $value ) {
					return $choice['value'] === $value;
				}
			);

			$value          = mv_get_value_from_param( reset( $chosen )['text'], 'title' );
			$field_content  = '<tr><td colspan="2" class="entry-view-field-name">Tipo de transporte</td></tr>';
			$field_content .= '<tr><td colspan="2" class="entry-view-field-value">' . $value . '</td></tr>';
		}
	}

	return $field_content;
}

add_filter( 'gform_field_content_1', 'mv_process_product_info_on_entry_view', 10, 3 );


/**
 * Process the product info before submission
 *
 * @param Array $product Current info.
 * @param Array $form    Gravity form data.
 * @param Array $entry   Gravity entry.
 *
 * @return Array Product info procceessed.
 */
function mv_process_product_info( $product, $form, $entry ) {
	$transport      = mv_get_transport_name( $form );
	$service        = mv_get_service_name( $form );
	$total_field_id = 'input_19';

	$product = array(
		'products' => array(
			array(
				'name'      => "$transport - $service",
				'price'     => GFCommon::to_number( rgpost( $total_field_id ) ),
				'quantity'  => 1,
			),
		),
	);

	if ( isset( $_POST[ $total_field_id ] ) ) {
		return $product;
	}
}

add_filter( 'gform_product_info_1', 'mv_process_product_info', 10, 3 );


/**
 * Get data from the transport field
 *
 * @param Array $form  Form data.
 *
 * @return String Data value.
 */
function mv_get_transport_name( $form ) {
	$field_id   = 6;
	$field      = GFAPI::get_field( $form, $field_id );
	$submission = $field->get_value_submission( array() );
	$choices    = $field['choices'];
	$selected   = array_filter(
		$choices,
		function( $choice ) use ( $submission ) {
			return $choice['value'] === $submission;
		}
	);

	$value = reset( $selected )['text'];
	$data  = mv_get_value_from_param( $value, 'title' );

	return $data;
}


/**
 * Get data from the service field
 *
 * @param Array $form  Form data.
 *
 * @return String Data value.
 */
function mv_get_service_name( $form ) {
	$field_id   = 23;
	$field      = GFAPI::get_field( $form, $field_id );
	$submission = $field->get_value_submission( array() );
	$data       = mv_get_value_from_param( $submission, 'title' );

	return $data;
}


/**
 * Get param value from shortcode
 *
 * @param String $data Shortcode tag.
 * @param String $arg Argument name.
 *
 * @return String Value of the argument.
 */
function mv_get_value_from_param( $data = '', $arg = 'title' ) {
	$regex = '/' . $arg . '="(.*?)"/m';

	preg_match_all( $regex, $data, $matches, PREG_SET_ORDER );

	$first_match = reset( $matches )[1] ?? '';
	return str_replace( '<br>', ' ', $first_match );
}
