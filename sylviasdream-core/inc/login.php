<?php
/**
 * 0.4.0: branded sign-in screens (wp-login.php).
 *
 * Split-screen layout: a photo panel on the left (hidden on phones) and the
 * sign-in card on the right. Covers sign in, lost password, reset password and
 * the two-step verification code screen (inc/two-factor.php). Works with any
 * theme because it only uses wp-login.php hooks.
 *
 * Switch off: define( 'SD_SECURITY_DISABLE', array( 'login_brand' ) ); or the
 * `sd_core_security_feature` filter with the key `login_brand`.
 *
 * Text: every line is filterable with `sd_core_login_texts` (array).
 * Photo: `sd_core_login_image` (URL). Logo: `sd_core_login_logo_url` (URL or '').
 *
 * @package SylviasDreamCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is the branded sign-in screen on?
 *
 * @return bool
 */
function sd_core_login_brand_on() {
	return function_exists( 'sd_core_security_on' ) ? sd_core_security_on( 'login_brand' ) : true;
}

/**
 * All sign-in screen text (filterable).
 *
 * @return array<string,mixed>
 */
function sd_core_login_texts() {
	$texts = array(
		'site'         => "Sylvia's Dream",
		'brand'        => "Sylvia's Dream Ministry",
		'eyebrow'      => __( 'Author & ministry team', 'sylviasdream-core' ),
		'headline'     => __( 'Sharing hope, one page at a time.', 'sylviasdream-core' ),
		'lead'         => __( "Sign in to manage the Sylvia's Dream website: books, devotionals and Heaven on Earth giving.", 'sylviasdream-core' ),
		'bullets'      => array(
			__( 'Manage books and orders', 'sylviasdream-core' ),
			__( 'Publish devotionals and pages', 'sylviasdream-core' ),
			__( 'Update Dream Partner giving', 'sylviasdream-core' ),
		),
		'tagline'      => __( 'Bringing Heaven Closer to Earth', 'sylviasdream-core' ),
		'back'         => __( "Back to Sylvia's Dream", 'sylviasdream-core' ),
		'login_title'  => __( 'Welcome back', 'sylviasdream-core' ),
		'login_sub'    => __( 'Sign in to continue to the website dashboard.', 'sylviasdream-core' ),
		'lost_title'   => __( 'Reset your password', 'sylviasdream-core' ),
		'lost_sub'     => __( 'Enter your username or email address and we will email you a link to choose a new password.', 'sylviasdream-core' ),
		'reset_title'  => __( 'Choose a new password', 'sylviasdream-core' ),
		'reset_sub'    => __( 'Use a long password that you do not use on any other website.', 'sylviasdream-core' ),
		'check_title'  => __( 'Check your email', 'sylviasdream-core' ),
		'check_sub'    => __( 'If the details match an account, a link is on its way.', 'sylviasdream-core' ),
		'twofa_title'  => __( 'Two-step verification', 'sylviasdream-core' ),
		'twofa_sub'    => __( 'One more step to keep this account safe.', 'sylviasdream-core' ),
		'page_login'   => __( 'Sign in', 'sylviasdream-core' ),
		'page_lost'    => __( 'Reset password', 'sylviasdream-core' ),
		'page_reset'   => __( 'New password', 'sylviasdream-core' ),
		'page_twofa'   => __( 'Verification code', 'sylviasdream-core' ),
		'username'     => __( 'Username or Email', 'sylviasdream-core' ),
		'button'       => __( 'Log in', 'sylviasdream-core' ),
	);
	/**
	 * Filter the sign-in screen text.
	 *
	 * @param array<string,mixed> $texts Text by key.
	 */
	return (array) apply_filters( 'sd_core_login_texts', $texts );
}

/**
 * One text value.
 *
 * @param string $key Key.
 * @return string
 */
function sd_core_login_text( $key ) {
	$t = sd_core_login_texts();
	return isset( $t[ $key ] ) && is_string( $t[ $key ] ) ? $t[ $key ] : '';
}

/**
 * The wp-login.php action being shown, grouped: login, lost, reset, twofa, check, other.
 *
 * @return string
 */
function sd_core_login_screen() {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- read-only screen detection.
	$action = isset( $GLOBALS['action'] ) && is_string( $GLOBALS['action'] ) ? sanitize_key( $GLOBALS['action'] ) : ( isset( $_REQUEST['action'] ) ? sanitize_key( wp_unslash( $_REQUEST['action'] ) ) : 'login' );
	if ( 'login' === $action && isset( $_GET['checkemail'] ) ) {
		return 'check';
	}
	// phpcs:enable
	switch ( $action ) {
		case 'login':
		case '':
			return 'login';
		case 'lostpassword':
		case 'retrievepassword':
			return 'lost';
		case 'rp':
		case 'resetpass':
			return 'reset';
		case 'sd_2fa':
			return 'twofa';
		case 'confirmaction':
		case 'postpass':
		case 'logout':
			return 'other';
	}
	return 'other';
}

/**
 * Login limit window in minutes (read from the same filter as the limiter).
 *
 * @return int 0 when the login limit is off.
 */
function sd_core_login_pause_minutes() {
	if ( ! function_exists( 'sd_core_login_limit' ) || ! sd_core_security_on( 'login_limit' ) ) {
		return 0;
	}
	list( , $window ) = sd_core_login_limit();
	return (int) ceil( $window / MINUTE_IN_SECONDS );
}

/**
 * Logo image URL: the site's custom logo, or '' for a text logo.
 *
 * @return string
 */
function sd_core_login_logo_url() {
	$url     = '';
	$logo_id = (int) get_theme_mod( 'custom_logo' );
	if ( $logo_id ) {
		$src = wp_get_attachment_image_url( $logo_id, 'medium' );
		$url = $src ? $src : (string) wp_get_attachment_image_url( $logo_id, 'full' );
	}
	/**
	 * Filter the sign-in logo URL. Return '' to show the site name in serif.
	 *
	 * @param string $url Logo URL.
	 */
	return (string) apply_filters( 'sd_core_login_logo_url', $url );
}

/**
 * Styles (and the brand fonts only when the child theme already loads them).
 */
function sd_core_login_assets() {
	if ( ! sd_core_login_brand_on() ) {
		return;
	}
	$deps = array( 'login' );
	// Same Google Fonts stylesheet the child theme uses; no new third party.
	if ( function_exists( 'sdc_google_fonts_url' ) && function_exists( 'sdc_get_bool' ) && sdc_get_bool( 'use_google_fonts' ) ) {
		// phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- versioned by its query.
		wp_enqueue_style( 'sd-core-login-fonts', sdc_google_fonts_url(), array(), null );
		$deps[] = 'sd-core-login-fonts';
	}
	wp_enqueue_style( 'sd-core-login', SD_CORE_URL . 'assets/login.css', $deps, SD_CORE_VERSION );

	$logo = sd_core_login_logo_url();
	if ( '' !== $logo ) {
		wp_add_inline_style(
			'sd-core-login',
			'body.sd-login #login h1 a{background-image:url("' . esc_url( $logo ) . '");background-size:contain;background-position:center;background-repeat:no-repeat;width:min(240px,80%);height:96px;text-indent:-9999px;overflow:hidden;font-size:0;}'
		);
	}
}
add_action( 'login_enqueue_scripts', 'sd_core_login_assets' );

/**
 * Body classes.
 *
 * @param string[] $classes Classes.
 * @return string[]
 */
function sd_core_login_body_class( $classes ) {
	if ( sd_core_login_brand_on() ) {
		$classes[] = 'sd-login';
		$classes[] = 'sd-login-' . sd_core_login_screen();
		if ( '' === sd_core_login_logo_url() ) {
			$classes[] = 'sd-login-textlogo';
		}
	}
	return $classes;
}
add_filter( 'login_body_class', 'sd_core_login_body_class' );

/**
 * Logo link goes to the home page.
 *
 * @param string $url URL.
 * @return string
 */
function sd_core_login_headerurl( $url ) {
	return sd_core_login_brand_on() ? home_url( '/' ) : $url;
}
add_filter( 'login_headerurl', 'sd_core_login_headerurl' );

/**
 * Logo link text is the site name.
 *
 * @param string $text Text.
 * @return string
 */
function sd_core_login_headertext( $text ) {
	if ( ! sd_core_login_brand_on() ) {
		return $text;
	}
	$name = get_bloginfo( 'name', 'display' );
	return '' !== $name ? $name : sd_core_login_text( 'site' );
}
add_filter( 'login_headertext', 'sd_core_login_headertext' );

/**
 * Browser tab title, e.g. "Sign in | Sylvia's Dream".
 *
 * @param string $login_title Title.
 * @return string
 */
function sd_core_login_title( $login_title ) {
	if ( ! sd_core_login_brand_on() ) {
		return $login_title;
	}
	$map    = array(
		'login' => 'page_login',
		'check' => 'page_lost',
		'lost'  => 'page_lost',
		'reset' => 'page_reset',
		'twofa' => 'page_twofa',
	);
	$screen = sd_core_login_screen();
	if ( ! isset( $map[ $screen ] ) ) {
		return $login_title;
	}
	return sd_core_login_text( $map[ $screen ] ) . ' | ' . sd_core_login_text( 'site' );
}
add_filter( 'login_title', 'sd_core_login_title', 20 );

/**
 * Heading and one-line subtitle above the card.
 *
 * @param string $message Existing message.
 * @return string
 */
function sd_core_login_intro( $message ) {
	if ( ! sd_core_login_brand_on() ) {
		return $message;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only.
	if ( ! empty( $_REQUEST['interim-login'] ) ) {
		return $message;
	}
	$map    = array(
		'login' => array( 'login_title', 'login_sub' ),
		'check' => array( 'check_title', 'check_sub' ),
		'lost'  => array( 'lost_title', 'lost_sub' ),
		'reset' => array( 'reset_title', 'reset_sub' ),
		'twofa' => array( 'twofa_title', 'twofa_sub' ),
	);
	$screen = sd_core_login_screen();
	if ( ! isset( $map[ $screen ] ) ) {
		return $message;
	}
	$title = sd_core_login_text( $map[ $screen ][0] );
	$sub   = sd_core_login_text( $map[ $screen ][1] );
	$intro = '<div class="sd-login-intro"><h2 class="sd-login-title">' . esc_html( $title ) . '</h2>';
	if ( '' !== $sub ) {
		$intro .= '<p class="sd-login-sub">' . esc_html( $sub ) . '</p>';
	}
	$intro .= '</div>';
	return $intro . $message;
}
add_filter( 'login_message', 'sd_core_login_intro', 5 );

/**
 * Left photo panel (hidden on phones by the stylesheet).
 */
function sd_core_login_aside() {
	if ( ! sd_core_login_brand_on() ) {
		return;
	}
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only.
	if ( ! empty( $_REQUEST['interim-login'] ) ) {
		return;
	}
	$t       = sd_core_login_texts();
	$image   = (string) apply_filters( 'sd_core_login_image', SD_CORE_URL . 'assets/login-photo.webp' );
	$bullets = isset( $t['bullets'] ) && is_array( $t['bullets'] ) ? array_filter( array_map( 'strval', $t['bullets'] ) ) : array();
	$check   = '<svg class="sd-login-check" width="20" height="20" viewBox="0 0 20 20" aria-hidden="true" focusable="false"><circle cx="10" cy="10" r="10" fill="currentColor" opacity=".22"/><path d="M5.5 10.4l3 3 6-6.4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
	?>
	<aside class="sd-login-aside" style="background-image:url('<?php echo esc_url( $image ); ?>')" aria-label="<?php echo esc_attr( sd_core_login_text( 'brand' ) ); ?>">
		<div class="sd-login-aside__top">
			<p class="sd-login-brand"><span class="sd-login-brand__mark" aria-hidden="true"></span><?php echo esc_html( sd_core_login_text( 'brand' ) ); ?></p>
		</div>
		<div class="sd-login-aside__body">
			<?php if ( '' !== sd_core_login_text( 'eyebrow' ) ) : ?>
				<p class="sd-login-eyebrow"><?php echo esc_html( sd_core_login_text( 'eyebrow' ) ); ?></p>
			<?php endif; ?>
			<p class="sd-login-headline"><?php echo esc_html( sd_core_login_text( 'headline' ) ); ?></p>
			<?php if ( '' !== sd_core_login_text( 'lead' ) ) : ?>
				<p class="sd-login-lead"><?php echo esc_html( sd_core_login_text( 'lead' ) ); ?></p>
			<?php endif; ?>
			<?php if ( $bullets ) : ?>
				<ul class="sd-login-points">
					<?php foreach ( $bullets as $bullet ) : ?>
						<li><?php echo $check; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG above. ?><span><?php echo esc_html( $bullet ); ?></span></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</div>
		<p class="sd-login-aside__foot"><?php echo esc_html( sd_core_login_text( 'tagline' ) ); ?></p>
	</aside>
	<?php
}
add_action( 'login_header', 'sd_core_login_aside' );

/**
 * "Back to Sylvia's Dream" link text (WordPress 5.7+).
 *
 * @param string $link HTML link.
 * @return string
 */
function sd_core_login_back_link( $link ) {
	if ( ! sd_core_login_brand_on() ) {
		return $link;
	}
	return sprintf( '<a href="%s">%s</a>', esc_url( home_url( '/' ) ), esc_html( sd_core_login_text( 'back' ) ) );
}
add_filter( 'login_site_html_link', 'sd_core_login_back_link' );

/**
 * Small "secure connection" note under the card. Numbers come from the real
 * login limit, so the text stays true when the limit is changed or off.
 */
function sd_core_login_secure_note() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only.
	if ( ! sd_core_login_brand_on() || ! empty( $_REQUEST['interim-login'] ) || ! in_array( sd_core_login_screen(), array( 'login', 'lost', 'reset', 'twofa', 'check' ), true ) ) {
		return;
	}
	$parts = array();
	if ( is_ssl() ) {
		$parts[] = __( 'Secure connection.', 'sylviasdream-core' );
	}
	$minutes = sd_core_login_pause_minutes();
	if ( $minutes > 0 ) {
		/* translators: %d: minutes. */
		$parts[] = sprintf( _n( 'Repeated failed attempts are paused for %d minute.', 'Repeated failed attempts are paused for %d minutes.', $minutes, 'sylviasdream-core' ), $minutes );
	}
	/**
	 * Filter the security note under the sign-in card. Return '' to hide it.
	 *
	 * @param string $note Note.
	 */
	$note = (string) apply_filters( 'sd_core_login_secure_note', implode( ' ', $parts ) );
	if ( '' === $note ) {
		return;
	}
	echo '<p class="sd-login-secure"><svg width="16" height="16" viewBox="0 0 16 16" aria-hidden="true" focusable="false"><path fill="currentColor" d="M8 1a3.5 3.5 0 0 0-3.5 3.5V6H4a1.5 1.5 0 0 0-1.5 1.5v6A1.5 1.5 0 0 0 4 15h8a1.5 1.5 0 0 0 1.5-1.5v-6A1.5 1.5 0 0 0 12 6h-.5V4.5A3.5 3.5 0 0 0 8 1zm-2 3.5a2 2 0 1 1 4 0V6H6V4.5z"/></svg><span>' . esc_html( $note ) . '</span></p>';
}
add_action( 'login_footer', 'sd_core_login_secure_note', 5 );

/**
 * Shorter field label and sentence-case button on wp-login.php only.
 *
 * @param string $translation Translated text.
 * @param string $text        Original text.
 * @param string $domain      Text domain.
 * @return string
 */
function sd_core_login_gettext( $translation, $text, $domain ) {
	if ( 'default' !== $domain || ! isset( $GLOBALS['pagenow'] ) || 'wp-login.php' !== $GLOBALS['pagenow'] ) {
		return $translation;
	}
	if ( 'Username or Email Address' === $text ) {
		return sd_core_login_brand_on() ? sd_core_login_text( 'username' ) : $translation;
	}
	if ( 'Log In' === $text ) {
		return sd_core_login_brand_on() ? sd_core_login_text( 'button' ) : $translation;
	}
	return $translation;
}
add_filter( 'gettext', 'sd_core_login_gettext', 20, 3 );
