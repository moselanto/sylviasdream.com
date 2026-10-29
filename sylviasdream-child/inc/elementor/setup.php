<?php
/**
 * 1.21.0: Elementor set-up for Sylvia's Dream.
 *
 * - Elementor theme support and brand colours/fonts for the Elementor Kit,
 *   taken from the theme's design tokens (theme.json / style.css).
 * - A missing Elementor default Kit is shown as a notice with a button to
 *   create it, instead of failing.
 * - Appearance > Set up page editor: install/activate Elementor and Redux
 *   from wordpress.org, create the starter header/footer, and build each main
 *   page in Elementor from its current content (only when an administrator
 *   clicks; the old content stays in the page and its revisions).
 * - Dashboard box "How to edit your website" in plain English.
 * - A simpler Elementor screen for editors who are not administrators.
 *
 * Every action is an admin-post.php handler with a nonce and a capability
 * check (security review: SECURITY-AUDIT.md S15). No AJAX or REST handlers.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Theme support for Elementor (no effect without Elementor).
 */
function sdc_el_theme_support() {
	add_theme_support( 'elementor' );
	add_theme_support( 'header-footer-elementor' );
}
add_action( 'after_setup_theme', 'sdc_el_theme_support', 25 );

/* -------------------------------------------------------------------------
 * Plugins
 * ---------------------------------------------------------------------- */

/**
 * Plugins the page editor uses (all free, from wordpress.org).
 *
 * @return array
 */
function sdc_el_plugins() {
	return array(
		'elementor'       => array(
			'name' => 'Elementor',
			'file' => 'elementor/elementor.php',
			'why'  => __( 'The visual page editor: click on any part of a page and change it.', 'sylviasdream-child' ),
		),
		'redux-framework' => array(
			'name' => 'Redux Framework',
			'file' => 'redux-framework/redux-framework.php',
			'why'  => __( 'The full Sylvia\'s Dream Theme Options screen. A basic screen works without it.', 'sylviasdream-child' ),
		),
	);
}

/**
 * Plugin status: missing | inactive | active.
 *
 * @param string $slug Slug.
 * @return string
 */
function sdc_el_plugin_status( $slug ) {
	$plugins = sdc_el_plugins();
	if ( ! isset( $plugins[ $slug ] ) ) {
		return 'missing';
	}
	if ( ! function_exists( 'is_plugin_active' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	if ( ! file_exists( WP_PLUGIN_DIR . '/' . $plugins[ $slug ]['file'] ) ) {
		return 'missing';
	}
	return is_plugin_active( $plugins[ $slug ]['file'] ) ? 'active' : 'inactive';
}

/**
 * admin-post: install and/or activate one plugin from wordpress.org.
 */
function sdc_el_install_plugin_action() {
	$slug = isset( $_GET['slug'] ) ? sanitize_key( wp_unslash( $_GET['slug'] ) ) : '';
	check_admin_referer( 'sdc_install_' . $slug );
	if ( ! current_user_can( 'install_plugins' ) || ! current_user_can( 'activate_plugins' ) ) {
		wp_die( esc_html__( 'Sorry, you are not allowed to install plugins.', 'sylviasdream-child' ), 403 );
	}
	$plugins = sdc_el_plugins();
	if ( ! isset( $plugins[ $slug ] ) ) {
		wp_die( esc_html__( 'Unknown plugin.', 'sylviasdream-child' ), 400 );
	}
	$p      = $plugins[ $slug ];
	$result = 'ok';
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	if ( 'missing' === sdc_el_plugin_status( $slug ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		$api = plugins_api(
			'plugin_information',
			array(
				'slug'   => $slug,
				'fields' => array( 'sections' => false ),
			)
		);
		if ( is_wp_error( $api ) || empty( $api->download_link ) ) {
			$result = 'fail';
		} else {
			$upgrader = new Plugin_Upgrader( new Automatic_Upgrader_Skin() );
			$done     = $upgrader->install( $api->download_link );
			if ( is_wp_error( $done ) || ! $done ) {
				$result = 'fail';
			}
		}
	}
	if ( 'ok' === $result && ! is_plugin_active( $p['file'] ) ) {
		$activated = activate_plugin( $p['file'], '', false, true );
		if ( is_wp_error( $activated ) ) {
			$result = 'fail';
		}
	}
	delete_transient( 'elementor_activation_redirect' );
	wp_safe_redirect( add_query_arg( array( 'page' => 'sdc-setup', 'sdc_done' => 'plugin-' . $result, 'sdc_slug' => $slug ), admin_url( 'themes.php' ) ) );
	exit;
}
add_action( 'admin_post_sdc_install_plugin', 'sdc_el_install_plugin_action' );

/* -------------------------------------------------------------------------
 * Elementor Kit: brand colours and fonts
 * ---------------------------------------------------------------------- */

/**
 * Active Elementor Kit ID (0 when missing or broken).
 *
 * @return int
 */
function sdc_el_kit_id() {
	if ( ! did_action( 'elementor/loaded' ) || ! class_exists( '\Elementor\Plugin' ) ) {
		return 0;
	}
	$id = absint( get_option( 'elementor_active_kit' ) );
	if ( ! $id ) {
		return 0;
	}
	$post = get_post( $id );
	return ( $post && 'elementor_library' === $post->post_type && 'trash' !== $post->post_status ) ? $id : 0;
}

/**
 * Brand palette and fonts from theme.json (falls back to the style.css tokens).
 *
 * @return array{colors:array,fonts:array}
 */
function sdc_el_brand_tokens() {
	$colors = array();
	$fonts  = array();
	if ( function_exists( 'wp_get_global_settings' ) ) {
		$palette = wp_get_global_settings( array( 'color', 'palette', 'theme' ) );
		foreach ( (array) $palette as $c ) {
			if ( ! empty( $c['slug'] ) && ! empty( $c['color'] ) && sanitize_hex_color( $c['color'] ) ) {
				$colors[ $c['slug'] ] = array( isset( $c['name'] ) ? $c['name'] : $c['slug'], $c['color'] );
			}
		}
		$families = wp_get_global_settings( array( 'typography', 'fontFamilies', 'theme' ) );
		foreach ( (array) $families as $f ) {
			if ( ! empty( $f['slug'] ) && ! empty( $f['name'] ) ) {
				$fonts[ $f['slug'] ] = $f['name'];
			}
		}
	}
	$colors += array(
		'gold'      => array( 'Gold', '#c18b2d' ),
		'gold-dark' => array( 'Gold (dark)', '#775014' ),
		'gold-light' => array( 'Gold (light)', '#f3e3bf' ),
		'cream'     => array( 'Cream', '#fcfaf5' ),
		'ink'       => array( 'Ink', '#231b14' ),
		'ink-2'     => array( 'Ink (soft)', '#5a4a3a' ),
	);
	$fonts += array(
		'serif' => 'Cormorant Garamond',
		'sans'  => 'Source Sans 3',
	);
	return array( 'colors' => $colors, 'fonts' => $fonts );
}

/**
 * Write the brand colours and fonts into the active Kit.
 *
 * @return bool
 */
function sdc_el_seed_kit() {
	$kit = sdc_el_kit_id();
	if ( ! $kit ) {
		return false;
	}
	$t        = sdc_el_brand_tokens();
	$c        = $t['colors'];
	$settings = get_post_meta( $kit, '_elementor_page_settings', true );
	$settings = is_array( $settings ) ? $settings : array();

	$settings['system_colors'] = array(
		array( '_id' => 'primary', 'title' => __( 'Primary', 'sylviasdream-child' ), 'color' => $c['gold-dark'][1] ),
		array( '_id' => 'secondary', 'title' => __( 'Secondary', 'sylviasdream-child' ), 'color' => $c['gold'][1] ),
		array( '_id' => 'text', 'title' => __( 'Text', 'sylviasdream-child' ), 'color' => $c['ink'][1] ),
		array( '_id' => 'accent', 'title' => __( 'Accent', 'sylviasdream-child' ), 'color' => $c['gold-dark'][1] ),
	);
	$custom = array();
	foreach ( $c as $slug => $pair ) {
		$custom[] = array(
			'_id'   => substr( 'sd' . md5( $slug ), 0, 7 ),
			'title' => $pair[0],
			'color' => $pair[1],
		);
	}
	$settings['custom_colors'] = $custom;

	$serif = $t['fonts']['serif'];
	$sans  = $t['fonts']['sans'];
	$settings['system_typography'] = array(
		array( '_id' => 'primary', 'title' => __( 'Primary', 'sylviasdream-child' ), 'typography_typography' => 'custom', 'typography_font_family' => $serif, 'typography_font_weight' => '600' ),
		array( '_id' => 'secondary', 'title' => __( 'Secondary', 'sylviasdream-child' ), 'typography_typography' => 'custom', 'typography_font_family' => $serif, 'typography_font_weight' => '500' ),
		array( '_id' => 'text', 'title' => __( 'Text', 'sylviasdream-child' ), 'typography_typography' => 'custom', 'typography_font_family' => $sans, 'typography_font_weight' => '400' ),
		array( '_id' => 'accent', 'title' => __( 'Accent', 'sylviasdream-child' ), 'typography_typography' => 'custom', 'typography_font_family' => $sans, 'typography_font_weight' => '600' ),
	);
	$settings['body_color']                   = $c['ink'][1];
	$settings['body_typography_typography']   = 'custom';
	$settings['body_typography_font_family']  = $sans;
	$settings['body_typography_font_size']    = array( 'unit' => 'px', 'size' => 19, 'sizes' => array() );
	$settings['link_normal_color']            = $c['gold-dark'][1];
	$settings['container_width']              = array( 'unit' => 'px', 'size' => max( 960, min( 1600, absint( sdc_opt( 'content_width' ) ) ) ), 'sizes' => array() );
	foreach ( array( 'h1', 'h2', 'h3' ) as $hx ) {
		$settings[ $hx . '_typography_typography' ]  = 'custom';
		$settings[ $hx . '_typography_font_family' ] = $serif;
	}
	update_post_meta( $kit, '_elementor_page_settings', $settings );

	// The theme's own styles stay in charge of colours and fonts.
	update_option( 'elementor_disable_color_schemes', 'yes' );
	update_option( 'elementor_disable_typography_schemes', 'yes' );
	update_option( 'sdc_el_kit_seeded', SYLVIASDREAM_CHILD_VERSION, false );
	sdc_el_clear_cache();
	return true;
}

/**
 * Clear Elementor's generated CSS (same as Elementor > Tools > Regenerate CSS).
 */
function sdc_el_clear_cache() {
	if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance->files_manager ) && method_exists( \Elementor\Plugin::$instance->files_manager, 'clear_cache' ) ) {
		try {
			\Elementor\Plugin::$instance->files_manager->clear_cache();
		} catch ( \Throwable $e ) {
			return;
		}
	}
}

/**
 * Seed the Kit once, automatically, the first time an administrator opens the dashboard with a Kit present.
 */
function sdc_el_auto_seed_kit() {
	if ( wp_doing_ajax() || ! current_user_can( 'edit_theme_options' ) || get_option( 'sdc_el_kit_seeded' ) || ! sdc_el_kit_id() ) {
		return;
	}
	sdc_el_seed_kit();
}
add_action( 'admin_init', 'sdc_el_auto_seed_kit', 30 );

/**
 * Create the Elementor default Kit when it is missing.
 *
 * @return bool
 */
function sdc_el_create_kit() {
	if ( sdc_el_kit_id() ) {
		return true;
	}
	if ( ! class_exists( '\Elementor\Plugin' ) || ! isset( \Elementor\Plugin::$instance->kits_manager ) ) {
		return false;
	}
	$km = \Elementor\Plugin::$instance->kits_manager;
	try {
		if ( method_exists( $km, 'create_default' ) ) {
			$id = $km->create_default();
		} elseif ( method_exists( $km, 'create_new_kit' ) ) {
			$id = $km->create_new_kit( __( 'Default Kit', 'sylviasdream-child' ) );
		} else {
			return false;
		}
	} catch ( \Throwable $e ) {
		return false;
	}
	if ( $id && ! get_option( 'elementor_active_kit' ) ) {
		update_option( 'elementor_active_kit', (int) $id );
	}
	return (bool) sdc_el_kit_id();
}

/**
 * admin-post: create the Kit, or apply the brand to it.
 */
function sdc_el_kit_action() {
	check_admin_referer( 'sdc_kit' );
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'Sorry, you are not allowed to do that.', 'sylviasdream-child' ), 403 );
	}
	$ok = sdc_el_create_kit() && sdc_el_seed_kit();
	wp_safe_redirect( add_query_arg( array( 'page' => 'sdc-setup', 'sdc_done' => $ok ? 'kit-ok' : 'kit-fail' ), admin_url( 'themes.php' ) ) );
	exit;
}
add_action( 'admin_post_sdc_kit', 'sdc_el_kit_action' );

/**
 * Notice when Elementor is active but its default Kit is missing.
 */
function sdc_el_kit_notice() {
	if ( ! did_action( 'elementor/loaded' ) || ! current_user_can( 'edit_theme_options' ) || sdc_el_kit_id() ) {
		return;
	}
	$url = wp_nonce_url( admin_url( 'admin-post.php?action=sdc_kit' ), 'sdc_kit' );
	echo '<div class="notice notice-warning"><p><strong>' . esc_html__( 'Elementor needs its settings page (the "default Kit").', 'sylviasdream-child' ) . '</strong> ' . esc_html__( 'Without it Elementor cannot load the Sylvia\'s Dream colours and fonts.', 'sylviasdream-child' ) . ' <a class="button button-primary" href="' . esc_url( $url ) . '">' . esc_html__( 'Create it now', 'sylviasdream-child' ) . '</a></p></div>';
}
add_action( 'admin_notices', 'sdc_el_kit_notice' );

/* -------------------------------------------------------------------------
 * Starter header and footer templates
 * ---------------------------------------------------------------------- */

/**
 * Elementor element id.
 *
 * @param string $seed Seed.
 * @return string
 */
function sdc_el_eid( $seed ) {
	return substr( md5( $seed . wp_rand() ), 0, 7 );
}

/**
 * One full-width section holding one widget.
 *
 * @param string $widget   Widget name (e.g. sd-site-header).
 * @param array  $settings Widget settings.
 * @return array
 */
function sdc_el_section( $widget, $settings = array() ) {
	$zero = array( 'unit' => 'px', 'top' => '0', 'right' => '0', 'bottom' => '0', 'left' => '0', 'isLinked' => true );
	return array(
		'id'       => sdc_el_eid( $widget . 's' ),
		'elType'   => 'section',
		'isInner'  => false,
		'settings' => array(
			'layout'  => 'full_width',
			'gap'     => 'no',
			'padding' => $zero,
		),
		'elements' => array(
			array(
				'id'       => sdc_el_eid( $widget . 'c' ),
				'elType'   => 'column',
				'isInner'  => false,
				'settings' => array(
					'_column_size' => 100,
					'padding'      => $zero,
				),
				'elements' => array(
					array(
						'id'         => sdc_el_eid( $widget . 'w' ),
						'elType'     => 'widget',
						'widgetType' => $widget,
						'isInner'    => false,
						'settings'   => $settings ? $settings : new stdClass(),
						'elements'   => array(),
					),
				),
			),
		),
	);
}

/**
 * Save Elementor data on a post.
 *
 * @param int    $post_id Post ID.
 * @param array  $data    Elements.
 * @param string $type    Elementor document type.
 */
function sdc_el_save_data( $post_id, $data, $type ) {
	update_post_meta( $post_id, '_elementor_edit_mode', 'builder' );
	update_post_meta( $post_id, '_elementor_template_type', $type );
	update_post_meta( $post_id, '_elementor_version', defined( 'ELEMENTOR_VERSION' ) ? ELEMENTOR_VERSION : '3.0.0' );
	update_post_meta( $post_id, '_elementor_data', wp_slash( wp_json_encode( $data ) ) );
	delete_post_meta( $post_id, '_elementor_css' );
}

/**
 * Create the starter header and footer templates (existing ones are kept).
 *
 * @return array<string,int>
 */
function sdc_el_create_starters() {
	$ids      = array();
	$starters = array(
		'sd-starter-header' => array( __( "Sylvia's Dream header (starter)", 'sylviasdream-child' ), 'sd-site-header' ),
		'sd-starter-footer' => array( __( "Sylvia's Dream footer (starter)", 'sylviasdream-child' ), 'sd-site-footer' ),
	);
	foreach ( $starters as $slug => $s ) {
		$existing = get_page_by_path( $slug, OBJECT, 'elementor_library' );
		if ( $existing ) {
			$ids[ $slug ] = (int) $existing->ID;
			continue;
		}
		$post_id = wp_insert_post(
			array(
				'post_type'   => 'elementor_library',
				'post_status' => 'publish',
				'post_title'  => $s[0],
				'post_name'   => $slug,
			),
			true
		);
		if ( is_wp_error( $post_id ) || ! $post_id ) {
			continue;
		}
		sdc_el_save_data( $post_id, array( sdc_el_section( $s[1] ) ), 'section' );
		if ( taxonomy_exists( 'elementor_library_type' ) ) {
			wp_set_object_terms( $post_id, 'section', 'elementor_library_type' );
		}
		$ids[ $slug ] = (int) $post_id;
	}
	return $ids;
}

/**
 * admin-post: create the starter header and footer.
 */
function sdc_el_starters_action() {
	check_admin_referer( 'sdc_starters' );
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( esc_html__( 'Sorry, you are not allowed to do that.', 'sylviasdream-child' ), 403 );
	}
	if ( ! post_type_exists( 'elementor_library' ) ) {
		wp_die( esc_html__( 'Please switch on Elementor first, then try again.', 'sylviasdream-child' ) );
	}
	$ids = sdc_el_create_starters();
	wp_safe_redirect( add_query_arg( array( 'page' => 'sdc-setup', 'sdc_done' => count( $ids ) === 2 ? 'starters-ok' : 'starters-fail' ), admin_url( 'themes.php' ) ) );
	exit;
}
add_action( 'admin_post_sdc_starters', 'sdc_el_starters_action' );

/* -------------------------------------------------------------------------
 * Build a page in Elementor from its current content
 * ---------------------------------------------------------------------- */

/**
 * The main pages and how to find them.
 *
 * @return array<string,array{0:string,1:int}> key => array( label, page ID or 0 )
 */
function sdc_el_main_pages() {
	$find = static function ( $path ) {
		$path = trim( (string) wp_parse_url( (string) $path, PHP_URL_PATH ), '/' );
		if ( '' === $path ) {
			return 0;
		}
		$p = get_page_by_path( $path );
		return $p ? (int) $p->ID : 0;
	};
	$pages = array(
		'home'     => array( __( 'Home', 'sylviasdream-child' ), 'page' === get_option( 'show_on_front' ) ? (int) get_option( 'page_on_front' ) : 0 ),
		'books'    => array( __( 'Books', 'sylviasdream-child' ), $find( sdc_get( 'books_page_url' ) ? sdc_get( 'books_page_url' ) : '/shop-for-books/' ) ),
		'story'    => array( __( 'My Story', 'sylviasdream-child' ), $find( '/my-story/' ) ),
		'speaking' => array( __( 'Invite Sylvia to speak', 'sylviasdream-child' ), $find( '/invite-sylvia-to-speak/' ) ),
		'podcasts' => array( __( 'Podcasts and videos', 'sylviasdream-child' ), $find( '/podcasts-and-videos/' ) ),
		'author'   => array( __( 'Author Support', 'sylviasdream-child' ), $find( '/author-support-publishing-services/' ) ),
		'hoe'      => array( __( 'Heaven on Earth', 'sylviasdream-child' ), $find( sdc_get( 'hoe_cta_url' ) ? sdc_get( 'hoe_cta_url' ) : '/heaven-on-earth/' ) ),
		'give'     => array( __( 'Give', 'sylviasdream-child' ), $find( sdc_get( 'give_url' ) ? sdc_get( 'give_url' ) : '/give/' ) ),
		'contact'  => array( __( 'Contact', 'sylviasdream-child' ), $find( sdc_get( 'contact_url' ) ? sdc_get( 'contact_url' ) : '/contact/' ) ),
	);
	return (array) apply_filters( 'sdc_el_main_pages', $pages );
}

/**
 * Patterns that have their own widget.
 *
 * @return array<string,string>
 */
function sdc_el_pattern_widgets() {
	return array(
		'sylviasdream-child/hero'            => 'sd-home-hero',
		'sylviasdream-child/trust-strip'     => 'sd-highlights',
		'sylviasdream-child/featured-books'  => 'sd-featured-books',
		'sylviasdream-child/about-sylvia'    => 'sd-meet-sylvia',
		'sylviasdream-child/heaven-on-earth' => 'sd-heaven-on-earth',
		'sylviasdream-child/dream-partners'  => 'sd-dream-partners',
		'sylviasdream-child/latest-posts'    => 'sd-latest-devotionals',
		'sylviasdream-child/author-promo'    => 'sd-author-support',
		'sylviasdream-child/books-showcase'  => 'sd-book-showcase',
		'sylviasdream-child/newsletter-cta'  => 'sd-newsletter',
		'sylviasdream-child/page-header'     => 'sd-page-banner',
		'sylviasdream-child/testimonials'    => 'sd-reader-reviews',
	);
}

/**
 * Content of a registered pattern.
 *
 * @param string $slug Slug.
 * @return string
 */
function sdc_el_pattern_content( $slug ) {
	if ( ! class_exists( 'WP_Block_Patterns_Registry' ) ) {
		return '';
	}
	$p = WP_Block_Patterns_Registry::get_instance()->get_registered( $slug );
	return ( $p && ! empty( $p['content'] ) ) ? (string) $p['content'] : '';
}

/**
 * A "Section from your page" widget holding one block's markup.
 *
 * @param string $markup Block markup.
 * @return array
 */
function sdc_el_kept_section( $markup ) {
	$parts  = sdc_old_section_parts( $markup );
	$texts  = array();
	$photos = array();
	foreach ( $parts['texts'] as $t ) {
		$texts[] = array( '_id' => sdc_el_eid( $t ), 'orig' => $t, 'text' => $t );
	}
	foreach ( $parts['photos'] as $p ) {
		$photos[] = array( '_id' => sdc_el_eid( $p['src'] ), 'orig' => $p['src'], 'photo' => array( 'id' => '', 'url' => $p['src'] ) );
	}
	return sdc_el_section( 'sd-kept-section', array( 'markup' => $markup, 'texts' => $texts, 'photos' => $photos ) );
}

/**
 * Text of the first element matching a test inside block markup.
 *
 * @param string $html  HTML.
 * @param string $query XPath.
 * @return string
 */
function sdc_el_xpath_text( $html, $query ) {
	$dom = sdc_old_section_dom( $html );
	if ( ! $dom ) {
		return '';
	}
	$xp = new DOMXPath( $dom );
	$n  = $xp->query( $query );
	return ( $n && $n->length ) ? trim( $n->item( 0 )->textContent ) : '';
}

/**
 * Elementor data built from a page's current content.
 *
 * @param WP_Post $post Page.
 * @param bool    $is_home Is the Home page.
 * @return array
 */
function sdc_el_page_data( $post, $is_home ) {
	$content = (string) $post->post_content;
	$data    = array();
	if ( '' === trim( $content ) || ! has_blocks( $content ) ) {
		if ( $is_home ) {
			foreach ( array( 'sd-home-hero', 'sd-highlights', 'sd-featured-books', 'sd-meet-sylvia', 'sd-heaven-on-earth', 'sd-dream-partners', 'sd-latest-devotionals', 'sd-reader-reviews', 'sd-newsletter' ) as $w ) {
				$data[] = sdc_el_section( $w );
			}
			return $data;
		}
		if ( '' !== trim( $content ) ) {
			$data[] = sdc_el_section( 'sd-page-banner' );
			$data[] = sdc_el_kept_section( $content );
			return $data;
		}
		return array( sdc_el_section( 'sd-page-banner' ) );
	}
	$map    = sdc_el_pattern_widgets();
	$banner = false;
	foreach ( parse_blocks( $content ) as $block ) {
		$name = isset( $block['blockName'] ) ? (string) $block['blockName'] : '';
		if ( '' === $name && '' === trim( (string) $block['innerHTML'] ) ) {
			continue; // Whitespace.
		}
		if ( 'core/pattern' === $name ) {
			$slug = isset( $block['attrs']['slug'] ) ? (string) $block['attrs']['slug'] : '';
			if ( isset( $map[ $slug ] ) ) {
				$data[] = sdc_el_section( $map[ $slug ] );
				$banner = $banner || in_array( $map[ $slug ], array( 'sd-page-banner', 'sd-home-hero' ), true );
				continue;
			}
			$inner = sdc_el_pattern_content( $slug );
			if ( '' !== trim( $inner ) ) {
				$data[] = sdc_el_kept_section( $inner );
			}
			continue;
		}
		$markup = serialize_block( $block );
		$class  = isset( $block['attrs']['className'] ) ? (string) $block['attrs']['className'] : '';
		if ( ! $banner && 'core/cover' === $name && false !== strpos( $class, 'sd-pagehead-cover' ) ) {
			$data[] = sdc_el_section(
				'sd-page-banner',
				array(
					'eyebrow' => sdc_el_xpath_text( $markup, '//*[contains(concat(" ",normalize-space(@class)," ")," sd-eyebrow ")]' ),
					'title'   => sdc_el_xpath_text( $markup, '//h1' ),
					'intro'   => sdc_el_xpath_text( $markup, '//*[contains(concat(" ",normalize-space(@class)," ")," sd-lead ")]' ),
					'image'   => array( 'id' => isset( $block['attrs']['id'] ) ? absint( $block['attrs']['id'] ) : '', 'url' => isset( $block['attrs']['url'] ) ? (string) $block['attrs']['url'] : '' ),
				)
			);
			$banner = true;
			continue;
		}
		$data[] = sdc_el_kept_section( $markup );
	}
	if ( ! $banner && ! $is_home ) {
		array_unshift( $data, sdc_el_section( 'sd-page-banner' ) );
	}
	return $data;
}

/**
 * admin-post: build one main page in Elementor.
 */
function sdc_el_build_page_action() {
	$id = isset( $_GET['page_id'] ) ? absint( $_GET['page_id'] ) : 0;
	check_admin_referer( 'sdc_build_page_' . $id );
	if ( ! $id || ! current_user_can( 'edit_theme_options' ) || ! current_user_can( 'edit_post', $id ) ) {
		wp_die( esc_html__( 'Sorry, you are not allowed to do that.', 'sylviasdream-child' ), 403 );
	}
	$post = get_post( $id );
	if ( ! $post || 'page' !== $post->post_type || ! did_action( 'elementor/loaded' ) ) {
		wp_die( esc_html__( 'Please switch on Elementor first, then try again.', 'sylviasdream-child' ) );
	}
	$again = ! empty( $_GET['again'] );
	if ( sdc_is_elementor_page( $id ) && ! $again ) {
		wp_safe_redirect( add_query_arg( array( 'page' => 'sdc-setup', 'sdc_done' => 'page-exists' ), admin_url( 'themes.php' ) ) );
		exit;
	}
	// Keep a copy of the page as it is now in its revisions (forced, even if unchanged).
	if ( function_exists( '_wp_put_post_revision' ) ) {
		_wp_put_post_revision( $post );
	}
	if ( sdc_is_elementor_page( $id ) ) {
		update_post_meta( $id, '_sdc_old_elementor_data', get_post_meta( $id, '_elementor_data', true ) );
	}
	$is_home = (int) get_option( 'page_on_front' ) === $id;
	$data    = sdc_el_page_data( $post, $is_home );
	if ( ! metadata_exists( 'post', $id, '_sdc_old_template' ) ) {
		update_post_meta( $id, '_sdc_old_template', (string) get_post_meta( $id, '_wp_page_template', true ) );
	}
	update_post_meta( $id, '_wp_page_template', 'template-full-width.php' );
	sdc_el_save_data( $id, $data, 'wp-page' );
	sdc_el_clear_cache();
	wp_safe_redirect( add_query_arg( array( 'page' => 'sdc-setup', 'sdc_done' => 'page-ok' ), admin_url( 'themes.php' ) ) );
	exit;
}
add_action( 'admin_post_sdc_build_page', 'sdc_el_build_page_action' );

/**
 * admin-post: go back to the old (block editor) version of a page.
 */
function sdc_el_unbuild_page_action() {
	$id = isset( $_GET['page_id'] ) ? absint( $_GET['page_id'] ) : 0;
	check_admin_referer( 'sdc_unbuild_page_' . $id );
	if ( ! $id || ! current_user_can( 'edit_theme_options' ) || ! current_user_can( 'edit_post', $id ) ) {
		wp_die( esc_html__( 'Sorry, you are not allowed to do that.', 'sylviasdream-child' ), 403 );
	}
	delete_post_meta( $id, '_elementor_edit_mode' );
	if ( metadata_exists( 'post', $id, '_sdc_old_template' ) ) {
		update_post_meta( $id, '_wp_page_template', (string) get_post_meta( $id, '_sdc_old_template', true ) );
		delete_post_meta( $id, '_sdc_old_template' );
	}
	wp_safe_redirect( add_query_arg( array( 'page' => 'sdc-setup', 'sdc_done' => 'page-back' ), admin_url( 'themes.php' ) ) );
	exit;
}
add_action( 'admin_post_sdc_unbuild_page', 'sdc_el_unbuild_page_action' );

/* -------------------------------------------------------------------------
 * Appearance > Set up page editor
 * ---------------------------------------------------------------------- */

/**
 * Menu item.
 */
function sdc_el_setup_menu() {
	add_theme_page( __( 'Set up page editor', 'sylviasdream-child' ), __( 'Set up page editor', 'sylviasdream-child' ), 'edit_theme_options', 'sdc-setup', 'sdc_el_setup_page' );
}
add_action( 'admin_menu', 'sdc_el_setup_menu' );

/**
 * Result message after an action.
 */
function sdc_el_setup_message() {
	$done = isset( $_GET['sdc_done'] ) ? sanitize_key( wp_unslash( $_GET['sdc_done'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display only.
	$msgs = array(
		'plugin-ok'     => array( 'success', __( 'Done. The plugin is installed and switched on.', 'sylviasdream-child' ) ),
		'plugin-fail'   => array( 'error', __( 'The plugin could not be installed here. Install it from Plugins > Add New instead.', 'sylviasdream-child' ) ),
		'kit-ok'        => array( 'success', __( 'Elementor now uses the Sylvia\'s Dream colours and fonts.', 'sylviasdream-child' ) ),
		'kit-fail'      => array( 'error', __( 'Elementor\'s settings page could not be created. Open Elementor > Tools and choose "Recreate Kit", then try again.', 'sylviasdream-child' ) ),
		'starters-ok'   => array( 'success', __( 'The starter header and footer are ready in Templates > Saved Templates. Choose them in Appearance > Sylvia\'s Dream Theme Options when you want to use them.', 'sylviasdream-child' ) ),
		'starters-fail' => array( 'error', __( 'The starter templates could not be created.', 'sylviasdream-child' ) ),
		'page-ok'       => array( 'success', __( 'The page is now built in Elementor. The old version is kept in the page\'s Revisions.', 'sylviasdream-child' ) ),
		'page-exists'   => array( 'warning', __( 'That page is already built in Elementor, so nothing was changed. Use "Build again" only if you want to start that page over.', 'sylviasdream-child' ) ),
		'page-back'     => array( 'success', __( 'The page uses its old version again (the Elementor version is kept, so you can switch back).', 'sylviasdream-child' ) ),
	);
	if ( isset( $msgs[ $done ] ) ) {
		echo '<div class="notice notice-' . esc_attr( $msgs[ $done ][0] ) . ' is-dismissible"><p>' . esc_html( $msgs[ $done ][1] ) . '</p></div>';
	}
}

/**
 * The set-up screen.
 */
function sdc_el_setup_page() {
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}
	$el_on = did_action( 'elementor/loaded' );
	?>
	<div class="wrap sdc-setup">
		<h1><?php esc_html_e( 'Set up the page editor', 'sylviasdream-child' ); ?></h1>
		<?php sdc_el_setup_message(); ?>
		<p class="sdc-setup__lead"><?php esc_html_e( 'Four steps. Each button does one thing and can be clicked again safely.', 'sylviasdream-child' ); ?></p>

		<h2><?php esc_html_e( 'Step 1. Install the free plugins', 'sylviasdream-child' ); ?></h2>
		<table class="widefat striped sdc-setup__table"><tbody>
		<?php
		foreach ( sdc_el_plugins() as $slug => $p ) :
			$status = sdc_el_plugin_status( $slug );
			$url    = wp_nonce_url( admin_url( 'admin-post.php?action=sdc_install_plugin&slug=' . $slug ), 'sdc_install_' . $slug );
			?>
			<tr>
				<td><strong><?php echo esc_html( $p['name'] ); ?></strong><br><?php echo esc_html( $p['why'] ); ?></td>
				<td style="width:260px">
					<?php if ( 'active' === $status ) : ?>
						<span class="sdc-ok"><?php esc_html_e( 'Ready', 'sylviasdream-child' ); ?></span>
					<?php elseif ( current_user_can( 'install_plugins' ) ) : ?>
						<a class="button button-primary button-hero" href="<?php echo esc_url( $url ); ?>"><?php echo 'missing' === $status ? esc_html__( 'Install and switch on', 'sylviasdream-child' ) : esc_html__( 'Switch on', 'sylviasdream-child' ); ?></a>
					<?php else : ?>
						<?php esc_html_e( 'Ask an administrator to install it.', 'sylviasdream-child' ); ?>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody></table>

		<h2><?php esc_html_e( 'Step 2. Give Elementor the Sylvia\'s Dream colours and fonts', 'sylviasdream-child' ); ?></h2>
		<?php if ( ! $el_on ) : ?>
			<p><?php esc_html_e( 'Install Elementor first (step 1).', 'sylviasdream-child' ); ?></p>
		<?php else : ?>
			<p>
				<?php echo sdc_el_kit_id() ? esc_html__( 'Elementor\'s settings page (default Kit) is ready.', 'sylviasdream-child' ) : esc_html__( 'Elementor\'s settings page (default Kit) is missing. The button creates it.', 'sylviasdream-child' ); ?>
				<a class="button button-primary" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=sdc_kit' ), 'sdc_kit' ) ); ?>"><?php esc_html_e( 'Use the Sylvia\'s Dream colours and fonts', 'sylviasdream-child' ); ?></a>
			</p>
		<?php endif; ?>

		<h2><?php esc_html_e( 'Step 3. Make the starter header and footer', 'sylviasdream-child' ); ?></h2>
		<?php if ( $el_on ) : ?>
			<p><a class="button button-primary" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=sdc_starters' ), 'sdc_starters' ) ); ?>"><?php esc_html_e( 'Create the starter header and footer', 'sylviasdream-child' ); ?></a>
			<?php esc_html_e( 'Nothing changes on the website until you choose them in Appearance > Sylvia\'s Dream Theme Options.', 'sylviasdream-child' ); ?></p>
		<?php else : ?>
			<p><?php esc_html_e( 'Install Elementor first (step 1).', 'sylviasdream-child' ); ?></p>
		<?php endif; ?>

		<h2><?php esc_html_e( 'Step 4. Build each main page in Elementor', 'sylviasdream-child' ); ?></h2>
		<p><?php esc_html_e( 'Each button copies the page as it is now into Elementor, part by part, with the same words, photos and buttons. The old version is kept in the page\'s Revisions, and "Use old version" switches back. Payment, Dream Partner and download links are never changed. The Journal stays the normal posts page.', 'sylviasdream-child' ); ?></p>
		<table class="widefat striped sdc-setup__table">
			<thead><tr><th><?php esc_html_e( 'Page', 'sylviasdream-child' ); ?></th><th><?php esc_html_e( 'Now', 'sylviasdream-child' ); ?></th><th><?php esc_html_e( 'Action', 'sylviasdream-child' ); ?></th></tr></thead>
			<tbody>
			<?php foreach ( sdc_el_main_pages() as $key => $pg ) : ?>
				<tr>
					<td><strong><?php echo esc_html( $pg[0] ); ?></strong></td>
					<?php if ( ! $pg[1] ) : ?>
						<td colspan="2"><?php esc_html_e( 'Page not found.', 'sylviasdream-child' ); ?></td>
					<?php else : ?>
						<?php $built = sdc_is_elementor_page( $pg[1] ); ?>
						<td><?php echo $built ? esc_html__( 'Built in Elementor', 'sylviasdream-child' ) : esc_html__( 'Normal editor', 'sylviasdream-child' ); ?></td>
						<td>
							<?php if ( ! $el_on ) : ?>
								<?php esc_html_e( 'Install Elementor first.', 'sylviasdream-child' ); ?>
							<?php elseif ( $built ) : ?>
								<a class="button button-primary" href="<?php echo esc_url( admin_url( 'post.php?post=' . (int) $pg[1] . '&action=elementor' ) ); ?>"><?php esc_html_e( 'Edit with Elementor', 'sylviasdream-child' ); ?></a>
								<a class="button" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=sdc_unbuild_page&page_id=' . (int) $pg[1] ), 'sdc_unbuild_page_' . (int) $pg[1] ) ); ?>"><?php esc_html_e( 'Use old version', 'sylviasdream-child' ); ?></a>
								<a class="button sdc-again" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=sdc_build_page&again=1&page_id=' . (int) $pg[1] ), 'sdc_build_page_' . (int) $pg[1] ) ); ?>" onclick="return confirm('<?php echo esc_js( __( 'Start this page over from its current saved content? Your Elementor changes are kept in a backup on the page.', 'sylviasdream-child' ) ); ?>');"><?php esc_html_e( 'Build again', 'sylviasdream-child' ); ?></a>
							<?php else : ?>
								<a class="button button-primary" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=sdc_build_page&page_id=' . (int) $pg[1] ), 'sdc_build_page_' . (int) $pg[1] ) ); ?>"><?php esc_html_e( 'Build this page in Elementor', 'sylviasdream-child' ); ?></a>
							<?php endif; ?>
						</td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<p><?php esc_html_e( 'After building pages, open Elementor > Tools > Regenerate CSS & Data once.', 'sylviasdream-child' ); ?></p>
	</div>
	<?php
}

/**
 * Larger, calmer set-up screen.
 */
function sdc_el_setup_styles() {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || ! in_array( $screen->id, array( 'appearance_page_sdc-setup', 'dashboard' ), true ) ) {
		return;
	}
	wp_add_inline_style( 'common', '.sdc-setup{max-width:1000px}.sdc-setup h2{font-size:1.5em;margin-top:2em}.sdc-setup p,.sdc-setup td{font-size:16px;line-height:1.6}.sdc-setup__lead{font-size:18px!important}.sdc-ok{color:#1e6b34;font-weight:700;font-size:16px}.sdc-guide ol{font-size:17px;line-height:1.7;padding-left:1.2em}.sdc-guide li{margin-bottom:.7em}.sdc-guide .button-hero{margin:.4em .4em 0 0}' );
}
add_action( 'admin_enqueue_scripts', 'sdc_el_setup_styles' );

/* -------------------------------------------------------------------------
 * Dashboard: "How to edit your website"
 * ---------------------------------------------------------------------- */

/**
 * Register the dashboard box.
 */
function sdc_el_dashboard_widget() {
	if ( ! current_user_can( 'edit_pages' ) || ! (int) sdc_opt( 'guide_widget' ) ) {
		return;
	}
	wp_add_dashboard_widget( 'sdc_edit_guide', __( 'How to edit your website', 'sylviasdream-child' ), 'sdc_el_dashboard_widget_html' );
	// Put it first.
	global $wp_meta_boxes;
	if ( isset( $wp_meta_boxes['dashboard']['normal']['core']['sdc_edit_guide'] ) ) {
		$box = array( 'sdc_edit_guide' => $wp_meta_boxes['dashboard']['normal']['core']['sdc_edit_guide'] );
		unset( $wp_meta_boxes['dashboard']['normal']['core']['sdc_edit_guide'] );
		$wp_meta_boxes['dashboard']['normal']['core'] = $box + $wp_meta_boxes['dashboard']['normal']['core']; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
	}
}
add_action( 'wp_dashboard_setup', 'sdc_el_dashboard_widget' );

/**
 * Dashboard box contents.
 */
function sdc_el_dashboard_widget_html() {
	$home = (int) get_option( 'page_on_front' );
	?>
	<div class="sdc-guide">
		<ol>
			<li><?php esc_html_e( 'Click "Pages" on the left. You will see a list of your pages.', 'sylviasdream-child' ); ?></li>
			<li><?php esc_html_e( 'Move your mouse over the page you want to change and click "Edit with Elementor".', 'sylviasdream-child' ); ?></li>
			<li><?php esc_html_e( 'Click on the words or the photo you want to change. A panel opens on the left.', 'sylviasdream-child' ); ?></li>
			<li><?php esc_html_e( 'In the panel, open "Texts" to change words, "Photos" to change a picture, "Links and buttons" for buttons.', 'sylviasdream-child' ); ?></li>
			<li><?php esc_html_e( 'Click the green "Update" button at the bottom left. Your change is now on the website.', 'sylviasdream-child' ); ?></li>
			<li><?php esc_html_e( 'Made a mistake? Click the clock symbol at the bottom left ("History") to go back.', 'sylviasdream-child' ); ?></li>
		</ol>
		<p>
			<?php if ( $home && did_action( 'elementor/loaded' ) && current_user_can( 'edit_post', $home ) ) : ?>
				<a class="button button-primary button-hero" href="<?php echo esc_url( admin_url( 'post.php?post=' . $home . '&action=elementor' ) ); ?>"><?php esc_html_e( 'Edit the Home page', 'sylviasdream-child' ); ?></a>
			<?php endif; ?>
			<a class="button button-hero" href="<?php echo esc_url( admin_url( 'edit.php?post_type=page' ) ); ?>"><?php esc_html_e( 'See all pages', 'sylviasdream-child' ); ?></a>
		</p>
		<p><?php esc_html_e( 'Books, prices and covers are changed in Products. New devotionals are written in Posts > Add New.', 'sylviasdream-child' ); ?></p>
	</div>
	<?php
}

/* -------------------------------------------------------------------------
 * Simpler Elementor screen for editors who are not administrators
 * ---------------------------------------------------------------------- */

/**
 * Is the simple editor screen on for this user?
 *
 * @return bool
 */
function sdc_el_simple_editor_on() {
	$on = (int) sdc_opt( 'simple_editor' ) && ! current_user_can( 'manage_options' );
	return (bool) apply_filters( 'sdc_el_simple_editor', $on );
}

/**
 * Hide advanced panels and adverts, larger panel text (CSS only, nothing is removed).
 */
function sdc_el_editor_styles() {
	$css = '#elementor-panel .sd-el-help,#elementor-panel .sd-el-note{font-size:14px;line-height:1.55;font-style:normal;color:inherit}';
	if ( sdc_el_simple_editor_on() ) {
		$css .= '#elementor-panel .elementor-control-title,#elementor-panel .elementor-control-field-description,#elementor-panel .elementor-panel-heading-title,#elementor-panel .elementor-repeater-row-item-title{font-size:15px}'
			. '#elementor-panel input,#elementor-panel textarea,#elementor-panel select{font-size:15px!important}'
			. '#elementor-panel .elementor-element .title{font-size:13px}'
			. '#elementor-panel-category-pro-elements,#elementor-panel-category-theme-elements,#elementor-panel-category-theme-elements-single,#elementor-panel-category-woocommerce-elements,#elementor-panel-category-wordpress,#elementor-panel-get-pro-elements,#elementor-panel-get-pro-elements-sticky,.elementor-panel-heading-promotion,.elementor-nerd-box,.e-notice-bar,#elementor-panel-footer-sub-menu-item-save-template,.elementor-control-type-section.elementor-control-section_custom_css_pro,.elementor-control-section_custom_attributes_pro,.elementor-control-section_motion_effect,.elementor-control-section_effects{display:none!important}'
			. '.elementor-panel-navigation .elementor-panel-navigation-tab.elementor-tab-control-advanced{display:none!important}';
	}
	wp_register_style( 'sdc-elementor-editor', false, array(), SYLVIASDREAM_CHILD_VERSION );
	wp_enqueue_style( 'sdc-elementor-editor' );
	wp_add_inline_style( 'sdc-elementor-editor', $css );
}
add_action( 'elementor/editor/after_enqueue_styles', 'sdc_el_editor_styles' );
