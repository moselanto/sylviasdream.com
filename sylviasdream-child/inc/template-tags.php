<?php
/**
 * Template helpers.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Static inline SVG icons (decorative, aria-hidden). Theme-owned markup.
 *
 * @param string $name Icon name.
 * @return string
 */
function sdc_icon( $name ) {
	$open  = '<svg class="sd-icon" aria-hidden="true" focusable="false" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">';
	$icons = array(
		'chevron'   => '<path d="M6 9l6 6 6-6"/>',
		'cart'      => '<circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2.5 3h2.6l2.4 12h11l2-8H6.3"/>',
		'search'    => '<circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/>',
		'close'     => '<path d="M6 6l12 12M18 6L6 18"/>',
		'book'      => '<path d="M4 5a2 2 0 0 1 2-2h13v16H6a2 2 0 0 0-2 2z"/><path d="M4 19V5"/>',
		'download'  => '<path d="M12 3v12M7 10l5 5 5-5M5 21h14"/>',
		'globe'     => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18"/>',
		'heart'     => '<path d="M12 20s-7-4.4-9-9a5 5 0 0 1 9-3 5 5 0 0 1 9 3c-2 4.6-9 9-9 9z"/>',
		'mail'      => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>',
		'facebook'  => '<path d="M15 3h-2.5A3.5 3.5 0 0 0 9 6.5V9H7v3.5h2V21h3.5v-8.5H15l.5-3.5h-3V7a1 1 0 0 1 1-1H15z"/>',
		'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r=".8"/>',
		'youtube'   => '<rect x="2.5" y="5.5" width="19" height="13" rx="4"/><path d="M10 9.5v5l4.5-2.5z"/>',
		'patreon'   => '<circle cx="14.5" cy="9.5" r="5.5"/><path d="M4 3.5v17"/>',
		'amazon'    => '<path d="M4 15c4.5 3 11.5 3 16 0"/><path d="M17 13.5l3 1.5-1 3"/><path d="M8 4h8v7H8z"/>',
		'arrow'     => '<path d="M5 12h14M13 6l6 6-6 6"/>',
	);
	return isset( $icons[ $name ] ) ? $open . $icons[ $name ] . '</svg>' : '';
}

/**
 * Allowed tags for printing theme icons through wp_kses().
 *
 * @return array<string,array<string,bool>>
 */
function sdc_svg_kses() {
	$common = array(
		'cx'           => true,
		'cy'           => true,
		'r'            => true,
		'x'            => true,
		'y'            => true,
		'rx'           => true,
		'width'        => true,
		'height'       => true,
		'd'            => true,
		'fill'         => true,
		'stroke'       => true,
		'stroke-width' => true,
	);
	return array(
		'svg'    => array(
			'class'           => true,
			'aria-hidden'     => true,
			'focusable'       => true,
			'width'           => true,
			'height'          => true,
			'viewbox'         => true,
			'fill'            => true,
			'stroke'          => true,
			'stroke-width'    => true,
			'stroke-linecap'  => true,
			'stroke-linejoin' => true,
		),
		'path'   => $common,
		'circle' => $common,
		'rect'   => $common,
	);
}

/**
 * Echo an icon safely.
 *
 * @param string $name Icon name.
 */
function sdc_the_icon( $name ) {
	echo wp_kses( sdc_icon( $name ), sdc_svg_kses() );
}

/**
 * Turn a site-relative or absolute link setting into a URL.
 *
 * @param string $value Setting value.
 * @return string
 */
function sdc_link( $value ) {
	if ( '' === $value ) {
		return '';
	}
	return str_starts_with( $value, '/' ) && ! str_starts_with( $value, '//' ) ? home_url( $value ) : $value;
}

/**
 * URL of the public Books page: the Customizer "Books page link"
 * (default /shop-for-books/), else the WooCommerce shop page, else home.
 *
 * @return string
 */
function sdc_shop_url() {
	$books = sdc_link( sdc_get( 'books_page_url' ) );
	if ( $books ) {
		return $books;
	}
	if ( function_exists( 'wc_get_page_permalink' ) ) {
		return wc_get_page_permalink( 'shop' );
	}
	return home_url( '/' );
}

/**
 * URL of My account > Downloads, or '' without WooCommerce.
 *
 * @return string
 */
function sdc_downloads_url() {
	if ( function_exists( 'wc_get_account_endpoint_url' ) ) {
		return wc_get_account_endpoint_url( 'downloads' );
	}
	return '';
}

/**
 * Output social links that have a URL set.
 */
function sdc_social_links() {
	$networks = array(
		'facebook'  => 'Facebook',
		'instagram' => 'Instagram',
		'youtube'   => 'YouTube',
		'patreon'   => 'Patreon',
		'amazon'    => __( 'Amazon author page', 'sylviasdream-child' ),
	);
	$items    = '';
	foreach ( $networks as $key => $label ) {
		$url = sdc_get( 'social_' . $key );
		if ( $url ) {
			$items .= sprintf(
				'<li><a href="%1$s" rel="noopener me" target="_blank">%2$s<span class="screen-reader-text">%3$s</span></a></li>',
				esc_url( $url ),
				wp_kses( sdc_icon( $key ), sdc_svg_kses() ),
				/* translators: %s: social network name. */
				esc_html( sprintf( __( '%s (opens in a new tab)', 'sylviasdream-child' ), $label ) )
			);
		}
	}
	$email = sdc_get( 'contact_email' );
	if ( $email && is_email( $email ) ) {
		$items .= sprintf(
			'<li><a href="%1$s">%2$s<span class="screen-reader-text">%3$s</span></a></li>',
			esc_url( 'mailto:' . antispambot( $email ) ),
			wp_kses( sdc_icon( 'mail' ), sdc_svg_kses() ),
			esc_html__( 'Email', 'sylviasdream-child' )
		);
	}
	if ( $items ) {
		echo '<ul class="sd-social" aria-label="' . esc_attr__( 'Social media', 'sylviasdream-child' ) . '">' . $items . '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- every part escaped above.
	}
}

/**
 * Output the cart link with item count (also used as a WooCommerce fragment).
 *
 * @return string
 */
function sdc_cart_link() {
	if ( ! function_exists( 'wc_get_cart_url' ) ) {
		return '';
	}
	$count = ( function_exists( 'WC' ) && WC()->cart ) ? (int) WC()->cart->get_cart_contents_count() : 0;
	/* translators: %d: number of items in the cart. */
	$label = sprintf( _n( 'Cart, %d item', 'Cart, %d items', $count, 'sylviasdream-child' ), $count );
	return sprintf(
		'<a class="sd-cart sd-iconbtn" href="%1$s" aria-label="%2$s">%3$s<span class="sd-cart__count" data-count="%4$d" aria-hidden="true">%4$d</span></a>',
		esc_url( wc_get_cart_url() ),
		esc_attr( $label ),
		wp_kses( sdc_icon( 'cart' ), sdc_svg_kses() ),
		$count
	);
}

/**
 * Elegant page header with one h1.
 *
 * @param string $title   Title (plain text).
 * @param string $eyebrow Optional small heading above.
 * @param string $intro   Optional intro text.
 */
function sdc_page_header( $title, $eyebrow = '', $intro = '' ) {
	?>
	<header class="sd-pagehead">
		<div class="sd-wrap sd-pagehead__inner">
			<?php if ( $eyebrow ) : ?>
				<p class="sd-eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
			<?php endif; ?>
			<h1 class="sd-pagehead__title"><?php echo esc_html( $title ); ?></h1>
			<?php if ( $intro ) : ?>
				<p class="sd-pagehead__intro"><?php echo esc_html( $intro ); ?></p>
			<?php endif; ?>
		</div>
	</header>
	<?php
}

/**
 * Post date and author line.
 */
function sdc_posted_on() {
	printf(
		'<p class="sd-meta"><time datetime="%1$s">%2$s</time></p>',
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() )
	);
}

/**
 * Devotional card used on the blog, archives, search and home page.
 *
 * @param string $heading_tag Tag for the card title (h2 on listings, h3 under an h2).
 */
function sdc_post_card( $heading_tag = 'h2' ) {
	$tag = in_array( $heading_tag, array( 'h2', 'h3' ), true ) ? $heading_tag : 'h2';
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'sd-post' ); ?>>
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="sd-post__media">
				<?php the_post_thumbnail( 'sd-card', array( 'loading' => 'lazy', 'alt' => '' ) ); ?>
			</div>
		<?php endif; ?>
		<div class="sd-post__body">
			<?php if ( 'post' === get_post_type() ) : ?>
				<?php sdc_posted_on(); ?>
			<?php else : ?>
				<p class="sd-meta"><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?></p>
			<?php endif; ?>
			<<?php echo esc_html( $tag ); ?> class="sd-post__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></<?php echo esc_html( $tag ); ?>>
			<div class="sd-post__excerpt"><?php the_excerpt(); ?></div>
		</div>
	</article>
	<?php
}

/**
 * Numbered pagination for listings.
 */
function sdc_pagination() {
	the_posts_pagination( array(
		'mid_size'           => 1,
		'prev_text'          => esc_html__( 'Previous', 'sylviasdream-child' ),
		'next_text'          => esc_html__( 'Next', 'sylviasdream-child' ),
		'screen_reader_text' => esc_html__( 'More reflections', 'sylviasdream-child' ),
	) );
}
