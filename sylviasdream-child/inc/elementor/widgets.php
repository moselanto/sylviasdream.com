<?php
/**
 * 1.21.0: Sylvia's Dream widgets for Elementor (free Elementor, no Pro).
 *
 * Every section used across the site's pages, the site header and footer and
 * a few small building blocks are widgets in the "Sylvia's Dream" panel. Each
 * widget has the same plain-English panels: Texts, Photos, Links and buttons,
 * Settings, and in the Style tab: Colours and text, Buttons, Space and
 * background. The list is in inc/elementor/registry.php.
 *
 * Nothing here touches Elementor before Elementor has loaded, so the site
 * works the same with Elementor switched off.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Registry entry for a widget key.
 *
 * @param string $key Widget key.
 * @return array
 */
function sdc_el_entry( $key ) {
	$reg = sdc_el_registry();
	return isset( $reg[ $key ] ) ? $reg[ $key ] : array(
		'title'  => $key,
		'help'   => '',
		'icon'   => 'eicon-heart',
		'render' => '',
		'fields' => array(),
	);
}

/* -------------------------------------------------------------------------
 * Small renderers (header, footer and building blocks)
 * ---------------------------------------------------------------------- */

/**
 * Run a callback with some Customizer settings changed for this widget only.
 *
 * @param array    $mods     sdc_ key (without prefix) => value.
 * @param callable $callback Callback.
 */
function sdc_el_with_mods( $mods, $callback ) {
	$filters = array();
	foreach ( $mods as $key => $value ) {
		$f = static function () use ( $value ) {
			return $value;
		};
		add_filter( 'theme_mod_sdc_' . $key, $f, 99 );
		$filters[ $key ] = $f;
	}
	try {
		call_user_func( $callback );
	} finally {
		foreach ( $filters as $key => $f ) {
			remove_filter( 'theme_mod_sdc_' . $key, $f, 99 );
		}
	}
}

/**
 * Full site header (with the mobile drawer, since free Elementor has no phone menu).
 *
 * @param array $a Values.
 */
function sdc_el_render_site_header( $a ) {
	static $done = false;
	if ( $done && ! sdc_widget_is_editor() ) {
		return; // The header uses fixed ids: show it once per page.
	}
	$done = true;
	$mods = array();
	if ( '' !== trim( (string) sdc_v( $a, 'give_label' ) ) ) {
		$mods['give_label'] = sanitize_text_field( (string) sdc_v( $a, 'give_label' ) );
	}
	if ( in_array( sdc_v( $a, 'utility' ), array( 'yes', 'no' ), true ) ) {
		$mods['show_utility_bar'] = 'yes' === sdc_v( $a, 'utility' );
	}
	sdc_el_with_mods(
		$mods,
		static function () {
			get_template_part( 'template-parts/site-header' );
		}
	);
}

/**
 * Full site footer.
 *
 * @param array $a Values.
 */
function sdc_el_render_site_footer( $a ) {
	$mods = array();
	if ( '' !== trim( (string) sdc_v( $a, 'about' ) ) ) {
		$mods['footer_about'] = sanitize_textarea_field( (string) sdc_v( $a, 'about' ) );
	}
	sdc_el_with_mods(
		$mods,
		static function () {
			get_template_part( 'template-parts/site-footer' );
		}
	);
}

/**
 * Logo.
 *
 * @param array $a Values.
 */
function sdc_el_render_logo( $a ) {
	$width = max( 40, min( 600, absint( sdc_v( $a, 'width', 180 ) ) ) );
	$id    = absint( sdc_v( $a, 'image_id', 0 ) );
	echo '<a class="sd-brand sd-el-logo" href="' . esc_url( home_url( '/' ) ) . '" rel="home" style="max-width:' . (int) $width . 'px">';
	if ( $id && wp_attachment_is_image( $id ) ) {
		echo wp_get_attachment_image( $id, 'medium', false, array( 'alt' => get_bloginfo( 'name' ) ) );
	} elseif ( has_custom_logo() && function_exists( 'sdc_header_logo' ) ) {
		sdc_header_logo();
	} else {
		echo '<span class="sd-brand__text"><span class="sd-brand__name notranslate" translate="no">' . esc_html( get_bloginfo( 'name' ) ) . '</span>';
		if ( sdc_get( 'tagline' ) ) {
			echo '<span class="sd-brand__tagline">' . esc_html( sdc_get( 'tagline' ) ) . '</span>';
		}
		echo '</span>';
	}
	echo '</a>';
}

/**
 * Simple menu.
 *
 * @param array $a Values.
 */
function sdc_el_render_menu( $a ) {
	$loc = (string) sdc_v( $a, 'location', 'footer_books' );
	if ( ! in_array( $loc, array( 'primary', 'footer_books', 'footer_about', 'footer_legal' ), true ) || ! has_nav_menu( $loc ) ) {
		if ( sdc_widget_is_editor() ) {
			echo '<p class="sd-el-empty">' . esc_html__( 'No menu is set for this place yet. Add one in Appearance > Menus.', 'sylviasdream-child' ) . '</p>';
		}
		return;
	}
	$layout = 'across' === sdc_v( $a, 'layout' ) ? ' sd-el-menu--across' : '';
	echo '<nav class="sd-el-menu' . esc_attr( $layout ) . '" aria-label="' . esc_attr( sdc_v( $a, 'title' ) ? sdc_v( $a, 'title' ) : __( 'Menu', 'sylviasdream-child' ) ) . '">';
	if ( sdc_v( $a, 'title' ) ) {
		echo '<p class="sd-footer__heading">' . esc_html( sdc_v( $a, 'title' ) ) . '</p>';
	}
	wp_nav_menu(
		array(
			'theme_location' => $loc,
			'container'      => false,
			'depth'          => 1,
			'fallback_cb'    => false,
		)
	);
	echo '</nav>';
}

/**
 * Social icons.
 *
 * @param array $a Values.
 */
function sdc_el_render_social( $a ) {
	unset( $a );
	sdc_social_links();
}

/**
 * The product a button widget is about.
 *
 * @param array $a Values.
 * @return WC_Product|null
 */
function sdc_el_product( $a ) {
	if ( ! function_exists( 'wc_get_product' ) ) {
		return null;
	}
	$id = absint( sdc_v( $a, 'product', 0 ) );
	if ( ! $id && is_singular( 'product' ) ) {
		$id = (int) get_queried_object_id();
	}
	$p = $id ? wc_get_product( $id ) : null;
	return $p instanceof WC_Product ? $p : null;
}

/**
 * Buy ebook button: displays the book's saved Stripe link, never changes it.
 *
 * @param array $a Values.
 */
function sdc_el_render_buy_ebook( $a ) {
	$p   = sdc_el_product( $a );
	$url = ( $p && function_exists( 'sdc_product_buy_link' ) ) ? sdc_product_buy_link( $p ) : '';
	if ( '' === $url ) {
		if ( sdc_widget_is_editor() ) {
			echo '<p class="sd-el-empty">' . esc_html__( 'Choose a book that has a "Buy ebook link (Stripe)". Books without one show no button.', 'sylviasdream-child' ) . '</p>';
		}
		return;
	}
	$label = trim( (string) sdc_v( $a, 'label' ) );
	printf(
		'<div class="sd-btn-row"><a class="sd-btn sd-btn--primary" href="%1$s" rel="noopener">%2$s</a></div>',
		esc_url( $url ),
		esc_html( '' !== $label ? $label : __( 'Buy ebook', 'sylviasdream-child' ) )
	);
}

/**
 * Amazon / Kindle button.
 *
 * @param array $a Values.
 */
function sdc_el_render_amazon( $a ) {
	$p      = sdc_el_product( $a );
	$fields = function_exists( 'sdc_amazon_fields' ) ? sdc_amazon_fields() : array();
	$key    = (string) sdc_v( $a, 'format', '_sdc_amazon_kindle' );
	$url    = ( $p && isset( $fields[ $key ] ) ) ? (string) $p->get_meta( $key ) : '';
	if ( '' === $url || ! sdc_is_amazon_url( $url ) ) {
		if ( sdc_widget_is_editor() ) {
			echo '<p class="sd-el-empty">' . esc_html__( 'Choose a book that has this Amazon address saved in Products.', 'sylviasdream-child' ) . '</p>';
		}
		return;
	}
	$label = trim( (string) sdc_v( $a, 'label' ) );
	printf(
		'<div class="sd-btn-row"><a class="sd-btn sd-btn--ghost" href="%1$s" target="_blank" rel="noopener">%2$s<span class="screen-reader-text"> %3$s</span></a></div>',
		esc_url( $url ),
		esc_html( '' !== $label ? $label : $fields[ $key ] ),
		esc_html__( '(opens Amazon in a new tab)', 'sylviasdream-child' )
	);
}

/**
 * Find the first Jetpack form block in a list of blocks (follows reusable blocks once).
 *
 * @param array $blocks Parsed blocks.
 * @param int   $depth  Recursion guard.
 * @return array|null
 */
function sdc_el_find_form_block( $blocks, $depth = 0 ) {
	if ( $depth > 6 ) {
		return null;
	}
	foreach ( $blocks as $b ) {
		$name = isset( $b['blockName'] ) ? (string) $b['blockName'] : '';
		if ( 'jetpack/contact-form' === $name ) {
			return $b;
		}
		if ( 'core/block' === $name && ! empty( $b['attrs']['ref'] ) ) {
			$ref = get_post( absint( $b['attrs']['ref'] ) );
			if ( $ref && 'wp_block' === $ref->post_type ) {
				$found = sdc_el_find_form_block( parse_blocks( $ref->post_content ), $depth + 1 );
				if ( $found ) {
					return $found;
				}
			}
		}
		if ( ! empty( $b['innerBlocks'] ) ) {
			$found = sdc_el_find_form_block( $b['innerBlocks'], $depth + 1 );
			if ( $found ) {
				return $found;
			}
		}
	}
	return null;
}

/**
 * Contact form wrapper: renders the existing Jetpack form from the Contact page.
 *
 * @param array $a Values.
 */
function sdc_el_render_contact_form( $a ) {
	$page = get_post( absint( sdc_v( $a, 'page', 0 ) ) );
	$form = ( $page && 'page' === $page->post_type ) ? sdc_el_find_form_block( parse_blocks( $page->post_content ) ) : null;
	echo '<div class="sd-el-form">';
	if ( sdc_v( $a, 'title' ) ) {
		echo '<h2>' . esc_html( sdc_v( $a, 'title' ) ) . '</h2>';
	}
	if ( sdc_v( $a, 'text' ) ) {
		echo '<p class="sd-lead">' . esc_html( sdc_v( $a, 'text' ) ) . '</p>';
	}
	if ( $form ) {
		echo render_block( $form ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Jetpack block output.
	} elseif ( sdc_widget_is_editor() ) {
		echo '<p class="sd-el-empty">' . esc_html__( 'No contact form was found on that page. Check the page number in Settings.', 'sylviasdream-child' ) . '</p>';
	}
	echo '</div>';
}

/**
 * Plain button.
 *
 * @param array $a Values.
 */
function sdc_el_render_button( $a ) {
	$style = in_array( sdc_v( $a, 'style' ), array( '', 'sd-btn--ghost', 'sd-btn--light' ), true ) ? (string) sdc_v( $a, 'style' ) : '';
	$align = in_array( sdc_v( $a, 'align' ), array( 'left', 'center', 'right' ), true ) ? (string) sdc_v( $a, 'align' ) : 'left';
	echo '<div class="sd-el-button sd-el-button--' . esc_attr( $align ) . '">';
	sdc_section_buttons( array( array( sdc_v( $a, 'label' ), sdc_v( $a, 'url' ), $style ) ) );
	echo '</div>';
}

/**
 * Dream Partner progress bar.
 *
 * @param array $a Values.
 */
function sdc_el_render_progress( $a ) {
	if ( ! shortcode_exists( 'sd_partner_progress' ) ) {
		if ( sdc_widget_is_editor() ) {
			echo '<p class="sd-el-empty">' . esc_html__( 'The progress bar needs the Sylvia\'s Dream Core plugin to be active.', 'sylviasdream-child' ) . '</p>';
		}
		return;
	}
	$card = 'yes' === sdc_v( $a, 'card', 'yes' );
	echo $card ? '<div class="sd-card sd-give-card">' : '<div>';
	echo do_shortcode( '[sd_partner_progress]' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- shortcode escapes its own output.
	echo '</div>';
}

/* -------------------------------------------------------------------------
 * Elementor registration
 * ---------------------------------------------------------------------- */

/**
 * "Sylvia's Dream" category, placed first in the panel.
 *
 * @param object $manager Elements manager.
 */
function sdc_el_category( $manager ) {
	if ( ! is_object( $manager ) || ! method_exists( $manager, 'add_category' ) ) {
		return;
	}
	$manager->add_category(
		'sylviasdream',
		array(
			'title' => __( "Sylvia's Dream", 'sylviasdream-child' ),
			'icon'  => 'eicon-heart',
		)
	);
	// Move the category to the top (Elementor has no public API for the order).
	try {
		$ref = new ReflectionObject( $manager );
		if ( $ref->hasProperty( 'categories' ) ) {
			$prop = $ref->getProperty( 'categories' );
			$prop->setAccessible( true );
			$cats = $prop->getValue( $manager );
			if ( is_array( $cats ) && isset( $cats['sylviasdream'] ) ) {
				$prop->setValue( $manager, array( 'sylviasdream' => $cats['sylviasdream'] ) + $cats );
			}
		}
	} catch ( \Throwable $e ) {
		return; // Order is cosmetic only.
	}
}
add_action( 'elementor/elements/categories_registered', 'sdc_el_category' );

/**
 * Register the widgets.
 *
 * @param object $widgets_manager Widgets manager.
 */
function sdc_el_register_widgets( $widgets_manager ) {
	if ( ! class_exists( '\Elementor\Widget_Base' ) || ! is_object( $widgets_manager ) ) {
		return;
	}
	require_once SYLVIASDREAM_CHILD_DIR . '/inc/elementor/widget-base.php';
	foreach ( sdc_el_widget_classes() as $class ) {
		if ( ! class_exists( $class ) ) {
			continue;
		}
		$widget = new $class();
		if ( method_exists( $widgets_manager, 'register' ) ) {
			$widgets_manager->register( $widget );
		} elseif ( method_exists( $widgets_manager, 'register_widget_type' ) ) {
			$widgets_manager->register_widget_type( $widget );
		}
	}
}
add_action( 'elementor/widgets/register', 'sdc_el_register_widgets' );

/**
 * Front-end and editor styles for the widgets.
 */
function sdc_el_styles() {
	if ( ! did_action( 'elementor/loaded' ) ) {
		return;
	}
	list( $url, $ver ) = sdc_asset( 'assets/css/elementor.css' );
	wp_enqueue_style( 'sylviasdream-child-elementor', $url, array( 'sylviasdream-child' ), $ver );
	// The section styles of ordinary pages (about.css) are needed on any page that may hold a widget.
	if ( ! wp_style_is( 'sylviasdream-child-about', 'enqueued' ) && ( sdc_is_elementor_page() || sdc_widget_is_editor() ) ) {
		list( $url, $ver ) = sdc_asset( 'assets/css/about.css' );
		wp_enqueue_style( 'sylviasdream-child-about', $url, array( 'sylviasdream-child-blocks' ), $ver );
	}
	if ( class_exists( 'WooCommerce' ) && ! wp_style_is( 'sylviasdream-child-shop', 'enqueued' ) ) {
		list( $url, $ver ) = sdc_asset( 'assets/css/woocommerce.css' );
		wp_enqueue_style( 'sylviasdream-child-shop', $url, array( 'sylviasdream-child' ), $ver );
	}
}
add_action( 'wp_enqueue_scripts', 'sdc_el_styles', 30 );
