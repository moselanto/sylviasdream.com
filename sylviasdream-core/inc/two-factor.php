<?php
/**
 * 0.4.0: two-factor authentication (TOTP, RFC 6238).
 *
 * Pure PHP, no external libraries or services: HMAC-SHA1, 30-second steps,
 * 6 digits, one step either side allowed, a used step cannot be reused.
 *
 * - Opt-in per user from Users > Profile (own profile only).
 * - Setup shows a QR code of the otpauth:// address (0.4.1: inline SVG made on
 *   this server by inc/qr-svg.php, no external service), plus the base32
 *   secret and the otpauth:// link as a fallback for manual entry in Google
 *   Authenticator, Microsoft Authenticator, Authy or similar. The QR code is
 *   shown only to the profile owner while setup is pending, never after
 *   setup and never to other users. Filter `sd_core_2fa_show_qr` (false hides
 *   it); if the encoder fails, only the key is shown.
 * - Turning it on needs a valid code; 8 one-time recovery codes are shown once
 *   and stored hashed (wp_hash_password).
 * - Turning it off needs the account password and a code (or recovery code).
 * - Sign in: after a correct password the auth cookie is NOT set. A short-lived
 *   random token (5 minutes, HttpOnly cookie, transient keyed by an HMAC of the
 *   token) leads to a branded code screen; 5 code attempts per token, plus a
 *   per-IP limit; then wp_set_auth_cookie() with the original Remember me.
 * - Administrators without 2FA see a dismissible reminder.
 * - Administrators (edit_users) can turn 2FA off for another user who lost
 *   their phone and recovery codes.
 *
 * Emergency switch (site-wide off): define( 'SD_2FA_DISABLE', true ); in
 * wp-config.php. Also off with SD_SECURITY_DISABLE key `two_factor` or the
 * `sd_core_security_feature` filter. Users' settings are kept and apply again
 * once the switch is removed.
 *
 * Secrets live in user meta (`_sd_2fa_secret`, protected meta: not exposed by
 * the REST API) and are never logged or shown after setup.
 *
 * @package SylviasDreamCore
 */

defined( 'ABSPATH' ) || exit;

/* -------------------------------------------------------------------------
 * Switches and settings
 * ---------------------------------------------------------------------- */

/**
 * Is two-factor authentication active site-wide?
 *
 * @return bool
 */
function sd_core_2fa_on() {
	if ( defined( 'SD_2FA_DISABLE' ) && SD_2FA_DISABLE ) {
		return false;
	}
	return function_exists( 'sd_core_security_on' ) ? sd_core_security_on( 'two_factor' ) : true;
}

/**
 * Has this user switched 2FA on (and is a secret stored)?
 *
 * @param int $user_id User ID.
 * @return bool
 */
function sd_core_2fa_enabled_for( $user_id ) {
	$user_id = (int) $user_id;
	return $user_id > 0
		&& '1' === (string) get_user_meta( $user_id, '_sd_2fa_enabled', true )
		&& '' !== (string) get_user_meta( $user_id, '_sd_2fa_secret', true );
}

/**
 * Name shown in the authenticator app.
 *
 * @return string
 */
function sd_core_2fa_issuer() {
	$name = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
	$name = '' !== $name ? $name : "Sylvia's Dream";
	return (string) apply_filters( 'sd_core_2fa_issuer', $name );
}

/**
 * Code attempt limits: array( per-token attempts, per-IP attempts, IP window seconds ).
 *
 * @return int[]
 */
function sd_core_2fa_limits() {
	$l = (array) apply_filters( 'sd_core_2fa_limits', array( 5, 10, 15 * MINUTE_IN_SECONDS ) );
	return array( max( 1, (int) ( $l[0] ?? 5 ) ), max( 1, (int) ( $l[1] ?? 10 ) ), max( 60, (int) ( $l[2] ?? 900 ) ) );
}

/* -------------------------------------------------------------------------
 * TOTP (RFC 4648 base32, RFC 4226 HOTP, RFC 6238 TOTP)
 * ---------------------------------------------------------------------- */

/**
 * Base32 encode (no padding).
 *
 * @param string $bin Binary string.
 * @return string
 */
function sd_core_2fa_base32_encode( $bin ) {
	$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
	$bits     = '';
	$len      = strlen( $bin );
	for ( $i = 0; $i < $len; $i++ ) {
		$bits .= str_pad( decbin( ord( $bin[ $i ] ) ), 8, '0', STR_PAD_LEFT );
	}
	$out  = '';
	$blen = strlen( $bits );
	for ( $i = 0; $i < $blen; $i += 5 ) {
		$out .= $alphabet[ bindec( str_pad( substr( $bits, $i, 5 ), 5, '0', STR_PAD_RIGHT ) ) ];
	}
	return $out;
}

/**
 * Base32 decode (ignores spaces, padding and case).
 *
 * @param string $b32 Base32 text.
 * @return string Binary string.
 */
function sd_core_2fa_base32_decode( $b32 ) {
	$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
	$b32      = strtoupper( (string) preg_replace( '/[^A-Za-z2-7]/', '', (string) $b32 ) );
	$bits     = '';
	$len      = strlen( $b32 );
	for ( $i = 0; $i < $len; $i++ ) {
		$v = strpos( $alphabet, $b32[ $i ] );
		if ( false === $v ) {
			return '';
		}
		$bits .= str_pad( decbin( $v ), 5, '0', STR_PAD_LEFT );
	}
	$out  = '';
	$blen = strlen( $bits );
	for ( $i = 0; $i + 8 <= $blen; $i += 8 ) {
		$out .= chr( bindec( substr( $bits, $i, 8 ) ) );
	}
	return $out;
}

/**
 * New random secret (160 bits, base32, 32 characters).
 *
 * @return string
 */
function sd_core_2fa_new_secret() {
	return sd_core_2fa_base32_encode( random_bytes( 20 ) );
}

/**
 * HOTP value for a counter (6 digits, HMAC-SHA1, dynamic truncation).
 *
 * @param string $key     Binary key.
 * @param int    $counter Counter.
 * @return string
 */
function sd_core_2fa_hotp( $key, $counter ) {
	$counter = max( 0, (int) $counter );
	// 8-byte big-endian counter; the high word is 0 until the year 2106.
	$msg    = pack( 'N', 0 ) . pack( 'N', $counter );
	$hash   = hash_hmac( 'sha1', $msg, $key, true );
	$offset = ord( $hash[19] ) & 0x0F;
	$value  = ( ( ord( $hash[ $offset ] ) & 0x7F ) << 24 )
		| ( ord( $hash[ $offset + 1 ] ) << 16 )
		| ( ord( $hash[ $offset + 2 ] ) << 8 )
		| ord( $hash[ $offset + 3 ] );
	return str_pad( (string) ( $value % 1000000 ), 6, '0', STR_PAD_LEFT );
}

/**
 * Check a 6-digit code against a secret.
 *
 * @param string $secret    Base32 secret.
 * @param string $code      Code typed by the user.
 * @param int    $last_step Last accepted step (codes at or before it are refused).
 * @return int|false The matching time step, or false.
 */
function sd_core_2fa_verify_totp( $secret, $code, $last_step = 0 ) {
	$code = (string) preg_replace( '/\D/', '', (string) $code );
	$key  = sd_core_2fa_base32_decode( $secret );
	if ( 6 !== strlen( $code ) || '' === $key ) {
		return false;
	}
	$now   = (int) floor( time() / 30 );
	$found = false;
	// Check every step in the window (constant work), compare with hash_equals.
	for ( $i = -1; $i <= 1; $i++ ) {
		$step = $now + $i;
		if ( hash_equals( sd_core_2fa_hotp( $key, $step ), $code ) && $step > (int) $last_step && false === $found ) {
			$found = $step;
		}
	}
	return $found;
}

/* -------------------------------------------------------------------------
 * Recovery codes
 * ---------------------------------------------------------------------- */

/**
 * Make 8 recovery codes, store their hashes, return the plain codes (show once).
 *
 * @param int $user_id User ID.
 * @return string[]
 */
function sd_core_2fa_new_recovery_codes( $user_id ) {
	$alphabet = 'abcdefghjkmnpqrstuvwxyz23456789';
	$max      = strlen( $alphabet ) - 1;
	$plain    = array();
	$hashes   = array();
	for ( $n = 0; $n < 8; $n++ ) {
		$code = '';
		for ( $i = 0; $i < 10; $i++ ) {
			$code .= $alphabet[ random_int( 0, $max ) ];
		}
		$plain[]  = substr( $code, 0, 5 ) . '-' . substr( $code, 5 );
		$hashes[] = wp_hash_password( $code );
	}
	update_user_meta( (int) $user_id, '_sd_2fa_recovery', $hashes );
	return $plain;
}

/**
 * Use a recovery code (removes it when it matches).
 *
 * @param int    $user_id User ID.
 * @param string $code    Code.
 * @return bool
 */
function sd_core_2fa_use_recovery_code( $user_id, $code ) {
	$code = strtolower( (string) preg_replace( '/[^A-Za-z0-9]/', '', (string) $code ) );
	if ( 10 !== strlen( $code ) ) {
		return false;
	}
	$hashes = get_user_meta( (int) $user_id, '_sd_2fa_recovery', true );
	if ( ! is_array( $hashes ) ) {
		return false;
	}
	foreach ( $hashes as $i => $hash ) {
		// No user ID passed: wp_check_password() must never rehash or set the account password.
		if ( is_string( $hash ) && wp_check_password( $code, $hash ) ) {
			unset( $hashes[ $i ] );
			update_user_meta( (int) $user_id, '_sd_2fa_recovery', array_values( $hashes ) );
			return true;
		}
	}
	return false;
}

/**
 * Remaining recovery codes.
 *
 * @param int $user_id User ID.
 * @return int
 */
function sd_core_2fa_recovery_left( $user_id ) {
	$hashes = get_user_meta( (int) $user_id, '_sd_2fa_recovery', true );
	return is_array( $hashes ) ? count( $hashes ) : 0;
}

/**
 * Check a code typed at sign-in or in the profile: TOTP first, then (optionally)
 * a recovery code. A matching TOTP step is recorded so it cannot be replayed.
 *
 * @param int    $user_id        User ID.
 * @param string $code           Code.
 * @param bool   $allow_recovery Accept a recovery code.
 * @return bool
 */
function sd_core_2fa_check_user_code( $user_id, $code, $allow_recovery = true ) {
	$secret = (string) get_user_meta( (int) $user_id, '_sd_2fa_secret', true );
	if ( '' === $secret ) {
		return false;
	}
	$step = sd_core_2fa_verify_totp( $secret, $code, (int) get_user_meta( (int) $user_id, '_sd_2fa_last_step', true ) );
	if ( false !== $step ) {
		update_user_meta( (int) $user_id, '_sd_2fa_last_step', (int) $step );
		return true;
	}
	return $allow_recovery && sd_core_2fa_use_recovery_code( $user_id, $code );
}

/**
 * Remove every 2FA setting for a user.
 *
 * @param int $user_id User ID.
 */
function sd_core_2fa_reset_user( $user_id ) {
	foreach ( array( '_sd_2fa_enabled', '_sd_2fa_secret', '_sd_2fa_pending', '_sd_2fa_recovery', '_sd_2fa_last_step' ) as $key ) {
		delete_user_meta( (int) $user_id, $key );
	}
	delete_transient( 'sd_2fa_codes_' . (int) $user_id );
}

/* -------------------------------------------------------------------------
 * Sign-in flow
 * ---------------------------------------------------------------------- */

/**
 * Cookie name for the pending sign-in token.
 *
 * @return string
 */
function sd_core_2fa_cookie_name() {
	return 'sd_2fa_' . ( defined( 'COOKIEHASH' ) ? COOKIEHASH : md5( site_url() ) );
}

/**
 * Transient key for a token (HMAC of the token, never the token itself).
 *
 * @param string $token Token.
 * @return string
 */
function sd_core_2fa_token_key( $token ) {
	return 'sd_2fa_t_' . substr( hash_hmac( 'sha256', (string) $token, wp_salt( 'auth' ) ), 0, 40 );
}

/**
 * Set or clear the token cookie.
 *
 * @param string $token   Token ('' clears).
 * @param int    $expires Expiry timestamp.
 */
function sd_core_2fa_cookie( $token, $expires ) {
	if ( headers_sent() ) {
		return;
	}
	setcookie(
		sd_core_2fa_cookie_name(),
		(string) $token,
		array(
			'expires'  => (int) $expires,
			'path'     => defined( 'SITECOOKIEPATH' ) && SITECOOKIEPATH ? SITECOOKIEPATH : '/',
			'domain'   => defined( 'COOKIE_DOMAIN' ) && COOKIE_DOMAIN ? COOKIE_DOMAIN : '',
			'secure'   => is_ssl(),
			'httponly' => true,
			'samesite' => 'Lax',
		)
	);
}

/**
 * Token from the cookie ('' when missing or malformed).
 *
 * @return string
 */
function sd_core_2fa_cookie_token() {
	$name  = sd_core_2fa_cookie_name();
	$token = isset( $_COOKIE[ $name ] ) ? sanitize_text_field( wp_unslash( $_COOKIE[ $name ] ) ) : '';
	return preg_match( '/^[a-f0-9]{64}$/', $token ) ? $token : '';
}

/**
 * Code screen URL.
 *
 * @param bool $interim Interim (re-authentication) login.
 * @return string
 */
function sd_core_2fa_screen_url( $interim = false ) {
	$args = array( 'action' => 'sd_2fa' );
	if ( $interim ) {
		$args['interim-login'] = '1';
	}
	return add_query_arg( $args, site_url( 'wp-login.php', 'login_post' ) );
}

/**
 * After a correct password for a 2FA user: stop the normal sign-in (no auth
 * cookie) and send the visitor to the code screen.
 *
 * Runs late on `authenticate` so the lockout check (30) and generic errors (99)
 * have already run. Application-password requests are left alone.
 *
 * @param WP_User|WP_Error|null $user     Result so far.
 * @param string                $username Username.
 * @param string                $password Password.
 * @return WP_User|WP_Error|null
 */
function sd_core_2fa_authenticate( $user, $username, $password ) {
	if ( ! $user instanceof WP_User || '' === (string) $password || ! sd_core_2fa_on() || ! sd_core_2fa_enabled_for( $user->ID ) ) {
		return $user;
	}
	if ( did_action( 'application_password_did_authenticate' ) ) {
		return $user;
	}
	if ( ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || ( defined( 'WP_CLI' ) && WP_CLI ) || wp_doing_ajax() || wp_doing_cron() || headers_sent() ) {
		return new WP_Error( 'sd_2fa_required', __( 'This account uses two-step verification. Please sign in on the website sign-in page.', 'sylviasdream-core' ) );
	}

	// phpcs:disable WordPress.Security.NonceVerification.Missing,WordPress.Security.NonceVerification.Recommended -- core / WooCommerce verified the login request; values are validated below.
	$remember = ! empty( $_POST['rememberme'] );
	$redirect = '';
	if ( ! empty( $_REQUEST['redirect_to'] ) ) {
		$redirect = esc_url_raw( wp_unslash( $_REQUEST['redirect_to'] ) );
	} elseif ( ! empty( $_POST['redirect'] ) ) { // WooCommerce My account form.
		$redirect = esc_url_raw( wp_unslash( $_POST['redirect'] ) );
	}
	$interim = ! empty( $_REQUEST['interim-login'] );
	// phpcs:enable

	$token   = bin2hex( random_bytes( 32 ) );
	$expires = time() + 5 * MINUTE_IN_SECONDS;
	set_transient(
		sd_core_2fa_token_key( $token ),
		array(
			'uid'      => (int) $user->ID,
			'remember' => $remember,
			'redirect' => (string) wp_validate_redirect( $redirect, '' ),
			'interim'  => $interim,
			'tries'    => 0,
			'exp'      => $expires,
		),
		5 * MINUTE_IN_SECONDS
	);
	sd_core_2fa_cookie( $token, $expires );
	wp_safe_redirect( sd_core_2fa_screen_url( $interim ) );
	exit;
}
add_filter( 'authenticate', 'sd_core_2fa_authenticate', 100, 3 );

/**
 * Print the code screen inside the standard (branded) wp-login.php layout.
 *
 * @param WP_Error $errors  Errors.
 * @param string   $token   Token.
 * @param bool     $interim Interim login.
 */
function sd_core_2fa_render_form( $errors, $token, $interim ) {
	login_header( __( 'Two-step verification', 'sylviasdream-core' ), '', $errors );
	?>
	<form name="sd2faform" id="sd2faform" class="sd-2fa-form" action="<?php echo esc_url( sd_core_2fa_screen_url( $interim ) ); ?>" method="post" autocomplete="off">
		<p class="sd-2fa-help"><?php esc_html_e( 'Open your authenticator app and enter the 6-digit code shown for this website.', 'sylviasdream-core' ); ?></p>
		<p>
			<label for="sd_2fa_code"><?php esc_html_e( 'Authentication code', 'sylviasdream-core' ); ?></label>
			<input type="text" name="sd_2fa_code" id="sd_2fa_code" class="input sd-2fa-input" value="" size="20" maxlength="20" inputmode="numeric" autocomplete="one-time-code" autocapitalize="off" spellcheck="false" required aria-describedby="sd-2fa-hint">
		</p>
		<p id="sd-2fa-hint" class="sd-2fa-hint"><?php esc_html_e( 'No phone with you? Enter one of your recovery codes instead.', 'sylviasdream-core' ); ?></p>
		<?php wp_nonce_field( 'sd_2fa_login_' . md5( $token ), 'sd_2fa_nonce' ); ?>
		<?php if ( $interim ) : ?>
			<input type="hidden" name="interim-login" value="1">
		<?php endif; ?>
		<p class="submit">
			<input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="<?php esc_attr_e( 'Verify and sign in', 'sylviasdream-core' ); ?>">
		</p>
	</form>
	<p id="nav"><a href="<?php echo esc_url( wp_login_url() ); ?>"><?php esc_html_e( 'Back to sign in', 'sylviasdream-core' ); ?></a></p>
	<?php
	login_footer( 'sd_2fa_code' );
}

/**
 * Print an "ended" screen (expired or too many attempts) and stop.
 *
 * @param string $message Message.
 */
function sd_core_2fa_render_ended( $message ) {
	$errors = new WP_Error( 'sd_2fa_ended', esc_html( $message ) );
	login_header( __( 'Two-step verification', 'sylviasdream-core' ), '', $errors );
	echo '<p id="nav"><a class="button button-primary button-large sd-2fa-restart" href="' . esc_url( wp_login_url() ) . '">' . esc_html__( 'Sign in again', 'sylviasdream-core' ) . '</a></p>';
	login_footer();
}

/**
 * Handle wp-login.php?action=sd_2fa (GET shows the form, POST checks the code).
 */
function sd_core_2fa_login_screen() {
	if ( ! sd_core_2fa_on() ) {
		wp_safe_redirect( wp_login_url() );
		exit;
	}
	nocache_headers();
	$token = sd_core_2fa_cookie_token();
	$key   = $token ? sd_core_2fa_token_key( $token ) : '';
	$data  = $key ? get_transient( $key ) : false;
	if ( ! is_array( $data ) || empty( $data['uid'] ) || (int) ( $data['exp'] ?? 0 ) < time() ) {
		if ( $key ) {
			delete_transient( $key );
		}
		sd_core_2fa_cookie( '', time() - YEAR_IN_SECONDS );
		sd_core_2fa_render_ended( __( 'Your sign-in has expired. Please sign in again.', 'sylviasdream-core' ) );
		exit;
	}
	$user    = get_userdata( (int) $data['uid'] );
	$interim = ! empty( $data['interim'] );
	$errors  = new WP_Error();
	if ( ! $user ) {
		delete_transient( $key );
		sd_core_2fa_render_ended( __( 'Your sign-in has expired. Please sign in again.', 'sylviasdream-core' ) );
		exit;
	}

	$method = isset( $_SERVER['REQUEST_METHOD'] ) ? strtoupper( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) ) : 'GET';
	if ( 'POST' === $method ) {
		list( $max_tries, $ip_max, $ip_window ) = sd_core_2fa_limits();
		$nonce = isset( $_POST['sd_2fa_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['sd_2fa_nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'sd_2fa_login_' . md5( $token ) ) ) {
			$errors->add( 'sd_2fa_nonce', __( 'This page expired. Please enter the code again.', 'sylviasdream-core' ) );
		} elseif ( function_exists( 'sd_core_rate_limited' ) && sd_core_rate_limited( '2fa', $ip_max, $ip_window, false ) ) {
			$errors->add( 'sd_2fa_locked', __( 'Too many incorrect codes from this connection. Please wait and try again later.', 'sylviasdream-core' ) );
		} else {
			$code = isset( $_POST['sd_2fa_code'] ) ? sanitize_text_field( wp_unslash( $_POST['sd_2fa_code'] ) ) : '';
			if ( '' !== $code && sd_core_2fa_check_user_code( $user->ID, $code, true ) ) {
				delete_transient( $key );
				sd_core_2fa_cookie( '', time() - YEAR_IN_SECONDS );
				wp_set_auth_cookie( $user->ID, ! empty( $data['remember'] ) );
				wp_set_current_user( $user->ID );
				/** This action is documented in wp-includes/user.php */
				do_action( 'wp_login', $user->user_login, $user );

				if ( $interim ) {
					$GLOBALS['interim_login'] = 'success'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- wp-login.php reads this for the modal.
					login_header( '', '<p class="message">' . esc_html__( 'You have logged in successfully.', 'sylviasdream-core' ) . '</p>' );
					echo '</div>';
					/** This action is documented in wp-login.php */
					do_action( 'login_footer' );
					echo '</body></html>';
					exit;
				}

				$requested   = (string) $data['redirect'];
				$redirect_to = '' !== $requested ? $requested : admin_url();
				/** This filter is documented in wp-login.php */
				$redirect_to = (string) apply_filters( 'login_redirect', $redirect_to, $requested, $user );
				if ( '' === $redirect_to || admin_url() === $redirect_to || 'wp-admin/' === $redirect_to ) {
					if ( ! $user->has_cap( 'edit_posts' ) ) {
						$redirect_to = $user->has_cap( 'read' ) ? admin_url( 'profile.php' ) : home_url( '/' );
					}
				}
				wp_safe_redirect( $redirect_to );
				exit;
			}

			if ( function_exists( 'sd_core_rate_limited' ) ) {
				sd_core_rate_limited( '2fa', $ip_max, $ip_window, true );
			}
			$data['tries'] = (int) $data['tries'] + 1;
			if ( $data['tries'] >= $max_tries ) {
				delete_transient( $key );
				sd_core_2fa_cookie( '', time() - YEAR_IN_SECONDS );
				/** Counts toward the login lockout in inc/security.php. */
				do_action( 'wp_login_failed', $user->user_login, new WP_Error( 'sd_2fa_failed', 'two-step verification failed' ) );
				sd_core_2fa_render_ended( __( 'Too many incorrect codes. For your security, please sign in again.', 'sylviasdream-core' ) );
				exit;
			}
			set_transient( $key, $data, max( 1, (int) $data['exp'] - time() ) );
			$left = $max_tries - $data['tries'];
			$errors->add(
				'sd_2fa_invalid',
				sprintf(
					/* translators: %d: attempts left. */
					esc_html( _n( 'That code is not correct. %d attempt left.', 'That code is not correct. %d attempts left.', $left, 'sylviasdream-core' ) ),
					$left
				)
			);
		}
	}
	sd_core_2fa_render_form( $errors, $token, $interim );
	exit;
}
add_action( 'login_form_sd_2fa', 'sd_core_2fa_login_screen' );

/* -------------------------------------------------------------------------
 * Profile: setup, recovery codes, turning off
 * ---------------------------------------------------------------------- */

/**
 * Own profile section.
 *
 * @param WP_User $user User being edited (always the current user here).
 */
function sd_core_2fa_profile_section( $user ) {
	if ( ! $user instanceof WP_User || (int) $user->ID !== get_current_user_id() ) {
		return;
	}
	$enabled = sd_core_2fa_enabled_for( $user->ID );
	$pending = (string) get_user_meta( $user->ID, '_sd_2fa_pending', true );
	$codes   = get_transient( 'sd_2fa_codes_' . (int) $user->ID );
	if ( false !== $codes ) {
		delete_transient( 'sd_2fa_codes_' . (int) $user->ID ); // Shown once.
	}
	?>
	<h2 id="sd-2fa"><?php esc_html_e( 'Two-factor authentication', 'sylviasdream-core' ); ?></h2>
	<table class="form-table sd-2fa" role="presentation">
		<tr>
			<th scope="row"><?php esc_html_e( 'Status', 'sylviasdream-core' ); ?></th>
			<td>
				<?php if ( ! sd_core_2fa_on() ) : ?>
					<p class="description"><?php esc_html_e( 'Two-factor authentication is switched off for the whole site at the moment, so no code is asked for at sign in. Your settings are kept.', 'sylviasdream-core' ); ?></p>
				<?php endif; ?>

				<?php if ( is_array( $codes ) && $codes ) : ?>
					<div class="sd-2fa-codes" role="status">
						<p><strong><?php esc_html_e( 'Your recovery codes. Save them now: they will not be shown again.', 'sylviasdream-core' ); ?></strong></p>
						<p><?php esc_html_e( 'Each code works once, if you cannot use your phone. Keep them somewhere safe, such as a password manager or a printed copy.', 'sylviasdream-core' ); ?></p>
						<ol>
							<?php foreach ( $codes as $c ) : ?>
								<li><code><?php echo esc_html( (string) $c ); ?></code></li>
							<?php endforeach; ?>
						</ol>
					</div>
				<?php endif; ?>

				<?php if ( $enabled ) : ?>
					<p class="sd-2fa-on"><strong><?php esc_html_e( 'On.', 'sylviasdream-core' ); ?></strong> <?php esc_html_e( 'A code from your authenticator app is needed each time you sign in.', 'sylviasdream-core' ); ?></p>
					<p>
						<?php
						printf(
							/* translators: %d: number of codes. */
							esc_html( _n( '%d recovery code left.', '%d recovery codes left.', sd_core_2fa_recovery_left( $user->ID ), 'sylviasdream-core' ) ),
							(int) sd_core_2fa_recovery_left( $user->ID )
						);
						?>
					</p>
					<fieldset class="sd-2fa-box">
						<legend><?php esc_html_e( 'Change two-factor settings', 'sylviasdream-core' ); ?></legend>
						<p>
							<label for="sd_2fa_code"><?php esc_html_e( 'Current code from your app (or a recovery code)', 'sylviasdream-core' ); ?></label><br>
							<input type="text" id="sd_2fa_code" name="sd_2fa_code" class="regular-text sd-2fa-input" inputmode="numeric" autocomplete="one-time-code" maxlength="20">
						</p>
						<p>
							<label for="sd_2fa_pass"><?php esc_html_e( 'Your password (only needed to turn it off)', 'sylviasdream-core' ); ?></label><br>
							<input type="password" id="sd_2fa_pass" name="sd_2fa_pass" class="regular-text" autocomplete="current-password">
						</p>
						<p>
							<button type="submit" class="button" name="sd_2fa_action" value="regen"><?php esc_html_e( 'Make new recovery codes', 'sylviasdream-core' ); ?></button>
							<button type="submit" class="button" name="sd_2fa_action" value="disable"><?php esc_html_e( 'Turn off two-factor authentication', 'sylviasdream-core' ); ?></button>
						</p>
					</fieldset>

				<?php elseif ( '' !== $pending ) : ?>
					<?php $uri = sd_core_2fa_otpauth_uri( $user, $pending ); ?>
					<?php
					// QR code of the otpauth:// address, made on this server (inc/qr-svg.php).
					// Only here: own profile, setup pending, 2FA not yet on. '' on any failure.
					$qr_svg = ( function_exists( 'sd_core_qr_svg' ) && (bool) apply_filters( 'sd_core_2fa_show_qr', true, $user ) ) ? sd_core_qr_svg( $uri, 220 ) : '';
					?>
					<ol class="sd-2fa-steps">
						<li><?php esc_html_e( 'Open an authenticator app on your phone (for example Google Authenticator, Microsoft Authenticator or Authy) and choose to add an account.', 'sylviasdream-core' ); ?></li>
						<?php if ( '' !== $qr_svg ) : ?>
							<li>
								<?php esc_html_e( 'Scan this QR code with the app:', 'sylviasdream-core' ); ?>
								<div class="sd-2fa-qr" style="margin:12px 0;line-height:0;">
									<?php echo sd_core_qr_kses( $qr_svg ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- allow-list filtered by sd_core_qr_kses(); built from integers only. ?>
								</div>
								<?php esc_html_e( 'Cannot scan it? Enter the key below instead.', 'sylviasdream-core' ); ?>
							</li>
						<?php endif; ?>
						<li>
							<?php esc_html_e( 'Account name: your username. Key:', 'sylviasdream-core' ); ?>
							<code class="sd-2fa-secret"><?php echo esc_html( implode( ' ', str_split( $pending, 4 ) ) ); ?></code>
							<?php esc_html_e( '(time based, 6 digits).', 'sylviasdream-core' ); ?>
							<br><a href="<?php echo esc_url( $uri, array( 'otpauth' ) ); ?>"><?php esc_html_e( 'On this phone? Open the key in your authenticator app', 'sylviasdream-core' ); ?></a>
						</li>
						<li>
							<label for="sd_2fa_code"><?php esc_html_e( 'Enter the 6-digit code the app shows:', 'sylviasdream-core' ); ?></label><br>
							<input type="text" id="sd_2fa_code" name="sd_2fa_code" class="regular-text sd-2fa-input" inputmode="numeric" autocomplete="one-time-code" maxlength="10">
						</li>
					</ol>
					<p>
						<button type="submit" class="button button-primary" name="sd_2fa_action" value="enable"><?php esc_html_e( 'Turn on two-factor authentication', 'sylviasdream-core' ); ?></button>
						<button type="submit" class="button" name="sd_2fa_action" value="cancel"><?php esc_html_e( 'Cancel setup', 'sylviasdream-core' ); ?></button>
					</p>

				<?php else : ?>
					<p><?php esc_html_e( 'Off. Add a second step to signing in: after your password, you type a 6-digit code from an app on your phone. This protects the website even if your password is ever guessed or stolen.', 'sylviasdream-core' ); ?></p>
					<p><button type="submit" class="button button-primary" name="sd_2fa_action" value="start"><?php esc_html_e( 'Set up two-factor authentication', 'sylviasdream-core' ); ?></button></p>
				<?php endif; ?>
			</td>
		</tr>
	</table>
	<?php
}
add_action( 'show_user_profile', 'sd_core_2fa_profile_section' );

/**
 * otpauth:// address for the setup key.
 *
 * @param WP_User $user   User.
 * @param string  $secret Base32 secret.
 * @return string
 */
function sd_core_2fa_otpauth_uri( $user, $secret ) {
	$issuer = sd_core_2fa_issuer();
	return 'otpauth://totp/' . rawurlencode( $issuer ) . ':' . rawurlencode( $user->user_login )
		. '?secret=' . rawurlencode( $secret )
		. '&issuer=' . rawurlencode( $issuer )
		. '&algorithm=SHA1&digits=6&period=30';
}

/**
 * Process the profile buttons. Runs inside core's profile save, after core has
 * checked the `update-user_{id}` nonce and the edit_user capability; adding an
 * error stops the whole save and shows it at the top of the page.
 *
 * @param WP_Error $errors Errors.
 * @param bool     $update Updating an existing user.
 * @param stdClass $user   User data.
 */
function sd_core_2fa_profile_save( $errors, $update, $user ) {
	if ( ! $update || empty( $user->ID ) || (int) $user->ID !== get_current_user_id() || ! current_user_can( 'edit_user', (int) $user->ID ) ) {
		return;
	}
	// phpcs:disable WordPress.Security.NonceVerification.Missing -- core verified update-user_{id}.
	if ( ! isset( $_POST['sd_2fa_action'] ) ) {
		return;
	}
	$uid    = (int) $user->ID;
	$action = sanitize_key( wp_unslash( $_POST['sd_2fa_action'] ) );
	$code   = isset( $_POST['sd_2fa_code'] ) ? sanitize_text_field( wp_unslash( $_POST['sd_2fa_code'] ) ) : '';
	$pass   = isset( $_POST['sd_2fa_pass'] ) ? (string) wp_unslash( $_POST['sd_2fa_pass'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- passwords are not sanitised; never stored.
	// phpcs:enable

	list( , $ip_max, $ip_window ) = sd_core_2fa_limits();
	$limited = function_exists( 'sd_core_rate_limited' ) && sd_core_rate_limited( '2fa_profile', $ip_max, $ip_window, false );
	$fail    = function ( $msg ) use ( $errors, $ip_max, $ip_window ) {
		if ( function_exists( 'sd_core_rate_limited' ) ) {
			sd_core_rate_limited( '2fa_profile', $ip_max, $ip_window, true );
		}
		$errors->add( 'sd_2fa', $msg );
	};
	if ( $limited && in_array( $action, array( 'enable', 'regen', 'disable' ), true ) ) {
		$errors->add( 'sd_2fa', __( 'Too many incorrect codes. Please wait a few minutes and try again.', 'sylviasdream-core' ) );
		return;
	}

	switch ( $action ) {
		case 'start':
			if ( ! sd_core_2fa_enabled_for( $uid ) ) {
				update_user_meta( $uid, '_sd_2fa_pending', sd_core_2fa_new_secret() );
			}
			break;

		case 'cancel':
			delete_user_meta( $uid, '_sd_2fa_pending' );
			break;

		case 'enable':
			$pending = (string) get_user_meta( $uid, '_sd_2fa_pending', true );
			if ( '' === $pending ) {
				$errors->add( 'sd_2fa', __( 'Please start the two-factor setup again.', 'sylviasdream-core' ) );
				break;
			}
			$step = sd_core_2fa_verify_totp( $pending, $code, 0 );
			if ( false === $step ) {
				$fail( __( 'That code is not correct. Check that the time on your phone is set automatically, then enter the newest code.', 'sylviasdream-core' ) );
				break;
			}
			update_user_meta( $uid, '_sd_2fa_secret', $pending );
			update_user_meta( $uid, '_sd_2fa_last_step', (int) $step );
			update_user_meta( $uid, '_sd_2fa_enabled', '1' );
			delete_user_meta( $uid, '_sd_2fa_pending' );
			set_transient( 'sd_2fa_codes_' . $uid, sd_core_2fa_new_recovery_codes( $uid ), 10 * MINUTE_IN_SECONDS );
			break;

		case 'regen':
			if ( ! sd_core_2fa_enabled_for( $uid ) ) {
				break;
			}
			if ( ! sd_core_2fa_check_user_code( $uid, $code, false ) ) {
				$fail( __( 'Enter the current 6-digit code from your app to make new recovery codes.', 'sylviasdream-core' ) );
				break;
			}
			set_transient( 'sd_2fa_codes_' . $uid, sd_core_2fa_new_recovery_codes( $uid ), 10 * MINUTE_IN_SECONDS );
			break;

		case 'disable':
			if ( ! sd_core_2fa_enabled_for( $uid ) ) {
				break;
			}
			$fresh = get_userdata( $uid );
			// No user ID passed so core never rehashes or resets the password here.
			if ( ! $fresh || '' === $pass || ! wp_check_password( $pass, $fresh->user_pass ) ) {
				$fail( __( 'Your password is not correct.', 'sylviasdream-core' ) );
				break;
			}
			if ( ! sd_core_2fa_check_user_code( $uid, $code, true ) ) {
				$fail( __( 'Enter a current code from your app (or a recovery code) to turn two-factor authentication off.', 'sylviasdream-core' ) );
				break;
			}
			sd_core_2fa_reset_user( $uid );
			break;
	}
}
add_action( 'user_profile_update_errors', 'sd_core_2fa_profile_save', 20, 3 );

/**
 * When editing another user: status and an administrator reset.
 *
 * @param WP_User $user User being edited.
 */
function sd_core_2fa_other_user_section( $user ) {
	if ( ! $user instanceof WP_User || ! current_user_can( 'edit_users' ) || ! current_user_can( 'edit_user', $user->ID ) ) {
		return;
	}
	?>
	<h2 id="sd-2fa"><?php esc_html_e( 'Two-factor authentication', 'sylviasdream-core' ); ?></h2>
	<table class="form-table" role="presentation">
		<tr>
			<th scope="row"><?php esc_html_e( 'Status', 'sylviasdream-core' ); ?></th>
			<td>
				<?php if ( sd_core_2fa_enabled_for( $user->ID ) ) : ?>
					<p><?php esc_html_e( 'On for this user.', 'sylviasdream-core' ); ?></p>
					<label><input type="checkbox" name="sd_2fa_admin_reset" value="1"> <?php esc_html_e( 'Turn off two-factor authentication for this user (for example if they lost their phone and recovery codes). They can set it up again from their profile.', 'sylviasdream-core' ); ?></label>
				<?php else : ?>
					<p><?php esc_html_e( 'Off. Users switch it on from their own profile.', 'sylviasdream-core' ); ?></p>
				<?php endif; ?>
			</td>
		</tr>
	</table>
	<?php
}
add_action( 'edit_user_profile', 'sd_core_2fa_other_user_section' );

/**
 * Save the administrator reset (core checked the update-user_{id} nonce).
 *
 * @param int $user_id User ID.
 */
function sd_core_2fa_other_user_save( $user_id ) {
	// phpcs:ignore WordPress.Security.NonceVerification.Missing -- core verified update-user_{id}.
	if ( empty( $_POST['sd_2fa_admin_reset'] ) || ! current_user_can( 'edit_users' ) || ! current_user_can( 'edit_user', (int) $user_id ) || (int) $user_id === get_current_user_id() ) {
		return;
	}
	sd_core_2fa_reset_user( (int) $user_id );
}
add_action( 'edit_user_profile_update', 'sd_core_2fa_other_user_save' );

/* -------------------------------------------------------------------------
 * Administrator reminder
 * ---------------------------------------------------------------------- */

/**
 * Remind administrators without 2FA (hidden on the profile screen, and for
 * 30 days after "Remind me later").
 */
function sd_core_2fa_admin_notice() {
	if ( ! sd_core_2fa_on() || ! current_user_can( 'manage_options' ) || sd_core_2fa_enabled_for( get_current_user_id() ) ) {
		return;
	}
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( $screen && 'profile' === $screen->id ) {
		return;
	}
	if ( (int) get_user_meta( get_current_user_id(), '_sd_2fa_notice_until', true ) > time() ) {
		return;
	}
	$later = wp_nonce_url( add_query_arg( 'sd_2fa_later', '1' ), 'sd_2fa_later' );
	?>
	<div class="notice notice-warning is-dismissible sd-2fa-notice">
		<p>
			<strong><?php esc_html_e( 'Protect this website:', 'sylviasdream-core' ); ?></strong>
			<?php esc_html_e( 'you are an administrator without two-factor authentication. It takes about two minutes with an app on your phone.', 'sylviasdream-core' ); ?>
		</p>
		<p>
			<a class="button button-primary" href="<?php echo esc_url( admin_url( 'profile.php#sd-2fa' ) ); ?>"><?php esc_html_e( 'Set it up in your profile', 'sylviasdream-core' ); ?></a>
			<a class="button-link" href="<?php echo esc_url( $later ); ?>"><?php esc_html_e( 'Remind me in 30 days', 'sylviasdream-core' ); ?></a>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'sd_core_2fa_admin_notice' );

/**
 * "Remind me in 30 days" link (nonce + capability checked).
 */
function sd_core_2fa_notice_later() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- checked below.
	if ( empty( $_GET['sd_2fa_later'] ) ) {
		return;
	}
	check_admin_referer( 'sd_2fa_later' );
	if ( current_user_can( 'manage_options' ) ) {
		update_user_meta( get_current_user_id(), '_sd_2fa_notice_until', time() + 30 * DAY_IN_SECONDS );
	}
	wp_safe_redirect( remove_query_arg( array( 'sd_2fa_later', '_wpnonce' ) ) );
	exit;
}
add_action( 'admin_init', 'sd_core_2fa_notice_later' );

/**
 * Keep the 2FA meta keys out of user exports and REST meta (protected keys
 * start with an underscore already); also never show them in profile custom
 * fields lists.
 *
 * @param bool   $protected Protected.
 * @param string $meta_key  Key.
 * @return bool
 */
function sd_core_2fa_protect_meta( $protected, $meta_key ) {
	return 0 === strpos( (string) $meta_key, '_sd_2fa_' ) ? true : $protected;
}
add_filter( 'is_protected_meta', 'sd_core_2fa_protect_meta', 10, 2 );
