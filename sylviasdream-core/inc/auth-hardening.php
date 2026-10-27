<?php
/**
 * 0.4.0: sign-in hardening that adds to inc/security.php (no duplication).
 *
 * Feature keys (SD_SECURITY_DISABLE / `sd_core_security_feature`):
 *  - login_errors     one generic message for a wrong username, email or
 *                     password; unknown accounts on lost-password get the
 *                     normal "check your email" result (wp-login.php and the
 *                     WooCommerce My account forms).
 *  - app_passwords    Application Passwords only for administrators.
 *  - session_cleanup  when a password changes, other sessions are ended.
 *
 * @package SylviasDreamCore
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
 * 1. Generic login errors (feature key: login_errors)
 * ---------------------------------------------------------------------- */

/**
 * The generic sign-in error text.
 *
 * @return string
 */
function sd_core_generic_login_message() {
	return (string) apply_filters( 'sd_core_generic_login_message', __( 'The username/email or password is incorrect.', 'sylviasdream-core' ) );
}

/**
 * Replace "unknown username", "unknown email" and "wrong password" errors
 * with one message, so accounts cannot be discovered. The lockout error
 * (inc/security.php) and empty-field errors are kept. wp_login_failed still
 * fires, so the login limit keeps counting.
 *
 * @param WP_User|WP_Error|null $user Result so far.
 * @return WP_User|WP_Error|null
 */
function sd_core_generic_login_errors( $user ) {
	if ( ! is_wp_error( $user ) || ! sd_core_security_on( 'login_errors' ) ) {
		return $user;
	}
	$hide = array( 'invalid_username', 'invalid_email', 'incorrect_password', 'invalidcombo' );
	if ( array_intersect( $hide, $user->get_error_codes() ) ) {
		return new WP_Error(
			'sd_invalid_login',
			sprintf( '<strong>%s</strong> %s', esc_html__( 'Error:', 'sylviasdream-core' ), esc_html( sd_core_generic_login_message() ) )
		);
	}
	return $user;
}
add_filter( 'authenticate', 'sd_core_generic_login_errors', 99 );

/**
 * WooCommerce My account login looks the email up itself and says "A user
 * could not be found with this email address". During that form only, let
 * core resolve the email instead (core accepts email sign-in), so the generic
 * message above applies. The lost-password lookup is not affected.
 *
 * @param bool $lookup Look up username from email.
 * @return bool
 */
function sd_core_wc_login_email_lookup( $lookup ) {
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- only reads which form was sent.
	if ( sd_core_security_on( 'login_errors' ) && isset( $_POST['woocommerce-login-nonce'] ) ) {
		return false;
	}
	return $lookup;
}
add_filter( 'woocommerce_get_username_from_email', 'sd_core_wc_login_email_lookup' );

/**
 * Lost password for an unknown username or email: behave exactly like a
 * known one ("check your email") instead of saying the account does not exist.
 * Rate-limit and empty-field errors are still shown.
 *
 * @param WP_Error      $errors    Errors so far.
 * @param WP_User|false $user_data Matching user, or false.
 */
function sd_core_generic_lostpassword( $errors, $user_data ) {
	if ( ! sd_core_security_on( 'login_errors' ) || $user_data || ! $errors instanceof WP_Error ) {
		return;
	}
	$other = array_diff( $errors->get_error_codes(), array( 'invalid_email', 'invalidcombo' ) );
	if ( $other ) {
		return;
	}
	// phpcs:disable WordPress.Security.NonceVerification.Missing,WordPress.Security.NonceVerification.Recommended -- only decides where to send the visitor.
	if ( isset( $_POST['wc_reset_password'] ) && function_exists( 'wc_get_account_endpoint_url' ) ) {
		$to = add_query_arg( 'reset-link-sent', 'true', wc_get_account_endpoint_url( 'lost-password' ) );
	} elseif ( isset( $GLOBALS['pagenow'] ) && 'wp-login.php' === $GLOBALS['pagenow'] ) {
		$to = ! empty( $_REQUEST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_REQUEST['redirect_to'] ) ) : site_url( 'wp-login.php?checkemail=confirm', 'login' );
	} else {
		return;
	}
	// phpcs:enable
	wp_safe_redirect( $to );
	exit;
}
add_action( 'lostpassword_post', 'sd_core_generic_lostpassword', 99, 2 );

/* -------------------------------------------------------------------------
 * 2. Application Passwords (feature key: app_passwords)
 * ---------------------------------------------------------------------- */

/**
 * Application Passwords stay available only to administrators.
 *
 * @param bool    $available Available.
 * @param WP_User $user      User.
 * @return bool
 */
function sd_core_app_passwords_admins_only( $available, $user ) {
	if ( ! sd_core_security_on( 'app_passwords' ) ) {
		return $available;
	}
	return $available && $user instanceof WP_User && user_can( $user, 'manage_options' );
}
add_filter( 'wp_is_application_passwords_available_for_user', 'sd_core_app_passwords_admins_only', 10, 2 );

/* -------------------------------------------------------------------------
 * 3. Sessions on password change (feature key: session_cleanup)
 * ---------------------------------------------------------------------- */

/**
 * User whose own password just changed in this request (their new cookie's
 * session is kept; every other session is ended).
 *
 * @param int|null $set User ID to remember.
 * @return int
 */
function sd_core_password_changed_user( $set = null ) {
	static $uid = 0;
	if ( null !== $set ) {
		$uid = (int) $set;
	}
	return $uid;
}

/**
 * Profile or WooCommerce account password change.
 *
 * @param int     $user_id  User ID.
 * @param WP_User $old_data User before the change.
 */
function sd_core_session_cleanup_on_update( $user_id, $old_data ) {
	if ( ! sd_core_security_on( 'session_cleanup' ) || ! class_exists( 'WP_Session_Tokens' ) || ! $old_data instanceof WP_User ) {
		return;
	}
	$new = get_userdata( (int) $user_id );
	if ( ! $new || hash_equals( (string) $old_data->user_pass, (string) $new->user_pass ) ) {
		return;
	}
	$sessions = WP_Session_Tokens::get_instance( (int) $user_id );
	if ( (int) $user_id === get_current_user_id() ) {
		$sessions->destroy_others( wp_get_session_token() );
		// Core issues a fresh cookie next; the session kept above is dropped then too.
		sd_core_password_changed_user( (int) $user_id );
	} else {
		$sessions->destroy_all();
	}
}
add_action( 'profile_update', 'sd_core_session_cleanup_on_update', 10, 2 );

/**
 * When core re-issues the cookie after a user changes their own password,
 * keep only that new session.
 *
 * @param string $cookie     Cookie value.
 * @param int    $expire     Expiry.
 * @param int    $expiration Expiration.
 * @param int    $user_id    User ID.
 * @param string $scheme     Scheme.
 * @param string $token      Session token.
 */
function sd_core_session_cleanup_new_cookie( $cookie, $expire, $expiration, $user_id, $scheme, $token ) {
	if ( (int) $user_id > 0 && (int) $user_id === sd_core_password_changed_user() && '' !== (string) $token && class_exists( 'WP_Session_Tokens' ) ) {
		WP_Session_Tokens::get_instance( (int) $user_id )->destroy_others( (string) $token );
		sd_core_password_changed_user( 0 );
	}
}
add_action( 'set_auth_cookie', 'sd_core_session_cleanup_new_cookie', 10, 6 );

/**
 * Password reset by email link: end every session for that account.
 *
 * @param WP_User $user User.
 */
function sd_core_session_cleanup_on_reset( $user ) {
	if ( sd_core_security_on( 'session_cleanup' ) && $user instanceof WP_User && class_exists( 'WP_Session_Tokens' ) ) {
		WP_Session_Tokens::get_instance( $user->ID )->destroy_all();
	}
}
add_action( 'after_password_reset', 'sd_core_session_cleanup_on_reset' );
