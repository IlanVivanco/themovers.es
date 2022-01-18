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
	wp_enqueue_style( 'themovers', get_stylesheet_directory_uri() . '/style.css', array( 'astra-theme-css' ), CHILD_THEME_THEMOVERS_VERSION, 'all' );

	wp_enqueue_script( 'powertips', get_stylesheet_directory_uri() . '/js/vendor/jquery.powertip.min.js', array( 'jquery' ), CHILD_THEME_THEMOVERS_VERSION, true );
	wp_enqueue_script( 'themovers', get_stylesheet_directory_uri() . '/js/main.js', array( 'jquery' ), CHILD_THEME_THEMOVERS_VERSION, true );

}

add_action( 'wp_enqueue_scripts', 'mv_enqueue_styles', 15 );

/**
 * Script that import modules must use a script tag with type="module",
 * so let's set it for the script.
 *
 * @param String $tag     The <script> tag for the enqueued script.
 * @param String $handle  The script's registered handle.
 * @param String $src     The script's source URL.
 *
 * @return String The <script> tag.
 */
function mv_load_scripts_as_module( $tag, $handle, $src ) {

	// You can add the JS handler to the array to load them as modules.
	$modules = array(
		'themovers',
	);

	if ( in_array( $handle, $modules, true ) ) {
		return str_replace( '<script ', '<script type="module" ', $tag );
	}

	return $tag;

}

add_filter( 'script_loader_tag', 'mv_load_scripts_as_module', 10, 3 );

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
			'item'        => '',
			'image'       => '',
			'title'       => '',
			'description' => null,
			'volume'      => null,
			'max'         => null,
			'tooltip'     => null,
		),
		$atts
	);

	$tooltip_layout = isset( $atts['tooltip'] ) ? '<span class="tooltip" data-powertip="' . $atts['tooltip'] . '">?</span>' : '';

	ob_start() ?>

		<div class="option-card" data-item="<?php echo $atts['item']; ?>">
			<img src="<?php echo $atts['image']; ?>" alt="<?php echo $atts['title']; ?>">

			<h3 class="title"><?php echo $atts['title']; ?></h3>
			<div class="description">
				<span><?php echo $atts['description']; ?></span>
				<?php echo $tooltip_layout; ?>
			</div>

			<?php if ( $atts['volume'] || $atts['max'] ) : ?>
				<footer class="meta">
					<?php if ( $atts['volume'] ) : ?>
						<div>
							<strong>Volumen</strong>
							<span><?php echo $atts['volume']; ?></span>
						</div>
					<?php endif; ?>

					<?php if ( $atts['max'] ) : ?>
						<div>
							<strong>Carga</strong>
							<span><?php echo $atts['max']; ?></span>
						</div>
					<?php endif; ?>
				</footer>
			<?php endif; ?>

		</div>

	<?php

	return ob_get_clean();

}

add_shortcode( 'option_card', 'mv_option_shortcode' );


/**
 * Add shortcode for gravity forms total module.
 *
 * @param Array $atts  Attributes passed to the shortcode.
 *
 * @return String      HTML for the total section.
 */
function mv_cart_total( $atts ) {

	// Attributes
	$atts = shortcode_atts(
		array(
			'service'   => '',
			'insurance' => '',
			'fee'       => '',
		),
		$atts
	);

	ob_start()
	?>

		<section class="cart-total">
			<div class="cart-total__price">
				<div class="cart-total__price-title">
					<?php _e( 'Moovers', 'themovers' ); ?>
				</div>
				<div class="cart-total__price-box">
					<span class="cart-total__price-amount">-</span>
					<span class="cart-total__price-currency">€</span>
				</div>
				<div class="cart-total__price-tag">
					<?php _e( 'Total', 'themovers' ); ?>
				</div>
			</div>

			<div class="cart-total__row service">
				<div class="cart-total__row-description tooltip" data-powertip="<?php echo $atts['service']; ?>">
					<?php _e( 'Coste del servicio', 'themovers' ); ?>
				</div>
				<div class="cart-total__row-amount"></div>
			</div>

			<div class="cart-total__row insurance">
				<div class="cart-total__row-description tooltip" data-powertip="<?php echo $atts['insurance']; ?>">
					<?php _e( 'Seguro de transporte', 'themovers' ); ?>
				</div>
				<div class="cart-total__row-amount"></div>
			</div>

			<div class="cart-total__row fee">
				<div class="cart-total__row-description tooltip" data-powertip="<?php echo $atts['fee']; ?>">
					<?php _e( 'Comisión Moovers', 'themovers' ); ?>
				</div>
				<div class="cart-total__row-amount"></div>
			</div>
		</section>

	<?php

	return ob_get_clean();
}

add_shortcode( 'cart_total', 'mv_cart_total' );
