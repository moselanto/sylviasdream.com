<?php
/**
 * Sylvia's Dream child theme - bootstrap.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

define( 'SYLVIASDREAM_CHILD_VERSION', '1.19.1' );
define( 'SYLVIASDREAM_CHILD_DIR', get_stylesheet_directory() );
define( 'SYLVIASDREAM_CHILD_URI', get_stylesheet_directory_uri() );

/**
 * Version an asset by its modified time so browsers pick up changes.
 *
 * @param string $relative Path relative to the child theme folder.
 * @return string
 */
function sdc_asset_version( $relative ) {
	$file = SYLVIASDREAM_CHILD_DIR . '/' . ltrim( $relative, '/' );
	return file_exists( $file ) ? (string) filemtime( $file ) : SYLVIASDREAM_CHILD_VERSION;
}

/**
 * Theme setup: text domain, WooCommerce support, image sizes.
 */
function sdc_setup() {
	load_child_theme_textdomain( 'sylviasdream-child', SYLVIASDREAM_CHILD_DIR . '/languages' );

	add_theme_support( 'woocommerce', array(
		'thumbnail_image_width' => 600,
		'single_image_width'    => 900,
		'product_grid'          => array(
			'default_rows'    => 3,
			'min_rows'        => 1,
			'default_columns' => 4,
			'min_columns'     => 2,
			'max_columns'     => 4,
		),
	) );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	// Block editor: the editor looks like the site (theme.json + blocks.css).
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_editor_style( array( 'assets/css/editor.css', 'assets/css/blocks.css' ) );

	// Menu locations are registered by the parent theme (primary,
	// footer_books, footer_about, footer_legal). They are re-registered
	// here with the same keys so existing menus keep attaching even if
	// the parent changes.
	register_nav_menus( array(
		'primary'      => __( 'Primary Navigation', 'sylviasdream-child' ),
		'footer_books' => __( 'Footer: Books & Resources', 'sylviasdream-child' ),
		'footer_about' => __( 'Footer: About & Ministry', 'sylviasdream-child' ),
		'footer_legal' => __( 'Footer: Policies', 'sylviasdream-child' ),
	) );
}
add_action( 'after_setup_theme', 'sdc_setup', 20 );

/**
 * Google Fonts stylesheet URL for the brand fonts (also used by the editor
 * and the preload link).
 *
 * @return string
 */
function sdc_google_fonts_url() {
	return 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=Source+Sans+3:wght@400;600;700&display=swap';
}

/**
 * Front-end assets. Child CSS loads after the parent base stylesheet.
 * 1.17.0: minified files are used when built (see sdc_asset()).
 */
function sdc_assets() {
	if ( sdc_get_bool( 'use_google_fonts' ) ) {
		// phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- Google Fonts URL is versioned by its query.
		wp_enqueue_style( 'sylviasdream-fonts', sdc_google_fonts_url(), array(), null );
	}

	$deps = array( 'sylviasdream-base' );
	if ( wp_style_is( 'sylviasdream-fonts', 'enqueued' ) ) {
		$deps[] = 'sylviasdream-fonts';
	}
	list( $url, $ver ) = sdc_asset( 'style.css' );
	wp_enqueue_style( 'sylviasdream-child', $url, $deps, $ver );

	if ( class_exists( 'WooCommerce' ) ) {
		list( $url, $ver ) = sdc_asset( 'assets/css/woocommerce.css' );
		wp_enqueue_style( 'sylviasdream-child-shop', $url, array( 'sylviasdream-child' ), $ver );
	}

	list( $url, $ver ) = sdc_asset( 'assets/css/blocks.css' );
	wp_enqueue_style( 'sylviasdream-child-blocks', $url, array( 'sylviasdream-child' ), $ver );

	// 1.9.0: polish stylesheet for ordinary pages (see sdc_is_about_page()).
	if ( sdc_is_about_page() ) {
		list( $url, $ver ) = sdc_asset( 'assets/css/about.css' );
		wp_enqueue_style( 'sylviasdream-child-about', $url, array( 'sylviasdream-child-blocks' ), $ver );
	}

	list( $url, $ver ) = sdc_asset( 'assets/js/nav.js' );
	wp_enqueue_script(
		'sylviasdream-child-nav',
		$url,
		array(),
		$ver,
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'sdc_assets', 20 );

/**
 * Preconnect to Google Fonts when they are used.
 *
 * @param array  $urls          URLs to print for resource hints.
 * @param string $relation_type The relation type.
 * @return array
 */
function sdc_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type && sdc_get_bool( 'use_google_fonts' ) ) {
		$urls[] = array( 'href' => 'https://fonts.googleapis.com' );
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'sdc_resource_hints', 10, 2 );

/**
 * Body classes used by the stylesheet.
 *
 * @param string[] $classes Classes.
 * @return string[]
 */
function sdc_body_classes( $classes ) {
	if ( sdc_get_bool( 'show_utility_bar' ) ) {
		$classes[] = 'sd-has-utility';
	}
	if ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() ) ) {
		$classes[] = 'sd-wide-page';
	}
	return $classes;
}
add_filter( 'body_class', 'sdc_body_classes' );

/**
 * Pages that get the polish stylesheet (about.css).
 * 1.9.0 started with the About pages; 1.11.0 covers every ordinary page plus
 * the Journal (posts page). Left out: the Home page (its own design) and the
 * WooCommerce Shop, Cart, Checkout and My account pages (woocommerce.css).
 * Filter `sdc_polish_page` (bool, page ID) to opt a page out.
 *
 * @return bool
 */
function sdc_is_about_page() {
	if ( is_front_page() ) {
		return false;
	}
	if ( is_home() ) {
		return (bool) apply_filters( 'sdc_polish_page', true, (int) get_option( 'page_for_posts' ) );
	}
	if ( ! is_page() ) {
		return false;
	}
	if ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() || is_account_page() || is_shop() ) ) {
		return false;
	}
	return (bool) apply_filters( 'sdc_polish_page', true, get_queried_object_id() );
}

/**
 * Body class for the About pages.
 *
 * @param string[] $classes Classes.
 * @return string[]
 */
function sdc_about_body_class( $classes ) {
	if ( sdc_is_about_page() ) {
		$classes[] = 'sd-about';
		$slug      = get_post_field( 'post_name', get_queried_object_id() );
		if ( $slug ) {
			$classes[] = 'sd-p-' . sanitize_html_class( $slug );
		}
	}
	return $classes;
}
add_filter( 'body_class', 'sdc_about_body_class' );

/**
 * Shorter, cleaner excerpts for devotional cards.
 *
 * @return int
 */
function sdc_excerpt_length() {
	return 28;
}
add_filter( 'excerpt_length', 'sdc_excerpt_length' );

/**
 * Plain ellipsis instead of [&hellip;].
 *
 * @return string
 */
function sdc_excerpt_more() {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'sdc_excerpt_more' );

require_once SYLVIASDREAM_CHILD_DIR . '/inc/customizer.php';
require_once SYLVIASDREAM_CHILD_DIR . '/inc/performance.php';
require_once SYLVIASDREAM_CHILD_DIR . '/inc/blocks.php';
require_once SYLVIASDREAM_CHILD_DIR . '/inc/template-tags.php';
require_once SYLVIASDREAM_CHILD_DIR . '/inc/class-sdc-walker-primary.php';
require_once SYLVIASDREAM_CHILD_DIR . '/inc/navigation.php';
require_once SYLVIASDREAM_CHILD_DIR . '/inc/widgets.php';
require_once SYLVIASDREAM_CHILD_DIR . '/inc/inner-pages.php';
require_once SYLVIASDREAM_CHILD_DIR . '/inc/languages.php';
require_once SYLVIASDREAM_CHILD_DIR . '/inc/hero-books.php';
require_once SYLVIASDREAM_CHILD_DIR . '/inc/cover-alt.php';
if ( class_exists( 'WooCommerce' ) ) {
	require_once SYLVIASDREAM_CHILD_DIR . '/inc/woocommerce.php';
}
