<?php
/**
 * Page-builder support: pattern category, block styles, editor fonts,
 * prototype image helper and Elementor compatibility.
 *
 * Patterns live in /patterns/*.php and are registered automatically by
 * WordPress from their file headers (Title, Slug, Categories).
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * URL of one of the bundled prototype photos.
 *
 * @param string $name One of hero, bible, mission, give, desk.
 * @return string
 */
function sdc_theme_image( $name ) {
	$allowed = array( 'hero', 'bible', 'mission', 'give', 'desk' );
	if ( ! in_array( $name, $allowed, true ) ) {
		return '';
	}
	return get_theme_file_uri( 'assets/images/' . $name . '.jpg' );
}

/**
 * Pattern category and block styles.
 */
function sdc_register_block_bits() {
	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category(
			'sylviasdream',
			array(
				'label'       => __( "Sylvia's Dream", 'sylviasdream-child' ),
				'description' => __( 'Ready-made sections in the Sylvia\'s Dream style.', 'sylviasdream-child' ),
			)
		);
	}

	if ( function_exists( 'register_block_style' ) ) {
		register_block_style(
			'core/group',
			array(
				'name'  => 'sd-card',
				'label' => __( 'Card', 'sylviasdream-child' ),
			)
		);
		register_block_style(
			'core/image',
			array(
				'name'  => 'sd-cover-shadow',
				'label' => __( 'Book cover shadow', 'sylviasdream-child' ),
			)
		);
		// The button "Outline" style is provided by WordPress core and
		// styled in assets/css/blocks.css; it is not registered twice.
	}
}
add_action( 'init', 'sdc_register_block_bits' );

/**
 * Load the brand fonts inside the block editor too.
 */
function sdc_editor_assets() {
	if ( sdc_get_bool( 'use_google_fonts' ) ) {
		wp_enqueue_style(
			'sylviasdream-editor-fonts',
			'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=Source+Sans+3:wght@400;600;700&display=swap',
			array(),
			null // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion -- Google Fonts URL is versioned by its query.
		);
	}
}
add_action( 'enqueue_block_editor_assets', 'sdc_editor_assets' );

/**
 * Elementor compatibility (works without Elementor installed).
 * Elementor content renders through the_content() on every template,
 * including "Full width (page builder)" and "Canvas".
 *
 * @param object $manager Elementor theme locations manager.
 */
function sdc_elementor_locations( $manager ) {
	if ( is_object( $manager ) && method_exists( $manager, 'register_all_core_location' ) ) {
		$manager->register_all_core_location();
	}
}
add_action( 'elementor/theme/register_locations', 'sdc_elementor_locations' );

/**
 * Body class for the page-builder templates.
 *
 * @param string[] $classes Classes.
 * @return string[]
 */
function sdc_builder_body_class( $classes ) {
	if ( is_singular() ) {
		$template = get_page_template_slug();
		if ( 'template-full-width.php' === $template ) {
			$classes[] = 'sd-template-full';
		} elseif ( 'template-canvas.php' === $template ) {
			$classes[] = 'sd-template-canvas';
		}
	}
	if ( is_front_page() && sdc_front_page_has_blocks() ) {
		$classes[] = 'sd-template-full';
	}
	return $classes;
}
add_filter( 'body_class', 'sdc_builder_body_class' );

/**
 * Does the static front page have its own block content?
 *
 * @return bool
 */
function sdc_front_page_has_blocks() {
	if ( 'page' !== get_option( 'show_on_front' ) ) {
		return false;
	}
	$page_id = (int) get_option( 'page_on_front' );
	if ( ! $page_id ) {
		return false;
	}
	$content = get_post_field( 'post_content', $page_id );
	return is_string( $content ) && '' !== trim( $content ) && has_blocks( $content );
}
