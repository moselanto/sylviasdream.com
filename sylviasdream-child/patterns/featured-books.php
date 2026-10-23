<?php
/**
 * Title: Featured books
 * Slug: sylviasdream-child/featured-books
 * Categories: sylviasdream
 * Description: Heading, intro and a grid of eight books from the shop.
 * Keywords: books, shop, products
 * Viewport Width: 1400
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
<!-- wp:paragraph {"align":"center","className":"sd-eyebrow"} -->
<p class="has-text-align-center sd-eyebrow">Featured books</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Books that bring hope and healing</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"align":"center","className":"sd-lead"} -->
<p class="has-text-align-center sd-lead">Start with the story that began it all, or choose the book for the season you are in.</p>
<!-- /wp:paragraph -->
<!-- wp:group {"align":"wide","layout":{"type":"default"}} -->
<div class="wp-block-group alignwide">
<!-- wp:shortcode -->
[products limit="8" columns="4" orderby="menu_order"]
<!-- /wp:shortcode -->
</div>
<!-- /wp:group -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( sdc_shop_url() ); ?>">See all books</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
