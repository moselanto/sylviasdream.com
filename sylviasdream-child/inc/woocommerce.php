<?php
/**
 * WooCommerce integration for the book shop: cover-first cards, book
 * product layout, format badges, cart count fragment.
 *
 * Loaded only when WooCommerce is active. Payments, Stripe and download
 * protection are not touched here.
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

/**
 * The theme prints its own page header on shop archives.
 */
add_filter( 'woocommerce_show_page_title', '__return_false' );

/**
 * Book covers are 2:3 portrait in the grid.
 *
 * @param array $size Size args.
 * @return array
 */
function sdc_wc_thumbnail_size( $size ) {
	return array(
		'width'  => 600,
		'height' => 900,
		'crop'   => 1,
	);
}
add_filter( 'woocommerce_get_image_size_thumbnail', 'sdc_wc_thumbnail_size' );

/**
 * Four columns on desktop (CSS controls 2 / 3 / 4 by screen width).
 *
 * @return int
 */
function sdc_wc_loop_columns() {
	return 4;
}
add_filter( 'loop_shop_columns', 'sdc_wc_loop_columns' );

/**
 * Twelve books per shop page.
 *
 * @return int
 */
function sdc_wc_per_page() {
	return 12;
}
add_filter( 'loop_shop_per_page', 'sdc_wc_per_page' );

/**
 * Four related books in one row.
 *
 * @param array $args Args.
 * @return array
 */
function sdc_wc_related_args( $args ) {
	$args['posts_per_page'] = 4;
	$args['columns']        = 4;
	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'sdc_wc_related_args' );

/**
 * Related heading wording from the prototype.
 *
 * @return string
 */
function sdc_wc_related_heading() {
	return __( 'You may also like', 'sylviasdream-child' );
}
add_filter( 'woocommerce_product_related_products_heading', 'sdc_wc_related_heading' );

/**
 * Format labels for a product: values of a "Format" attribute
 * (global pa_format or a custom attribute named Format), otherwise
 * "eBook" for downloadable products. Never invented beyond that.
 *
 * @param WC_Product $product Product.
 * @return string[]
 */
function sdc_product_formats( $product ) {
	if ( ! $product instanceof WC_Product ) {
		return array();
	}
	$raw = (string) $product->get_attribute( 'pa_format' );
	if ( '' === $raw ) {
		$raw = (string) $product->get_attribute( 'format' );
	}
	$formats = array_filter( array_map( 'trim', preg_split( '/[,|]/', $raw ) ) );
	if ( ! $formats && $product->is_downloadable() ) {
		$formats = array( __( 'eBook', 'sylviasdream-child' ) );
	}
	return array_values( array_unique( $formats ) );
}

/**
 * Print format badges.
 *
 * @param string $context 'single' or 'loop'.
 */
function sdc_product_format_badges( $context = 'single' ) {
	global $product;
	$formats = sdc_product_formats( $product );
	if ( ! $formats ) {
		return;
	}
	echo '<ul class="sd-formats sd-formats--' . esc_attr( 'loop' === $context ? 'loop' : 'single' ) . '" aria-label="' . esc_attr__( 'Formats', 'sylviasdream-child' ) . '">';
	foreach ( $formats as $format ) {
		echo '<li class="sd-pill">' . esc_html( $format ) . '</li>';
	}
	echo '</ul>';
}

/**
 * Badges under the price on single product pages.
 */
function sdc_single_format_badges() {
	sdc_product_format_badges( 'single' );
}

/**
 * Badges on product cards.
 */
function sdc_loop_format_badges() {
	sdc_product_format_badges( 'loop' );
}

/**
 * Re-order the single product summary: title, subtitle (short
 * description), price, formats, add to cart, meta. Description moves
 * out of tabs into a readable column below.
 */
function sdc_wc_single_layout() {
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
	add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 6 );
	add_action( 'woocommerce_single_product_summary', 'sdc_single_format_badges', 15 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
	add_action( 'woocommerce_after_single_product_summary', 'sdc_wc_product_description', 10 );
	add_action( 'woocommerce_after_shop_loop_item_title', 'sdc_loop_format_badges', 6 );
}
add_action( 'wp', 'sdc_wc_single_layout' );

/**
 * Full description in a readable column, then reviews if enabled.
 */
function sdc_wc_product_description() {
	global $product;
	if ( ! $product instanceof WC_Product ) {
		return;
	}
	$content = $product->get_description();
	if ( '' !== trim( wp_strip_all_tags( $content ) ) ) {
		?>
		<section class="sd-product-description" aria-labelledby="sd-about-book">
			<h2 id="sd-about-book"><?php esc_html_e( 'About this book', 'sylviasdream-child' ); ?></h2>
			<div class="sd-prose"><?php echo wp_kses_post( apply_filters( 'the_content', $content ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals -- core filter. ?></div>
		</section>
		<?php
	}
	if ( comments_open() && wc_review_ratings_enabled() ) {
		echo '<section class="sd-product-reviews">';
		comments_template();
		echo '</section>';
	}
}

/**
 * Keep the header cart count current after AJAX add to cart.
 *
 * @param array $fragments Fragments.
 * @return array
 */
function sdc_wc_cart_fragment( $fragments ) {
	$fragments['a.sd-cart'] = sdc_cart_link();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'sdc_wc_cart_fragment' );

/**
 * Breadcrumb markup that matches the theme.
 *
 * @param array $defaults Defaults.
 * @return array
 */
function sdc_wc_breadcrumb( $defaults ) {
	$defaults['delimiter']   = '<span class="sd-crumbs__sep" aria-hidden="true">&rsaquo;</span>';
	$defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb sd-crumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'sylviasdream-child' ) . '">';
	$defaults['wrap_after']  = '</nav>';
	return $defaults;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'sdc_wc_breadcrumb' );

/**
 * Published, catalogue-visible products for the home page: featured
 * first, then newest. Returns an empty array when none are published.
 *
 * @param int $limit Maximum number.
 * @return WC_Product[]
 */
function sdc_home_products( $limit ) {
	$limit    = max( 1, (int) $limit );
	$featured = wc_get_products( array(
		'status'     => 'publish',
		'visibility' => 'catalog',
		'featured'   => true,
		'limit'      => $limit,
		'orderby'    => 'menu_order',
		'order'      => 'ASC',
	) );
	$ids      = array();
	foreach ( $featured as $p ) {
		$ids[] = $p->get_id();
	}
	if ( count( $featured ) < $limit ) {
		$more     = wc_get_products( array(
			'status'     => 'publish',
			'visibility' => 'catalog',
			'limit'      => $limit - count( $featured ),
			'exclude'    => $ids,
			'orderby'    => 'date',
			'order'      => 'DESC',
		) );
		$featured = array_merge( $featured, $more );
	}
	return $featured;
}

/**
 * "Return to shop" and "Continue shopping" go to the Books page too.
 *
 * @return string
 */
function sdc_wc_books_redirect() {
	return sdc_shop_url();
}
add_filter( 'woocommerce_return_to_shop_redirect', 'sdc_wc_books_redirect' );
add_filter( 'woocommerce_continue_shopping_redirect', 'sdc_wc_books_redirect' );


/**
 * 1.8.4: keep placeholder and empty product categories out of the menus.
 * WooCommerce's default "Uncategorized" is never shown. Any other product
 * category link (taxonomy item or custom /product-category/<slug>/ link) is
 * hidden while it has no published products, so "Coming soon" reappears on
 * its own once a published book is filed under it. Sub-items go with it.
 * Appearance > Menus is unaffected.
 *
 * @param WP_Post[] $items Menu items.
 * @return WP_Post[]
 */
function sdc_hide_empty_product_cat_items( $items ) {
	if ( is_admin() || ! is_array( $items ) ) {
		return $items;
	}
	$hidden = array();
	foreach ( $items as $item ) {
		$term = null;
		if ( isset( $item->type, $item->object ) && 'taxonomy' === $item->type && 'product_cat' === $item->object ) {
			$term = get_term( (int) $item->object_id, 'product_cat' );
		} elseif ( isset( $item->type, $item->url ) && 'custom' === $item->type && preg_match( '#/product-category/([^/?\#]+)/?#', (string) $item->url, $m ) ) {
			$term = get_term_by( 'slug', sanitize_title( rawurldecode( $m[1] ) ), 'product_cat' );
		} else {
			continue;
		}
		if ( ! $term || is_wp_error( $term ) || 'uncategorized' === $term->slug || (int) $term->count < 1 ) {
			$hidden[] = (int) $item->ID;
		}
	}
	if ( ! $hidden ) {
		return $items;
	}
	do {
		$added = false;
		foreach ( $items as $item ) {
			$id = (int) $item->ID;
			if ( ! in_array( $id, $hidden, true ) && in_array( (int) $item->menu_item_parent, $hidden, true ) ) {
				$hidden[] = $id;
				$added    = true;
			}
		}
	} while ( $added );
	return array_filter(
		$items,
		function ( $item ) use ( $hidden ) {
			return ! in_array( (int) $item->ID, $hidden, true );
		}
	);
}
add_filter( 'wp_nav_menu_objects', 'sdc_hide_empty_product_cat_items', 20 );
