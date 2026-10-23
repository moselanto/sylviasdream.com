<?php
/**
 * Title: Heaven on Earth band
 * Slug: sylviasdream-child/heaven-on-earth
 * Categories: sylviasdream
 * Description: Full-width photo of the Kenya ministry with a statement and button.
 * Keywords: ministry, kenya, heaven on earth
 * Viewport Width: 1400
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:cover {"url":"<?php echo esc_url( sdc_theme_image( 'mission' ) ); ?>","alt":"A village church in rural Kenya at golden hour","dimRatio":70,"overlayColor":"ink","minHeight":560,"minHeightUnit":"px","align":"full","className":"sd-band-cover","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-cover alignfull sd-band-cover" style="min-height:560px"><span aria-hidden="true" class="wp-block-cover__background has-ink-background-color has-background-dim-70 has-background-dim"></span><img class="wp-block-cover__image-background" alt="A village church in rural Kenya at golden hour" src="<?php echo esc_url( sdc_theme_image( 'mission' ) ); ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container">
<!-- wp:paragraph {"align":"center","className":"sd-eyebrow"} -->
<p class="has-text-align-center sd-eyebrow">Heaven on Earth, Kenya</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textAlign":"center","textColor":"white"} -->
<h2 class="wp-block-heading has-text-align-center has-white-color has-text-color">Rescuing vulnerable children, educating the next generation and bringing the love of Christ to rural villages.</h2>
<!-- /wp:heading -->
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/heaven-on-earth/' ) ); ?>">About the ministry</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div></div>
<!-- /wp:cover -->
