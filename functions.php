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

	wp_enqueue_style( 'themovers-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'), CHILD_THEME_THEMOVERS_VERSION, 'all' );

}

add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 15 );