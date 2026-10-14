<?php
/**
 * 301 redirects from retired and duplicate pages to the one page kept.
 *
 * OFF by default. Turn on in Settings > Sylvia's Dream on launch day,
 * after the merged pages are published. Thank-you / download pages are
 * never redirected here, so past buyers keep access.
 *
 * The map is a DRAFT from the 13 Oct 2026 audit and must be confirmed
 * with Sylvia (see docs/REDIRECT-MAP.md). Final target slugs may change
 * once pages are renamed.
 *
 * @package SylviasDreamCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Old path => new path (site-relative, trailing slash).
 *
 * @return array<string,string>
 */
function sd_core_redirect_map() {
	$map = array(
		'/home/' => '/',
		'/new-home/' => '/',
		'/sylvias-dream/' => '/from-fear-to-freedom-3/',
		'/from-fear-to-freedom/' => '/from-fear-to-freedom-3/',
		'/the-hidden-key-to-healing-and-breakthrough/' => '/the-hidden-key-to-healing-and-breakthrough-3/',
		'/how-to-find-the-real-you-2/' => '/how-to-find-the-real-you/',
		'/my-autobiography/' => '/my-story/',
		'/manifesting-miracles-now/' => '/shop-for-books/',
	);
	/**
	 * Filter the redirect map.
	 *
	 * @param array<string,string> $map Map.
	 */
	return (array) apply_filters( 'sd_core_redirect_map', $map );
}

/**
 * Perform the redirect.
 */
function sd_core_do_redirects() {
	if ( ! (int) sd_core_opt( 'redirects_enabled' ) || is_admin() ) {
		return;
	}
	$path = (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$path = trailingslashit( strtolower( $path ) );
	$map  = sd_core_redirect_map();
	if ( isset( $map[ $path ] ) && $map[ $path ] !== $path ) {
		wp_safe_redirect( home_url( $map[ $path ] ), 301, 'Sylvias Dream Core' );
		exit;
	}
}
add_action( 'template_redirect', 'sd_core_do_redirects', 1 );
