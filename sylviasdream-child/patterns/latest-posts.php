<?php
/**
 * Title: Latest from Sylvia
 * Slug: sylviasdream-child/latest-posts
 * Categories: sylviasdream
 * Description: Three newest devotionals as cards, with a link to all reflections (prototype v4).
 * Viewport Width: 1400
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;
$sdc_blog = get_option( 'page_for_posts' ) ? get_permalink( (int) get_option( 'page_for_posts' ) ) : home_url( '/' );
?>
<!-- wp:group {"align":"full","className":"sd-home-band","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull sd-home-band"><!-- wp:group {"className":"sd-band__inner","layout":{"type":"default"}} -->
<div class="wp-block-group sd-band__inner"><!-- wp:paragraph {"align":"center","className":"sd-eyebrow"} -->
<p class="has-text-align-center sd-eyebrow">Grace for the Journey</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">Latest from Sylvia</h2>
<!-- /wp:heading -->

<!-- wp:latest-posts {"postsToShow":3,"displayPostContent":true,"excerptLength":22,"displayPostDate":true,"postLayout":"grid","columns":3,"displayFeaturedImage":true,"featuredImageSizeSlug":"medium_large","className":"sd-latest"} /-->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $sdc_blog ); ?>">Read all reflections</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
