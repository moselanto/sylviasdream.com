<?php
/**
 * 1.20.0: side cart drawer (mini cart).
 *
 * Opens after a book is added (AJAX, on shop cards and the single book page)
 * and when the header cart icon is clicked. Uses only WooCommerce's own
 * wc-ajax endpoints (add_to_cart, remove_from_cart, get_refreshed_fragments)
 * and the standard cart fragments filter: no custom AJAX handler, no outside
 * services. Without JavaScript the cart icon and remove links still work as
 * plain links.
 *
 * Switch off in Customizer > Sylvia's Dream > Shop and book pages, or with the
 * filter `sdc_cart_drawer_enabled`.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is the drawer used on this request? Not on the cart and checkout pages,
 * where the full cart is already on screen.
 *
 * @return bool
 */
function sdc_cart_drawer_enabled() {
	$on = sdc_get_bool( 'cart_drawer' );
	if ( ! function_exists( 'WC' ) || is_admin() ) {
		$on = false;
	} elseif ( function_exists( 'is_cart' ) && ( is_cart() || is_checkout() ) ) {
		$on = false;
	}
	return (bool) apply_filters( 'sdc_cart_drawer_enabled', $on );
}

/**
 * Does the cart hold only items that need no shipping (eBooks)?
 *
 * @return bool
 */
function sdc_cart_is_virtual() {
	return function_exists( 'WC' ) && WC()->cart && ! WC()->cart->is_empty() && ! WC()->cart->needs_shipping();
}

/**
 * Does the cart hold at least one downloadable item?
 *
 * @return bool
 */
function sdc_cart_has_download() {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return false;
	}
	foreach ( WC()->cart->get_cart() as $item ) {
		if ( ! empty( $item['data'] ) && $item['data'] instanceof WC_Product && $item['data']->is_downloadable() ) {
			return true;
		}
	}
	return false;
}

/**
 * Drawer content (items, subtotal, buttons or the empty state). This element
 * is also the WooCommerce fragment, so it is replaced after every change.
 *
 * @return string
 */
function sdc_cart_drawer_content() {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return '<div class="sd-minicart__content"></div>';
	}
	$cart = WC()->cart;
	ob_start();
	echo '<div class="sd-minicart__content">';
	if ( $cart->is_empty() ) {
		?>
		<div class="sd-minicart__empty">
			<span class="sd-minicart__empty-icon"><?php echo wp_kses( sdc_icon( 'book' ), sdc_svg_kses() ); ?></span>
			<p><?php esc_html_e( 'Your cart is empty.', 'sylviasdream-child' ); ?></p>
			<a class="sd-btn" href="<?php echo esc_url( sdc_shop_url() ); ?>"><?php esc_html_e( 'Browse the books', 'sylviasdream-child' ); ?></a>
		</div>
		<?php
	} else {
		echo '<ul class="sd-minicart__items">';
		foreach ( $cart->get_cart() as $key => $item ) {
			$product = isset( $item['data'] ) ? $item['data'] : null;
			if ( ! $product instanceof WC_Product || ! $product->exists() || $item['quantity'] <= 0 ) {
				continue;
			}
			if ( ! apply_filters( 'woocommerce_widget_cart_item_visible', true, $item, $key ) ) { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals -- WooCommerce filter.
				continue;
			}
			$name  = apply_filters( 'woocommerce_cart_item_name', $product->get_name(), $item, $key ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals -- WooCommerce filter.
			$plain = wp_strip_all_tags( $product->get_name() );
			$link  = $product->is_visible() ? $product->get_permalink( $item ) : '';
			$qty   = (int) $item['quantity'];
			$type  = $product->is_downloadable() ? __( 'eBook', 'sylviasdream-child' ) : '';
			?>
			<li class="sd-minicart__item">
				<span class="sd-minicart__cover">
					<?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail', array( 'alt' => '', 'loading' => 'lazy' ) ) ); ?>
				</span>
				<span class="sd-minicart__info">
					<?php if ( $link ) : ?>
						<a class="sd-minicart__title" href="<?php echo esc_url( $link ); ?>"><?php echo wp_kses_post( $name ); ?></a>
					<?php else : ?>
						<span class="sd-minicart__title"><?php echo wp_kses_post( $name ); ?></span>
					<?php endif; ?>
					<span class="sd-minicart__meta">
						<?php if ( $type ) : ?>
							<span class="sd-minicart__type"><?php echo esc_html( $type ); ?></span>
						<?php endif; ?>
						<?php if ( $qty > 1 ) : ?>
							<span class="sd-minicart__qty"><?php echo esc_html( sprintf( /* translators: %d: quantity. */ __( 'Qty %d', 'sylviasdream-child' ), $qty ) ); ?></span>
						<?php endif; ?>
					</span>
					<span class="sd-minicart__price"><?php echo wp_kses_post( $cart->get_product_subtotal( $product, $qty ) ); ?></span>
				</span>
				<a class="sd-minicart__remove" href="<?php echo esc_url( wc_get_cart_remove_url( $key ) ); ?>" data-cart_item_key="<?php echo esc_attr( $key ); ?>">
					<?php echo wp_kses( sdc_icon( 'close' ), sdc_svg_kses() ); ?>
					<span class="screen-reader-text"><?php echo esc_html( sprintf( /* translators: %s: book title. */ __( 'Remove %s from the cart', 'sylviasdream-child' ), $plain ) ); ?></span>
				</a>
			</li>
			<?php
		}
		echo '</ul>';
		$note = sdc_cart_is_virtual() ? sdc_get( 'cart_drawer_note' ) : '';
		?>
		<div class="sd-minicart__foot">
			<p class="sd-minicart__subtotal"><span><?php esc_html_e( 'Subtotal', 'sylviasdream-child' ); ?></span><strong><?php echo wp_kses_post( $cart->get_cart_subtotal() ); ?></strong></p>
			<?php if ( $note ) : ?>
				<p class="sd-minicart__note"><?php echo wp_kses( sdc_icon( 'download' ), sdc_svg_kses() ); ?><span><?php echo esc_html( $note ); ?></span></p>
			<?php endif; ?>
			<div class="sd-minicart__buttons">
				<a class="sd-btn sd-btn--ghost" href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php esc_html_e( 'View cart', 'sylviasdream-child' ); ?></a>
				<a class="sd-btn" href="<?php echo esc_url( wc_get_checkout_url() ); ?>"><?php esc_html_e( 'Checkout', 'sylviasdream-child' ); ?></a>
			</div>
			<?php if ( sdc_get( 'trust_secure' ) ) : ?>
				<p class="sd-minicart__secure"><?php echo wp_kses( sdc_trust_icon( 'lock' ), sdc_svg_kses() ); ?><span><?php echo esc_html( sdc_get( 'trust_secure' ) ); ?></span></p>
			<?php endif; ?>
		</div>
		<?php
	}
	echo '</div>';
	return (string) ob_get_clean();
}

/**
 * Small icons used by the drawer and checkout trust row.
 *
 * @param string $name lock|check.
 * @return string
 */
function sdc_trust_icon( $name ) {
	$paths = array(
		'lock'  => '<rect x="5" y="10.5" width="14" height="10" rx="2"/><path d="M8 10.5V7.5a4 4 0 0 1 8 0v3"/>',
		'check' => '<path d="M5 12.5l4.5 4.5L19 7.5"/>',
	);
	if ( ! isset( $paths[ $name ] ) ) {
		return sdc_icon( $name );
	}
	return '<svg class="sd-icon" aria-hidden="true" focusable="false" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $paths[ $name ] . '</svg>';
}

/**
 * Drawer shell, printed once in the footer.
 */
function sdc_cart_drawer_markup() {
	if ( ! sdc_cart_drawer_enabled() ) {
		return;
	}
	?>
	<div id="sd-minicart" class="sd-minicart" hidden>
		<div class="sd-minicart__backdrop" data-sd-minicart-close></div>
		<div class="sd-minicart__panel" role="dialog" aria-modal="true" aria-labelledby="sd-minicart-title" tabindex="-1">
			<div class="sd-minicart__head">
				<h2 id="sd-minicart-title" class="sd-minicart__heading"><?php echo wp_kses( sdc_icon( 'cart' ), sdc_svg_kses() ); ?><span><?php esc_html_e( 'Your cart', 'sylviasdream-child' ); ?></span></h2>
				<button type="button" class="sd-iconbtn sd-minicart__close" data-sd-minicart-close>
					<?php echo wp_kses( sdc_icon( 'close' ), sdc_svg_kses() ); ?>
					<span class="screen-reader-text"><?php esc_html_e( 'Close cart', 'sylviasdream-child' ); ?></span>
				</button>
			</div>
			<p class="sd-minicart__status" role="status" aria-live="polite"></p>
			<?php echo sdc_cart_drawer_content(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped inside. ?>
		</div>
	</div>
	<?php
}
add_action( 'wp_footer', 'sdc_cart_drawer_markup', 5 );

/**
 * Keep the drawer content current through WooCommerce fragments.
 *
 * @param array $fragments Fragments.
 * @return array
 */
function sdc_cart_drawer_fragment( $fragments ) {
	$fragments['div.sd-minicart__content'] = sdc_cart_drawer_content();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'sdc_cart_drawer_fragment' );

/**
 * Drawer script and its settings.
 */
function sdc_cart_drawer_assets() {
	if ( ! sdc_cart_drawer_enabled() || ! class_exists( 'WC_AJAX' ) ) {
		return;
	}
	list( $url, $ver ) = sdc_asset( 'assets/js/cart-drawer.js' );
	wp_enqueue_script(
		'sylviasdream-child-cart-drawer',
		$url,
		array(),
		$ver,
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);
	$data = array(
		'endpoint'      => WC_AJAX::get_endpoint( '%%endpoint%%' ),
		'cartUrl'       => wc_get_cart_url(),
		'redirectToCart' => ( 'yes' === get_option( 'woocommerce_cart_redirect_after_add' ) ),
		'singleAjax'    => (bool) apply_filters( 'sdc_single_ajax_add_to_cart', true ),
		'i18n'          => array(
			'added'   => __( 'Added to your cart.', 'sylviasdream-child' ),
			'removed' => __( 'Removed from your cart.', 'sylviasdream-child' ),
			'adding'  => __( 'Adding to cart', 'sylviasdream-child' ),
		),
	);
	wp_add_inline_script(
		'sylviasdream-child-cart-drawer',
		'window.SDC_MINICART = ' . wp_json_encode( $data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES ) . ';',
		'before'
	);
}
add_action( 'wp_enqueue_scripts', 'sdc_cart_drawer_assets', 30 );
