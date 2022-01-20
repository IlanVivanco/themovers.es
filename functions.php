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
	wp_enqueue_style( 'themovers', get_stylesheet_directory_uri() . '/style.css', array( 'astra-theme-css', 'powertips' ), CHILD_THEME_THEMOVERS_VERSION, 'all' );

	wp_enqueue_script( 'powertips', get_stylesheet_directory_uri() . '/js/vendor/jquery.powertip.min.js', array( 'jquery' ), CHILD_THEME_THEMOVERS_VERSION, true );
	wp_enqueue_script( 'themovers', get_stylesheet_directory_uri() . '/js/main.js', array( 'jquery', 'powertips' ), CHILD_THEME_THEMOVERS_VERSION, true );

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
	// We can add the JS handler to the array to load them as modules.
	$enabled_handlers = array(
		'themovers',
	);

	if ( in_array( $handle, $enabled_handlers, true ) ) {
		return str_replace( '<script', '<script type="module"', $tag );
	}

	return $tag;
}

add_filter( 'script_loader_tag', 'mv_load_scripts_as_module', 10, 3 );

// Load includes
locate_template( 'inc/shortcodes.php', true );

if ( class_exists( 'GFCommon' ) ) {
	locate_template( 'inc/gravity.php', true );
}
