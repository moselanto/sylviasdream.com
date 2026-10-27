<?php
/**
 * 0.4.0: light admin branding.
 *
 *  - "Sylvia's Dream" admin colour scheme (Users > Profile > Admin Color
 *    Scheme). Default for NEW users only; existing users keep their choice.
 *  - Footer text on the left; the WordPress version on the right is kept.
 *  - "Welcome" dashboard widget with quick links to existing admin screens.
 *  - WordPress logo menu removed from the toolbar for non-administrators.
 *
 * Switch off: SD_SECURITY_DISABLE key `admin_brand` or the
 * `sd_core_security_feature` filter. Text: `sd_core_admin_footer_text`,
 * `sd_core_admin_welcome`.
 *
 * @package SylviasDreamCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is admin branding on?
 *
 * @return bool
 */
function sd_core_admin_brand_on() {
	return function_exists( 'sd_core_security_on' ) ? sd_core_security_on( 'admin_brand' ) : true;
}

/**
 * Register the colour scheme.
 */
function sd_core_admin_color_scheme() {
	if ( ! sd_core_admin_brand_on() ) {
		return;
	}
	wp_admin_css_color(
		'sylviasdream',
		__( "Sylvia's Dream", 'sylviasdream-core' ),
		SD_CORE_URL . 'assets/admin-colors.css?ver=' . rawurlencode( SD_CORE_VERSION ),
		array( '#2B2116', '#775014', '#A47E3B', '#F7F2E7' ),
		array(
			'base'    => '#E9DFC8',
			'focus'   => '#ffffff',
			'current' => '#ffffff',
		)
	);
}
add_action( 'admin_init', 'sd_core_admin_color_scheme' );

/**
 * New users start with the Sylvia's Dream scheme (they can change it).
 *
 * @param int $user_id User ID.
 */
function sd_core_admin_color_new_user( $user_id ) {
	if ( ! sd_core_admin_brand_on() || ! (bool) apply_filters( 'sd_core_admin_color_default', true ) ) {
		return;
	}
	$current = (string) get_user_meta( (int) $user_id, 'admin_color', true );
	if ( '' === $current || 'fresh' === $current ) {
		update_user_meta( (int) $user_id, 'admin_color', 'sylviasdream' );
	}
}
add_action( 'user_register', 'sd_core_admin_color_new_user' );

/**
 * Small admin stylesheet (welcome widget, 2FA profile section).
 */
function sd_core_admin_assets() {
	wp_enqueue_style( 'sd-core-admin', SD_CORE_URL . 'assets/admin.css', array(), SD_CORE_VERSION );
}
add_action( 'admin_enqueue_scripts', 'sd_core_admin_assets' );

/**
 * Footer text (left side).
 *
 * @param string $text Text.
 * @return string
 */
function sd_core_admin_footer_text( $text ) {
	if ( ! sd_core_admin_brand_on() ) {
		return $text;
	}
	$html = sprintf(
		/* translators: %s: Pimofy Digital link. */
		esc_html__( "Sylvia's Dream Ministry website · Built and supported by %s", 'sylviasdream-core' ),
		'<a href="' . esc_url( 'https://pimofydigital.com' ) . '" rel="noopener" target="_blank">Pimofy Digital</a>'
	);
	return (string) apply_filters( 'sd_core_admin_footer_text', '<span id="footer-thankyou" class="sd-admin-footer">' . $html . '</span>' );
}
add_filter( 'admin_footer_text', 'sd_core_admin_footer_text', 20 );

/**
 * Welcome widget links (only screens that exist and the user can open).
 *
 * @return array<int,array{label:string,url:string}>
 */
function sd_core_admin_welcome_links() {
	$links = array();
	if ( post_type_exists( 'product' ) && current_user_can( 'edit_products' ) ) {
		$links[] = array(
			'label' => __( 'Products (books)', 'sylviasdream-core' ),
			'url'   => admin_url( 'edit.php?post_type=product' ),
		);
	}
	if ( current_user_can( 'edit_pages' ) ) {
		$links[] = array(
			'label' => __( 'Pages', 'sylviasdream-core' ),
			'url'   => admin_url( 'edit.php?post_type=page' ),
		);
	}
	if ( current_user_can( 'edit_posts' ) ) {
		$links[] = array(
			'label' => __( 'Posts (devotionals)', 'sylviasdream-core' ),
			'url'   => admin_url( 'edit.php' ),
		);
	}
	if ( current_user_can( 'upload_files' ) ) {
		$links[] = array(
			'label' => __( 'Media', 'sylviasdream-core' ),
			'url'   => admin_url( 'upload.php' ),
		);
	}
	if ( current_user_can( 'manage_options' ) ) {
		$links[] = array(
			'label' => __( "Settings > Sylvia's Dream", 'sylviasdream-core' ),
			'url'   => admin_url( 'options-general.php?page=sd-core' ),
		);
	}
	return $links;
}

/**
 * Register the widget.
 */
function sd_core_admin_dashboard_widget() {
	if ( ! sd_core_admin_brand_on() || ! current_user_can( 'edit_posts' ) ) {
		return;
	}
	wp_add_dashboard_widget( 'sd_core_welcome', __( "Welcome to Sylvia's Dream", 'sylviasdream-core' ), 'sd_core_admin_dashboard_render' );
}
add_action( 'wp_dashboard_setup', 'sd_core_admin_dashboard_widget' );

/**
 * Widget content.
 */
function sd_core_admin_dashboard_render() {
	$text = (array) apply_filters(
		'sd_core_admin_welcome',
		array(
			'intro'   => __( 'Quick links to the parts of the website you use most.', 'sylviasdream-core' ),
			'support' => __( 'Need help with the website? Contact Pimofy Digital, who build and support it.', 'sylviasdream-core' ),
		)
	);
	$links = sd_core_admin_welcome_links();
	echo '<div class="sd-welcome">';
	if ( ! empty( $text['intro'] ) ) {
		echo '<p>' . esc_html( (string) $text['intro'] ) . '</p>';
	}
	if ( $links ) {
		echo '<ul class="sd-welcome__links">';
		foreach ( $links as $link ) {
			echo '<li><a class="button" href="' . esc_url( $link['url'] ) . '">' . esc_html( $link['label'] ) . '</a></li>';
		}
		echo '</ul>';
	}
	if ( function_exists( 'sd_core_2fa_on' ) && sd_core_2fa_on() && current_user_can( 'read' ) && ! sd_core_2fa_enabled_for( get_current_user_id() ) ) {
		echo '<p class="sd-welcome__tip"><a href="' . esc_url( admin_url( 'profile.php#sd-2fa' ) ) . '">' . esc_html__( 'Turn on two-factor authentication for your account', 'sylviasdream-core' ) . '</a></p>';
	}
	if ( ! empty( $text['support'] ) ) {
		echo '<p class="sd-welcome__support">' . esc_html( (string) $text['support'] ) . '</p>';
	}
	echo '</div>';
}

/**
 * Remove the WordPress logo menu from the toolbar for non-administrators.
 *
 * @param WP_Admin_Bar $bar Toolbar.
 */
function sd_core_admin_bar_tidy( $bar ) {
	if ( sd_core_admin_brand_on() && ! current_user_can( 'manage_options' ) ) {
		$bar->remove_node( 'wp-logo' );
	}
}
add_action( 'admin_bar_menu', 'sd_core_admin_bar_tidy', 999 );
