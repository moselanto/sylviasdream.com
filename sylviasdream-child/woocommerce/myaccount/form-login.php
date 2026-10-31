<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * Sylvia's Dream child theme 1.23.0: centred sign-in card (and Create account
 * card when registration is on), show/hide password, styled checkbox, trust
 * line. Every WooCommerce hook, nonce and field name is kept unchanged.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$sdc_can_register = 'yes' === get_option( 'woocommerce_enable_myaccount_registration' );
// phpcs:ignore WordPress.Security.NonceVerification.Missing -- only decides which tab opens first after a failed registration.
$sdc_start = ( $sdc_can_register && isset( $_POST['register'] ) ) ? 'register' : 'login';
?>
<div class="sd-auth<?php echo $sdc_can_register ? ' sd-auth--split' : ''; ?>" data-sd-auth data-sd-start="<?php echo esc_attr( $sdc_start ); ?>">

<?php do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php if ( $sdc_can_register ) : ?>

<div class="sd-auth__tabs" data-sd-auth-tabs data-label="<?php esc_attr_e( 'Sign in or create an account', 'sylviasdream-child' ); ?>" hidden>
	<button type="button" class="sd-auth__tab" id="sd-tab-login" data-sd-tab="login" aria-controls="sd-panel-login"><?php esc_html_e( 'Sign in', 'sylviasdream-child' ); ?></button>
	<button type="button" class="sd-auth__tab" id="sd-tab-register" data-sd-tab="register" aria-controls="sd-panel-register"><?php esc_html_e( 'Create account', 'sylviasdream-child' ); ?></button>
</div>

<div class="u-columns col2-set" id="customer_login">

	<div class="u-column1 col-1 sd-auth__panel" id="sd-panel-login" data-sd-panel="login">

<?php else : ?>

	<div class="sd-auth__panel" id="sd-panel-login" data-sd-panel="login">

<?php endif; ?>

		<div class="sd-auth__card">
			<div class="sd-auth__head">
				<h2 class="sd-auth__title"><?php esc_html_e( 'Sign in', 'sylviasdream-child' ); ?></h2>
				<p class="sd-auth__sub"><?php esc_html_e( 'Sign in to find your ebook downloads and your orders.', 'sylviasdream-child' ); ?></p>
			</div>

			<form class="woocommerce-form woocommerce-form-login login" method="post" novalidate>

				<?php do_action( 'woocommerce_login_form_start' ); ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="username"><?php esc_html_e( 'Username or email address', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" autocapitalize="none" spellcheck="false" value="<?php echo ( ! empty( $_POST['username'] ) && is_string( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
				</p>
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
					<span class="sd-pw" data-sd-pw>
						<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" required aria-required="true" />
						<?php sdc_account_password_toggle( 'password' ); ?>
					</span>
				</p>

				<?php do_action( 'woocommerce_login_form' ); ?>

				<div class="sd-auth__row">
					<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme sd-check">
						<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
					</label>
					<p class="woocommerce-LostPassword lost_password">
						<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Forgot password?', 'sylviasdream-child' ); ?></a>
					</p>
				</div>

				<p class="form-row sd-auth__submit">
					<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
					<button type="submit" class="woocommerce-button button woocommerce-form-login__submit sd-btn sd-btn--block<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"><?php esc_html_e( 'Sign in', 'sylviasdream-child' ); ?></button>
				</p>

				<?php do_action( 'woocommerce_login_form_end' ); ?>

			</form>

			<?php sdc_account_trust_line(); ?>
		</div>

<?php if ( $sdc_can_register ) : ?>

	</div>

	<div class="u-column2 col-2 sd-auth__panel" id="sd-panel-register" data-sd-panel="register">

		<div class="sd-auth__card">
			<div class="sd-auth__head">
				<h2 class="sd-auth__title"><?php esc_html_e( 'Create account', 'sylviasdream-child' ); ?></h2>
				<p class="sd-auth__sub"><?php esc_html_e( 'Create an account to keep your ebook downloads and orders in one place.', 'sylviasdream-child' ); ?></p>
			</div>

			<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

				<?php do_action( 'woocommerce_register_form_start' ); ?>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="reg_username"><?php esc_html_e( 'Username', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
						<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" autocapitalize="none" spellcheck="false" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
					</p>

				<?php endif; ?>

				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
					<label for="reg_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
					<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" required aria-required="true" /><?php // @codingStandardsIgnoreLine ?>
				</p>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="reg_password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
						<span class="sd-pw" data-sd-pw>
							<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" required aria-required="true" />
							<?php sdc_account_password_toggle( 'reg_password' ); ?>
						</span>
					</p>

				<?php else : ?>

					<p class="sd-auth__note"><?php esc_html_e( 'A link to set a new password will be sent to your email address.', 'woocommerce' ); ?></p>

				<?php endif; ?>

				<?php do_action( 'woocommerce_register_form' ); ?>

				<p class="woocommerce-form-row form-row sd-auth__submit">
					<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
					<button type="submit" class="woocommerce-Button woocommerce-button button sd-btn sd-btn--block<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?> woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Create account', 'sylviasdream-child' ); ?></button>
				</p>

				<?php do_action( 'woocommerce_register_form_end' ); ?>

			</form>

			<?php sdc_account_trust_line(); ?>
		</div>

	</div>

</div>

<?php else : ?>

	</div>

<?php endif; ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>

</div>
