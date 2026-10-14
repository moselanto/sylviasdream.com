<?php
/**
 * WooCommerce wrapper for the book shop.
 *
 * @package SylviasDreamChild
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<main id="main-content" class="site-main">
	<div class="container">
		<?php woocommerce_content(); ?>
	</div>
</main>
<?php
get_footer();
