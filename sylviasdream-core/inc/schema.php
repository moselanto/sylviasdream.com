<?php
/**
 * Structured data: Sylvia as author (Person), the website, Heaven on Earth
 * (Organization) and each WooCommerce book (Book).
 *
 * Only facts already published on the site are used. Everything is
 * filterable so it can be corrected without code changes to templates.
 *
 * @package SylviasDreamCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Person and WebSite graph on the home page.
 *
 * @return array<int,array<string,mixed>>
 */
function sd_core_schema_home() {
	$home   = home_url( '/' );
	$person = array(
		'@type'       => 'Person',
		'@id'         => $home . '#sylvia',
		'name'        => 'Sylvia Suhr',
		'url'         => $home,
		'jobTitle'    => 'Christian author, speaker and missionary',
		'description' => get_bloginfo( 'description' ),
		'sameAs'      => array_values( array_filter( array(
			function_exists( 'sdc_get' ) ? sdc_get( 'social_instagram' ) : '',
			function_exists( 'sdc_get' ) ? sdc_get( 'social_patreon' ) : '',
			function_exists( 'sdc_get' ) ? sdc_get( 'social_youtube' ) : '',
			function_exists( 'sdc_get' ) ? sdc_get( 'social_facebook' ) : '',
		) ) ),
	);
	$site = array(
		'@type'     => 'WebSite',
		'@id'       => $home . '#website',
		'url'       => $home,
		'name'      => get_bloginfo( 'name' ),
		'publisher' => array( '@id' => $home . '#sylvia' ),
	);
	$org = array(
		'@type'   => 'Organization',
		'@id'     => $home . '#heaven-on-earth',
		'name'    => (string) sd_core_opt( 'charity_name' ),
		'founder' => array( '@id' => $home . '#sylvia' ),
		'areaServed' => 'Kenya',
	);
	return array( $person, $site, $org );
}

/**
 * Book node for a WooCommerce product.
 *
 * @param int $product_id Product ID.
 * @return array<string,mixed>|null
 */
function sd_core_schema_book( $product_id ) {
	if ( ! function_exists( 'wc_get_product' ) ) {
		return null;
	}
	$product = wc_get_product( $product_id );
	if ( ! $product ) {
		return null;
	}
	$node = array(
		'@type'       => 'Book',
		'name'        => $product->get_name(),
		'url'         => get_permalink( $product_id ),
		'author'      => array( '@id' => home_url( '/' ) . '#sylvia' ),
		'description' => wp_strip_all_tags( $product->get_short_description() ? $product->get_short_description() : $product->get_description() ),
		'bookFormat'  => $product->is_downloadable() ? 'https://schema.org/EBook' : 'https://schema.org/Paperback',
	);
	$image = wp_get_attachment_image_url( $product->get_image_id(), 'large' );
	if ( $image ) {
		$node['image'] = $image;
	}
	// 0.1.2: a published book with a price also carries an Offer (price, currency,
	// availability), so search engines can show it as an ebook for sale.
	if ( 'publish' === $product->get_status() && '' !== (string) $product->get_price() ) {
		$node['offers'] = array(
			'@type'         => 'Offer',
			'price'         => wc_format_decimal( $product->get_price(), wc_get_price_decimals() ),
			'priceCurrency' => get_woocommerce_currency(),
			'availability'  => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
			'url'           => get_permalink( $product_id ),
		);
	}
	$node['inLanguage'] = get_bloginfo( 'language' );
	$node['@id']        = get_permalink( $product_id ) . '#book';
	return $node;
}

/**
 * Print JSON-LD.
 */
function sd_core_print_schema() {
	$graph = array();
	if ( is_front_page() ) {
		$graph = sd_core_schema_home();
	} elseif ( function_exists( 'is_product' ) && is_product() ) {
		$book = sd_core_schema_book( get_queried_object_id() );
		if ( $book ) {
			$graph[] = $book;
		}
	}
	$graph = (array) apply_filters( 'sd_core_schema_graph', $graph );
	if ( ! $graph ) {
		return;
	}
	echo "\n" . '<script type="application/ld+json">' . wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => $graph ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
add_action( 'wp_head', 'sd_core_print_schema', 20 );
