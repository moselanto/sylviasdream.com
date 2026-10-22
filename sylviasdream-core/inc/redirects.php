<?php
/**
 * 301 redirects from retired and duplicate pages to the one page kept.
 *
 * OFF by default. Turn on in Settings > Sylvia's Dream on launch day,
 * after the merged pages are published. Thank-you / download pages are
 * never redirected here, so past buyers keep access.
 *
 * Map revised 22 Oct 2026 after a page-by-page content check (see
 * docs/REDIRECT-MAP.md). /manifesting-miracles-now/ is left out until
 * Sylvia decides whether to keep it.
 *
 * @package SylviasDreamCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Old path => new path (relative to the site home, trailing slash).
 *
 * @return array<string,string>
 */
function sd_core_redirect_map() {
	$map = array(
		'/home/'                                       => '/',
		'/new-home/'                                   => '/',
		'/sylvias-dream/'                              => '/from-fear-to-freedom-3/',
		'/from-fear-to-freedom/'                       => '/from-fear-to-freedom-3/',
		'/the-hidden-key-to-healing-and-breakthrough/' => '/the-hidden-key-to-healing-and-breakthrough-3/',
		'/my-autobiography/'                           => '/one-life/',
	);
	/**
	 * Filter the redirect map.
	 *
	 * @param array<string,string> $map Map.
	 */
	return (array) apply_filters( 'sd_core_redirect_map', $map );
}

/**
 * Current request path relative to the site home, lower case, trailing slash.
 * Works when WordPress lives in a subfolder (e.g. /sylviasdream/).
 *
 * @return string
 */
function sd_core_request_path() {
	$path      = (string) wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	$path      = trailingslashit( strtolower( $path ) );
	$home_path = trailingslashit( strtolower( (string) wp_parse_url( home_url( '/' ), PHP_URL_PATH ) ) );
	if ( '/' !== $home_path && 0 === strpos( $path, $home_path ) ) {
		$path = '/' . substr( $path, strlen( $home_path ) );
	}
	return trailingslashit( $path );
}

/**
 * Perform the redirect.
 */
function sd_core_do_redirects() {
	if ( ! (int) sd_core_opt( 'redirects_enabled' ) || is_admin() ) {
		return;
	}
	$path = sd_core_request_path();
	$map  = sd_core_redirect_map();
	if ( isset( $map[ $path ] ) && $map[ $path ] !== $path ) {
		wp_safe_redirect( home_url( $map[ $path ] ), 301, 'Sylvias Dream Core' );
		exit;
	}
}
add_action( 'template_redirect', 'sd_core_do_redirects', 1 );
