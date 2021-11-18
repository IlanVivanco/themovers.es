<?php
/**
 * TheMoverS Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package TheMoverS
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_THEMOVERS_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
function mv_enqueue_styles() {

	wp_enqueue_style( 'powertips', get_stylesheet_directory_uri() . '/css/vendor/jquery.powertip.min.css', array( 'astra-theme-css' ), CHILD_THEME_THEMOVERS_VERSION, 'all' );
	wp_enqueue_style( 'themovers-theme-css', get_stylesheet_directory_uri() . '/style.css', array( 'astra-theme-css' ), CHILD_THEME_THEMOVERS_VERSION, 'all' );

	wp_enqueue_script( 'powertips', get_stylesheet_directory_uri() . '/js/vendor/jquery.powertip.min.js', array( 'jquery' ), CHILD_THEME_THEMOVERS_VERSION, true );
	wp_enqueue_script( 'themovers-theme-js', get_stylesheet_directory_uri() . '/js/main.js', array( 'jquery' ), CHILD_THEME_THEMOVERS_VERSION, true );

}

add_action( 'wp_enqueue_scripts', 'mv_enqueue_styles', 15 );

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
add_filter( 'gform_pre_submission_filter_1', 'mv_process_shortcodes_on_gravity_fields' );


/**
 * Add shortcode for gravity forms checkbox options.
 *
 * @param Array $atts  Attributes passed to the shortcode.
 *
 * @return String      HTML for the checkbox.
 */
function mv_option_shortcode( $atts ) {

	// Attributes
	$atts = shortcode_atts(
		array(
			'image'   => '',
			'title'   => '',
			'tooltip' => '',
		),
		$atts
	);

	return '
		<div class="option-card">
			<img

				src="' . $atts['image'] . '"
				alt="' . $atts['title'] . '">
			<span>' . $atts['title'] . '</span>
			<a href="#"
				class="tooltip"
				data-powertip="' . $atts['tooltip'] . '">?</a>
		</div>
	';

}

add_shortcode( 'option_card', 'mv_option_shortcode' );
