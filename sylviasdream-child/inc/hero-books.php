<?php
/**
 * 1.15.0: home hero book covers.
 *
 * Fills the right-hand side of the home hero (the cover block with class
 * "sd-hero-cover") with three of Sylvia's book covers, each linking to its
 * book page. Covers come from the book pages themselves (the image in their
 * "sd-book-cover" block), so nothing is hard-coded to one site's media IDs.
 * Shown on wide screens only (see style.css). Filter `sdc_hero_book_pages`
 * to change the pages (slugs, middle one shown largest).
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Cover image ID from a book page's "sd-book-cover" image block.
 *
 * @param WP_Post $page Book page.
 * @return int Attachment ID, or 0.
 */
function sdc_book_page_cover_id( $page ) {
	$content = (string) $page->post_content;
	if ( preg_match( '/<figure[^>]*sd-book-cover[^>]*>.*?wp-image-(\d+)/s', $content, $m ) ) {
		return (int) $m[1];
	}
	if ( has_post_thumbnail( $page ) ) {
		return (int) get_post_thumbnail_id( $page );
	}
	return 0;
}

/**
 * 1.23.13: published product whose name starts with $name and has an image.
 *
 * @param string $name Start of the product name.
 * @return WC_Product|null
 */
function sdc_hero_book_product( $name ) {
	if ( '' === $name || ! function_exists( 'wc_get_products' ) ) {
		return null;
	}
	foreach ( wc_get_products( array( 'status' => 'publish', 'limit' => 10, 's' => $name ) ) as $product ) {
		if ( 0 === stripos( $product->get_name(), $name ) && $product->get_image_id() ) {
			return $product;
		}
	}
	return null;
}

/**
 * HTML for the hero covers, or '' when fewer than two covers are found.
 *
 * @return string
 */
function sdc_hero_books_html() {
	$slugs = (array) apply_filters( 'sdc_hero_book_pages', array( 'from-fear-to-freedom-3', 'one-life', 'sent' ) );
	// 1.23.13: product to use when a book page has no "sd-book-cover" image
	// (the older live pages), matched by the start of the product name.
	$names = (array) apply_filters(
		'sdc_hero_book_products',
		array(
			'from-fear-to-freedom-3' => 'From Fear to Freedom',
			'one-life'               => 'One Life',
			'sent'                   => 'SENT',
		)
	);
	$items = array();
	foreach ( $slugs as $slug ) {
		$page   = get_page_by_path( sanitize_title( $slug ) );
		$page   = ( $page && 'publish' === $page->post_status ) ? $page : null;
		$img_id = $page ? sdc_book_page_cover_id( $page ) : 0;
		$url    = $page ? get_permalink( $page ) : '';
		$title  = $page ? get_the_title( $page ) : '';
		if ( ! $img_id && ! empty( $names[ $slug ] ) ) {
			$product = sdc_hero_book_product( (string) $names[ $slug ] );
			if ( $product ) {
				$img_id = (int) $product->get_image_id();
				$url    = $url ? $url : $product->get_permalink();
				$title  = $title ? $title : $product->get_name();
			}
		}
		if ( ! $img_id || ! $url ) {
			continue;
		}
		$items[] = array(
			'url'   => $url,
			'title' => $title,
			'img'   => $img_id,
		);
	}
	if ( count( $items ) < 2 ) {
		return '';
	}
	$html = '<div class="sd-hero-books sd-hero-books--' . count( $items ) . '">';
	foreach ( array_values( $items ) as $i => $item ) {
		$html .= sprintf(
			'<a class="sd-hero-book sd-hero-book--%1$d" href="%2$s" aria-label="%3$s">%4$s</a>',
			$i + 1,
			esc_url( $item['url'] ),
			esc_attr( wp_strip_all_tags( $item['title'] ) ),
			wp_get_attachment_image(
				$item['img'],
				'large',
				false,
				array(
					'loading'  => 'eager',
					'decoding' => 'async',
					'alt'      => '',
					'sizes'    => '(min-width: 1100px) 240px, 1px',
				)
			)
		);
	}
	return $html . '</div>';
}

/**
 * Add the covers inside the home hero cover block.
 *
 * @param string $content Rendered block.
 * @param array  $block   Block data.
 * @return string
 */
function sdc_hero_books_render( $content, $block ) {
	if ( is_admin() || ! is_front_page() ) {
		return $content;
	}
	$class = isset( $block['attrs']['className'] ) ? (string) $block['attrs']['className'] : '';
	if ( false === strpos( $class, 'sd-hero-cover' ) || false !== strpos( $content, 'sd-hero-books' ) ) {
		return $content;
	}
	// 1.16.0: the hero photo is the largest thing on the first screen; load it first (handoff: LCP).
	$content = preg_replace( '/<img(?![^>]*fetchpriority)([^>]*wp-block-cover__image-background)/', '<img fetchpriority="high" decoding="async"$1', $content, 1 );
	$content = preg_replace( '/(<img[^>]*wp-block-cover__image-background[^>]*?)\sloading="lazy"/', '$1', $content, 1 );
	$books   = sdc_hero_books_html();
	$pos   = strrpos( $content, '</div>' );
	if ( '' === $books || false === $pos ) {
		return $content;
	}
	return substr( $content, 0, $pos ) . $books . substr( $content, $pos );
}
add_filter( 'render_block_core/cover', 'sdc_hero_books_render', 10, 2 );
