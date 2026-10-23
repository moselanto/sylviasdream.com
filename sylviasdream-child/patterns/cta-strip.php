<?php
/**
 * Title: Call to action strip
 * Slug: sylviasdream-child/cta-strip
 * Categories: sylviasdream
 * Description: Gold band with a short heading and one button.
 * Keywords: cta, button, banner
 * Viewport Width: 1400
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"backgroundColor":"gold-dark","className":"sd-cta-strip","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull has-gold-dark-background-color has-background sd-cta-strip" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
<!-- wp:heading {"textAlign":"center","textColor":"white"} -->
<h2 class="wp-block-heading has-text-align-center has-white-color has-text-color">Books that bring hope and healing</h2>
<!-- /wp:heading -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( sdc_shop_url() ); ?>">Explore the Books</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:group -->
