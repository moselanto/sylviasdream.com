<?php
/**
 * Settings > Sylvia's Dream (administrators only; saved through the Settings
 * API, so WordPress checks the nonce and the manage_options capability).
 *
 * @package SylviasDreamCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Option defaults.
 *
 * @return array<string,mixed>
 */
function sd_core_defaults() {
	return array(
		'partners_current'  => 0,
		'partners_goal'     => 100,
		'charity_name'      => 'Heaven on Earth Ministries',
		'charity_reg'       => '',
		'redirects_enabled' => 0,
		'preview_enabled'   => 0,
		'test_payments_enabled' => 0,
		'protected_slugs'   => implode( "\n", sd_core_default_protected_slugs() ),
		'og_default_image'  => '',
	);
}

/**
 * Read one option.
 *
 * @param string $key Key.
 * @return mixed
 */
function sd_core_opt( $key ) {
	$opts = wp_parse_args( (array) get_option( 'sd_core', array() ), sd_core_defaults() );
	return $opts[ $key ] ?? null;
}

/**
 * Register the settings page.
 */
function sd_core_admin_menu() {
	add_options_page( __( "Sylvia's Dream", 'sylviasdream-core' ), __( "Sylvia's Dream", 'sylviasdream-core' ), 'manage_options', 'sd-core', 'sd_core_render_settings' );
}
add_action( 'admin_menu', 'sd_core_admin_menu' );

/**
 * Register setting with sanitisation.
 */
function sd_core_register_setting() {
	register_setting(
		'sd_core',
		'sd_core',
		array(
			'type'              => 'array',
			'sanitize_callback' => 'sd_core_sanitize',
			'default'           => sd_core_defaults(),
		)
	);
}
add_action( 'admin_init', 'sd_core_register_setting' );

/**
 * Sanitise settings.
 *
 * @param mixed $in Raw input.
 * @return array<string,mixed>
 */
function sd_core_sanitize( $in ) {
	$in    = is_array( $in ) ? $in : array();
	$slugs = array_filter( array_map( 'sanitize_title', preg_split( '/[\r\n]+/', (string) ( $in['protected_slugs'] ?? '' ) ) ) );
	return array(
		'partners_current'  => max( 0, absint( $in['partners_current'] ?? 0 ) ),
		'partners_goal'     => max( 1, absint( $in['partners_goal'] ?? 100 ) ),
		'charity_name'      => sanitize_text_field( $in['charity_name'] ?? '' ),
		'charity_reg'       => sanitize_text_field( $in['charity_reg'] ?? '' ),
		'redirects_enabled' => empty( $in['redirects_enabled'] ) ? 0 : 1,
		'preview_enabled'   => ( empty( $in['preview_enabled'] ) || sd_core_is_live_site() ) ? 0 : 1,
		'test_payments_enabled' => ( empty( $in['test_payments_enabled'] ) || sd_core_is_live_site() ) ? 0 : 1,
		'protected_slugs'   => implode( "\n", $slugs ),
		'og_default_image'  => esc_url_raw( trim( (string) ( $in['og_default_image'] ?? '' ) ), array( 'http', 'https' ) ),
	);
}

/**
 * Settings screen.
 */
function sd_core_render_settings() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( "Sylvia's Dream settings", 'sylviasdream-core' ); ?></h1>
		<form method="post" action="options.php">
			<?php settings_fields( 'sd_core' ); ?>
			<table class="form-table" role="presentation">
				<tr><th scope="row"><label for="sd-pc"><?php esc_html_e( 'Dream Partners so far', 'sylviasdream-core' ); ?></label></th>
					<td><input id="sd-pc" type="number" min="0" name="sd_core[partners_current]" value="<?php echo esc_attr( sd_core_opt( 'partners_current' ) ); ?>"></td></tr>
				<tr><th scope="row"><label for="sd-pg"><?php esc_html_e( 'Dream Partner goal', 'sylviasdream-core' ); ?></label></th>
					<td><input id="sd-pg" type="number" min="1" name="sd_core[partners_goal]" value="<?php echo esc_attr( sd_core_opt( 'partners_goal' ) ); ?>"></td></tr>
				<tr><th scope="row"><label for="sd-cn"><?php esc_html_e( 'Charity name', 'sylviasdream-core' ); ?></label></th>
					<td><input id="sd-cn" class="regular-text" type="text" name="sd_core[charity_name]" value="<?php echo esc_attr( sd_core_opt( 'charity_name' ) ); ?>"></td></tr>
				<tr><th scope="row"><label for="sd-cr"><?php esc_html_e( 'Charity registration number', 'sylviasdream-core' ); ?></label></th>
					<td><input id="sd-cr" class="regular-text" type="text" name="sd_core[charity_reg]" value="<?php echo esc_attr( sd_core_opt( 'charity_reg' ) ); ?>">
					<p class="description"><?php esc_html_e( 'Shown with [sd_charity_reg]. Leave blank until confirmed.', 'sylviasdream-core' ); ?></p></td></tr>
				<tr><th scope="row"><?php esc_html_e( 'Redirects', 'sylviasdream-core' ); ?></th>
					<td><label><input type="checkbox" name="sd_core[redirects_enabled]" value="1" <?php checked( 1, (int) sd_core_opt( 'redirects_enabled' ) ); ?>> <?php esc_html_e( 'Turn on old-page redirects (launch day only)', 'sylviasdream-core' ); ?></label></td></tr>
				<tr><th scope="row"><?php esc_html_e( 'Reviewer preview', 'sylviasdream-core' ); ?></th>
					<td><?php if ( sd_core_is_live_site() ) : ?>
						<p class="description"><?php esc_html_e( 'Not available on the live website.', 'sylviasdream-core' ); ?></p>
					<?php else : ?>
						<label><input type="checkbox" name="sd_core[preview_enabled]" value="1" <?php checked( 1, (int) sd_core_opt( 'preview_enabled' ) ); ?>> <?php esc_html_e( 'Show Draft books to signed-in reviewers (Contributor role or higher). Test site only.', 'sylviasdream-core' ); ?></label>
					<?php endif; ?></td></tr>
				<tr><th scope="row"><?php esc_html_e( 'Test payments', 'sylviasdream-core' ); ?></th>
					<td><?php if ( sd_core_is_live_site() ) : ?>
						<p class="description"><?php esc_html_e( 'Not available on the live website.', 'sylviasdream-core' ); ?></p>
					<?php else : ?>
						<label><input type="checkbox" name="sd_core[test_payments_enabled]" value="1" <?php checked( 1, (int) sd_core_opt( 'test_payments_enabled' ) ); ?>> <?php esc_html_e( 'Add a "Test payment (no money)" option at checkout so reviewers can order and download without paying. Test site only.', 'sylviasdream-core' ); ?></label>
					<?php endif; ?></td></tr>
				<tr><th scope="row"><label for="sd-ps"><?php esc_html_e( 'Download / thank-you page slugs', 'sylviasdream-core' ); ?></label></th>
					<td><textarea id="sd-ps" class="large-text code" rows="10" name="sd_core[protected_slugs]"><?php echo esc_textarea( sd_core_opt( 'protected_slugs' ) ); ?></textarea>
					<p class="description"><?php esc_html_e( 'One per line. These pages stay working for past buyers but are hidden from search engines and the sitemap.', 'sylviasdream-core' ); ?></p></td></tr>
				<tr><th scope="row"><label for="sd-og"><?php esc_html_e( 'Default sharing image', 'sylviasdream-core' ); ?></label></th>
					<td><input id="sd-og" class="large-text code" type="url" name="sd_core[og_default_image]" value="<?php echo esc_attr( sd_core_opt( 'og_default_image' ) ); ?>" placeholder="<?php echo esc_attr( SD_CORE_URL . 'assets/og-default.jpg' ); ?>">
					<p class="description"><?php esc_html_e( 'Shown when a page without a featured image is shared (Facebook, WhatsApp, X). Best size 1200 x 630 pixels. Paste an image address from the Media Library, or leave blank for the built-in image.', 'sylviasdream-core' ); ?></p></td></tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

/**
 * 0.4.3: true on the live domain (sylviasdream.com), where test-only switches
 * such as reviewer preview can never be turned on.
 *
 * @return bool
 */
function sd_core_is_live_site() {
	$host = strtolower( (string) wp_parse_url( home_url(), PHP_URL_HOST ) );
	return (bool) apply_filters( 'sd_core_is_live_site', in_array( $host, array( 'sylviasdream.com', 'www.sylviasdream.com' ), true ) );
}
