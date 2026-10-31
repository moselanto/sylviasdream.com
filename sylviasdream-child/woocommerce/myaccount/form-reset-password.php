<?php
/**
 * Lost password reset form.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-reset-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * Sylvia's Dream child theme 1.23.0: card style, guidance text, show/hide
 * password. Hidden reset key/login fields, hooks and nonce unchanged; the
 * WooCommerce password strength meter attaches to password_1 as before.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.2.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="sd-auth sd-auth--single" data-sd-auth>
<?php do_action( 'woocommerce_before_reset_password_form' ); ?>

<div class="sd-auth__card">
	<div class="sd-auth__head">
		<h2 class="sd-auth__title"><?php esc_html_e( 'Choose a new password', 'sylviasdream-child' ); ?></h2>
	</div>

	<form method="post" class="woocommerce-ResetPassword lost_reset_password">

		<p class="sd-auth__sub"><?php echo apply_filters( 'woocommerce_reset_password_message', esc_html__( 'Enter a new password below.', 'woocommerce' ) ); ?></p><?php // @codingStandardsIgnoreLine ?>
		<p class="sd-auth__note"><?php esc_html_e( 'Use a long password that you do not use on any other website. The strength meter below shows when it is strong enough.', 'sylviasdream-child' ); ?></p>

		<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
			<label for="password_1"><?php esc_html_e( 'New password', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
			<span class="sd-pw" data-sd-pw>
				<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_1" id="password_1" autocomplete="new-password" required aria-required="true" />
				<?php sdc_account_password_toggle( 'password_1' ); ?>
			</span>
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
			<label for="password_2"><?php esc_html_e( 'Re-enter new password', 'woocommerce' ); ?>&nbsp;<span class="required" aria-hidden="true">*</span><span class="screen-reader-text"><?php esc_html_e( 'Required', 'woocommerce' ); ?></span></label>
			<span class="sd-pw" data-sd-pw>
				<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_2" id="password_2" autocomplete="new-password" required aria-required="true" />
				<?php sdc_account_password_toggle( 'password_2' ); ?>
			</span>
		</p>

		<input type="hidden" name="reset_key" value="<?php echo esc_attr( $args['key'] ); ?>" />
		<input type="hidden" name="reset_login" value="<?php echo esc_attr( $args['login'] ); ?>" />

		<div class="clear"></div>

		<?php do_action( 'woocommerce_resetpassword_form' ); ?>

		<p class="woocommerce-form-row form-row sd-auth__submit">
			<input type="hidden" name="wc_reset_password" value="true" />
			<button type="submit" class="woocommerce-Button button sd-btn sd-btn--block<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" value="<?php esc_attr_e( 'Save', 'woocommerce' ); ?>"><?php esc_html_e( 'Save new password', 'sylviasdream-child' ); ?></button>
		</p>

		<?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>

	</form>

	<?php sdc_account_trust_line(); ?>
</div>

<?php do_action( 'woocommerce_after_reset_password_form' ); ?>
</div>
