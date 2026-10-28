<?php
/**
 * 0.3.0: reviewer preview mode ("exact experience" before launch).
 *
 * OFF unless wp-config.php sets:  define( 'SD_PREVIEW_MODE', true );
 * or (0.4.3) Settings > Sylvia's Dream > Reviewer preview is ticked. Never on
 * the live domain, whichever switch is used.
 * Use it on the dev site only. When on:
 *  - signed-in users who can edit posts (or have the `sd_reviewer` capability)
 *    see Draft products in the shop, category, search and product block loops
 *    and on the home page, and can open each Draft product page;
 *  - visitors who are not signed in never see drafts;
 *  - Draft products can never be added to the cart;
 *  - preview pages are sent with noindex.
 * Products stay Draft: nothing here changes a product's status.
 *
 * @package SylviasDreamCore
 */

defined( 'ABSPATH' ) || exit;

/**
 * Is preview mode active for this request?
 *
 * @return bool
 */
function sd_core_preview_active() {
	$on = ( defined( 'SD_PREVIEW_MODE' ) && true === SD_PREVIEW_MODE ) || 1 === (int) sd_core_opt( 'preview_enabled' );
	if ( ! $on || ! is_user_logged_in() || sd_core_is_live_site() ) {
		return false;
	}
	$can = current_user_can( 'edit_posts' ) || current_user_can( 'sd_reviewer' );
	/**
	 * Filter who may see Draft products in preview mode.
	 *
	 * @param bool $can Current user may preview.
	 */
	return (bool) apply_filters( 'sd_core_preview_can_view', $can );
}

/**
 * Product statuses to show in front-end loops.
 *
 * @return string[]
 */
function sd_core_preview_product_statuses() {
	return sd_core_preview_active() ? array( 'publish', 'draft' ) : array( 'publish' );
}

/**
 * Add drafts to the main product queries (shop, categories, tags, search,
 * single product).
 *
 * @param WP_Query $query Query.
 */
function sd_core_preview_main_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() || ! sd_core_preview_active() ) {
		return;
	}
	$type     = (array) $query->get( 'post_type' );
	$is_prod  = in_array( 'product', $type, true )
		|| $query->is_post_type_archive( 'product' )
		|| $query->is_tax( array( 'product_cat', 'product_tag' ) )
		|| ( '' !== (string) $query->get( 'product' ) );
	if ( $query->is_search() ) {
		// Search covers posts and pages too: only Draft products are added.
		add_filter( 'posts_where', 'sd_core_preview_search_where', 10, 2 );
		return;
	}
	if ( $is_prod ) {
		$query->set( 'post_status', array( 'publish', 'draft' ) );
	}
}
add_action( 'pre_get_posts', 'sd_core_preview_main_query', 20 );

/**
 * In search results, allow Draft rows only for products.
 *
 * @param string   $where SQL WHERE.
 * @param WP_Query $query Query.
 * @return string
 */
function sd_core_preview_search_where( $where, $query ) {
	global $wpdb;
	if ( ! $query->is_main_query() || ! $query->is_search() ) {
		return $where;
	}
	remove_filter( 'posts_where', 'sd_core_preview_search_where', 10 );
	$where .= $wpdb->prepare( " OR ( {$wpdb->posts}.post_type = %s AND {$wpdb->posts}.post_status = %s", 'product', 'draft' );
	// Repeat the search terms for the extra rows.
	$s = (string) $query->get( 's' );
	if ( '' !== $s ) {
		$like   = '%' . $wpdb->esc_like( $s ) . '%';
		$where .= $wpdb->prepare( " AND ( {$wpdb->posts}.post_title LIKE %s OR {$wpdb->posts}.post_excerpt LIKE %s OR {$wpdb->posts}.post_content LIKE %s )", $like, $like, $like );
	}
	return $where . ' )';
}

/**
 * Product shortcodes ([products]) and product blocks in preview.
 *
 * @param array $args Query args.
 * @return array
 */
function sd_core_preview_query_args( $args ) {
	if ( ! sd_core_preview_active() ) {
		return $args;
	}
	$type = isset( $args['post_type'] ) ? (array) $args['post_type'] : array();
	if ( in_array( 'product', $type, true ) ) {
		$args['post_status'] = array( 'publish', 'draft' );
	}
	return $args;
}
add_filter( 'woocommerce_shortcode_products_query', 'sd_core_preview_query_args' );
add_filter( 'query_loop_block_query_vars', 'sd_core_preview_query_args' );
add_filter( 'woocommerce_blocks_product_grid_query_args', 'sd_core_preview_query_args' );

/**
 * Theme hook: statuses used by the home page and the Books mega menu.
 *
 * @param string[] $statuses Statuses.
 * @return string[]
 */
function sd_core_preview_theme_statuses( $statuses ) {
	return sd_core_preview_active() ? array_values( array_unique( array_merge( (array) $statuses, array( 'draft' ) ) ) ) : $statuses;
}
add_filter( 'sdc_product_statuses', 'sd_core_preview_theme_statuses' );

/**
 * Draft products are never purchasable, even for administrators.
 *
 * @param bool       $purchasable Purchasable.
 * @param WC_Product $product     Product.
 * @return bool
 */
function sd_core_preview_not_purchasable( $purchasable, $product ) {
	if ( ! is_object( $product ) || ! method_exists( $product, 'get_id' ) ) {
		return $purchasable;
	}
	$id = $product->get_parent_id() ? $product->get_parent_id() : $product->get_id();
	if ( 'publish' !== get_post_status( $id ) ) {
		return false;
	}
	return $purchasable;
}
add_filter( 'woocommerce_is_purchasable', 'sd_core_preview_not_purchasable', 99, 2 );
add_filter( 'woocommerce_variation_is_purchasable', 'sd_core_preview_not_purchasable', 99, 2 );

/**
 * Refuse add-to-cart for a Draft product (direct ?add-to-cart= requests too).
 *
 * @param bool $passed     Passed.
 * @param int  $product_id Product ID.
 * @return bool
 */
function sd_core_preview_block_add_to_cart( $passed, $product_id ) {
	if ( 'publish' !== get_post_status( absint( $product_id ) ) ) {
		if ( function_exists( 'wc_add_notice' ) ) {
			wc_add_notice( __( 'This book is not on sale yet.', 'sylviasdream-core' ), 'error' );
		}
		return false;
	}
	return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'sd_core_preview_block_add_to_cart', 99, 2 );

/**
 * noindex while drafts may be on the page.
 *
 * @param array $robots Robots directives.
 * @return array
 */
function sd_core_preview_robots( $robots ) {
	if ( sd_core_preview_active() ) {
		$robots['noindex']  = true;
		$robots['nofollow'] = true;
	}
	return $robots;
}
add_filter( 'wp_robots', 'sd_core_preview_robots', 100 );

/**
 * Small notice on a Draft product page so reviewers know it is not live yet.
 */
function sd_core_preview_notice() {
	if ( ! sd_core_preview_active() || ! function_exists( 'is_product' ) || ! is_product() || 'draft' !== get_post_status( get_queried_object_id() ) ) {
		return;
	}
	echo '<p class="sd-preview-note" role="note">' . esc_html__( 'Preview: this book is still a draft and is not visible to the public yet. Buying is switched off until launch.', 'sylviasdream-core' ) . '</p>';
}
add_action( 'woocommerce_before_single_product', 'sd_core_preview_notice', 5 );
