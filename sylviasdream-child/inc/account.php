<?php
/**
 * 1.23.0: professional sign-in and My account area (WooCommerce).
 *
 * Presentation only. The WooCommerce template overrides live in
 * woocommerce/myaccount/ and keep every hook, nonce and field name. This file
 * holds their helpers, the account stylesheet and script (loaded only on the
 * My account page and its endpoints), friendlier menu labels and body classes.
 *
 * Not here on purpose (already done by the core plugin, sylviasdream-core):
 * generic sign-in errors on wp-login.php and My account (auth-hardening.php,
 * key `login_errors`) and the branded wp-login.php screens (login.php, key
 * `login_brand`). WooCommerce itself keeps the account page out of page caches.
 *
 * Switch the whole layer off with the `sdc_perf_feature` filter, key
 * `account_polish` (templates then fall back to plain WooCommerce styling).
 * Menu labels: filter `sdc_account_menu_labels`.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is this the My account page (any endpoint, shortcode or Elementor-built)?
 *
 * @return bool
 */
function sdc_is_account_area() {
	return function_exists( 'is_account_page' ) && is_account_page();
}

/**
 * Account stylesheet and script, only on the account page.
 */
function sdc_account_assets() {
	if ( ! sdc_is_account_area() || ! sdc_perf_on( 'account_polish' ) ) {
		return;
	}
	$deps = array( 'sylviasdream-child' );
	foreach ( array( 'sylviasdream-child-shop', 'sylviasdream-child-button-safety', 'elementor-frontend' ) as $handle ) {
		if ( wp_style_is( $handle, 'registered' ) || wp_style_is( $handle, 'enqueued' ) ) {
			$deps[] = $handle;
		}
	}
	list( $url, $ver ) = sdc_asset( 'assets/css/account.css' );
	wp_enqueue_style( 'sylviasdream-child-account', $url, $deps, $ver );

	list( $url, $ver ) = sdc_asset( 'assets/js/account.js' );
	wp_enqueue_script(
		'sylviasdream-child-account',
		$url,
		array(),
		$ver,
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'sdc_account_assets', 100 );

/**
 * Body classes: sd-account on every account screen, sd-account-auth on the
 * signed-out screens (sign in, lost password, reset password).
 *
 * @param string[] $classes Classes.
 * @return string[]
 */
function sdc_account_body_class( $classes ) {
	if ( ! sdc_is_account_area() || ! sdc_perf_on( 'account_polish' ) ) {
		return $classes;
	}
	$classes[] = 'sd-account';
	if ( ! is_user_logged_in() || ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'lost-password' ) ) ) {
		$classes[] = 'sd-account-auth';
	}
	return $classes;
}
add_filter( 'body_class', 'sdc_account_body_class' );

/**
 * Clearer menu labels. Only labels change; no endpoint is removed and the
 * order stays as WooCommerce (or another plugin) set it.
 *
 * @param array<string,string> $items Endpoint => label.
 * @return array<string,string>
 */
function sdc_account_menu_items( $items ) {
	if ( ! is_array( $items ) ) {
		return $items;
	}
	$labels = (array) apply_filters(
		'sdc_account_menu_labels',
		array(
			'downloads'       => __( 'My ebooks', 'sylviasdream-child' ),
			'customer-logout' => __( 'Sign out', 'sylviasdream-child' ),
		)
	);
	foreach ( $labels as $endpoint => $label ) {
		if ( isset( $items[ $endpoint ] ) && is_string( $label ) && '' !== $label ) {
			$items[ $endpoint ] = $label;
		}
	}
	return $items;
}
add_filter( 'woocommerce_account_menu_items', 'sdc_account_menu_items', 20 );

/**
 * The Downloads page title matches its menu label.
 *
 * @param string $title Title.
 * @return string
 */
function sdc_account_downloads_title( $title ) {
	$items = function_exists( 'wc_get_account_menu_items' ) ? wc_get_account_menu_items() : array();
	return isset( $items['downloads'] ) ? $items['downloads'] : $title;
}
add_filter( 'woocommerce_endpoint_downloads_title', 'sdc_account_downloads_title' );

/**
 * Decorative icon for an account section (inline SVG, aria-hidden).
 *
 * @param string $endpoint Endpoint key.
 * @return string
 */
function sdc_account_icon( $endpoint ) {
	$paths = array(
		'dashboard'       => '<path d="M3 11l9-7 9 7"/><path d="M5 10v10h14V10"/><path d="M10 20v-5h4v5"/>',
		'orders'          => '<rect x="5" y="3" width="14" height="18" rx="2"/><path d="M9 8h6M9 12h6M9 16h4"/>',
		'downloads'       => '<path d="M4 5a2 2 0 0 1 2-2h13v16H6a2 2 0 0 0-2 2z"/><path d="M4 19V5"/><path d="M12 7v6M9.5 10.5L12 13l2.5-2.5"/>',
		'edit-address'    => '<path d="M12 21s-6-5.5-6-11a6 6 0 0 1 12 0c0 5.5-6 11-6 11z"/><circle cx="12" cy="10" r="2.2"/>',
		'payment-methods' => '<rect x="3" y="6" width="18" height="12" rx="2"/><path d="M3 10h18M7 15h3"/>',
		'edit-account'    => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
		'customer-logout' => '<path d="M14 4h4a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2h-4"/><path d="M10 16l-4-4 4-4M6 12h10"/>',
		'shop'            => '<path d="M4 5a2 2 0 0 1 2-2h13v16H6a2 2 0 0 0-2 2z"/><path d="M4 19V5"/>',
	);
	$path  = isset( $paths[ $endpoint ] ) ? $paths[ $endpoint ] : '<circle cx="12" cy="12" r="3"/>';
	return '<svg class="sd-icon" aria-hidden="true" focusable="false" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' . $path . '</svg>';
}

/**
 * Quick-action cards on the dashboard: only sections that exist in the menu.
 *
 * @return array<int,array{endpoint:string,url:string,label:string,desc:string}>
 */
function sdc_account_dashboard_cards() {
	$items = wc_get_account_menu_items();
	$desc  = array(
		'downloads'       => __( 'Download the ebooks you have bought.', 'sylviasdream-child' ),
		'orders'          => __( 'See your orders and receipts.', 'sylviasdream-child' ),
		'edit-address'    => __( 'Update your billing details.', 'sylviasdream-child' ),
		'edit-account'    => __( 'Change your name, email or password.', 'sylviasdream-child' ),
		'customer-logout' => __( 'Sign out of your account on this device.', 'sylviasdream-child' ),
	);
	$cards = array();
	foreach ( array( 'downloads', 'orders', 'edit-account', 'edit-address', 'customer-logout' ) as $endpoint ) {
		if ( ! isset( $items[ $endpoint ] ) ) {
			continue;
		}
		$cards[] = array(
			'endpoint' => $endpoint,
			// For customer-logout this is wc_logout_url(), with its nonce.
			'url'      => wc_get_account_endpoint_url( $endpoint ),
			'label'    => $items[ $endpoint ],
			'desc'     => $desc[ $endpoint ],
		);
	}
	return (array) apply_filters( 'sdc_account_dashboard_cards', $cards );
}

/**
 * Show/hide password button (hidden until account.js turns it on).
 *
 * @param string $input_id ID of the password input.
 */
function sdc_account_password_toggle( $input_id ) {
	$eye = '<svg class="sd-icon sd-pw__eye" aria-hidden="true" focusable="false" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>';
	printf(
		'<button type="button" class="sd-pw__toggle" aria-controls="%1$s" aria-pressed="false" aria-label="%2$s" data-sd-pw-toggle hidden>%3$s<span class="sd-pw__text" aria-hidden="true" data-show="%4$s" data-hide="%5$s">%4$s</span></button>',
		esc_attr( $input_id ),
		esc_attr__( 'Show password', 'sylviasdream-child' ),
		wp_kses( $eye, sdc_svg_kses() ),
		esc_attr__( 'Show', 'sylviasdream-child' ),
		esc_attr__( 'Hide', 'sylviasdream-child' )
	);
}

/**
 * "Secure sign-in" line with a lock. Only printed on an HTTPS connection,
 * so it is always true.
 */
function sdc_account_trust_line() {
	if ( ! is_ssl() ) {
		return;
	}
	$text = (string) apply_filters( 'sdc_account_trust_text', __( 'Secure sign-in', 'sylviasdream-child' ) );
	if ( '' === $text ) {
		return;
	}
	$lock = function_exists( 'sdc_trust_icon' ) ? sdc_trust_icon( 'lock' ) : '';
	printf(
		'<p class="sd-auth__trust">%1$s<span>%2$s</span></p>',
		wp_kses( $lock, sdc_svg_kses() ),
		esc_html( $text )
	);
}

/**
 * Empty state for My ebooks and Orders, with a Browse books button to the
 * Books page (woocommerce_return_to_shop_redirect, set by the theme).
 *
 * @param string $endpoint Icon key.
 * @param string $title    Heading.
 * @param string $text     Line under the heading.
 */
function sdc_account_empty_state( $endpoint, $title, $text ) {
	$url = apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ); // phpcs:ignore WooCommerce.Commenting.CommentHooks.MissingHookComment -- core WooCommerce filter.
	?>
	<div class="sd-empty woocommerce-info" role="status">
		<span class="sd-empty__icon" aria-hidden="true"><?php echo wp_kses( sdc_account_icon( $endpoint ), sdc_svg_kses() ); ?></span>
		<h2 class="sd-empty__title"><?php echo esc_html( $title ); ?></h2>
		<p class="sd-empty__text"><?php echo esc_html( $text ); ?></p>
		<?php if ( $url ) : ?>
			<a class="sd-btn wc-forward" href="<?php echo esc_url( $url ); ?>"><?php echo wp_kses( sdc_account_icon( 'shop' ), sdc_svg_kses() ); ?><span><?php esc_html_e( 'Browse books', 'sylviasdream-child' ); ?></span></a>
		<?php endif; ?>
	</div>
	<?php
}
