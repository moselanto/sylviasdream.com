<?php
/**
 * 1.16.1: automatic alt text for book covers that have none.
 *
 * - A cover image block (class "sd-book-cover") with an empty alt gets
 *   "Book cover of <page title> by Sylvia Suhr".
 * - A WooCommerce product image with an empty alt gets
 *   "Book cover of <product name> by Sylvia Suhr".
 * Alt text typed in the Media Library or the block always wins. The title is
 * the page or product name already on the site, so no new wording is invented.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Alt text for a cover, from a title.
 *
 * @param string $title Book or page title.
 * @return string
 */
function sdc_cover_alt( $title ) {
	$title = trim( wp_strip_all_tags( (string) $title ) );
	if ( '' === $title ) {
		return '';
	}
	/* translators: %s: book title. */
	return sprintf( __( 'Book cover of %s by Sylvia Suhr', 'sylviasdream-child' ), $title );
}

/**
 * Cover image blocks with an empty alt.
 *
 * @param string $content Rendered block.
 * @param array  $block   Block data.
 * @return string
 */
function sdc_cover_block_alt( $content, $block ) {
	$class = isset( $block['attrs']['className'] ) ? (string) $block['attrs']['className'] : '';
	if ( false === strpos( $class, 'sd-book-cover' ) || ! is_singular() ) {
		return $content;
	}
	$alt = sdc_cover_alt( get_the_title( get_queried_object_id() ) );
	if ( '' === $alt ) {
		return $content;
	}
	// Empty alt attribute, or no alt attribute at all, on the first <img>.
	if ( preg_match( '/<img\b[^>]*\balt=""/', $content ) ) {
		return preg_replace( '/(<img\b[^>]*\b)alt=""/', '$1alt="' . esc_attr( $alt ) . '"', $content, 1 );
	}
	if ( ! preg_match( '/<img\b[^>]*\balt=/', $content ) ) {
		return preg_replace( '/<img\b/', '<img alt="' . esc_attr( $alt ) . '"', $content, 1 );
	}
	return $content;
}
add_filter( 'render_block_core/image', 'sdc_cover_block_alt', 10, 2 );

/**
 * Product images (shop grid, product page, cart) with an empty alt.
 *
 * @param array        $attr       Image attributes.
 * @param WP_Post      $attachment Attachment.
 * @return array
 */
function sdc_product_image_alt( $attr, $attachment ) {
	if ( ! empty( $attr['alt'] ) || ! $attachment instanceof WP_Post ) {
		return $attr;
	}
	$parent_id = (int) $attachment->post_parent;
	$product   = null;
	if ( $parent_id && 'product' === get_post_type( $parent_id ) ) {
		$product = $parent_id;
	} elseif ( 'product' === get_post_type() && (int) get_post_thumbnail_id() === (int) $attachment->ID ) {
		$product = get_the_ID();
	}
	if ( $product ) {
		$alt = sdc_cover_alt( get_the_title( $product ) );
		if ( '' !== $alt ) {
			$attr['alt'] = $alt;
		}
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'sdc_product_image_alt', 10, 2 );
