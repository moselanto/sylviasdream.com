<?php
/**
 * Title: Newsletter and contact
 * Slug: sylviasdream-child/newsletter-cta
 * Categories: sylviasdream
 * Description: Dark band inviting readers to the weekly letter, with a sign-up form (Jetpack) and a Contact button.
 * Keywords: newsletter, subscribe, contact, email
 * Viewport Width: 1400
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"backgroundColor":"ink","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull has-ink-background-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
<!-- wp:columns {"verticalAlignment":"center","align":"wide"} -->
<div class="wp-block-columns are-vertically-aligned-center alignwide">
<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center">
<!-- wp:paragraph {"className":"sd-eyebrow"} -->
<p class="sd-eyebrow">Grace for the Journey</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"textColor":"white"} -->
<h2 class="wp-block-heading has-white-color has-text-color">Weekly encouragement in your inbox</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"white"} -->
<p class="has-white-color has-text-color">Receive Sylvia’s devotional letter, new book announcements and news from Kenya.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center">
<!-- wp:jetpack/subscriptions /-->
<!-- wp:paragraph {"textColor":"gold-light"} -->
<p class="has-gold-light-color has-text-color">No spam. Unsubscribe at any time.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact Sylvia</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
