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
 * Product statuses shown on the home page and in the Books mega menu.
 * Always "publish"; the core plugin's preview mode (SD_PREVIEW_MODE, dev only)
 * adds "draft" for signed-in reviewers through the `sdc_product_statuses` filter.
 *
 * @return string[]
 */
function sdc_product_statuses() {
	$statuses = (array) apply_filters( 'sdc_product_statuses', array( 'publish' ) );
	$statuses = array_values( array_intersect( $statuses, array( 'publish', 'draft' ) ) );
	return $statuses ? $statuses : array( 'publish' );
}

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
 * Re-order the single product summary to match the prototype (1.18.0):
 * topic pill, title, subtitle, short description, then a buy box holding
 * the price, formats, add to cart, Amazon buttons and the download note;
 * editions and meta below. Description, book details and related books
 * follow in full-width sections. Loop cards get subtitle, formats and a
 * topic / price row.
 */
function sdc_wc_single_layout() {
	// Single book.
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
	add_action( 'woocommerce_single_product_summary', 'sdc_single_topic_pill', 4 );
	add_action( 'woocommerce_single_product_summary', 'sdc_single_subtitle', 5 );
	add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 6 );
	add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 7 );
	add_action( 'woocommerce_single_product_summary', 'sdc_buybox_open', 9 );
	add_action( 'woocommerce_single_product_summary', 'sdc_buybox_price_note', 11 );
	add_action( 'woocommerce_single_product_summary', 'sdc_single_format_badges', 15 );
	add_action( 'woocommerce_single_product_summary', 'sdc_amazon_buttons', 31 );
	add_action( 'woocommerce_single_product_summary', 'sdc_buybox_secure_note', 32 );
	add_action( 'woocommerce_single_product_summary', 'sdc_buybox_close', 35 );
	add_action( 'woocommerce_single_product_summary', 'sdc_single_editions', 36 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
	add_action( 'woocommerce_after_single_product_summary', 'sdc_wc_product_description', 10 );
	add_action( 'woocommerce_after_single_product_summary', 'sdc_wc_product_details', 12 );

	// Book cards (shop, topics, search, [products] shortcode, related books).
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
	add_action( 'woocommerce_after_shop_loop_item_title', 'sdc_loop_subtitle', 3 );
	add_action( 'woocommerce_after_shop_loop_item_title', 'sdc_loop_format_badges', 6 );
	add_action( 'woocommerce_after_shop_loop_item_title', 'sdc_loop_meta', 10 );
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
 * Home page Featured Books (1.19.1): a random pick of published,
 * catalogue-visible books on every page load, so visitors see different
 * titles each time. Returns an empty array when none are published.
 *
 * @param int $limit Maximum number.
 * @return WC_Product[]
 */
function sdc_home_products( $limit ) {
	$limit = max( 1, (int) $limit );
	$ids   = wc_get_products(
		array(
			'status'     => sdc_product_statuses(),
			'visibility' => 'catalog',
			'limit'      => 100,
			'return'     => 'ids',
		)
	);
	if ( ! $ids ) {
		return array();
	}
	shuffle( $ids );
	$ids = (array) apply_filters( 'sdc_home_product_ids', array_slice( $ids, 0, $limit ), $limit );
	return array_values( array_filter( array_map( 'wc_get_product', $ids ) ) );
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
		// 1.12.0: topics show even while empty (as in the prototype); only Uncategorized is hidden.
		if ( ! $term || is_wp_error( $term ) || 'uncategorized' === $term->slug ) {
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

/* ==========================================================================
   1.18.0: book pages matched to the approved prototype.
   Every word and number below comes from the product in WooCommerce (or a
   Customizer field); nothing about a book is written into the theme.
   ========================================================================== */

/**
 * First non-empty value of a product attribute (global pa_ or custom).
 *
 * @param WC_Product $product Product.
 * @param string[]   $names   Attribute names without the pa_ prefix.
 * @return string
 */
function sdc_product_attr_value( $product, $names ) {
	if ( ! $product instanceof WC_Product ) {
		return '';
	}
	foreach ( (array) $names as $name ) {
		foreach ( array( 'pa_' . $name, $name ) as $key ) {
			$value = trim( (string) $product->get_attribute( $key ) );
			if ( '' !== $value ) {
				return $value;
			}
		}
	}
	return '';
}

/**
 * Book subtitle: the "Subtitle" field on the product's General tab, else a
 * product attribute named Subtitle. Empty when neither is set.
 *
 * @param WC_Product $product Product.
 * @return string
 */
function sdc_product_subtitle( $product ) {
	if ( ! $product instanceof WC_Product ) {
		return '';
	}
	$subtitle = trim( (string) $product->get_meta( '_sdc_subtitle' ) );
	if ( '' === $subtitle ) {
		$subtitle = sdc_product_attr_value( $product, array( 'subtitle' ) );
	}
	return (string) apply_filters( 'sdc_product_subtitle', $subtitle, $product );
}

/**
 * Editions (languages, large print...) from an attribute named Edition,
 * Editions or Language.
 *
 * @param WC_Product $product Product.
 * @return string[]
 */
function sdc_product_editions( $product ) {
	$raw = sdc_product_attr_value( $product, array( 'edition', 'editions', 'language', 'languages' ) );
	$out = array_values( array_unique( array_filter( array_map( 'trim', preg_split( '/[,|]/', $raw ) ) ) ) );
	return (array) apply_filters( 'sdc_product_editions', $out, $product );
}

/**
 * The book's topic: its first product category other than Uncategorized.
 *
 * @param WC_Product $product Product.
 * @return WP_Term|null
 */
function sdc_product_topic( $product ) {
	if ( ! $product instanceof WC_Product ) {
		return null;
	}
	$terms = get_the_terms( $product->get_id(), 'product_cat' );
	if ( ! $terms || is_wp_error( $terms ) ) {
		return null;
	}
	foreach ( $terms as $term ) {
		if ( 'uncategorized' !== $term->slug ) {
			return $term;
		}
	}
	return null;
}

/**
 * Amazon fields on the product General tab: meta key => button label.
 *
 * @return array<string,string>
 */
function sdc_amazon_fields() {
	return array(
		'_sdc_amazon_paperback' => __( 'Buy paperback on Amazon', 'sylviasdream-child' ),
		'_sdc_amazon_hardcover' => __( 'Buy hardcover on Amazon', 'sylviasdream-child' ),
		'_sdc_amazon_kindle'    => __( 'Buy on Kindle', 'sylviasdream-child' ),
	);
}

/**
 * Is this an Amazon address (amazon.* or amzn.*)?
 *
 * @param string $url URL.
 * @return bool
 */
function sdc_is_amazon_url( $url ) {
	$host = strtolower( (string) wp_parse_url( (string) $url, PHP_URL_HOST ) );
	return '' !== $host && (bool) preg_match( '/(^|\.)(amazon|amzn)\.[a-z.]+$/', $host );
}

/**
 * Amazon links saved on the product.
 *
 * @param WC_Product $product Product.
 * @return array<int,array{url:string,label:string}>
 */
function sdc_product_amazon_links( $product ) {
	$links = array();
	if ( ! $product instanceof WC_Product ) {
		return $links;
	}
	foreach ( sdc_amazon_fields() as $key => $label ) {
		$url = (string) $product->get_meta( $key );
		if ( $url && sdc_is_amazon_url( $url ) ) {
			$links[] = array(
				'url'   => $url,
				'label' => $label,
			);
		}
	}
	return (array) apply_filters( 'sdc_product_amazon_links', $links, $product );
}

/**
 * Product edit screen, General tab: Subtitle and Amazon links.
 */
function sdc_product_admin_fields() {
	echo '<div class="options_group">';
	woocommerce_wp_text_input(
		array(
			'id'          => '_sdc_subtitle',
			'label'       => __( 'Subtitle', 'sylviasdream-child' ),
			'desc_tip'    => true,
			'description' => __( 'Shown under the title on the book page and on book cards.', 'sylviasdream-child' ),
		)
	);
	woocommerce_wp_text_input(
		array(
			'id'          => '_sdc_free_download',
			'label'       => __( 'Free download link', 'sylviasdream-child' ),
			'type'        => 'url',
			'placeholder' => home_url( '/wp-content/uploads/...' ),
			'desc_tip'    => true,
			'description' => __( 'Only for books Sylvia gives away free. A file or page on this website. When filled, the book shows a Free download button instead of a price and Add to cart. Leave blank for paid books.', 'sylviasdream-child' ),
		)
	);
	woocommerce_wp_text_input(
		array(
			'id'          => '_sdc_buy_link',
			'label'       => __( 'Buy ebook link (Stripe)', 'sylviasdream-child' ),
			'type'        => 'url',
			'placeholder' => 'https://buy.stripe.com/...',
			'desc_tip'    => true,
			'description' => __( "Sylvia's existing Stripe payment link for this ebook (buy.stripe.com only). When filled, the book shows a Buy ebook button that opens it. Never create or edit Stripe links here; copy the one already used on the website.", 'sylviasdream-child' ),
		)
	);
	foreach ( sdc_amazon_fields() as $key => $label ) {
		woocommerce_wp_text_input(
			array(
				'id'          => $key,
				'label'       => $label,
				'type'        => 'url',
				'placeholder' => 'https://www.amazon.com.au/...',
				'desc_tip'    => true,
				'description' => __( 'Amazon address only. Leave blank to hide the button.', 'sylviasdream-child' ),
			)
		);
	}
	echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'sdc_product_admin_fields' );

/**
 * Save the fields above. WooCommerce has already checked its nonce.
 *
 * @param WC_Product $product Product being saved.
 */
function sdc_product_admin_save( $product ) {
	if ( ! current_user_can( 'edit_post', $product->get_id() ) ) {
		return;
	}
	// phpcs:disable WordPress.Security.NonceVerification.Missing -- verified by WooCommerce before this hook.
	if ( isset( $_POST['_sdc_subtitle'] ) ) {
		$product->update_meta_data( '_sdc_subtitle', sanitize_text_field( wp_unslash( $_POST['_sdc_subtitle'] ) ) );
	}
	if ( isset( $_POST['_sdc_free_download'] ) ) {
		$free = esc_url_raw( trim( wp_unslash( $_POST['_sdc_free_download'] ) ), array( 'https', 'http' ) );
		$product->update_meta_data( '_sdc_free_download', ( $free && sdc_is_own_site_url( $free ) ) ? $free : '' );
	}
	if ( isset( $_POST['_sdc_buy_link'] ) ) {
		$buy = esc_url_raw( trim( wp_unslash( $_POST['_sdc_buy_link'] ) ), array( 'https' ) );
		$product->update_meta_data( '_sdc_buy_link', ( $buy && sdc_is_stripe_link( $buy ) ) ? $buy : '' );
	}
	foreach ( array_keys( sdc_amazon_fields() ) as $key ) {
		if ( ! isset( $_POST[ $key ] ) ) {
			continue;
		}
		$url = esc_url_raw( trim( wp_unslash( $_POST[ $key ] ) ), array( 'https', 'http' ) );
		$product->update_meta_data( $key, ( $url && sdc_is_amazon_url( $url ) ) ? $url : '' );
	}
	// phpcs:enable
}
add_action( 'woocommerce_admin_process_product_object', 'sdc_product_admin_save' );

/**
 * Topic pill above the title (links to the topic page).
 */
function sdc_single_topic_pill() {
	global $product;
	$topic = sdc_product_topic( $product );
	if ( ! $topic ) {
		return;
	}
	$link = get_term_link( $topic );
	if ( is_wp_error( $link ) ) {
		return;
	}
	printf( '<a class="sd-pill sd-topic-pill" href="%1$s">%2$s</a>', esc_url( $link ), esc_html( $topic->name ) );
}

/**
 * Italic subtitle under the title.
 */
function sdc_single_subtitle() {
	global $product;
	$subtitle = sdc_product_subtitle( $product );
	if ( '' !== $subtitle ) {
		echo '<p class="sd-product-subtitle">' . esc_html( $subtitle ) . '</p>';
	}
}

/**
 * Open the buy box, with the price label.
 */
function sdc_buybox_open() {
	global $product;
	echo '<div class="sd-buybox">';
	if ( $product instanceof WC_Product && '' !== $product->get_price_html() ) {
		$label = $product->is_downloadable() ? __( 'eBook price', 'sylviasdream-child' ) : __( 'Price', 'sylviasdream-child' );
		echo '<p class="sd-buybox__label">' . esc_html( $label ) . '</p>';
	}
}

/**
 * Currency note beside the price (Customizer text; %s = shop currency).
 */
function sdc_buybox_price_note() {
	global $product;
	if ( ! $product instanceof WC_Product || '' === $product->get_price_html() || '' !== sdc_product_free_download( $product ) ) {
		return;
	}
	$note = sdc_get( 'shop_price_note' );
	if ( '' === $note ) {
		return;
	}
	// 1.18.2: a saved note that contains a web address (dev QA 27 Oct showed
	// "Charged in https://...") falls back to the default wording.
	if ( false !== stripos( $note, 'http' ) || false !== strpos( $note, '/wp-content/' ) ) {
		$defaults = sdc_defaults();
		$note     = (string) ( $defaults['shop_price_note'] ?? '' );
	}
	$currency = get_woocommerce_currency();
	$note     = str_replace( array( '%s', '{currency}' ), $currency, $note );
	echo '<p class="sd-buybox__note">' . esc_html( $note ) . '</p>';
}

/**
 * Amazon buttons saved on the product.
 */
function sdc_amazon_buttons() {
	global $product;
	$links = sdc_product_amazon_links( $product );
	if ( ! $links ) {
		return;
	}
	echo '<div class="sd-btn-row sd-buybox__amazon">';
	foreach ( $links as $link ) {
		printf(
			'<a class="sd-btn sd-btn--ghost" href="%1$s" target="_blank" rel="noopener">%2$s<span class="screen-reader-text"> %3$s</span></a>',
			esc_url( $link['url'] ),
			esc_html( $link['label'] ),
			esc_html__( '(opens Amazon in a new tab)', 'sylviasdream-child' )
		);
	}
	echo '</div>';
}

/**
 * Download note under the buttons (downloadable books only).
 */
function sdc_buybox_secure_note() {
	global $product;
	$note = sdc_get( 'shop_secure_note' );
	if ( '' === $note || ! $product instanceof WC_Product || ! $product->is_downloadable() || ! $product->is_purchasable() ) {
		return;
	}
	echo '<p class="sd-buybox__secure">' . wp_kses( sdc_icon( 'download' ), sdc_svg_kses() ) . '<span>' . esc_html( $note ) . '</span></p>';
}

/**
 * Close the buy box.
 */
function sdc_buybox_close() {
	echo '</div>';
}

/**
 * Editions list.
 */
function sdc_single_editions() {
	global $product;
	$editions = sdc_product_editions( $product );
	if ( ! $editions ) {
		return;
	}
	echo '<div class="sd-editions"><h2 class="sd-editions__title">' . esc_html__( 'Editions', 'sylviasdream-child' ) . '</h2><ul class="sd-formats">';
	foreach ( $editions as $edition ) {
		echo '<li class="sd-pill sd-pill--grey">' . esc_html( $edition ) . '</li>';
	}
	echo '</ul></div>';
}

/**
 * "Book details" table: the product's visible attributes (and weight or
 * dimensions for printed books), as entered in WooCommerce.
 */
function sdc_wc_product_details() {
	global $product;
	if ( ! $product instanceof WC_Product ) {
		return;
	}
	$has = false;
	foreach ( $product->get_attributes() as $attribute ) {
		if ( $attribute->get_visible() ) {
			$has = true;
			break;
		}
	}
	if ( ! $has && ! ( $product->has_weight() || $product->has_dimensions() ) ) {
		return;
	}
	echo '<section class="sd-product-details" aria-labelledby="sd-book-details"><h2 id="sd-book-details">' . esc_html__( 'Book details', 'sylviasdream-child' ) . '</h2>';
	wc_display_product_attributes( $product );
	echo '</section>';
}

/**
 * Subtitle on book cards.
 */
function sdc_loop_subtitle() {
	global $product;
	$subtitle = sdc_product_subtitle( $product );
	if ( '' !== $subtitle ) {
		echo '<p class="sd-card-subtitle">' . esc_html( $subtitle ) . '</p>';
	}
}

/**
 * Topic and price row on book cards (inside the card link, so no links here).
 */
function sdc_loop_meta() {
	global $product;
	if ( ! $product instanceof WC_Product ) {
		return;
	}
	$topic = sdc_product_topic( $product );
	$price = $product->get_price_html();
	if ( ! $topic && '' === $price ) {
		return;
	}
	echo '<div class="sd-card-meta">';
	echo '<span class="sd-card-meta__topic">' . ( $topic ? esc_html( $topic->name ) : '' ) . '</span>';
	if ( '' !== $price ) {
		echo '<span class="price">' . wp_kses_post( $price ) . '</span>';
	}
	echo '</div>';
}

/**
 * Cards for books that cannot be bought yet say "View the book".
 *
 * @param string     $text    Button text.
 * @param WC_Product $product Product.
 * @return string
 */
function sdc_loop_button_text( $text, $product ) {
	if ( $product instanceof WC_Product && $product->is_type( 'simple' ) && ! ( $product->is_purchasable() && $product->is_in_stock() ) ) {
		return __( 'View the book', 'sylviasdream-child' );
	}
	return $text;
}
add_filter( 'woocommerce_product_add_to_cart_text', 'sdc_loop_button_text', 10, 2 );

/**
 * Book search box and topic chips (prototype "searchbar" and "filters").
 * Topics are the product categories in WooCommerce (Uncategorized left out);
 * like the menu, a topic shows even before its books are published.
 *
 * @return string HTML.
 */
function sdc_shop_toolbar_html() {
	$all_url = sdc_shop_url();
	$current = is_product_category() ? get_queried_object_id() : 0;
	$terms   = get_terms(
		(array) apply_filters(
			'sdc_shop_topic_args',
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => false,
				'parent'     => 0,
				'orderby'    => 'name',
			)
		)
	);
	$search  = ( is_search() && 'product' === get_query_var( 'post_type' ) ) ? get_search_query() : '';
	$id      = wp_unique_id( 'sd-book-search-' );
	ob_start();
	?>
	<div class="sd-shop-toolbar">
		<form class="sd-booksearch" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label class="screen-reader-text" for="<?php echo esc_attr( $id ); ?>"><?php esc_html_e( 'Search books', 'sylviasdream-child' ); ?></label>
			<input type="search" id="<?php echo esc_attr( $id ); ?>" name="s" value="<?php echo esc_attr( $search ); ?>" placeholder="<?php echo esc_attr( sdc_get( 'shop_search_placeholder' ) ); ?>">
			<input type="hidden" name="post_type" value="product">
			<button type="submit" class="sd-btn"><?php echo wp_kses( sdc_icon( 'search' ), sdc_svg_kses() ); ?><span><?php esc_html_e( 'Search', 'sylviasdream-child' ); ?></span></button>
		</form>
		<?php if ( $terms && ! is_wp_error( $terms ) ) : ?>
			<nav class="sd-chips" aria-label="<?php esc_attr_e( 'Browse books by topic', 'sylviasdream-child' ); ?>">
				<ul>
					<li><a href="<?php echo esc_url( $all_url ); ?>"<?php echo ( ! $current && '' === $search ) ? ' aria-current="page"' : ''; ?>><?php esc_html_e( 'All', 'sylviasdream-child' ); ?></a></li>
					<?php
					foreach ( $terms as $term ) :
						if ( 'uncategorized' === $term->slug ) {
							continue;
						}
						$link = get_term_link( $term );
						if ( is_wp_error( $link ) ) {
							continue;
						}
						?>
						<li><a href="<?php echo esc_url( $link ); ?>"<?php echo ( (int) $term->term_id === (int) $current ) ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $term->name ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</nav>
		<?php endif; ?>
	</div>
	<?php
	return (string) ob_get_clean();
}

/**
 * Print the toolbar on shop, topic and book-search pages.
 */
function sdc_shop_toolbar() {
	echo sdc_shop_toolbar_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in sdc_shop_toolbar_html().
}

/**
 * [sd_book_filters] for the Books page (a normal page with [products]).
 *
 * @return string
 */
function sdc_book_filters_shortcode() {
	return sdc_shop_toolbar_html();
}
add_shortcode( 'sd_book_filters', 'sdc_book_filters_shortcode' );

/**
 * Optional note under the book grid (Customizer; blank hides it).
 */
function sdc_shop_note() {
	$note = sdc_get( 'shop_note' );
	if ( '' !== $note ) {
		echo '<p class="sd-shop-note">' . esc_html( $note ) . '</p>';
	}
}
add_action( 'woocommerce_after_shop_loop', 'sdc_shop_note', 20 );

/* --------------------------------------------------------------------------
 * 1.18.1: free books (Sylvia's choice: SENT, How to Write and Publish, and
 * 7 Days to Trusting God). A product with a "Free download link" shows a
 * Free download button, "Free" instead of a price, and cannot be added to
 * the cart. Every other book stays paid. Links must point to this website.
 * ------------------------------------------------------------------------ */

/**
 * Is the URL on this website (same host as home_url)?
 *
 * @param string $url URL.
 * @return bool
 */
function sdc_is_own_site_url( $url ) {
	$host = strtolower( (string) wp_parse_url( (string) $url, PHP_URL_HOST ) );
	$home = strtolower( (string) wp_parse_url( home_url(), PHP_URL_HOST ) );
	return '' !== $host && $host === $home;
}

/**
 * Free download link for a product, or ''.
 *
 * @param WC_Product|null $product Product.
 * @return string
 */
function sdc_product_free_download( $product ) {
	if ( ! $product instanceof WC_Product ) {
		return '';
	}
	$url = (string) $product->get_meta( '_sdc_free_download' );
	return ( $url && sdc_is_own_site_url( $url ) ) ? $url : '';
}

/**
 * Free books are never added to the cart.
 *
 * @param bool       $purchasable Purchasable.
 * @param WC_Product $product     Product.
 * @return bool
 */
function sdc_free_not_purchasable( $purchasable, $product ) {
	return '' !== sdc_product_free_download( $product ) ? false : $purchasable;
}
add_filter( 'woocommerce_is_purchasable', 'sdc_free_not_purchasable', 20, 2 );

/**
 * Show "Free" instead of a price.
 *
 * @param string     $html    Price HTML.
 * @param WC_Product $product Product.
 * @return string
 */
function sdc_free_price_html( $html, $product ) {
	if ( '' === sdc_product_free_download( $product ) ) {
		return $html;
	}
	return '<span class="sd-price-free">' . esc_html__( 'Free', 'sylviasdream-child' ) . '</span>';
}
add_filter( 'woocommerce_get_price_html', 'sdc_free_price_html', 20, 2 );

/**
 * Free download button in the buy box.
 */
function sdc_free_download_button() {
	global $product;
	$url = sdc_product_free_download( $product );
	if ( '' === $url ) {
		return;
	}
	printf(
		'<div class="sd-btn-row sd-buybox__free"><a class="sd-btn sd-btn--primary" href="%1$s">%2$s%3$s</a></div>',
		esc_url( $url ),
		wp_kses( sdc_icon( 'download' ), sdc_svg_kses() ),
		esc_html__( 'Free download', 'sylviasdream-child' )
	);
}
add_action( 'woocommerce_single_product_summary', 'sdc_free_download_button', 29 );

/**
 * Card button text for free books.
 *
 * @param string     $text    Button text.
 * @param WC_Product $product Product.
 * @return string
 */
function sdc_free_loop_button_text( $text, $product ) {
	return '' !== sdc_product_free_download( $product ) ? __( 'Free download', 'sylviasdream-child' ) : $text;
}
add_filter( 'woocommerce_product_add_to_cart_text', 'sdc_free_loop_button_text', 20, 2 );

/**
 * 1.19.0: is this a Stripe payment link (https://buy.stripe.com/...)?
 *
 * @param string $url URL.
 * @return bool
 */
function sdc_is_stripe_link( $url ) {
	$parts = wp_parse_url( (string) $url );
	return ! empty( $parts['scheme'] ) && 'https' === $parts['scheme'] && ! empty( $parts['host'] ) && 'buy.stripe.com' === strtolower( $parts['host'] ) && ! empty( $parts['path'] ) && '/' !== $parts['path'];
}

/**
 * Saved Stripe buy link for a product ('' when none).
 *
 * @param WC_Product|null $product Product.
 * @return string
 */
function sdc_product_buy_link( $product ) {
	if ( ! $product instanceof WC_Product ) {
		return '';
	}
	$url = (string) $product->get_meta( '_sdc_buy_link' );
	return sdc_is_stripe_link( $url ) ? $url : '';
}

/**
 * Buy ebook button in the buy box (Sylvia's existing Stripe link). Works for
 * Draft books too, because it does not use the cart.
 */
function sdc_buy_link_button() {
	global $product;
	$url = sdc_product_buy_link( $product );
	if ( '' === $url || '' !== sdc_product_free_download( $product ) ) {
		return;
	}
	printf(
		'<div class="sd-btn-row sd-buybox__buy"><a class="sd-btn sd-btn--primary" href="%1$s" rel="noopener">%2$s</a></div><p class="sd-buybox__secure">%3$s</p>',
		esc_url( $url ),
		esc_html__( 'Buy ebook', 'sylviasdream-child' ),
		esc_html__( 'Secure payment by Stripe. Your download link appears straight after payment.', 'sylviasdream-child' )
	);
}
add_action( 'woocommerce_single_product_summary', 'sdc_buy_link_button', 28 );

/**
 * Hide the cart button when a Stripe buy link is used, so readers see one clear option.
 *
 * @param bool       $purchasable Purchasable.
 * @param WC_Product $product     Product.
 * @return bool
 */
function sdc_buy_link_not_purchasable( $purchasable, $product ) {
	return ( $product instanceof WC_Product && '' !== sdc_product_buy_link( $product ) ) ? false : $purchasable;
}
add_filter( 'woocommerce_is_purchasable', 'sdc_buy_link_not_purchasable', 20, 2 );
