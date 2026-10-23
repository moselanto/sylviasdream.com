<?php
/**
 * WooCommerce wrapper for the book shop (shop, categories, single book).
 * Cart, checkout and account are normal pages (page.php).
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main id="main-content" class="site-main sd-shop" tabindex="-1">
	<?php if ( is_singular( 'product' ) ) : ?>
		<div class="sd-wrap sd-section sd-section--product">
			<?php woocommerce_breadcrumb(); ?>
			<?php woocommerce_content(); ?>
		</div>
	<?php else : ?>
		<?php
		$sdc_intro = '';
		if ( is_shop() ) {
			$sdc_intro = sdc_get( 'shop_intro' );
		} elseif ( is_product_taxonomy() ) {
			$sdc_intro = wp_strip_all_tags( term_description() );
		}
		sdc_page_header( wp_strip_all_tags( woocommerce_page_title( false ) ), sdc_get( 'shop_eyebrow' ), $sdc_intro );
		?>
		<div class="sd-wrap sd-section">
			<?php woocommerce_content(); ?>
		</div>
	<?php endif; ?>
</main>
<?php
get_footer();
