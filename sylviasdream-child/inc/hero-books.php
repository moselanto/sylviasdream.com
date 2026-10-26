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
 * HTML for the hero covers, or '' when fewer than two covers are found.
 *
 * @return string
 */
function sdc_hero_books_html() {
	$slugs = (array) apply_filters( 'sdc_hero_book_pages', array( 'from-fear-to-freedom-3', 'one-life', 'sent' ) );
	$items = array();
	foreach ( $slugs as $slug ) {
		$page = get_page_by_path( sanitize_title( $slug ) );
		if ( ! $page || 'publish' !== $page->post_status ) {
			continue;
		}
		$img_id = sdc_book_page_cover_id( $page );
		if ( ! $img_id ) {
			continue;
		}
		$items[] = array(
			'url'   => get_permalink( $page ),
			'title' => get_the_title( $page ),
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
