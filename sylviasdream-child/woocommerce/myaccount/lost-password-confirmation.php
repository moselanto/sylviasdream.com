<?php
/**
 * Lost password confirmation text.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/lost-password-confirmation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * Sylvia's Dream child theme 1.23.0: confirmation in the account card style.
 * The notice, filter and hooks are unchanged.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.9.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="sd-auth sd-auth--single">
<?php wc_print_notice( esc_html__( 'Password reset email has been sent.', 'woocommerce' ) ); ?>

<div class="sd-auth__card sd-auth__card--center">
	<span class="sd-auth__badge" aria-hidden="true"><?php echo wp_kses( sdc_icon( 'mail' ), sdc_svg_kses() ); ?></span>
	<div class="sd-auth__head">
		<h2 class="sd-auth__title"><?php esc_html_e( 'Check your email', 'sylviasdream-child' ); ?></h2>
	</div>

	<?php do_action( 'woocommerce_before_lost_password_confirmation_message' ); ?>

	<p class="sd-auth__sub"><?php echo esc_html( apply_filters( 'woocommerce_lost_password_confirmation_message', esc_html__( 'A password reset email has been sent to the email address on file for your account, but may take several minutes to show up in your inbox. Please wait at least 10 minutes before attempting another reset.', 'woocommerce' ) ) ); ?></p>
	<p class="sd-auth__note"><?php esc_html_e( 'Open the link in the email to choose a new password. If you cannot find it, check your spam or junk folder.', 'sylviasdream-child' ); ?></p>

	<?php do_action( 'woocommerce_after_lost_password_confirmation_message' ); ?>

	<p class="sd-auth__back"><a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php esc_html_e( 'Back to sign in', 'sylviasdream-child' ); ?></a></p>
</div>
</div>
