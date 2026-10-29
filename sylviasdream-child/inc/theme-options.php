<?php
/**
 * 1.21.0: "Sylvia's Dream Theme Options" (Appearance menu).
 *
 * Uses the free Redux Framework plugin when it is active. Without Redux a
 * basic built-in screen with the same options is shown instead, so nothing
 * depends on Redux being installed.
 *
 * Options live in the option `sdc_options`. The contact email and social links
 * are NOT stored twice: they stay in the existing Customizer settings
 * (sdc_get()), the options screen shows those values and writes changes back,
 * so whatever is already set is never lost.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

define( 'SDC_OPT', 'sdc_options' );

/**
 * Options that are really Customizer settings (option key => sdc_get key).
 *
 * @return array<string,string>
 */
function sdc_opt_mod_map() {
	return array(
		'contact_email'    => 'contact_email',
		'social_facebook'  => 'social_facebook',
		'social_instagram' => 'social_instagram',
		'social_youtube'   => 'social_youtube',
		'social_patreon'   => 'social_patreon',
		'social_amazon'    => 'social_amazon',
		'tagline'          => 'tagline',
		'give_label'       => 'give_label',
		'give_url'         => 'give_url',
	);
}

/**
 * Brand colours that can be changed (option key => array( CSS variable, default, label )).
 * Defaults are the theme's design tokens (style.css :root / theme.json).
 *
 * @return array<string,array{0:string,1:string,2:string}>
 */
function sdc_opt_colours() {
	return array(
		'colour_gold_dark' => array( '--gold-d', '#775014', __( 'Buttons and links (dark gold)', 'sylviasdream-child' ) ),
		'colour_gold'      => array( '--gold', '#c18b2d', __( 'Decoration gold', 'sylviasdream-child' ) ),
		'colour_ink'       => array( '--ink', '#231b14', __( 'Main text colour', 'sylviasdream-child' ) ),
		'colour_cream'     => array( '--cream', '#fcfaf5', __( 'Page background (cream)', 'sylviasdream-child' ) ),
		'colour_footer'    => array( '--footer', '#241b13', __( 'Footer background', 'sylviasdream-child' ) ),
	);
}

/**
 * Defaults for the options that live only here.
 *
 * @return array<string,mixed>
 */
function sdc_opt_defaults() {
	$d = array(
		'header_template' => '0',
		'footer_template' => '0',
		'sticky_header'   => 1,
		'content_width'   => 1200,
		'custom_css'      => '',
		'simple_editor'   => 1,
		'guide_widget'    => 1,
	);
	foreach ( sdc_opt_colours() as $key => $c ) {
		$d[ $key ] = $c[1];
	}
	return $d;
}

/**
 * Read an option. Linked options are read from the Customizer setting.
 *
 * @param string $key     Key.
 * @param mixed  $default Fallback.
 * @return mixed
 */
function sdc_opt( $key, $default = null ) {
	$map = sdc_opt_mod_map();
	if ( isset( $map[ $key ] ) ) {
		return sdc_get( $map[ $key ] );
	}
	$all      = get_option( SDC_OPT, array() );
	$defaults = sdc_opt_defaults();
	if ( is_array( $all ) && array_key_exists( $key, $all ) && '' !== $all[ $key ] && null !== $all[ $key ] ) {
		return $all[ $key ];
	}
	if ( null !== $default ) {
		return $default;
	}
	return isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
}

/**
 * Normalise a colour value (Redux may store an array).
 *
 * @param mixed $c Value.
 * @return string Hex colour or ''.
 */
function sdc_opt_hex( $c ) {
	if ( is_array( $c ) ) {
		$c = isset( $c['color'] ) ? $c['color'] : ( isset( $c['rgba'] ) ? $c['rgba'] : '' );
	}
	$hex = sanitize_hex_color( (string) $c );
	return $hex ? $hex : '';
}

/**
 * Clean every option on save (used by the built-in screen and after Redux saves).
 *
 * @param mixed $in Raw options.
 * @return array
 */
function sdc_opt_clean( $in ) {
	$in  = is_array( $in ) ? $in : array();
	$out = array();
	foreach ( array( 'header_template', 'footer_template' ) as $k ) {
		$v         = isset( $in[ $k ] ) ? (string) $in[ $k ] : '0';
		$out[ $k ] = (string) absint( $v );
	}
	foreach ( array( 'sticky_header', 'simple_editor', 'guide_widget' ) as $k ) {
		$out[ $k ] = empty( $in[ $k ] ) ? 0 : 1;
	}
	$w                    = isset( $in['content_width'] ) ? absint( is_array( $in['content_width'] ) ? reset( $in['content_width'] ) : $in['content_width'] ) : 1200;
	$out['content_width'] = max( 960, min( 1600, $w ? $w : 1200 ) );
	$out['custom_css']    = isset( $in['custom_css'] ) ? wp_strip_all_tags( (string) $in['custom_css'] ) : '';
	foreach ( sdc_opt_colours() as $key => $c ) {
		$hex         = isset( $in[ $key ] ) ? sdc_opt_hex( $in[ $key ] ) : '';
		$out[ $key ] = $hex ? $hex : $c[1];
	}
	return $out;
}

/**
 * Write the linked options back to the Customizer settings.
 *
 * @param array $in Raw options.
 */
function sdc_opt_sync_to_mods( $in ) {
	if ( ! is_array( $in ) ) {
		return;
	}
	foreach ( sdc_opt_mod_map() as $opt => $mod ) {
		if ( ! array_key_exists( $opt, $in ) ) {
			continue;
		}
		$v = trim( (string) $in[ $opt ] );
		if ( 'contact_email' === $opt ) {
			$v = sanitize_email( $v );
		} elseif ( 0 === strpos( $opt, 'social_' ) ) {
			$v = esc_url_raw( $v );
		} elseif ( 'give_url' === $opt ) {
			$v = sdc_sanitize_link( $v );
		} else {
			$v = sanitize_text_field( $v );
		}
		set_theme_mod( 'sdc_' . $mod, $v );
	}
}

/**
 * Copy the current Customizer values into the stored options before the Redux
 * panel loads, so it always shows what the site really uses.
 */
function sdc_opt_sync_from_mods() {
	$all = get_option( SDC_OPT, array() );
	$all = is_array( $all ) ? $all : array();
	foreach ( sdc_opt_mod_map() as $opt => $mod ) {
		$all[ $opt ] = sdc_get( $mod );
	}
	update_option( SDC_OPT, $all, false );
}

/**
 * CSS from the options: brand colours, content width, custom CSS.
 *
 * @return string
 */
function sdc_opt_css() {
	$vars = array();
	foreach ( sdc_opt_colours() as $key => $c ) {
		$hex = sdc_opt_hex( sdc_opt( $key ) );
		if ( $hex && strtolower( $hex ) !== strtolower( $c[1] ) ) {
			$vars[] = $c[0] . ':' . $hex;
		}
	}
	$w = absint( sdc_opt( 'content_width' ) );
	if ( $w && 1200 !== $w ) {
		$vars[] = '--max:' . max( 960, min( 1600, $w ) ) . 'px';
	}
	$css = $vars ? ':root{' . implode( ';', $vars ) . '}' : '';
	$css .= wp_strip_all_tags( (string) sdc_opt( 'custom_css' ) );
	return $css;
}

/**
 * Print the option CSS after the child stylesheet.
 */
function sdc_opt_enqueue_css() {
	$css = sdc_opt_css();
	if ( '' !== $css && wp_style_is( 'sylviasdream-child', 'enqueued' ) ) {
		wp_add_inline_style( 'sylviasdream-child', $css );
	}
}
add_action( 'wp_enqueue_scripts', 'sdc_opt_enqueue_css', 40 );

/**
 * Template choices for the site-wide header / footer.
 *
 * @return array
 */
function sdc_opt_template_choices() {
	$list = function_exists( 'sdc_builder_template_choices' ) ? sdc_builder_template_choices() : array();
	return array( '0' => __( 'The normal Sylvia\'s Dream header / footer (built in)', 'sylviasdream-child' ) ) + $list;
}

/* -------------------------------------------------------------------------
 * Redux panel
 * ---------------------------------------------------------------------- */

/**
 * Register the Redux panel (only when Redux is active).
 */
function sdc_opt_redux_config() {
	if ( ! class_exists( 'Redux' ) ) {
		return;
	}
	$set_args    = method_exists( 'Redux', 'set_args' ) ? 'set_args' : 'setArgs';
	$set_section = method_exists( 'Redux', 'set_section' ) ? 'set_section' : 'setSection';
	if ( ! method_exists( 'Redux', $set_args ) || ! method_exists( 'Redux', $set_section ) ) {
		return;
	}
	if ( is_admin() && current_user_can( 'edit_theme_options' ) ) {
		sdc_opt_sync_from_mods();
	}

	call_user_func(
		array( 'Redux', $set_args ),
		SDC_OPT,
		array(
			'opt_name'            => SDC_OPT,
			'display_name'        => __( "Sylvia's Dream Theme Options", 'sylviasdream-child' ),
			'display_version'     => SYLVIASDREAM_CHILD_VERSION,
			'menu_type'           => 'submenu',
			'page_parent'         => 'themes.php',
			'menu_title'          => __( "Sylvia's Dream Theme Options", 'sylviasdream-child' ),
			'page_title'          => __( "Sylvia's Dream Theme Options", 'sylviasdream-child' ),
			'page_slug'           => 'sdc-theme-options',
			'page_permissions'    => 'edit_theme_options',
			'admin_bar'           => false,
			'dev_mode'            => false,
			'disable_tracking'    => true,
			'update_notice'       => false,
			'customizer'          => false,
			'save_defaults'       => true,
			'default_show'        => false,
			'show_import_export'  => false,
			'show_options_object' => false,
			'use_cdn'             => false,
			'ajax_save'           => true,
			'async_typography'    => false,
			'google_api_key'      => '',
			'output'              => false,
			'output_tag'          => false,
			'footer_credit'       => ' ',
			'database'            => '',
		)
	);

	$templates = sdc_opt_template_choices();
	$starter   = wp_nonce_url( admin_url( 'admin-post.php?action=sdc_starters' ), 'sdc_starters' );

	call_user_func(
		array( 'Redux', $set_section ),
		SDC_OPT,
		array(
			'title'  => __( 'Header and footer', 'sylviasdream-child' ),
			'id'     => 'sdc_layout',
			'icon'   => 'el el-website',
			'fields' => array(
				array(
					'id'       => 'header_template',
					'type'     => 'select',
					'title'    => __( 'Header for the whole website', 'sylviasdream-child' ),
					'subtitle' => __( 'Keep the built-in header, or choose a header you made in Elementor. Each page can choose its own in its page settings.', 'sylviasdream-child' ),
					'options'  => $templates,
					'default'  => '0',
				),
				array(
					'id'       => 'footer_template',
					'type'     => 'select',
					'title'    => __( 'Footer for the whole website', 'sylviasdream-child' ),
					'options'  => $templates,
					'default'  => '0',
				),
				array(
					'id'      => 'sticky_header',
					'type'    => 'switch',
					'title'   => __( 'Keep the header at the top while scrolling', 'sylviasdream-child' ),
					'default' => true,
				),
				array(
					'id'      => 'sdc_starter_note',
					'type'    => 'raw',
					'title'   => __( 'Starter templates', 'sylviasdream-child' ),
					'content' => '<p><a class="button" href="' . esc_url( $starter ) . '">' . esc_html__( 'Create the starter header and footer in Elementor', 'sylviasdream-child' ) . '</a></p><p>' . esc_html__( 'This makes Elementor copies of the current header and footer. Nothing changes on the website until you choose them above.', 'sylviasdream-child' ) . '</p>',
				),
			),
		)
	);

	$colour_fields = array();
	foreach ( sdc_opt_colours() as $key => $c ) {
		$colour_fields[] = array(
			'id'          => $key,
			'type'        => 'color',
			'title'       => $c[2],
			'default'     => $c[1],
			'transparent' => false,
		);
	}
	$colour_fields[] = array(
		'id'      => 'content_width',
		'type'    => 'slider',
		'title'   => __( 'Content width (pixels)', 'sylviasdream-child' ),
		'min'     => 960,
		'max'     => 1600,
		'step'    => 20,
		'default' => 1200,
	);
	call_user_func(
		array( 'Redux', $set_section ),
		SDC_OPT,
		array(
			'title'  => __( 'Colours and width', 'sylviasdream-child' ),
			'id'     => 'sdc_brand',
			'icon'   => 'el el-brush',
			'fields' => $colour_fields,
		)
	);

	$contact = array(
		array( 'id' => 'tagline', 'type' => 'text', 'title' => __( 'Line under the site name', 'sylviasdream-child' ), 'default' => sdc_get( 'tagline' ) ),
		array( 'id' => 'give_label', 'type' => 'text', 'title' => __( 'Give button words', 'sylviasdream-child' ), 'default' => sdc_get( 'give_label' ) ),
		array( 'id' => 'give_url', 'type' => 'text', 'title' => __( 'Give button goes to', 'sylviasdream-child' ), 'subtitle' => __( 'A page of this website such as /give/. Payment links themselves are not changed here.', 'sylviasdream-child' ), 'default' => sdc_get( 'give_url' ) ),
		array( 'id' => 'contact_email', 'type' => 'text', 'title' => __( 'Contact email', 'sylviasdream-child' ), 'default' => sdc_get( 'contact_email' ) ),
	);
	foreach ( array( 'facebook' => 'Facebook', 'instagram' => 'Instagram', 'youtube' => 'YouTube', 'patreon' => 'Patreon', 'amazon' => __( 'Amazon author page', 'sylviasdream-child' ) ) as $k => $label ) {
		$contact[] = array(
			'id'      => 'social_' . $k,
			'type'    => 'text',
			/* translators: %s: network name. */
			'title'   => sprintf( __( '%s address', 'sylviasdream-child' ), $label ),
			'default' => sdc_get( 'social_' . $k ),
		);
	}
	call_user_func(
		array( 'Redux', $set_section ),
		SDC_OPT,
		array(
			'title'  => __( 'Contact and social links', 'sylviasdream-child' ),
			'id'     => 'sdc_contact',
			'icon'   => 'el el-envelope',
			'desc'   => __( 'These are the same settings as Appearance > Customise > Sylvia\'s Dream. Changing them here changes them there too.', 'sylviasdream-child' ),
			'fields' => $contact,
		)
	);

	call_user_func(
		array( 'Redux', $set_section ),
		SDC_OPT,
		array(
			'title'  => __( 'Editing help', 'sylviasdream-child' ),
			'id'     => 'sdc_editing',
			'icon'   => 'el el-heart',
			'fields' => array(
				array(
					'id'       => 'simple_editor',
					'type'     => 'switch',
					'title'    => __( 'Simple Elementor screen for editors', 'sylviasdream-child' ),
					'subtitle' => __( 'Hides advanced Elementor panels and upgrade adverts for everyone who is not an administrator, and makes the panel text larger.', 'sylviasdream-child' ),
					'default'  => true,
				),
				array(
					'id'      => 'guide_widget',
					'type'    => 'switch',
					'title'   => __( 'Show the "How to edit your website" box on the Dashboard', 'sylviasdream-child' ),
					'default' => true,
				),
				array(
					'id'      => 'custom_css',
					'type'    => 'textarea',
					'title'   => __( 'Extra CSS (for the web designer)', 'sylviasdream-child' ),
					'default' => '',
				),
			),
		)
	);
}
add_action( 'after_setup_theme', 'sdc_opt_redux_config', 30 );

/**
 * After Redux saves: clean the values and write linked ones to the Customizer.
 *
 * @param array $options Saved options.
 */
function sdc_opt_after_redux_save( $options ) {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	sdc_opt_sync_to_mods( $options );
}
add_action( 'redux/options/' . SDC_OPT . '/saved', 'sdc_opt_after_redux_save' );

/* -------------------------------------------------------------------------
 * Built-in screen when Redux is not installed
 * ---------------------------------------------------------------------- */

/**
 * Register the setting (Settings API: nonce and capability handled by WordPress).
 */
function sdc_opt_register_setting() {
	if ( class_exists( 'Redux' ) ) {
		return;
	}
	register_setting(
		'sdc_options_group',
		SDC_OPT,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'sdc_opt_fallback_sanitize',
			'default'           => array(),
		)
	);
}
add_action( 'admin_init', 'sdc_opt_register_setting' );

/**
 * Sanitise the built-in screen and sync linked values.
 *
 * @param mixed $in Raw.
 * @return array
 */
function sdc_opt_fallback_sanitize( $in ) {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		$old = get_option( SDC_OPT, array() );
		return is_array( $old ) ? $old : array();
	}
	$in = is_array( $in ) ? wp_unslash( $in ) : array();
	sdc_opt_sync_to_mods( $in );
	return sdc_opt_clean( $in );
}

/**
 * Menu item for the built-in screen.
 */
function sdc_opt_fallback_menu() {
	if ( class_exists( 'Redux' ) ) {
		return;
	}
	add_theme_page(
		__( "Sylvia's Dream Theme Options", 'sylviasdream-child' ),
		__( "Sylvia's Dream Theme Options", 'sylviasdream-child' ),
		'edit_theme_options',
		'sdc-theme-options',
		'sdc_opt_fallback_page'
	);
}
add_action( 'admin_menu', 'sdc_opt_fallback_menu' );

/**
 * The built-in options screen.
 */
function sdc_opt_fallback_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	$templates = sdc_opt_template_choices();
	$setup     = admin_url( 'themes.php?page=sdc-setup' );
	?>
	<div class="wrap sdc-admin">
		<h1><?php esc_html_e( "Sylvia's Dream Theme Options", 'sylviasdream-child' ); ?></h1>
		<div class="notice notice-info inline"><p>
			<?php esc_html_e( 'This is the basic options screen. For the full screen, install the free Redux Framework plugin:', 'sylviasdream-child' ); ?>
			<a href="<?php echo esc_url( $setup ); ?>"><?php esc_html_e( 'Set up the page editor', 'sylviasdream-child' ); ?></a>
		</p></div>
		<form method="post" action="options.php">
			<?php settings_fields( 'sdc_options_group' ); ?>
			<h2><?php esc_html_e( 'Header and footer', 'sylviasdream-child' ); ?></h2>
			<table class="form-table" role="presentation">
				<?php foreach ( array( 'header_template' => __( 'Header for the whole website', 'sylviasdream-child' ), 'footer_template' => __( 'Footer for the whole website', 'sylviasdream-child' ) ) as $key => $label ) : ?>
					<tr>
						<th scope="row"><label for="sdc-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label></th>
						<td><select id="sdc-<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( SDC_OPT . '[' . $key . ']' ); ?>">
							<?php foreach ( $templates as $val => $name ) : ?>
								<option value="<?php echo esc_attr( (string) $val ); ?>" <?php selected( (string) sdc_opt( $key ), (string) $val ); ?>><?php echo esc_html( $name ); ?></option>
							<?php endforeach; ?>
						</select></td>
					</tr>
				<?php endforeach; ?>
				<?php
				$checks = array(
					'sticky_header' => __( 'Keep the header at the top while scrolling', 'sylviasdream-child' ),
					'simple_editor' => __( 'Simple Elementor screen for editors (hides advanced panels for non-administrators)', 'sylviasdream-child' ),
					'guide_widget'  => __( 'Show the "How to edit your website" box on the Dashboard', 'sylviasdream-child' ),
				);
				foreach ( $checks as $key => $label ) :
					?>
					<tr>
						<th scope="row"><?php echo esc_html( $label ); ?></th>
						<td><label><input type="checkbox" name="<?php echo esc_attr( SDC_OPT . '[' . $key . ']' ); ?>" value="1" <?php checked( (int) sdc_opt( $key ), 1 ); ?>> <?php esc_html_e( 'Yes', 'sylviasdream-child' ); ?></label></td>
					</tr>
				<?php endforeach; ?>
			</table>
			<h2><?php esc_html_e( 'Colours and width', 'sylviasdream-child' ); ?></h2>
			<table class="form-table" role="presentation">
				<?php foreach ( sdc_opt_colours() as $key => $c ) : ?>
					<tr>
						<th scope="row"><label for="sdc-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $c[2] ); ?></label></th>
						<td><input type="color" id="sdc-<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( SDC_OPT . '[' . $key . ']' ); ?>" value="<?php echo esc_attr( sdc_opt_hex( sdc_opt( $key ) ) ); ?>"> <code><?php echo esc_html( $c[1] ); ?></code> <?php esc_html_e( '(original)', 'sylviasdream-child' ); ?></td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<th scope="row"><label for="sdc-content-width"><?php esc_html_e( 'Content width (pixels)', 'sylviasdream-child' ); ?></label></th>
					<td><input type="number" min="960" max="1600" step="20" id="sdc-content-width" name="<?php echo esc_attr( SDC_OPT . '[content_width]' ); ?>" value="<?php echo esc_attr( (string) absint( sdc_opt( 'content_width' ) ) ); ?>"></td>
				</tr>
			</table>
			<h2><?php esc_html_e( 'Contact and social links', 'sylviasdream-child' ); ?></h2>
			<p><?php esc_html_e( 'Same settings as Appearance > Customise > Sylvia\'s Dream.', 'sylviasdream-child' ); ?></p>
			<table class="form-table" role="presentation">
				<?php
				$texts = array(
					'tagline'          => __( 'Line under the site name', 'sylviasdream-child' ),
					'give_label'       => __( 'Give button words', 'sylviasdream-child' ),
					'give_url'         => __( 'Give button goes to', 'sylviasdream-child' ),
					'contact_email'    => __( 'Contact email', 'sylviasdream-child' ),
					'social_facebook'  => __( 'Facebook address', 'sylviasdream-child' ),
					'social_instagram' => __( 'Instagram address', 'sylviasdream-child' ),
					'social_youtube'   => __( 'YouTube address', 'sylviasdream-child' ),
					'social_patreon'   => __( 'Patreon address', 'sylviasdream-child' ),
					'social_amazon'    => __( 'Amazon author page address', 'sylviasdream-child' ),
				);
				foreach ( $texts as $key => $label ) :
					?>
					<tr>
						<th scope="row"><label for="sdc-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label></th>
						<td><input type="text" class="regular-text" id="sdc-<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( SDC_OPT . '[' . $key . ']' ); ?>" value="<?php echo esc_attr( (string) sdc_opt( $key ) ); ?>"></td>
					</tr>
				<?php endforeach; ?>
				<tr>
					<th scope="row"><label for="sdc-custom-css"><?php esc_html_e( 'Extra CSS (for the web designer)', 'sylviasdream-child' ); ?></label></th>
					<td><textarea id="sdc-custom-css" class="large-text code" rows="6" name="<?php echo esc_attr( SDC_OPT . '[custom_css]' ); ?>"><?php echo esc_textarea( (string) sdc_opt( 'custom_css' ) ); ?></textarea></td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
