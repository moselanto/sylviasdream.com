<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * Sylvia's Dream child theme 1.23.0: greeting and quick-action cards (only for
 * account sections that exist). The original intro, its log out link and every
 * dashboard hook are kept.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);

$sdc_name = $current_user->first_name ? $current_user->first_name : $current_user->display_name;
?>

<div class="sd-dash">
	<div class="sd-dash__hello">
		<h2 class="sd-dash__title">
			<?php
			/* translators: %s: customer first name or display name. */
			printf( esc_html__( 'Welcome back, %s', 'sylviasdream-child' ), esc_html( $sdc_name ) );
			?>
		</h2>
		<p class="sd-dash__sub">
			<?php
			printf(
				/* translators: 1: user display name 2: logout url */
				wp_kses( __( 'Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'woocommerce' ), $allowed_html ),
				'<strong>' . esc_html( $current_user->display_name ) . '</strong>',
				esc_url( wc_logout_url() )
			);
			?>
		</p>
	</div>

	<?php $sdc_cards = sdc_account_dashboard_cards(); ?>
	<?php if ( $sdc_cards ) : ?>
		<ul class="sd-dash__cards">
			<?php foreach ( $sdc_cards as $sdc_card ) : ?>
				<li>
					<a class="sd-dash__card<?php echo 'customer-logout' === $sdc_card['endpoint'] ? ' sd-dash__card--quiet' : ''; ?>" href="<?php echo esc_url( $sdc_card['url'] ); ?>">
						<span class="sd-dash__icon" aria-hidden="true"><?php echo wp_kses( sdc_account_icon( $sdc_card['endpoint'] ), sdc_svg_kses() ); ?></span>
						<span class="sd-dash__text">
							<span class="sd-dash__label"><?php echo esc_html( $sdc_card['label'] ); ?></span>
							<?php if ( '' !== $sdc_card['desc'] ) : ?>
								<span class="sd-dash__desc"><?php echo esc_html( $sdc_card['desc'] ); ?></span>
							<?php endif; ?>
						</span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<p class="sd-dash__more">
		<?php
		/* translators: 1: Orders URL 2: Address URL 3: Account URL. */
		$dashboard_desc = __( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">billing address</a>, and <a href="%3$s">edit your password and account details</a>.', 'woocommerce' );
		if ( wc_shipping_enabled() ) {
			/* translators: 1: Orders URL 2: Addresses URL 3: Account URL. */
			$dashboard_desc = __( 'From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">shipping and billing addresses</a>, and <a href="%3$s">edit your password and account details</a>.', 'woocommerce' );
		}
		printf(
			wp_kses( $dashboard_desc, $allowed_html ),
			esc_url( wc_get_endpoint_url( 'orders' ) ),
			esc_url( wc_get_endpoint_url( 'edit-address' ) ),
			esc_url( wc_get_endpoint_url( 'edit-account' ) )
		);
		?>
	</p>
</div>

<?php
	/**
	 * My Account dashboard.
	 *
	 * @since 2.6.0
	 */
	do_action( 'woocommerce_account_dashboard' );

	/**
	 * Deprecated woocommerce_before_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_before_my_account' );

	/**
	 * Deprecated woocommerce_after_my_account action.
	 *
	 * @deprecated 2.6.0
	 */
	do_action( 'woocommerce_after_my_account' );

/* Omit closing PHP tag at the end of PHP files to avoid "headers already sent" issues. */
