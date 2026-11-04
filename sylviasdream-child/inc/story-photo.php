<?php
/**
 * 1.23.4: Sylvia's photo in place of the "SS" monogram placeholder.
 *
 * The My Story introduction holds a core/group block with the class
 * "sd-monogram" (gold "SS / Sylvia Suhr" box). When a photo is chosen, that
 * block is printed as an image instead. The photo is chosen in either place:
 *
 * - Appearance > Customize > Home: about Sylvia > "My Story: photo shown
 *   instead of the SS placeholder" (theme setting sdc_story_photo). Used by
 *   the normal block editor page and as the default everywhere.
 * - Elementor "Section from your page" widget > Photos > "Photo instead of the
 *   SS placeholder". Overrides the Customizer photo for that widget only.
 *
 * Works for both because kept sections are rendered with do_blocks(), so the
 * same render_block filter runs. The page's own wording, buttons and links are
 * not touched; with no photo chosen the monogram shows exactly as before.
 *
 * Filter sdc_story_photo_id can change the attachment id (0 = monogram).
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Attachment id chosen by a widget for the section being rendered (0 = none).
 *
 * @param int|null $set New value, or null to read.
 * @return int
 */
function sdc_story_photo_override( $set = null ) {
	static $id = 0;
	if ( null !== $set ) {
		$id = absint( $set );
	}
	return $id;
}

/**
 * The photo to show instead of the monogram, or 0 to keep the monogram.
 *
 * @return int
 */
function sdc_story_photo_id() {
	$id = sdc_story_photo_override();
	if ( ! $id && function_exists( 'sdc_get_image' ) ) {
		$id = sdc_get_image( 'story_photo' );
	}
	$id = absint( apply_filters( 'sdc_story_photo_id', $id ) );
	return ( $id && wp_attachment_is_image( $id ) ) ? $id : 0;
}

/**
 * Markup for the portrait (same classes as the 1.23.3 Image block).
 *
 * @param int $id Attachment id.
 * @return string
 */
function sdc_story_photo_html( $id ) {
	$alt = trim( (string) get_post_meta( $id, '_wp_attachment_image_alt', true ) );
	if ( '' === $alt ) {
		$alt = __( 'Sylvia Suhr', 'sylviasdream-child' );
	}
	$img = wp_get_attachment_image(
		$id,
		'large',
		false,
		array(
			'alt'      => $alt,
			'loading'  => 'eager',
			'decoding' => 'async',
			'sizes'    => '(max-width: 781px) 340px, 440px',
		)
	);
	if ( '' === $img ) {
		return '';
	}
	return '<figure class="wp-block-image size-large sd-photo-round sd-portrait">' . $img . '</figure>';
}

/**
 * Swap the monogram group for the chosen photo.
 *
 * @param string $html  Rendered block.
 * @param array  $block Parsed block.
 * @return string
 */
function sdc_story_photo_block( $html, $block ) {
	$cls = isset( $block['attrs']['className'] ) ? (string) $block['attrs']['className'] : '';
	if ( false === strpos( ' ' . $cls . ' ', ' sd-monogram ' ) ) {
		return $html;
	}
	$id = sdc_story_photo_id();
	if ( ! $id ) {
		return $html;
	}
	$photo = sdc_story_photo_html( $id );
	return '' !== $photo ? $photo : $html;
}
add_filter( 'render_block_core/group', 'sdc_story_photo_block', 10, 2 );
