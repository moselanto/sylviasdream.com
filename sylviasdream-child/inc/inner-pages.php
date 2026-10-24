<?php
/**
 * Inner pages (1.4.0, prototype v4): section label for the page header,
 * header photo, tidy rendering of older page content (whole-bold paragraphs,
 * empty paragraphs, a duplicate first title), a video grid for runs of
 * embeds, and "Keep exploring" links at the foot of each page.
 * Nothing here changes saved page content or wording; it only affects how
 * pages are displayed.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Are we rendering an ordinary inner page (not Home, not shop pages)?
 *
 * @return bool
 */
function sdc_is_inner_page() {
	if ( is_admin() || ! is_page() || is_front_page() ) {
		return false;
	}
	if ( function_exists( 'is_woocommerce' ) && ( is_cart() || is_checkout() || is_account_page() ) ) {
		return false;
	}
	return true;
}

/**
 * Top-level main-menu label that leads to a page ("About" for My Story).
 *
 * @param int    $post_id Page ID.
 * @param string $title   Page title (fallback comparison).
 * @return string
 */
function sdc_page_section_label( $post_id, $title = '' ) {
	$locations = get_nav_menu_locations();
	if ( empty( $locations['primary'] ) ) {
		return '';
	}
	$items = wp_get_nav_menu_items( $locations['primary'] );
	if ( ! $items ) {
		return '';
	}
	$by_id = array();
	foreach ( $items as $item ) {
		$by_id[ (int) $item->ID ] = $item;
	}
	$here  = sdc_url_path( (string) get_permalink( $post_id ) );
	$match = null;
	foreach ( $items as $item ) {
		$is_obj  = ( 'post_type' === $item->type && (int) $item->object_id === (int) $post_id );
		$is_path = ( $item->url && '#' !== $item->url && sdc_url_path( $item->url ) === $here );
		if ( $is_obj || $is_path ) {
			$match = $item;
			break;
		}
	}
	if ( ! $match ) {
		return '';
	}
	$guard = 0;
	while ( (int) $match->menu_item_parent && isset( $by_id[ (int) $match->menu_item_parent ] ) && $guard++ < 5 ) {
		$match = $by_id[ (int) $match->menu_item_parent ];
	}
	$label = wp_strip_all_tags( $match->title );
	return ( 0 === strcasecmp( trim( $label ), trim( wp_strip_all_tags( $title ) ) ) ) ? '' : $label;
}

/**
 * Header photo for the current page: featured image, else a theme photo
 * picked by the page address, else the Bible photo.
 *
 * @return string URL.
 */
function sdc_page_header_image() {
	if ( is_singular() && has_post_thumbnail() ) {
		$src = wp_get_attachment_image_url( get_post_thumbnail_id(), 'full' );
		if ( $src ) {
			return $src;
		}
	}
	if ( ! function_exists( 'sdc_theme_image' ) ) {
		return '';
	}
	$slug = is_singular() ? (string) get_post_field( 'post_name', get_queried_object_id() ) : '';
	$map  = array(
		'my-story'  => 'hero',
		'podcast'   => 'mission',
		'video'     => 'mission',
		'heaven'    => 'mission',
		'kenya'     => 'mission',
		'ministr'   => 'mission',
		'give'      => 'give',
		'partner'   => 'give',
		'donat'     => 'give',
		'author'    => 'desk',
		'publish'   => 'desk',
		'devotion'  => 'bible',
		'speak'     => 'bible',
		'contact'   => 'desk',
	);
	foreach ( $map as $needle => $name ) {
		if ( false !== strpos( $slug, $needle ) ) {
			return sdc_theme_image( $name );
		}
	}
	return sdc_theme_image( 'bible' );
}

/**
 * Display-only tidy-up of older page content.
 *
 * @param string $html  Block HTML.
 * @param array  $block Parsed block.
 * @return string
 */
function sdc_tidy_inner_blocks( $html, $block ) {
	if ( ! sdc_is_inner_page() || empty( $block['blockName'] ) ) {
		return $html;
	}
	if ( 'core/paragraph' === $block['blockName'] ) {
		$trim = trim( $html );
		// Empty paragraphs (including "<p><strong></strong></p>") add stray gaps.
		if ( '' === trim( str_replace( '&nbsp;', '', wp_strip_all_tags( $trim ) ) ) && false === stripos( $trim, '<img' ) ) {
			return '';
		}
		// A paragraph that is bold from start to end reads as a highlighted passage.
		if ( preg_match( '#^<p([^>]*)>\s*<strong>(.*)</strong>\s*</p>$#s', $trim, $m ) && false === stripos( $m[2], '<strong' ) && false === stripos( $m[2], '</strong>' ) ) {
			$attrs = $m[1];
			if ( preg_match( '/class="([^"]*)"/', $attrs ) ) {
				$attrs = preg_replace( '/class="([^"]*)"/', 'class="$1 sd-emph"', $attrs, 1 );
			} else {
				$attrs .= ' class="sd-emph"';
			}
			return '<p' . $attrs . '>' . $m[2] . '</p>';
		}
	}
	// 1.4.1: page banners built from the Page header pattern get the same breadcrumb.
	if ( 'core/cover' === $block['blockName'] && false !== strpos( $html, 'sd-pagehead-cover' ) && false === strpos( $html, 'sd-crumbs' ) ) {
		$crumbs = '<nav class="sd-crumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'sylviasdream-child' ) . '"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'sylviasdream-child' ) . '</a> <span aria-hidden="true">&rsaquo;</span> <span aria-current="page">' . esc_html( get_the_title() ) . '</span></nav>';
		$out    = preg_replace( '#(<div class="wp-block-cover__inner-container[^"]*"[^>]*>)#', '$1' . $crumbs, $html, 1 );
		return is_string( $out ) ? $out : $html;
	}
	// The theme header already shows the page title; hide a matching first H1.
	if ( 'core/heading' === $block['blockName'] && isset( $block['attrs']['level'] ) && 1 === (int) $block['attrs']['level'] && ! empty( $GLOBALS['sdc_header_title'] ) ) {
		$norm = function ( $s ) {
			return strtolower( preg_replace( '/\s+/', ' ', trim( html_entity_decode( wp_strip_all_tags( (string) $s ), ENT_QUOTES, 'UTF-8' ) ) ) );
		};
		if ( $norm( $html ) === $norm( $GLOBALS['sdc_header_title'] ) ) {
			$GLOBALS['sdc_header_title'] = '';
			return '';
		}
	}
	return $html;
}
add_filter( 'render_block', 'sdc_tidy_inner_blocks', 10, 2 );

/**
 * Two or more videos in a row become a tidy two-column grid.
 *
 * @param string $content Rendered content.
 * @return string
 */
function sdc_video_grid( $content ) {
	if ( ! sdc_is_inner_page() || false === strpos( $content, 'wp-block-embed' ) ) {
		return $content;
	}
	$out = preg_replace_callback(
		// Each figure may not contain another figure tag, so a run never spans
		// across a columns block or other wrappers (1.4.1 fix).
		'#(?:<figure class="wp-block-embed[^"]*"[^>]*>(?:(?!</?figure).)*</figure>\s*){2,}#s',
		function ( $m ) {
			return '<div class="sd-video-grid">' . $m[0] . '</div>';
		},
		$content
	);
	return is_string( $out ) ? $out : $content;
}
add_filter( 'the_content', 'sdc_video_grid', 20 );

/**
 * URL of a main-menu item whose title starts with the given text.
 *
 * @param string $starts Title start.
 * @return string
 */
function sdc_menu_url_starting( $starts ) {
	$locations = get_nav_menu_locations();
	if ( empty( $locations['primary'] ) ) {
		return '';
	}
	foreach ( (array) wp_get_nav_menu_items( $locations['primary'] ) as $item ) {
		if ( 0 === stripos( trim( wp_strip_all_tags( $item->title ) ), $starts ) && $item->url && '#' !== $item->url ) {
			return $item->url;
		}
	}
	return '';
}

/**
 * Print "Keep exploring" links (prototype: every page leads somewhere).
 */
function sdc_related_links() {
	if ( ! sdc_is_inner_page() || is_page_template( 'template-canvas.php' ) ) {
		return;
	}
	$page_url = function ( $path ) {
		$p = get_page_by_path( $path );
		return ( $p && 'publish' === $p->post_status ) ? get_permalink( $p ) : '';
	};
	$links = array(
		'books'    => array( __( 'Books', 'sylviasdream-child' ), __( 'Find the book for your season', 'sylviasdream-child' ), sdc_shop_url() ),
		'about'    => array( __( 'About', 'sylviasdream-child' ), __( "Sylvia's story", 'sylviasdream-child' ), $page_url( 'my-story' ) ),
		'hoe'      => array( __( 'Ministry', 'sylviasdream-child' ), __( 'Heaven on Earth, Kenya', 'sylviasdream-child' ), sdc_menu_url_starting( 'Heaven on Earth' ) ),
		'give'     => array( __( 'Give', 'sylviasdream-child' ), __( 'Become a Dream Partner', 'sylviasdream-child' ), sdc_link( sdc_get( 'give_url' ) ) ),
		'media'    => array( __( 'Watch', 'sylviasdream-child' ), __( 'Podcasts and videos', 'sylviasdream-child' ), $page_url( 'podcasts-and-videos' ) ),
		'speaking' => array( __( 'Events', 'sylviasdream-child' ), __( 'Invite Sylvia to speak', 'sylviasdream-child' ), $page_url( 'invite-sylvia-to-speak' ) ),
		'author'   => array( __( 'Writers', 'sylviasdream-child' ), __( 'Author Support', 'sylviasdream-child' ), $page_url( 'author-support-publishing-services' ) ),
		'contact'  => array( __( 'Contact', 'sylviasdream-child' ), __( 'Send Sylvia a message', 'sylviasdream-child' ), sdc_link( sdc_get( 'contact_url' ) ) ),
	);
	$slug = (string) get_post_field( 'post_name', get_queried_object_id() );
	$next = array(
		'my-story'                           => array( 'books', 'hoe', 'speaking' ),
		'podcasts-and-videos'                => array( 'speaking', 'about', 'books' ),
		'author-support-publishing-services' => array( 'books', 'contact', 'about' ),
		'invite-sylvia-to-speak'             => array( 'contact', 'media', 'about' ),
		'contact'                            => array( 'books', 'about', 'give' ),
	);
	$keys = isset( $next[ $slug ] ) ? $next[ $slug ] : array( 'books', 'about', 'give', 'contact' );
	$here = sdc_url_path( (string) get_permalink() );
	$cards = '';
	$count = 0;
	foreach ( $keys as $key ) {
		list( $eyebrow, $title, $url ) = $links[ $key ];
		if ( ! $url || sdc_url_path( $url ) === $here || $count >= 3 ) {
			continue;
		}
		$cards .= sprintf(
			'<a class="sd-next__card" href="%1$s"><span class="sd-next__eyebrow">%2$s</span><span class="sd-next__title">%3$s</span><span class="sd-next__arrow" aria-hidden="true">&rarr;</span></a>',
			esc_url( $url ),
			esc_html( $eyebrow ),
			esc_html( $title )
		);
		$count++;
	}
	if ( ! $cards ) {
		return;
	}
	printf(
		'<section class="sd-next" aria-labelledby="sd-next-title"><div class="sd-wrap"><h2 class="sd-next__heading" id="sd-next-title">%1$s</h2><div class="sd-next__grid">%2$s</div></div></section>',
		esc_html__( 'Keep exploring', 'sylviasdream-child' ),
		$cards // Escaped above.
	);
}
