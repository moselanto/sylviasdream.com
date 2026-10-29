<?php
/**
 * 1.20.0: cart, checkout and order-received polish (classic shortcodes).
 *
 * Presentation only: no payment gateway is added, removed or changed. For
 * eBook-only carts (nothing to ship) the checkout asks only for first name,
 * last name, email and country; WooCommerce needs no address for virtual
 * orders. Switch off in Customizer > Shop and book pages ("Checkout: for
 * eBook-only orders ...") or with the filter `sdc_checkout_short_form`.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Use the short eBook checkout form for this cart?
 *
 * @return bool
 */
function sdc_checkout_short_form() {
	$on = sdc_get_bool( 'checkout_short_form' ) && function_exists( 'sdc_cart_is_virtual' ) && sdc_cart_is_virtual();
	return (bool) apply_filters( 'sdc_checkout_short_form', $on );
}

/**
 * Trim address fields for eBook-only orders and order the rest clearly.
 *
 * @param array $fields Checkout fields.
 * @return array
 */
function sdc_checkout_fields( $fields ) {
	if ( ! sdc_checkout_short_form() || empty( $fields['billing'] ) ) {
		return $fields;
	}
	foreach ( array( 'billing_company', 'billing_address_1', 'billing_address_2', 'billing_city', 'billing_state', 'billing_postcode', 'billing_phone' ) as $key ) {
		unset( $fields['billing'][ $key ] );
	}
	$layout = array(
		'billing_first_name' => array( 10, array( 'form-row-first' ) ),
		'billing_last_name'  => array( 20, array( 'form-row-last' ) ),
		'billing_email'      => array( 30, array( 'form-row-wide' ) ),
		'billing_country'    => array( 40, array( 'form-row-wide', 'address-field', 'update_totals_on_change' ) ),
	);
	foreach ( $layout as $key => $conf ) {
		if ( isset( $fields['billing'][ $key ] ) ) {
			$fields['billing'][ $key ]['priority'] = $conf[0];
			$fields['billing'][ $key ]['class']    = $conf[1];
		}
	}
	if ( isset( $fields['billing']['billing_email'] ) ) {
		$fields['billing']['billing_email']['description'] = __( 'Your receipt and download link are sent here.', 'sylviasdream-child' );
	}
	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'sdc_checkout_fields', 20 );

/**
 * No order notes box for eBook-only orders.
 *
 * @param bool $enabled Enabled.
 * @return bool
 */
function sdc_checkout_order_notes( $enabled ) {
	return sdc_checkout_short_form() ? false : $enabled;
}
add_filter( 'woocommerce_enable_order_notes_field', 'sdc_checkout_order_notes', 20 );

/**
 * "Your details" heading instead of "Billing details" for eBook-only orders.
 *
 * @param string $translated Translated text.
 * @param string $text       Original text.
 * @param string $domain     Text domain.
 * @return string
 */
function sdc_checkout_heading_text( $translated, $text, $domain ) {
	if ( 'woocommerce' === $domain && 'Billing details' === $text && function_exists( 'is_checkout' ) && is_checkout() && sdc_checkout_short_form() ) {
		return __( 'Your details', 'sylviasdream-child' );
	}
	return $translated;
}
add_filter( 'gettext', 'sdc_checkout_heading_text', 20, 3 );

/**
 * Trust row (secure payment, instant download for eBooks). Customizer text.
 */
function sdc_trust_row() {
	$items = array();
	if ( sdc_get( 'trust_secure' ) ) {
		$items[] = array( 'lock', sdc_get( 'trust_secure' ) );
	}
	if ( sdc_get( 'trust_download' ) && function_exists( 'sdc_cart_has_download' ) && sdc_cart_has_download() ) {
		$items[] = array( 'download', sdc_get( 'trust_download' ) );
	}
	if ( ! $items ) {
		return;
	}
	echo '<ul class="sd-trust" aria-label="' . esc_attr__( 'Shopping with us', 'sylviasdream-child' ) . '">';
	foreach ( $items as $item ) {
		printf( '<li>%1$s<span>%2$s</span></li>', wp_kses( sdc_trust_icon( $item[0] ), sdc_svg_kses() ), esc_html( $item[1] ) );
	}
	echo '</ul>';
}
add_action( 'woocommerce_after_cart_totals', 'sdc_trust_row' );
add_action( 'woocommerce_checkout_after_order_review', 'sdc_trust_row' );

/**
 * Order summary heading wrapper class (classic checkout).
 *
 * @param array $classes Body classes.
 * @return array
 */
function sdc_checkout_body_class( $classes ) {
	if ( function_exists( 'is_checkout' ) && is_checkout() && sdc_checkout_short_form() ) {
		$classes[] = 'sd-checkout-ebook';
	}
	return $classes;
}
add_filter( 'body_class', 'sdc_checkout_body_class' );

/**
 * Download note on the order-received page (paid orders with downloads).
 *
 * @param int $order_id Order ID.
 */
function sdc_thankyou_download_note( $order_id ) {
	$order = wc_get_order( $order_id );
	$note  = sdc_get( 'thankyou_download_note' );
	if ( ! $order || '' === $note || ! $order->has_downloadable_item() || ! $order->is_download_permitted() ) {
		return;
	}
	printf(
		'<div class="sd-thankyou-note">%1$s<p>%2$s</p></div>',
		wp_kses( sdc_icon( 'download' ), sdc_svg_kses() ),
		esc_html( $note )
	);
}
add_action( 'woocommerce_before_thankyou', 'sdc_thankyou_download_note', 5 );
