<?php
/**
 * 0.2.0: social sharing tags and page-level structured data.
 *
 * - Open Graph and Twitter card tags (title, description, image, URL) so a
 *   shared link shows a picture and text. Skipped when Jetpack, Yoast SEO or
 *   Rank Math already print them.
 * - JSON-LD: BlogPosting for posts, WebPage plus BreadcrumbList for pages.
 *
 * No wording is invented: the description is the page excerpt, else the
 * first sentence(s) of the page's own text (up to 160 characters).
 *
 * @package SylviasDreamCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Does another plugin already print Open Graph tags?
 *
 * @return bool
 */
function sd_core_og_handled_elsewhere() {
	if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'SEOPRESS_VERSION' ) ) {
		return true;
	}
	if ( class_exists( 'Jetpack' ) && apply_filters( 'jetpack_enable_open_graph', false ) ) {
		return true;
	}
	return (bool) apply_filters( 'sd_core_og_handled_elsewhere', false );
}

/**
 * Short description from the post's own words.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function sd_core_page_description( $post_id ) {
	$post = get_post( $post_id );
	if ( ! $post ) {
		return '';
	}
	$text = has_excerpt( $post ) ? $post->post_excerpt : $post->post_content;
	$text = wp_strip_all_tags( strip_shortcodes( do_blocks( $text ) ) );
	$text = trim( preg_replace( '/\s+/u', ' ', html_entity_decode( $text, ENT_QUOTES, 'UTF-8' ) ) );
	if ( '' === $text ) {
		return '';
	}
	if ( mb_strlen( $text ) <= 160 ) {
		return $text;
	}
	$cut = mb_substr( $text, 0, 160 );
	$end = max( mb_strrpos( $cut, '. ' ), mb_strrpos( $cut, '? ' ), mb_strrpos( $cut, '! ' ) );
	if ( $end && $end > 60 ) {
		return mb_substr( $cut, 0, $end + 1 );
	}
	$space = mb_strrpos( $cut, ' ' );
	return rtrim( mb_substr( $cut, 0, $space ? $space : 157 ), ' ,;:' ) . '…';
}

/**
 * Absolute URL for a site-relative address (/wp-content/...), using the site's
 * scheme and host only, so a subfolder install is not doubled.
 *
 * @param string $url URL.
 * @return string
 */
function sd_core_absolute_url( $url ) {
	$url = (string) $url;
	if ( str_starts_with( $url, '//' ) ) {
		return set_url_scheme( $url );
	}
	if ( str_starts_with( $url, '/' ) ) {
		$p = wp_parse_url( home_url() );
		return ( isset( $p['scheme'] ) ? $p['scheme'] : 'https' ) . '://' . $p['host'] . ( isset( $p['port'] ) ? ':' . $p['port'] : '' ) . $url;
	}
	return $url;
}

/**
 * Share image: featured image, else the first image in the content, else the site icon.
 *
 * @param int $post_id Post ID.
 * @return string
 */
function sd_core_page_image( $post_id ) {
	$thumb = get_post_thumbnail_id( $post_id );
	if ( $thumb ) {
		$url = wp_get_attachment_image_url( $thumb, 'large' );
		if ( $url ) {
			return $url;
		}
	}
	$content = (string) get_post_field( 'post_content', $post_id );
	if ( preg_match( '/<img[^>]+src="([^"]+)"/i', $content, $m ) ) {
		return esc_url_raw( sd_core_absolute_url( $m[1] ) );
	}
	if ( preg_match( '/"url":"([^"]+\.(?:jpe?g|png|webp))"/i', $content, $m ) ) {
		return esc_url_raw( sd_core_absolute_url( $m[1] ) );
	}
	$icon = get_site_icon_url( 512 );
	return $icon ? $icon : '';
}

/**
 * Print Open Graph and Twitter tags.
 */
function sd_core_print_social_tags() {
	if ( is_admin() || is_feed() || sd_core_og_handled_elsewhere() ) {
		return;
	}
	$site = get_bloginfo( 'name' );
	if ( is_front_page() ) {
		$id    = (int) get_option( 'page_on_front' );
		$title = $site;
		$desc  = get_bloginfo( 'description' );
		$url   = home_url( '/' );
		$type  = 'website';
	} elseif ( is_singular() ) {
		$id    = get_queried_object_id();
		$title = wp_strip_all_tags( get_the_title( $id ) );
		$desc  = sd_core_page_description( $id );
		$url   = get_permalink( $id );
		$type  = is_singular( 'post' ) ? 'article' : 'website';
	} else {
		return;
	}
	$image = $id ? sd_core_page_image( $id ) : '';
	if ( '' === (string) $desc && $id ) {
		$desc = sd_core_page_description( $id );
	}
	$tags = array(
		'og:site_name'   => $site,
		'og:type'        => $type,
		'og:title'       => $title,
		'og:description' => $desc,
		'og:url'         => $url,
		'og:image'       => $image,
		'og:locale'      => str_replace( '-', '_', get_bloginfo( 'language' ) ),
	);
	echo "\n";
	foreach ( $tags as $property => $content ) {
		if ( '' !== (string) $content ) {
			printf( '<meta property="%s" content="%s" />' . "\n", esc_attr( $property ), esc_attr( $content ) );
		}
	}
	printf( '<meta name="twitter:card" content="%s" />' . "\n", $image ? 'summary_large_image' : 'summary' );
}
add_action( 'wp_head', 'sd_core_print_social_tags', 5 );

/**
 * Page and post nodes for the JSON-LD graph.
 *
 * @param array $graph Graph.
 * @return array
 */
function sd_core_schema_pages( $graph ) {
	if ( is_front_page() || ! is_singular( array( 'post', 'page' ) ) ) {
		return $graph;
	}
	$id   = get_queried_object_id();
	$url  = get_permalink( $id );
	$home = home_url( '/' );
	$desc = sd_core_page_description( $id );
	$img  = sd_core_page_image( $id );
	if ( is_singular( 'post' ) ) {
		$node = array(
			'@type'            => 'BlogPosting',
			'@id'              => $url . '#article',
			'headline'         => wp_strip_all_tags( get_the_title( $id ) ),
			'url'              => $url,
			'datePublished'    => get_the_date( DATE_W3C, $id ),
			'dateModified'     => get_the_modified_date( DATE_W3C, $id ),
			'author'           => array( '@id' => $home . '#sylvia' ),
			'publisher'        => array( '@id' => $home . '#sylvia' ),
			'mainEntityOfPage' => $url,
			'inLanguage'       => get_bloginfo( 'language' ),
		);
	} else {
		$node = array(
			'@type'      => 'WebPage',
			'@id'        => $url . '#webpage',
			'name'       => wp_strip_all_tags( get_the_title( $id ) ),
			'url'        => $url,
			'isPartOf'   => array( '@id' => $home . '#website' ),
			'inLanguage' => get_bloginfo( 'language' ),
		);
	}
	if ( $desc ) {
		$node['description'] = $desc;
	}
	if ( $img ) {
		$node['image'] = $img;
	}
	$graph[] = $node;

	// Breadcrumbs: Home > (parent pages) > this page.
	$items = array( array( 'name' => get_bloginfo( 'name' ), 'url' => $home ) );
	foreach ( array_reverse( get_post_ancestors( $id ) ) as $ancestor ) {
		$items[] = array( 'name' => wp_strip_all_tags( get_the_title( $ancestor ) ), 'url' => get_permalink( $ancestor ) );
	}
	$items[]  = array( 'name' => wp_strip_all_tags( get_the_title( $id ) ), 'url' => $url );
	$list     = array();
	foreach ( $items as $i => $item ) {
		$list[] = array(
			'@type'    => 'ListItem',
			'position' => $i + 1,
			'name'     => $item['name'],
			'item'     => $item['url'],
		);
	}
	$graph[] = array(
		'@type'           => 'BreadcrumbList',
		'@id'             => $url . '#breadcrumb',
		'itemListElement' => $list,
	);
	return $graph;
}
add_filter( 'sd_core_schema_graph', 'sd_core_schema_pages' );
