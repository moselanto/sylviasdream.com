<?php
/**
 * Sylvia's Dream child theme - bootstrap.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

define( 'SYLVIASDREAM_CHILD_VERSION', '0.1.0' );

/**
 * Child theme assets. Loads after the parent base stylesheet.
 */
function sdc_assets() {
	wp_enqueue_style( 'sylviasdream-child', get_stylesheet_uri(), array( 'sylviasdream-base' ), SYLVIASDREAM_CHILD_VERSION );
	wp_enqueue_script( 'sylviasdream-child-nav', get_stylesheet_directory_uri() . '/assets/js/nav.js', array(), SYLVIASDREAM_CHILD_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );
}
add_action( 'wp_enqueue_scripts', 'sdc_assets', 20 );

/**
 * WooCommerce support (book shop).
 */
function sdc_woocommerce_support() {
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
}
add_action( 'after_setup_theme', 'sdc_woocommerce_support' );

require_once get_stylesheet_directory() . '/inc/customizer.php';
require_once get_stylesheet_directory() . '/inc/template-tags.php';
require_once get_stylesheet_directory() . '/inc/widgets.php';
