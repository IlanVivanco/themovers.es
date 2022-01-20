<?php
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

			<?php if ( ! empty( $atts['service'] ) ) : ?>
				<div class="cart-total__row service">
					<div class="cart-total__row-description tooltip" data-powertip="<?php echo $atts['service']; ?>">
						<?php _e( 'Coste del servicio', 'themovers' ); ?>
					</div>
					<div class="cart-total__row-amount"></div>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $atts['insurance'] ) ) : ?>
				<div class="cart-total__row insurance">
					<div class="cart-total__row-description tooltip" data-powertip="<?php echo $atts['insurance']; ?>">
						<?php _e( 'Seguro de transporte', 'themovers' ); ?>
					</div>
					<div class="cart-total__row-amount"></div>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $atts['fee'] ) ) : ?>
				<div class="cart-total__row fee">
					<div class="cart-total__row-description tooltip" data-powertip="<?php echo $atts['fee']; ?>">
						<?php _e( 'Comisión Moovers', 'themovers' ); ?>
					</div>
					<div class="cart-total__row-amount"></div>
				</div>
			<?php endif; ?>
		</section>

	<?php

	return ob_get_clean();
}

add_shortcode( 'cart_total', 'mv_cart_total' );