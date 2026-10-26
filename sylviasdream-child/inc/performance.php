<?php
/**
 * 1.17.0: front-end performance.
 *
 * Each feature is switchable with the filter `sdc_perf_feature`
 * ( bool $on, string $feature ). Feature keys:
 *  - min_assets      enqueue *.min.css / *.min.js when present and up to date
 *  - emoji           remove the emoji detection script and styles
 *  - preload         preload the brand font stylesheet and the LCP image
 *  - img_attrs       decoding="async" and correct sizes on content images
 *  - yt_facade       click-to-play YouTube facade (no YouTube scripts until clicked)
 *  - defer           defer/async a short list of non-critical third-party scripts
 *  - wc_assets       skip WooCommerce/WC Blocks CSS on pages with no shop content
 *
 * Nothing here changes saved content. Cart, checkout, My account and product
 * pages always keep every WooCommerce asset.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is a performance feature on?
 *
 * @param string $feature Feature key.
 * @return bool
 */
function sdc_perf_on( $feature ) {
	return (bool) apply_filters( 'sdc_perf_feature', true, $feature );
}

/**
 * URL of a theme asset, preferring the minified build (scripts/build-assets.js)
 * when it exists and is at least as new as the source. SCRIPT_DEBUG loads the
 * source.
 *
 * @param string $relative Path relative to the child theme, e.g. assets/js/nav.js.
 * @return array{0:string,1:string} URL and version.
 */
function sdc_asset( $relative ) {
	$relative = ltrim( $relative, '/' );
	$use      = $relative;
	if ( sdc_perf_on( 'min_assets' ) && ! ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ) {
		$min = preg_replace( '/\.(css|js)$/', '.min.$1', $relative );
		$src = SYLVIASDREAM_CHILD_DIR . '/' . $relative;
		$mfp = SYLVIASDREAM_CHILD_DIR . '/' . $min;
		if ( $min && file_exists( $mfp ) && ( ! file_exists( $src ) || filemtime( $mfp ) >= filemtime( $src ) ) ) {
			$use = $min;
		}
	}
	return array( get_theme_file_uri( $use ), sdc_asset_version( $use ) );
}

/* Emoji ---------------------------------------------------------------- */

/**
 * Remove the emoji script and styles (modern browsers draw emoji natively).
 */
function sdc_disable_emoji() {
	if ( ! sdc_perf_on( 'emoji' ) ) {
		return;
	}
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_enqueue_scripts', 'wp_enqueue_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	add_filter( 'emoji_svg_url', '__return_false' );
}
add_action( 'init', 'sdc_disable_emoji' );

/**
 * Drop the emoji CDN from resource hints.
 *
 * @param array  $urls Hints.
 * @param string $type Relation.
 * @return array
 */
function sdc_drop_emoji_hint( $urls, $type ) {
	if ( 'dns-prefetch' === $type && sdc_perf_on( 'emoji' ) ) {
		$urls = array_filter(
			$urls,
			function ( $u ) {
				$href = is_array( $u ) ? ( $u['href'] ?? '' ) : (string) $u;
				return false === strpos( $href, 's.w.org/images/core/emoji' );
			}
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'sdc_drop_emoji_hint', 10, 2 );

/* Preload -------------------------------------------------------------- */

/**
 * The Largest Contentful Paint image for this request, as attributes for a
 * preload link: home hero cover block, else the inner-page header photo.
 *
 * @return array{href:string,srcset:string,sizes:string}|null
 */
function sdc_lcp_image() {
	if ( is_front_page() && function_exists( 'sdc_front_page_has_blocks' ) && sdc_front_page_has_blocks() ) {
		$content = (string) get_post_field( 'post_content', (int) get_option( 'page_on_front' ) );
		foreach ( parse_blocks( $content ) as $block ) {
			if ( 'core/cover' !== ( $block['blockName'] ?? '' ) ) {
				continue;
			}
			$id  = isset( $block['attrs']['id'] ) ? (int) $block['attrs']['id'] : 0;
			$url = isset( $block['attrs']['url'] ) ? (string) $block['attrs']['url'] : '';
			if ( $id ) {
				$src = wp_get_attachment_image_url( $id, 'full' );
				return array(
					'href'   => $src ? $src : $url,
					'srcset' => (string) wp_get_attachment_image_srcset( $id, 'full' ),
					'sizes'  => '100vw',
				);
			}
			return $url ? array( 'href' => $url, 'srcset' => '', 'sizes' => '' ) : null;
		}
		return null;
	}
	if ( function_exists( 'sdc_is_inner_page' ) && sdc_is_inner_page() && function_exists( 'sdc_page_header_image_data' ) ) {
		$post = get_post( get_queried_object_id() );
		if ( $post && function_exists( 'sdc_content_starts_with_banner' ) && sdc_content_starts_with_banner( $post->post_content ) ) {
			return null; // The page draws its own banner.
		}
		$img = sdc_page_header_image_data();
		return $img['src'] ? array( 'href' => $img['src'], 'srcset' => $img['srcset'], 'sizes' => $img['srcset'] ? '100vw' : '' ) : null;
	}
	return null;
}

/**
 * Print preload links early in <head>.
 */
function sdc_preload_links() {
	if ( ! sdc_perf_on( 'preload' ) || is_admin() ) {
		return;
	}
	if ( sdc_get_bool( 'use_google_fonts' ) ) {
		printf( '<link rel="preload" as="style" href="%s">' . "\n", esc_url( sdc_google_fonts_url() ) );
	}
	$lcp = sdc_lcp_image();
	if ( $lcp && $lcp['href'] ) {
		printf(
			'<link rel="preload" as="image" href="%1$s"%2$s%3$s fetchpriority="high">' . "\n",
			esc_url( $lcp['href'] ),
			$lcp['srcset'] ? ' imagesrcset="' . esc_attr( $lcp['srcset'] ) . '"' : '',
			$lcp['sizes'] ? ' imagesizes="' . esc_attr( $lcp['sizes'] ) . '"' : ''
		);
	}
}
add_action( 'wp_head', 'sdc_preload_links', 2 );

/* Images --------------------------------------------------------------- */

/**
 * decoding="async" on content images that lack it (core adds loading="lazy"
 * below the fold and width/height from the attachment).
 *
 * @param string $tag     <img> tag.
 * @param string $context Context.
 * @return string
 */
function sdc_content_img_attrs( $tag, $context ) {
	if ( sdc_perf_on( 'img_attrs' ) && false === strpos( $tag, ' decoding=' ) ) {
		$tag = preg_replace( '/^<img\b/', '<img decoding="async"', $tag, 1 );
	}
	return $tag;
}
add_filter( 'wp_content_img_tag', 'sdc_content_img_attrs', 20, 2 );

/* YouTube facade ------------------------------------------------------- */

/**
 * Replace a YouTube embed iframe with a click-to-play facade. The original
 * iframe stays inside <noscript>, and the YouTube URL, title and aspect ratio
 * are kept, so the video plays exactly as before once clicked.
 *
 * @param string $content Rendered block.
 * @param array  $block   Block.
 * @return string
 */
function sdc_youtube_facade( $content, $block ) {
	if ( is_admin() || is_feed() || ! sdc_perf_on( 'yt_facade' ) || false === strpos( $content, '<iframe' ) ) {
		return $content;
	}
	$provider = isset( $block['attrs']['providerNameSlug'] ) ? (string) $block['attrs']['providerNameSlug'] : '';
	if ( 'youtube' !== $provider && false === strpos( $content, 'youtube' ) ) {
		return $content;
	}
	if ( ! preg_match( '#<iframe\b[^>]*\bsrc="(https?://(?:www\.)?youtube(?:-nocookie)?\.com/embed/([A-Za-z0-9_-]{6,20})[^"]*)"[^>]*>\s*</iframe>#i', $content, $m ) ) {
		return $content;
	}
	$iframe = $m[0];
	$src    = html_entity_decode( $m[1], ENT_QUOTES );
	$vid    = $m[2];
	$title  = preg_match( '/\btitle="([^"]*)"/i', $iframe, $t ) ? html_entity_decode( $t[1], ENT_QUOTES ) : __( 'Video', 'sylviasdream-child' );
	$play   = add_query_arg( 'autoplay', '1', $src );
	$facade = sprintf(
		'<div class="sd-yt" data-sd-yt="%1$s" data-sd-yt-title="%2$s"><button type="button" class="sd-yt__btn" aria-label="%3$s"><img class="sd-yt__thumb" src="%4$s" alt="" width="480" height="360" loading="lazy" decoding="async"><span class="sd-yt__play" aria-hidden="true"></span></button><noscript>%5$s</noscript></div>',
		esc_url( $play ),
		esc_attr( $title ),
		/* translators: %s: video title. */
		esc_attr( sprintf( __( 'Play video: %s', 'sylviasdream-child' ), $title ) ),
		esc_url( 'https://i.ytimg.com/vi/' . rawurlencode( $vid ) . '/hqdefault.jpg' ),
		$iframe
	);
	if ( ! wp_script_is( 'sylviasdream-child-yt', 'enqueued' ) ) {
		list( $url, $ver ) = sdc_asset( 'assets/js/yt-facade.js' );
		wp_enqueue_script( 'sylviasdream-child-yt', $url, array(), $ver, array( 'strategy' => 'defer', 'in_footer' => true ) );
	}
	return str_replace( $iframe, $facade, $content );
}
add_filter( 'render_block_core/embed', 'sdc_youtube_facade', 20, 2 );

/* Scripts and styles --------------------------------------------------- */

/**
 * Defer / async a few non-critical scripts. WordPress's loading-strategy API
 * keeps dependency order safe (it falls back to blocking when needed).
 */
function sdc_script_strategies() {
	if ( ! sdc_perf_on( 'defer' ) ) {
		return;
	}
	$handles = (array) apply_filters(
		'sdc_defer_script_handles',
		array(
			'comment-reply'        => 'async',
			'wc-order-attribution' => 'defer',
			'sourcebuster-js'      => 'defer',
		)
	);
	foreach ( $handles as $handle => $strategy ) {
		if ( wp_script_is( $handle, 'registered' ) && in_array( $strategy, array( 'defer', 'async' ), true ) ) {
			wp_script_add_data( $handle, 'strategy', $strategy );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'sdc_script_strategies', 99 );

/**
 * Does this request show any shop content?
 *
 * @return bool
 */
function sdc_request_needs_wc() {
	if ( ! function_exists( 'is_woocommerce' ) ) {
		return false;
	}
	if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() || is_front_page() || is_search() ) {
		return true;
	}
	if ( is_singular() ) {
		$post = get_post();
		if ( $post && ( false !== strpos( $post->post_content, 'wp:woocommerce/' ) || preg_match( '/\[(products|product|product_page|product_category|add_to_cart|woocommerce_[a-z_]+)\b/', $post->post_content ) ) ) {
			return true;
		}
		$books = function_exists( 'sdc_shop_url' ) ? sdc_url_path( sdc_shop_url() ) : '';
		if ( $books && sdc_url_path( (string) get_permalink( $post ) ) === $books ) {
			return true;
		}
	}
	return (bool) apply_filters( 'sdc_request_needs_wc', false );
}

/**
 * Skip WooCommerce core/blocks stylesheets on pages with no shop content
 * (blog posts, ordinary pages). The theme's own woocommerce.css, the cart
 * count and the cart-fragments script stay, so the header cart keeps working.
 */
function sdc_trim_wc_assets() {
	if ( ! sdc_perf_on( 'wc_assets' ) || sdc_request_needs_wc() || ! function_exists( 'is_woocommerce' ) ) {
		return;
	}
	foreach ( array( 'woocommerce-general', 'woocommerce-layout', 'woocommerce-smallscreen', 'wc-blocks-style', 'wc-blocks-vendors-style' ) as $handle ) {
		wp_dequeue_style( $handle );
	}
}
add_action( 'wp_enqueue_scripts', 'sdc_trim_wc_assets', 100 );
