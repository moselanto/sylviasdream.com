<?php
/**
 * Small template helpers.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Output social links that have a URL set.
 */
function sdc_social_links() {
	$networks = array(
		'facebook'  => 'Facebook',
		'instagram' => 'Instagram',
		'youtube'   => 'YouTube',
		'patreon'   => 'Patreon',
	);
	echo '<ul class="sd-social">';
	foreach ( $networks as $key => $label ) {
		$url = sdc_get( 'social_' . $key );
		if ( $url ) {
			printf( '<li><a href="%1$s" rel="noopener" target="_blank">%2$s</a></li>', esc_url( $url ), esc_html( $label ) );
		}
	}
	echo '</ul>';
}

/**
 * Turn a site-relative or absolute link setting into a URL.
 *
 * @param string $value Setting value.
 * @return string
 */
function sdc_link( $value ) {
	if ( '' === $value ) {
		return '';
	}
	return str_starts_with( $value, '/' ) ? home_url( $value ) : $value;
}
