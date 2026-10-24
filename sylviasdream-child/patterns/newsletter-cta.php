<?php
/**
 * Title: Newsletter sign-up
 * Slug: sylviasdream-child/newsletter-cta
 * Categories: sylviasdream
 * Description: Gold sign-up card: heading, intro, email field (Jetpack Subscribe) with a Contact fallback.
 * Viewport Width: 1400
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"sd-home-band","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull sd-home-band"><!-- wp:group {"className":"sd-band__inner sd-news-card","layout":{"type":"default"}} -->
<div class="wp-block-group sd-band__inner sd-news-card"><!-- wp:columns {"verticalAlignment":"center"} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:paragraph {"className":"sd-eyebrow"} -->
<p class="sd-eyebrow">Grace for the Journey</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Weekly encouragement in your inbox</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Receive Sylvia’s devotional letter, new book announcements and news from Kenya.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:jetpack/subscriptions /-->

<!-- wp:buttons {"className":"sd-news-fallback"} -->
<div class="wp-block-buttons sd-news-fallback"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact Sylvia</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:paragraph {"className":"sd-news-note"} -->
<p class="sd-news-note">No spam. Unsubscribe at any time.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
