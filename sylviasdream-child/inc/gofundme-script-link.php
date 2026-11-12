<?php
/**
 * 1.23.16: remove the stray GoFundMe script address shown as text on the page.
 *
 * The Heaven on Earth page's saved GoFundMe block ends with
 * <script src="https://www.gofundme.com/static/js/embed.js">. WordPress.com
 * does not keep that tag; on live it was shown as a visible text link to the
 * script file. The theme already loads the same script properly
 * (sdc_enqueue_gofundme()), so the visible link is removed from the rendered
 * page only. Saved content is not changed.
 *
 * Only the script address itself is removed (as a link, a bare address or a
 * leftover script tag). The GoFundMe widget (div.gfm-embed), the donation
 * buttons (gofund.me / gofundme.com/f/... links) and the Dream Partner link
 * are not touched. Switch off with the filter sdc_strip_gofundme_script_link.
 *
 * @package sylviasdream-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Remove the GoFundMe embed.js address from rendered HTML.
 *
 * @param string $html Rendered HTML.
 * @return string
 */
function sdc_strip_gofundme_script_link( $html ) {
	if ( ! is_string( $html ) || false === stripos( $html, 'static/js/embed.js' ) ) {
		return $html;
	}
	if ( ! apply_filters( 'sdc_strip_gofundme_script_link', true ) ) {
		return $html;
	}
	$addr = '(?:https?:)?//(?:www\.)?gofundme\.com/static/js/embed\.js[^"\'\s<]*';
	$had_widget = false !== strpos( $html, 'gfm-embed' );
	$out = $html;
	// A paragraph holding nothing but the link (or the address).
	$out = preg_replace( '#<p\b[^>]*>\s*(?:<a\b[^>]*href=["\']' . $addr . '["\'][^>]*>.*?</a>|' . $addr . ')\s*</p>#is', '', $out );
	// The link itself.
	$out = preg_replace( '#<a\b[^>]*href=["\']' . $addr . '["\'][^>]*>.*?</a>#is', '', $out );
	// A script tag left in the content (the theme loads the file instead).
	$out = preg_replace( '#<script\b[^>]*src=["\']' . $addr . '["\'][^>]*>\s*</script>#is', '', $out );
	// A bare address left as text (not inside an attribute).
	$out = preg_replace( '#(?<![="\'/\w])' . $addr . '#i', '', $out );
	if ( ! is_string( $out ) ) {
		return $html;
	}
	if ( $had_widget && $out !== $html && function_exists( 'sdc_enqueue_gofundme' ) ) {
		sdc_enqueue_gofundme();
	}
	return $out;
}
add_filter( 'the_content', 'sdc_strip_gofundme_script_link', 99 );
add_filter( 'render_block', 'sdc_strip_gofundme_script_link', 99 );
add_filter( 'elementor/frontend/the_content', 'sdc_strip_gofundme_script_link', 99 );
add_filter( 'elementor/widget/render_content', 'sdc_strip_gofundme_script_link', 99 );
