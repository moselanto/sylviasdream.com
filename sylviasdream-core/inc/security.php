<?php
/**
 * 0.3.0: site hardening.
 *
 * Every feature below can be switched off without editing this file:
 *
 *  - in wp-config.php:  define( 'SD_SECURITY_DISABLE', array( 'headers', 'csp' ) );
 *    (list of feature keys), or define( 'SD_SECURITY_DISABLE', 'all' );
 *  - or with the filter `sd_core_security_feature` ( bool $on, string $feature ).
 *
 * Feature keys: headers, csp, hsts, login_limit, form_limit, session_expiry,
 * xmlrpc, rest_users, author_enum, version, cors, https, password_length,
 * upload_mimes.
 *
 * 0.4.0 adds keys handled in other files: login_errors, app_passwords,
 * session_cleanup (inc/auth-hardening.php), two_factor (inc/two-factor.php,
 * also SD_2FA_DISABLE), login_brand (inc/login.php), admin_brand
 * (inc/admin-brand.php). SD_SECURITY_DISABLE 'all' switches these off too.
 *
 * Everything here uses WordPress APIs only, so it is safe on WordPress.com
 * (where some of it is already done by the platform) and on ordinary hosts.
 * Password hashing is left to WordPress core (bcrypt since WP 6.8).
 *
 * @package SylviasDreamCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is a hardening feature on?
 *
 * @param string $feature Feature key (see file header).
 * @return bool
 */
function sd_core_security_on( $feature ) {
	$on = true;
	if ( defined( 'SD_SECURITY_DISABLE' ) ) {
		$off = SD_SECURITY_DISABLE;
		if ( 'all' === $off || ( is_array( $off ) && in_array( $feature, $off, true ) ) ) {
			$on = false;
		}
	}
	/**
	 * Switch a hardening feature on or off.
	 *
	 * @param bool   $on      Whether the feature is on.
	 * @param string $feature Feature key.
	 */
	return (bool) apply_filters( 'sd_core_security_feature', $on, $feature );
}

/**
 * Visitor IP used for rate limiting. REMOTE_ADDR only: forwarded headers can be
 * forged. Hosts behind a proxy can correct it with the `sd_core_client_ip` filter.
 *
 * @return string
 */
function sd_core_client_ip() {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$ip = filter_var( $ip, FILTER_VALIDATE_IP ) ? $ip : '0.0.0.0';
	return (string) apply_filters( 'sd_core_client_ip', $ip );
}

/* -------------------------------------------------------------------------
 * 1. Security headers (feature keys: headers, csp, hsts)
 * ---------------------------------------------------------------------- */

/**
 * Send conservative security headers on front-end responses.
 */
function sd_core_security_headers() {
	if ( headers_sent() || is_admin() || ! sd_core_security_on( 'headers' ) ) {
		return;
	}
	$headers = array(
		'X-Content-Type-Options' => 'nosniff',
		'Referrer-Policy'        => 'strict-origin-when-cross-origin',
		'X-Frame-Options'        => 'SAMEORIGIN',
		'Permissions-Policy'     => 'camera=(), microphone=(), geolocation=(), payment=(self "https://js.stripe.com" "https://checkout.stripe.com"), usb=(), interest-cohort=()',
	);
	if ( is_ssl() && sd_core_security_on( 'hsts' ) ) {
		// No includeSubDomains / preload: the domain's other hosts are not ours to decide.
		$headers['Strict-Transport-Security'] = 'max-age=31536000';
	}
	if ( sd_core_security_on( 'csp' ) ) {
		// Report-Only: never blocks anything, so YouTube, Jetpack, Stripe, PayPal,
		// GoFundMe and Google Translate keep working. Tighten only after reviewing reports.
		$headers['Content-Security-Policy-Report-Only'] = implode(
			'; ',
			array(
				"default-src 'self' https:",
				"script-src 'self' 'unsafe-inline' 'unsafe-eval' https:",
				"style-src 'self' 'unsafe-inline' https:",
				"img-src 'self' data: blob: https:",
				"font-src 'self' data: https:",
				"connect-src 'self' https: wss:",
				"frame-src 'self' https:",
				"media-src 'self' https: blob:",
				"object-src 'none'",
				"base-uri 'self'",
				"form-action 'self' https:",
				"frame-ancestors 'self'",
			)
		);
	}
	/**
	 * Filter the security headers (name => value). Return an empty value to drop one.
	 *
	 * @param array<string,string> $headers Headers.
	 */
	$headers = (array) apply_filters( 'sd_core_security_headers', $headers );
	foreach ( $headers as $name => $value ) {
		if ( '' !== (string) $value ) {
			header( $name . ': ' . $value );
		}
	}
}
add_action( 'send_headers', 'sd_core_security_headers' );

/* -------------------------------------------------------------------------
 * 2. Rate limiting (feature keys: login_limit, form_limit)
 * ---------------------------------------------------------------------- */

/**
 * Count one hit in a rate-limit bucket and say whether it is over the limit.
 * Stored in a transient keyed by a hash of bucket + IP (no raw IPs stored).
 *
 * @param string $bucket Bucket name.
 * @param int    $max    Allowed hits per window.
 * @param int    $window Window in seconds.
 * @param bool   $count  Count this hit (false = only check).
 * @return bool True when the limit is exceeded.
 */
function sd_core_rate_limited( $bucket, $max, $window, $count = true ) {
	$key  = 'sd_rl_' . md5( $bucket . '|' . sd_core_client_ip() );
	$data = get_transient( $key );
	$data = is_array( $data ) ? $data : array(
		'n' => 0,
		't' => time(),
	);
	if ( $count ) {
		$data['n']++;
		$ttl = max( 1, (int) $window - ( time() - (int) $data['t'] ) );
		set_transient( $key, $data, $ttl );
	}
	return (int) $data['n'] > (int) $max;
}

/**
 * Login limit settings: array( max attempts, window seconds ).
 *
 * @return int[]
 */
function sd_core_login_limit() {
	$limit = (array) apply_filters( 'sd_core_login_limit', array( 5, 15 * MINUTE_IN_SECONDS ) );
	return array( max( 1, (int) ( $limit[0] ?? 5 ) ), max( 60, (int) ( $limit[1] ?? 900 ) ) );
}

/**
 * Refuse a login while the IP is locked out.
 *
 * @param WP_User|WP_Error|null $user Result so far.
 * @return WP_User|WP_Error|null
 */
function sd_core_login_check_lock( $user ) {
	if ( ! sd_core_security_on( 'login_limit' ) ) {
		return $user;
	}
	list( $max, $window ) = sd_core_login_limit();
	if ( sd_core_rate_limited( 'login', $max, $window, false ) ) {
		return new WP_Error(
			'sd_login_locked',
			sprintf(
				/* translators: %d: minutes. */
				__( 'Too many failed sign-in attempts. Please wait %d minutes and try again.', 'sylviasdream-core' ),
				(int) ceil( $window / MINUTE_IN_SECONDS )
			)
		);
	}
	return $user;
}
add_filter( 'authenticate', 'sd_core_login_check_lock', 30 );

/**
 * Count a failed login.
 */
function sd_core_login_failed() {
	if ( sd_core_security_on( 'login_limit' ) ) {
		list( $max, $window ) = sd_core_login_limit();
		sd_core_rate_limited( 'login', $max, $window );
	}
}
add_action( 'wp_login_failed', 'sd_core_login_failed' );

/**
 * Limit password-reset requests (5 per hour per IP).
 *
 * @param WP_Error $errors Errors.
 */
function sd_core_lostpassword_limit( $errors ) {
	if ( ! sd_core_security_on( 'form_limit' ) || ! $errors instanceof WP_Error ) {
		return;
	}
	if ( sd_core_rate_limited( 'lostpassword', (int) apply_filters( 'sd_core_lostpassword_limit', 5 ), HOUR_IN_SECONDS ) ) {
		$errors->add( 'sd_rate_limited', __( 'Too many requests. Please try again later.', 'sylviasdream-core' ) );
	}
}
add_action( 'lostpassword_post', 'sd_core_lostpassword_limit' );

/**
 * Limit comment posting (10 per 10 minutes per IP). The theme and plugin have
 * no front-end form handlers of their own; contact forms are Jetpack Forms,
 * which bring their own nonce and Akismet checks.
 *
 * @param array $commentdata Comment data.
 * @return array
 */
function sd_core_comment_limit( $commentdata ) {
	if ( sd_core_security_on( 'form_limit' ) && ! current_user_can( 'moderate_comments' ) && sd_core_rate_limited( 'comment', (int) apply_filters( 'sd_core_comment_limit', 10 ), 10 * MINUTE_IN_SECONDS ) ) {
		wp_die( esc_html__( 'You are posting too quickly. Please wait a few minutes.', 'sylviasdream-core' ), '', array( 'response' => 429, 'back_link' => true ) );
	}
	return $commentdata;
}
add_filter( 'preprocess_comment', 'sd_core_comment_limit', 1 );

/* -------------------------------------------------------------------------
 * 3. Session expiry (feature key: session_expiry)
 * ---------------------------------------------------------------------- */

/**
 * 8 hours, or 7 days with "Remember me".
 *
 * @param int  $length   Seconds.
 * @param int  $user_id  User ID.
 * @param bool $remember Remember me.
 * @return int
 */
function sd_core_auth_cookie_expiration( $length, $user_id, $remember ) {
	if ( ! sd_core_security_on( 'session_expiry' ) ) {
		return $length;
	}
	return $remember ? 7 * DAY_IN_SECONDS : 8 * HOUR_IN_SECONDS;
}
add_filter( 'auth_cookie_expiration', 'sd_core_auth_cookie_expiration', 20, 3 );

/* -------------------------------------------------------------------------
 * 4. Information exposure (xmlrpc, rest_users, author_enum, version)
 * ---------------------------------------------------------------------- */

if ( sd_core_security_on( 'xmlrpc' ) ) {
	// Note: the Jetpack connection uses its own endpoint on WordPress.com; on a
	// self-hosted Jetpack site switch this off if Jetpack reports an XML-RPC error.
	add_filter( 'xmlrpc_enabled', '__return_false' );
	add_filter(
		'xmlrpc_methods',
		function ( $methods ) {
			unset( $methods['pingback.ping'], $methods['pingback.extensions.getPingbacks'] );
			return $methods;
		}
	);
	add_filter(
		'wp_headers',
		function ( $headers ) {
			unset( $headers['X-Pingback'] );
			return $headers;
		}
	);
}

/**
 * Hide /wp/v2/users from visitors who are not signed in.
 *
 * @param array $endpoints REST endpoints.
 * @return array
 */
function sd_core_rest_hide_users( $endpoints ) {
	if ( sd_core_security_on( 'rest_users' ) && ! is_user_logged_in() ) {
		foreach ( array_keys( $endpoints ) as $route ) {
			if ( 0 === strpos( $route, '/wp/v2/users' ) ) {
				unset( $endpoints[ $route ] );
			}
		}
	}
	return $endpoints;
}
add_filter( 'rest_endpoints', 'sd_core_rest_hide_users' );

/**
 * Block ?author=N enumeration and author archives for visitors.
 */
function sd_core_block_author_enum() {
	if ( ! sd_core_security_on( 'author_enum' ) || is_user_logged_in() || is_admin() ) {
		return;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only check.
	if ( isset( $_GET['author'] ) || is_author() ) {
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'sd_core_block_author_enum', 0 );

/**
 * No author URLs in the core sitemap or oEmbed data.
 *
 * @param WP_Sitemaps_Provider $provider Provider.
 * @param string               $name     Name.
 * @return WP_Sitemaps_Provider|false
 */
function sd_core_sitemap_no_users( $provider, $name ) {
	return ( 'users' === $name && sd_core_security_on( 'author_enum' ) ) ? false : $provider;
}
add_filter( 'wp_sitemaps_add_provider', 'sd_core_sitemap_no_users', 10, 2 );

/**
 * Remove the author name/URL from oEmbed responses.
 *
 * @param array $data oEmbed data.
 * @return array
 */
function sd_core_oembed_no_author( $data ) {
	if ( sd_core_security_on( 'author_enum' ) ) {
		unset( $data['author_url'] );
	}
	return $data;
}
add_filter( 'oembed_response_data', 'sd_core_oembed_no_author' );

if ( sd_core_security_on( 'version' ) ) {
	remove_action( 'wp_head', 'wp_generator' );
	add_filter( 'the_generator', '__return_empty_string' );
}

/* -------------------------------------------------------------------------
 * 5. CORS (feature key: cors)
 * ---------------------------------------------------------------------- */

/**
 * The REST API sends Access-Control-Allow-Origin only for this site's own
 * origin. WordPress core normally echoes any Origin back; the site has no
 * cross-origin API clients, so other origins get no CORS header at all.
 *
 * @param mixed $value Response.
 * @return mixed
 */
function sd_core_rest_cors( $value ) {
	if ( ! sd_core_security_on( 'cors' ) ) {
		return $value;
	}
	remove_filter( 'rest_pre_serve_request', 'rest_send_cors_headers' );
	$origin = get_http_origin();
	if ( $origin ) {
		$allowed = (array) apply_filters( 'sd_core_cors_origins', array( untrailingslashit( home_url() ), untrailingslashit( site_url() ) ) );
		$own     = array();
		foreach ( $allowed as $url ) {
			$p = wp_parse_url( $url );
			if ( ! empty( $p['host'] ) ) {
				$own[] = strtolower( ( $p['scheme'] ?? 'https' ) . '://' . $p['host'] . ( isset( $p['port'] ) ? ':' . $p['port'] : '' ) );
			}
		}
		if ( in_array( strtolower( $origin ), $own, true ) && ! headers_sent() ) {
			header( 'Access-Control-Allow-Origin: ' . esc_url_raw( $origin ) );
			header( 'Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, PATCH, DELETE' );
			header( 'Access-Control-Allow-Credentials: true' );
			header( 'Vary: Origin', false );
		}
	}
	return $value;
}
add_action(
	'rest_api_init',
	function () {
		add_filter( 'rest_pre_serve_request', 'sd_core_rest_cors', 9 );
	},
	20
);

/* -------------------------------------------------------------------------
 * 6. HTTPS (feature key: https). Admin: define( 'FORCE_SSL_ADMIN', true ).
 * ---------------------------------------------------------------------- */

/**
 * Is this request already HTTPS, including behind a TLS-terminating proxy?
 *
 * @return bool
 */
function sd_core_request_is_https() {
	if ( is_ssl() ) {
		return true;
	}
	$proto = isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) ? strtolower( sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) ) ) : '';
	if ( 'https' === $proto ) {
		return true;
	}
	$cf = isset( $_SERVER['HTTP_CF_VISITOR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_CF_VISITOR'] ) ) : '';
	return false !== strpos( $cf, 'https' );
}

/**
 * Redirect plain-HTTP front-end requests to HTTPS when the site address is
 * https. Never runs for the CLI, cron, AJAX, REST or when a proxy already
 * reports HTTPS, so it cannot loop.
 */
function sd_core_force_https() {
	if ( ! sd_core_security_on( 'https' ) || 'https' !== wp_parse_url( home_url(), PHP_URL_SCHEME ) ) {
		return;
	}
	if ( sd_core_request_is_https() || wp_doing_ajax() || wp_doing_cron() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || ( defined( 'WP_CLI' ) && WP_CLI ) || empty( $_SERVER['HTTP_HOST'] ) ) {
		return;
	}
	$uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '/';
	$p   = wp_parse_url( home_url() );
	wp_safe_redirect( 'https://' . $p['host'] . ( isset( $p['port'] ) ? ':' . $p['port'] : '' ) . '/' . ltrim( (string) $uri, '/' ), 301 );
	exit;
}
add_action( 'template_redirect', 'sd_core_force_https', -1 );

/* -------------------------------------------------------------------------
 * 7. Password length (feature key: password_length). Hashing stays with core.
 * ---------------------------------------------------------------------- */

/**
 * Minimum password length.
 *
 * @return int
 */
function sd_core_min_password_length() {
	return max( 8, (int) apply_filters( 'sd_core_min_password_length', 12 ) );
}

/**
 * Check a new password and add an error when it is too short.
 *
 * @param WP_Error $errors Errors.
 * @param string   $pass   Password.
 */
function sd_core_check_password_length( $errors, $pass ) {
	if ( sd_core_security_on( 'password_length' ) && is_string( $pass ) && '' !== $pass && mb_strlen( $pass ) < sd_core_min_password_length() ) {
		$errors->add(
			'sd_password_short',
			sprintf(
				/* translators: %d: number of characters. */
				__( 'Please use a password of at least %d characters.', 'sylviasdream-core' ),
				sd_core_min_password_length()
			)
		);
	}
}

add_action(
	'user_profile_update_errors',
	function ( $errors, $update, $user ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- core verified the profile nonce.
		$pass = isset( $_POST['pass1'] ) ? (string) wp_unslash( $_POST['pass1'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- passwords are not sanitised.
		sd_core_check_password_length( $errors, $pass );
	},
	10,
	3
);
add_action(
	'validate_password_reset',
	function ( $errors ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- core verified the reset key.
		$pass = isset( $_POST['pass1'] ) ? (string) wp_unslash( $_POST['pass1'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- passwords are not sanitised.
		sd_core_check_password_length( $errors, $pass );
	}
);
add_action(
	'woocommerce_save_account_details_errors',
	function ( $errors ) {
		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- WooCommerce verified the nonce.
		$pass = isset( $_POST['password_1'] ) ? (string) wp_unslash( $_POST['password_1'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- passwords are not sanitised.
		sd_core_check_password_length( $errors, $pass );
	}
);

/* -------------------------------------------------------------------------
 * 8. Upload types (feature key: upload_mimes)
 * ---------------------------------------------------------------------- */

/**
 * Only the file types the site needs: images, PDF, EPUB, DOCX, MP3, MP4.
 * SVG is never allowed here (it can carry scripts); add a sanitising SVG
 * plugin and the `sd_core_upload_mimes` filter if SVG is ever needed.
 *
 * @param array<string,string> $mimes Allowed types.
 * @return array<string,string>
 */
function sd_core_upload_mimes( $mimes ) {
	if ( ! sd_core_security_on( 'upload_mimes' ) ) {
		return $mimes;
	}
	$allowed = array(
		'jpg|jpeg|jpe' => 'image/jpeg',
		'png'          => 'image/png',
		'gif'          => 'image/gif',
		'webp'         => 'image/webp',
		'avif'         => 'image/avif',
		'ico'          => 'image/x-icon',
		'pdf'          => 'application/pdf',
		'epub'         => 'application/epub+zip',
		'docx'         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
		'mp3|m4a|m4b'  => 'audio/mpeg',
		'mp4|m4v'      => 'video/mp4',
	);
	return (array) apply_filters( 'sd_core_upload_mimes', $allowed, $mimes );
}
add_filter( 'upload_mimes', 'sd_core_upload_mimes', 99 );

/**
 * Belt and braces: refuse SVG/SVGZ even if another plugin allows them.
 *
 * @param array $file Upload.
 * @return array
 */
function sd_core_block_svg( $file ) {
	if ( sd_core_security_on( 'upload_mimes' ) && isset( $file['name'] ) && preg_match( '/\.svgz?$/i', (string) $file['name'] ) && ! apply_filters( 'sd_core_allow_svg', false ) ) {
		$file['error'] = __( 'SVG files are not allowed for security reasons. Please upload a PNG or JPG.', 'sylviasdream-core' );
	}
	return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'sd_core_block_svg' );
