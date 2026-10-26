<?php
/**
 * Navigation extras (1.2.0, updated prototype):
 * - submenu item descriptions (menu item "Description" field),
 * - "Start here" featured books in the Books mega menu,
 * - persistent mobile dock (Home, Books, Ministry, Give; 1.8.5 dropped More).
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * Show each submenu item's Description under its title in the primary menu.
 *
 * @param string   $title Item title (HTML).
 * @param WP_Post  $item  Menu item.
 * @param stdClass $args  wp_nav_menu() arguments.
 * @param int      $depth Depth.
 * @return string
 */
function sdc_menu_item_description( $title, $item, $args, $depth ) {
	if ( (int) $depth < 1 || ! is_object( $args ) || empty( $args->theme_location ) || 'primary' !== $args->theme_location ) {
		return $title;
	}
	$desc = isset( $item->description ) ? trim( (string) $item->description ) : '';
	if ( '' === $desc ) {
		return $title;
	}
	return '<span class="sd-menu-title">' . $title . '</span><span class="sd-menu-desc">' . esc_html( $desc ) . '</span>';
}
add_filter( 'nav_menu_item_title', 'sdc_menu_item_description', 10, 4 );

/**
 * "Start here" column for the Books mega menu: up to three published,
 * featured books. Nothing is shown while no featured book is published.
 *
 * @return string
 */
function sdc_mega_featured_html() {
	if ( ! function_exists( 'wc_get_products' ) ) {
		return '';
	}
	$label = sdc_get( 'mega_feature_label' );
	if ( '' === $label ) {
		return '';
	}
	$products = wc_get_products(
		array(
			'status'   => 'publish',
			'featured' => true,
			'limit'    => 3,
			'orderby'  => 'menu_order',
			'order'    => 'ASC',
		)
	);
	if ( empty( $products ) ) {
		// 1.5.4: until featured books are published, show the prototype's three
		// "Start here" covers (drawn in CSS) linking to the Books page.
		$picks = array(
			array( 'The Report of the Lord', '#3a2a12', '#e0b75a' ),
			array( 'One Life', '#5b3a1a', '#c18b2d' ),
			array( 'From Fear to Freedom', '#2f4a5c', '#8fb3c4' ),
		);
		$items = '';
		foreach ( $picks as $pick ) {
			$items .= sprintf(
				'<a class="sd-mega__book" href="%1$s"><span class="sd-cover" style="background:linear-gradient(160deg,%2$s 0%%,%2$s 55%%,%3$s 140%%)" aria-hidden="true"><span class="sd-cover__title">%4$s</span></span><span>%4$s</span></a>',
				esc_url( sdc_shop_url() ),
				esc_attr( $pick[1] ),
				esc_attr( $pick[2] ),
				esc_html( $pick[0] )
			);
		}
		return '<li class="menu-item sd-mega__col sd-mega__feature"><span class="sd-mega__heading">' . esc_html( $label ) . '</span><div class="sd-mega__books">' . $items . '</div></li>';
	}
	$items = '';
	foreach ( $products as $product ) {
		$items .= sprintf(
			'<a class="sd-mega__book" href="%1$s">%2$s<span>%3$s</span></a>',
			esc_url( $product->get_permalink() ),
			$product->get_image( 'woocommerce_thumbnail', array( 'loading' => 'lazy' ) ), // Escaped by WooCommerce.
			esc_html( $product->get_name() )
		);
	}
	return '<li class="menu-item sd-mega__col sd-mega__feature"><span class="sd-mega__heading">' . esc_html( $label ) . '</span><div class="sd-mega__books">' . $items . '</div></li>';
}

/**
 * Normalise a URL to its path with a trailing slash, for active states.
 *
 * @param string $url URL.
 * @return string
 */
function sdc_url_path( $url ) {
	$path = (string) wp_parse_url( $url, PHP_URL_PATH );
	return trailingslashit( '' === $path ? '/' : $path );
}

/**
 * Is the mobile dock shown on this request?
 *
 * @return bool
 */
function sdc_show_dock() {
	return sdc_get_bool( 'show_mobile_dock' ) && ! is_page_template( 'template-canvas.php' );
}

/**
 * Body class so the page leaves room for the dock.
 *
 * @param string[] $classes Classes.
 * @return string[]
 */
function sdc_dock_body_class( $classes ) {
	if ( sdc_show_dock() ) {
		$classes[] = 'sd-has-dock';
	}
	return $classes;
}
add_filter( 'body_class', 'sdc_dock_body_class' );

/**
 * Print the mobile dock (visible on phones only, see style.css).
 */
function sdc_mobile_dock() {
	if ( ! sdc_show_dock() ) {
		return;
	}

	$request = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '/'; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- sanitised with esc_url_raw.
	$current = sdc_url_path( $request );

	$home_url     = home_url( '/' );
	$books_url    = sdc_shop_url();
	$ministry_url = sdc_link( sdc_get( 'dock_ministry_url' ) );
	$give_url     = sdc_link( sdc_get( 'give_url' ) );

	$is_shop = function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_cart() || is_checkout() );

	$items = array(
		'home'     => array(
			'url'    => $home_url,
			'label'  => __( 'Home', 'sylviasdream-child' ),
			'active' => is_front_page(),
			'icon'   => '<path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1z"/>',
		),
		'books'    => array(
			'url'    => $books_url,
			'label'  => __( 'Books', 'sylviasdream-child' ),
			'active' => $is_shop || ( $books_url && sdc_url_path( $books_url ) === $current ),
			'icon'   => '<path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v17H6.5A2.5 2.5 0 0 0 4 22z"/><path d="M4 5.5V22M8 7h8M8 11h8"/>',
		),
		'ministry' => array(
			'url'    => $ministry_url,
			'label'  => sdc_get( 'dock_ministry_label' ),
			'active' => $ministry_url && 0 === strpos( $current, sdc_url_path( $ministry_url ) ) && '/' !== sdc_url_path( $ministry_url ),
			'icon'   => '<path d="M12 21s-8-4.5-8-11a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 6.5-8 11-8 11z"/><path d="M12 6v8M8 10h8"/>',
		),
		'give'     => array(
			'url'    => $give_url,
			'label'  => sdc_get( 'give_label' ),
			'active' => $give_url && sdc_url_path( $give_url ) === $current,
			'icon'   => '<path d="M20.8 8.6c0 5-8.8 11-8.8 11s-8.8-6-8.8-11A4.6 4.6 0 0 1 12 6.4a4.6 4.6 0 0 1 8.8 2.2z"/>',
		),
	);

	$svg_kses = array(
		'path' => array( 'd' => true ),
	);

	echo '<nav class="sd-dock" aria-label="' . esc_attr__( 'Quick navigation', 'sylviasdream-child' ) . '">';
	foreach ( $items as $key => $item ) {
		if ( empty( $item['url'] ) || '' === (string) $item['label'] ) {
			continue;
		}
		printf(
			'<a href="%1$s" class="sd-dock__item sd-dock__item--%2$s"%3$s><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%4$s</svg><span>%5$s</span></a>',
			esc_url( $item['url'] ),
			esc_attr( $key ),
			$item['active'] ? ' aria-current="page"' : '',
			wp_kses( $item['icon'], $svg_kses ),
			esc_html( $item['label'] )
		);
	}
	// 1.8.5: no "More" here; the menu button in the header opens the menu.
	echo '</nav>';
}
