<?php
/**
 * Title: Dream Partners band
 * Slug: sylviasdream-child/dream-partners
 * Categories: sylviasdream
 * Description: Monthly giving invitation with the live Dream Partner progress bar and a Give button.
 * Keywords: give, donate, dream partners
 * Viewport Width: 1400
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"backgroundColor":"white","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull has-white-background-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
<!-- wp:columns {"verticalAlignment":"center","align":"wide"} -->
<div class="wp-block-columns are-vertically-aligned-center alignwide">
<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center">
<!-- wp:paragraph {"className":"sd-eyebrow"} -->
<p class="sd-eyebrow">Dream Partners</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class="wp-block-heading">Become a Dream Partner</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"sd-lead"} -->
<p class="sd-lead">Join a community of monthly givers bringing hope, education and the love of Christ to children in rural Kenya.</p>
<!-- /wp:paragraph -->
<!-- wp:shortcode -->
[sd_partner_progress]
<!-- /wp:shortcode -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/give/' ) ); ?>">Become a Dream Partner</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center">
<!-- wp:image {"sizeSlug":"large","className":"sd-photo-round"} -->
<figure class="wp-block-image size-large sd-photo-round"><img src="<?php echo esc_url( sdc_theme_image( 'give' ) ); ?>" alt="Cupped hands holding a young seedling in soil"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
