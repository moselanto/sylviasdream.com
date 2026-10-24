<?php
/**
 * Title: Have a book on your heart?
 * Slug: sylviasdream-child/author-promo
 * Categories: sylviasdream
 * Description: Author Support promotion: photo and invitation (prototype v4).
 * Viewport Width: 1400
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"sd-home-band sd-home-band--alt","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull sd-home-band sd-home-band--alt"><!-- wp:group {"className":"sd-band__inner","layout":{"type":"default"}} -->
<div class="wp-block-group sd-band__inner"><!-- wp:columns {"verticalAlignment":"center","className":"sd-split"} -->
<div class="wp-block-columns are-vertically-aligned-center sd-split"><!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:paragraph {"className":"sd-eyebrow"} -->
<p class="sd-eyebrow">Author Support</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Have a book on your heart?</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"sd-lead"} -->
<p class="sd-lead">Personal guidance with manuscript preparation, self-publishing, book launches, and marketing.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/author-support-publishing-services/#book-enquiry' ) ); ?>">Let’s Talk About Your Book</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:image {"sizeSlug":"large","className":"sd-photo-round"} -->
<figure class="wp-block-image size-large sd-photo-round"><img src="<?php echo esc_url( sdc_theme_image( 'desk' ) ); ?>" alt="A writer's desk with books and wildflowers"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
