<?php
/**
 * 1.23.6: one setting for the GoFundMe button link.
 *
 * The Heaven on Earth and Give pages each have a "Go Fund Me" button (core
 * button block) that pointed to an old fundraiser address. In Elementor those
 * buttons sit inside "Section from your page" widgets, which keep links as
 * they are, so the address could not be changed from the editor.
 *
 * Appearance > Customize > Sylvia's Dream > Home: Heaven on Earth and giving >
 * "GoFundMe button link" (theme setting sdc_gofundme_url). When filled, every
 * button-style link to gofund.me or gofundme.com is sent to that address,
 * in the block editor and in Elementor kept sections (both render through
 * do_blocks(), so the same render_block filter runs). Blank = links unchanged.
 *
 * Only <a> button links change. The embedded GoFundMe widget (div.gfm-embed,
 * data-url) and its script are not touched, nor any button label.
 *
 * Filter sdc_gofundme_url can change the address.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * The GoFundMe address chosen in the Customizer ('' = leave links as they are).
 *
 * @return string
 */
function sdc_gofundme_url() {
	$url = (string) get_theme_mod( 'sdc_gofundme_url', '' );
	$url = (string) apply_filters( 'sdc_gofundme_url', $url );
	if ( '' === $url || ! sdc_is_gofundme_host( $url ) ) {
		return '';
	}
	return $url;
}

/**
 * Is this address on gofund.me or gofundme.com?
 *
 * @param string $href Link.
 * @return bool
 */
function sdc_is_gofundme_host( $href ) {
	$host = strtolower( (string) wp_parse_url( (string) $href, PHP_URL_HOST ) );
	foreach ( array( 'gofund.me', 'gofundme.com' ) as $h ) {
		if ( $host === $h || str_ends_with( $host, '.' . $h ) ) {
			return true;
		}
	}
	return false;
}

/**
 * Point GoFundMe button links at the chosen address.
 *
 * @param string $html  Rendered block.
 * @param array  $block Block.
 * @return string
 */
function sdc_gofundme_button_link( $html, $block ) {
	if ( false === stripos( $html, 'gofund' ) ) {
		return $html;
	}
	$url = sdc_gofundme_url();
	if ( '' === $url ) {
		return $html;
	}
	return (string) preg_replace_callback(
		'#<a\b[^>]*>#i',
		static function ( $m ) use ( $url ) {
			$tag = $m[0];
			if ( ! preg_match( '#\bhref=(["\'])(.*?)\1#i', $tag, $h ) ) {
				return $tag;
			}
			if ( ! sdc_is_gofundme_host( html_entity_decode( $h[2] ) ) ) {
				return $tag;
			}
			if ( ! preg_match( '#\bclass=(["\'])[^"\']*(wp-block-button__link|sd-btn)#i', $tag ) ) {
				return $tag; // Buttons only; ordinary text links stay as written.
			}
			return str_replace( $h[0], 'href=' . $h[1] . esc_url( $url ) . $h[1], $tag );
		},
		$html
	);
}
add_filter( 'render_block_core/button', 'sdc_gofundme_button_link', 10, 2 );
