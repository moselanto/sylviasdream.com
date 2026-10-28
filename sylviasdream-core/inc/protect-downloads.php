<?php
/**
 * Keep post-purchase download pages working for existing buyers,
 * but hide them from search engines, the Jetpack sitemap and site search.
 *
 * Phase 1 of the project (day 1). Safe to activate on the live site:
 * nothing visible changes for visitors or buyers.
 *
 * @package SylviasDreamCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Slugs of the current thank-you / download pages (audit, 13 Oct 2026).
 *
 * @return string[]
 */
function sd_core_default_protected_slugs() {
	return array(
		'thank-you-for-purchasing-the-hidden-key-to-healing-and-breakthrough',
		'when-the-word-becomes-life-download',
		'thank-you-for-purchasing-learning-to-recognise-gods-voice',
		'thank-you-the-report-of-the-lord',
		'the-hidden-key-to-healing-and-breakthrough-2',
		'thank-you-for-purchasing-guard-your-heart',
		'sent-2',
		'thank-you-for-your-purchase-2',
		'thank-you-angelique-finds-her-light',
		'from-chance-to-choice',
		'from-fear-to-freedom-2',
		'thank-you-the-kings-way',
		'thank-you-for-becoming-a-dream-partner',
		'all-shall-be-well',
		'thank-you-for-your-purchase',
		'thank-you-one-life',
		'thank-you-angelique',
		'your-free-gift',
		'how-to-find-the-real-you-2',
		'thank-you-for-your-purchase-3',
	);
}

/**
 * Configured protected slugs.
 *
 * @return string[]
 */
function sd_core_protected_slugs() {
	$raw = (string) sd_core_opt( 'protected_slugs' );
	return array_values( array_filter( array_map( 'trim', preg_split( '/[\r\n]+/', $raw ) ) ) );
}

/**
 * Is this post a protected download page?
 *
 * @param WP_Post|int|null $post Post.
 * @return bool
 */
function sd_core_is_protected( $post = null ) {
	$post = get_post( $post );
	if ( ! $post || 'page' !== $post->post_type ) {
		return false;
	}
	return in_array( $post->post_name, sd_core_protected_slugs(), true );
}

/**
 * noindex, nofollow on protected pages.
 *
 * @param array<string,bool> $robots Robots directives.
 * @return array<string,bool>
 */
function sd_core_robots( $robots ) {
	if ( is_page() && sd_core_is_protected() ) {
		$robots['noindex']  = true;
		$robots['nofollow'] = true;
		unset( $robots['max-image-preview'] );
	}
	return $robots;
}
add_filter( 'wp_robots', 'sd_core_robots', 99 );

/**
 * Header-level noindex as well (covers crawlers that skip meta tags).
 */
function sd_core_robots_header() {
	if ( is_page() && sd_core_is_protected() && ! headers_sent() ) {
		header( 'X-Robots-Tag: noindex, nofollow', true );
	}
}
add_action( 'template_redirect', 'sd_core_robots_header' );

/**
 * Remove from the Jetpack sitemap (WordPress.com).
 *
 * @param bool    $skip Whether to skip.
 * @param WP_Post $post Post.
 * @return bool
 */
function sd_core_jetpack_sitemap_skip( $skip, $post ) {
	return sd_core_is_protected( $post ) ? true : $skip;
}
add_filter( 'jetpack_sitemap_skip_post', 'sd_core_jetpack_sitemap_skip', 10, 2 );

/**
 * Remove from the core WordPress sitemap if it is in use.
 *
 * @param array<string,mixed> $args Query args.
 * @param string              $post_type Post type.
 * @return array<string,mixed>
 */
function sd_core_core_sitemap_exclude( $args, $post_type ) {
	if ( 'page' !== $post_type ) {
		return $args;
	}
	$ids = array();
	foreach ( sd_core_protected_slugs() as $slug ) {
		$page = get_page_by_path( $slug );
		if ( $page ) {
			$ids[] = $page->ID;
		}
	}
	if ( $ids ) {
		$args['post__not_in'] = array_merge( $args['post__not_in'] ?? array(), $ids );
	}
	return $args;
}
add_filter( 'wp_sitemaps_posts_query_args', 'sd_core_core_sitemap_exclude', 10, 2 );

/**
 * Exclude from on-site search results.
 *
 * @param WP_Query $query Query.
 */
function sd_core_search_exclude( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! $query->is_search() ) {
		return;
	}
	$ids = array();
	foreach ( sd_core_protected_slugs() as $slug ) {
		$page = get_page_by_path( $slug );
		if ( $page ) {
			$ids[] = $page->ID;
		}
	}
	if ( $ids ) {
		$query->set( 'post__not_in', $ids );
	}
}
add_action( 'pre_get_posts', 'sd_core_search_exclude' );
