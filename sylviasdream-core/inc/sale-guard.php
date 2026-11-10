<?php
/**
 * 0.4.5: sale guard for ebooks.
 *
 * A paid ebook (downloadable or virtual product) that has no price, or no
 * download file, cannot be added to the cart or bought. This stops a reader
 * paying for a book whose file has not been attached yet. The theme shows
 * "Coming soon" for these books (child 1.23.8); the Stripe "Buy ebook"
 * button and Amazon buttons are never touched by this guard.
 *
 * Free books (theme meta _sdc_free_download) are left alone.
 * Switch off: add_filter( 'sd_core_sale_guard_enabled', '__return_false' );
 * or Settings > Sylvia's Dream > "Coming soon guard".
 *
 * @package SylviasDreamCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is the guard on?
 *
 * @return bool
 */
function sd_core_sale_guard_enabled() {
	$on = 1 === (int) sd_core_opt( 'sale_guard_enabled' );
	return (bool) apply_filters( 'sd_core_sale_guard_enabled', $on );
}

/**
 * Why a paid ebook cannot be sold yet.
 *
 * @param WC_Product|null $product Product.
 * @return string[] Empty when ready; otherwise any of 'price', 'file'.
 */
function sd_core_ebook_missing( $product ) {
	if ( ! $product instanceof WC_Product || ! $product->is_type( 'simple' ) ) {
		return array();
	}
	if ( ! $product->is_downloadable() && ! $product->is_virtual() ) {
		return array();
	}
	if ( '' !== (string) $product->get_meta( '_sdc_free_download' ) ) {
		return array();
	}
	$missing = array();
	$price   = (string) $product->get_price( 'edit' );
	if ( '' === $price || (float) $price <= 0 ) {
		$missing[] = 'price';
	}
	$has_file = false;
	if ( $product->is_downloadable() ) {
		foreach ( (array) $product->get_downloads( 'edit' ) as $download ) {
			if ( is_object( $download ) && method_exists( $download, 'get_file' ) && '' !== trim( (string) $download->get_file() ) ) {
				$has_file = true;
				break;
			}
		}
	}
	if ( ! $has_file ) {
		$missing[] = 'file';
	}
	/**
	 * Filter the missing list for one product.
	 *
	 * @param string[]   $missing Missing parts.
	 * @param WC_Product $product Product.
	 */
	return (array) apply_filters( 'sd_core_ebook_missing', $missing, $product );
}

/**
 * Should this book show "Coming soon" instead of being buyable?
 *
 * @param WC_Product|null $product Product.
 * @return bool
 */
function sd_core_ebook_coming_soon( $product ) {
	return sd_core_sale_guard_enabled() && array() !== sd_core_ebook_missing( $product );
}

/**
 * Not purchasable while something is missing. Runs late (priority 40) so no
 * other filter can turn it back on.
 *
 * @param bool       $purchasable Purchasable.
 * @param WC_Product $product     Product.
 * @return bool
 */
function sd_core_sale_guard_purchasable( $purchasable, $product ) {
	if ( $purchasable && sd_core_ebook_coming_soon( $product ) ) {
		return false;
	}
	return $purchasable;
}
add_filter( 'woocommerce_is_purchasable', 'sd_core_sale_guard_purchasable', 40, 2 );

/**
 * Remind editors on the product screen why a book shows "Coming soon".
 */
function sd_core_sale_guard_admin_notice() {
	if ( ! function_exists( 'get_current_screen' ) || ! function_exists( 'wc_get_product' ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || 'product' !== $screen->id ) {
		return;
	}
	$post_id = isset( $_GET['post'] ) ? absint( wp_unslash( $_GET['post'] ) ) : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read only.
	if ( ! $post_id || ! current_user_can( 'edit_post', $post_id ) || ! sd_core_sale_guard_enabled() ) {
		return;
	}
	$missing = sd_core_ebook_missing( wc_get_product( $post_id ) );
	if ( ! $missing ) {
		return;
	}
	$parts = array();
	if ( in_array( 'price', $missing, true ) ) {
		$parts[] = __( 'a price', 'sylviasdream-core' );
	}
	if ( in_array( 'file', $missing, true ) ) {
		$parts[] = __( 'a download file', 'sylviasdream-core' );
	}
	printf(
		'<div class="notice notice-warning"><p>%s</p></div>',
		esc_html(
			sprintf(
				/* translators: %s: missing parts, e.g. "a price and a download file". */
				__( 'This ebook shows "Coming soon" and cannot be bought until it has %s.', 'sylviasdream-core' ),
				implode( __( ' and ', 'sylviasdream-core' ), $parts )
			)
		)
	);
}
add_action( 'admin_notices', 'sd_core_sale_guard_admin_notice' );
