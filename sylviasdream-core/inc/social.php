<?php
/**
 * Social sharing tags (0.2.0) and page-level structured data.
 *
 * - Open Graph and Twitter card tags (title, description, image with size and
 *   alt, URL, site name) so a shared link shows a picture and text. Skipped
 *   when Jetpack, Yoast SEO, Rank Math or SEOPress already print them.
 * - 0.3.0: every page has a share image (default 1200 x 630 image, editable in
 *   Settings > Sylvia's Dream), and the site tagline is the description fallback.
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
 * Default sharing image (0.3.0): the Settings > Sylvia's Dream image when set,
 * else the bundled 1200 x 630 assets/og-default.jpg.
 *
 * @return array{url:string,width:int,height:int,alt:string}
 */
function sd_core_default_og_image() {
	$custom = (string) sd_core_opt( 'og_default_image' );
	$image  = array(
		'url'    => SD_CORE_URL . 'assets/og-default.jpg',
		'width'  => 1200,
		'height' => 630,
		'alt'    => get_bloginfo( 'name' ),
	);
	if ( '' !== $custom ) {
		$image['url']    = $custom;
		$id              = attachment_url_to_postid( $custom );
		$src             = $id ? wp_get_attachment_image_src( $id, 'full' ) : false;
		$image['width']  = $src ? (int) $src[1] : 0;
		$image['height'] = $src ? (int) $src[2] : 0;
		$alt             = $id ? trim( (string) get_post_meta( $id, '_wp_attachment_image_alt', true ) ) : '';
		$image['alt']    = '' !== $alt ? $alt : $image['alt'];
	}
	/**
	 * Filter the default sharing image.
	 *
	 * @param array $image url, width, height, alt.
	 */
	return (array) apply_filters( 'sd_core_default_og_image', $image );
}

/**
 * Share image with size and alt text: featured image, else the first image in
 * the content (filter `sd_core_og_use_content_image`, default true, so book
 * pages share their cover), else the default sharing image.
 *
 * @param int $post_id Post ID (0 for archives).
 * @return array{url:string,width:int,height:int,alt:string}
 */
function sd_core_page_image_data( $post_id ) {
	$post_id = (int) $post_id;
	$thumb   = $post_id ? get_post_thumbnail_id( $post_id ) : 0;
	if ( $thumb ) {
		$src = wp_get_attachment_image_src( $thumb, 'large' );
		if ( $src ) {
			$alt = trim( (string) get_post_meta( $thumb, '_wp_attachment_image_alt', true ) );
			return array(
				'url'    => $src[0],
				'width'  => (int) $src[1],
				'height' => (int) $src[2],
				'alt'    => '' !== $alt ? $alt : wp_strip_all_tags( get_the_title( $post_id ) ),
			);
		}
	}
	if ( $post_id && apply_filters( 'sd_core_og_use_content_image', true, $post_id ) ) {
		$content = (string) get_post_field( 'post_content', $post_id );
		if ( preg_match( '/<img[^>]+src="([^"]+)"/i', $content, $m ) || preg_match( '/"url":"([^"]+\.(?:jpe?g|png|webp))"/i', $content, $m ) ) {
			return array(
				'url'    => esc_url_raw( sd_core_absolute_url( $m[1] ) ),
				'width'  => 0,
				'height' => 0,
				'alt'    => wp_strip_all_tags( get_the_title( $post_id ) ),
			);
		}
	}
	return sd_core_default_og_image();
}

/**
 * Share image URL (kept for the JSON-LD graph).
 *
 * @param int $post_id Post ID.
 * @return string
 */
function sd_core_page_image( $post_id ) {
	$data = sd_core_page_image_data( $post_id );
	return (string) $data['url'];
}

/**
 * Print Open Graph and Twitter tags.
 */
function sd_core_print_social_tags() {
	if ( is_admin() || is_feed() || sd_core_og_handled_elsewhere() ) {
		return;
	}
	$site    = get_bloginfo( 'name' );
	$tagline = get_bloginfo( 'description' );
	$id      = 0;
	$type    = 'website';
	if ( is_front_page() ) {
		$id    = (int) get_option( 'page_on_front' );
		$title = $site;
		$desc  = $tagline;
		$url   = home_url( '/' );
	} elseif ( is_singular() ) {
		$id    = get_queried_object_id();
		$title = wp_strip_all_tags( get_the_title( $id ) );
		$desc  = sd_core_page_description( $id );
		$url   = get_permalink( $id );
		$type  = is_singular( 'post' ) ? 'article' : 'website';
	} elseif ( is_home() && get_option( 'page_for_posts' ) ) {
		$id    = (int) get_option( 'page_for_posts' );
		$title = wp_strip_all_tags( get_the_title( $id ) );
		$desc  = sd_core_page_description( $id );
		$url   = get_permalink( $id );
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$term  = get_queried_object();
		$title = single_term_title( '', false );
		$desc  = $term instanceof WP_Term ? wp_strip_all_tags( term_description( $term ) ) : '';
		$link  = $term instanceof WP_Term ? get_term_link( $term ) : '';
		$url   = is_wp_error( $link ) ? '' : (string) $link;
	} elseif ( is_post_type_archive() ) {
		$title = post_type_archive_title( '', false );
		$desc  = '';
		$url   = (string) get_post_type_archive_link( (string) get_query_var( 'post_type' ) );
	} else {
		return;
	}
	if ( '' === (string) $desc && $id ) {
		$desc = sd_core_page_description( $id );
	}
	// 0.3.0: the site tagline is the last fallback for the description.
	if ( '' === trim( (string) $desc ) ) {
		$desc = $tagline;
	}
	$image = sd_core_page_image_data( $id );
	$tags  = array(
		'og:site_name'    => $site,
		'og:type'         => $type,
		'og:title'        => $title,
		'og:description'  => trim( (string) $desc ),
		'og:url'          => $url,
		'og:image'        => $image['url'],
		'og:image:width'  => $image['width'] ? (string) $image['width'] : '',
		'og:image:height' => $image['height'] ? (string) $image['height'] : '',
		'og:image:alt'    => $image['alt'],
		'og:locale'       => str_replace( '-', '_', get_bloginfo( 'language' ) ),
	);
	echo "\n";
	foreach ( $tags as $property => $content ) {
		if ( '' !== (string) $content ) {
			printf( '<meta property="%s" content="%s" />' . "\n", esc_attr( $property ), esc_attr( $content ) );
		}
	}
	$twitter = array(
		'twitter:card'        => $image['url'] ? 'summary_large_image' : 'summary',
		'twitter:title'       => $title,
		'twitter:description' => trim( (string) $desc ),
		'twitter:image'       => $image['url'],
		'twitter:image:alt'   => $image['alt'],
	);
	foreach ( $twitter as $name => $content ) {
		if ( '' !== (string) $content ) {
			printf( '<meta name="%s" content="%s" />' . "\n", esc_attr( $name ), esc_attr( $content ) );
		}
	}
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
	$items[] = array( 'name' => wp_strip_all_tags( get_the_title( $id ) ), 'url' => $url );
	$list    = array();
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
