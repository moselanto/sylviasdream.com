<?php
/**
 * 0.4.4: "Test payment (no money)" checkout option for the TEST SITE ONLY.
 *
 * Lets a reviewer place a real WooCommerce order and receive the secure
 * download, without any money moving. It is registered only when:
 *  - Settings > Sylvia's Dream > "Test payments" is ticked, and
 *  - the site is not the live domain (sd_core_is_live_site() is false).
 * On sylviasdream.com the gateway class is never registered and the setting
 * cannot be saved as on, so it can never appear at checkout there.
 *
 * @package SylviasDreamCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Are test payments allowed on this request?
 *
 * @return bool
 */
function sd_core_test_payments_active() {
	if ( sd_core_is_live_site() ) {
		return false;
	}
	return 1 === (int) sd_core_opt( 'test_payments_enabled' );
}

/**
 * Define the gateway class once WooCommerce has loaded.
 */
function sd_core_test_gateway_class() {
	if ( ! class_exists( 'WC_Payment_Gateway' ) || class_exists( 'SD_Core_Test_Gateway' ) || ! sd_core_test_payments_active() ) {
		return;
	}

	/**
	 * Test gateway: completes the order with no payment.
	 */
	class SD_Core_Test_Gateway extends WC_Payment_Gateway {

		/**
		 * Set up.
		 */
		public function __construct() {
			$this->id                 = 'sd_test_payment';
			$this->method_title       = __( 'Test payment (no money)', 'sylviasdream-core' );
			$this->method_description = __( 'Test site only. Places the order and releases downloads without taking any payment. Never available on sylviasdream.com.', 'sylviasdream-core' );
			$this->has_fields         = false;
			$this->supports           = array( 'products' );
			$this->title              = __( 'Test payment (no money is taken)', 'sylviasdream-core' );
			$this->description        = __( 'Preview website only: this places a test order so you can check the email and the secure download. No card is needed and nothing is charged.', 'sylviasdream-core' );
			$this->enabled            = 'yes';
		}

		/**
		 * Only on the test site, whatever the stored settings say.
		 *
		 * @return bool
		 */
		public function is_available() {
			return sd_core_test_payments_active();
		}

		/**
		 * Complete the order without payment.
		 *
		 * @param int $order_id Order ID.
		 * @return array
		 */
		public function process_payment( $order_id ) {
			$order = wc_get_order( $order_id );
			if ( ! $order || ! sd_core_test_payments_active() ) {
				wc_add_notice( __( 'Test payments are not available.', 'sylviasdream-core' ), 'error' );
				return array( 'result' => 'failure' );
			}
			$order->add_order_note( __( 'TEST ORDER: placed with the test-site payment option. No money was taken.', 'sylviasdream-core' ) );
			$order->update_meta_data( '_sd_test_order', 'yes' );
			$order->payment_complete( 'sd-test-' . $order_id );
			// Ebook-only orders become Completed, so the download email is sent now.
			if ( 'processing' === $order->get_status() && ! $order->needs_processing() ) {
				$order->update_status( 'completed' );
			}
			if ( function_exists( 'WC' ) && WC()->cart ) {
				WC()->cart->empty_cart();
			}
			return array(
				'result'   => 'success',
				'redirect' => $this->get_return_url( $order ),
			);
		}
	}
}
add_action( 'plugins_loaded', 'sd_core_test_gateway_class', 20 );

/**
 * Register the gateway (test site only).
 *
 * @param string[] $gateways Gateway class names.
 * @return string[]
 */
function sd_core_register_test_gateway( $gateways ) {
	if ( sd_core_test_payments_active() && class_exists( 'SD_Core_Test_Gateway' ) ) {
		$gateways[] = 'SD_Core_Test_Gateway';
	}
	return $gateways;
}
add_filter( 'woocommerce_payment_gateways', 'sd_core_register_test_gateway' );

/**
 * Mark test orders in the order email subject so nobody mistakes them.
 *
 * @param string   $subject Subject.
 * @param WC_Order $order   Order.
 * @return string
 */
function sd_core_test_order_subject( $subject, $order ) {
	if ( $order instanceof WC_Order && 'yes' === $order->get_meta( '_sd_test_order' ) ) {
		return '[TEST, no payment] ' . $subject;
	}
	return $subject;
}
foreach ( array( 'customer_completed_order', 'customer_processing_order', 'new_order' ) as $sd_core_email_id ) {
	add_filter( 'woocommerce_email_subject_' . $sd_core_email_id, 'sd_core_test_order_subject', 20, 2 );
}
unset( $sd_core_email_id );
