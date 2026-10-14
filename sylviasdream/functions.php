<?php
/**
 * Sylvia's Dream Base Theme - bootstrap.
 *
 * @package SylviasDream
 */

defined( 'ABSPATH' ) || exit;

define( 'SYLVIASDREAM_VERSION', '1.0.0' );

/**
 * Theme supports and registered menus.
 */
function sylviasdream_setup() {
	load_theme_textdomain( 'sylviasdream', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'editor-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'custom-logo', array(
		'height'      => 120,
		'width'       => 360,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );

	register_nav_menus( array(
		'primary'      => __( 'Primary Navigation', 'sylviasdream' ),
		'footer_books' => __( 'Footer: Books & Resources', 'sylviasdream' ),
		'footer_about' => __( 'Footer: About & Ministry', 'sylviasdream' ),
		'footer_legal' => __( 'Footer: Policies', 'sylviasdream' ),
	) );

	add_image_size( 'sd-hero', 1800, 1000, true );
	add_image_size( 'sd-cover', 600, 900, true );
	add_image_size( 'sd-card', 800, 600, true );
}
add_action( 'after_setup_theme', 'sylviasdream_setup' );

/**
 * Content width for embeds.
 */
function sylviasdream_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'sylviasdream_content_width', 760 );
}
add_action( 'after_setup_theme', 'sylviasdream_content_width', 0 );

/**
 * Base stylesheet. The child theme enqueues its own assets on top.
 */
function sylviasdream_base_assets() {
	wp_enqueue_style( 'sylviasdream-base', get_template_directory_uri() . '/style.css', array(), SYLVIASDREAM_VERSION );
}
add_action( 'wp_enqueue_scripts', 'sylviasdream_base_assets', 5 );
