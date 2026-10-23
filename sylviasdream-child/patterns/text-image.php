<?php
/**
 * Title: Two columns: text and image
 * Slug: sylviasdream-child/text-image
 * Categories: sylviasdream
 * Description: Heading and text beside a photo. Swap the photo from the image toolbar.
 * Keywords: text, image, columns, two column
 * Viewport Width: 1400
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60)">
<!-- wp:columns {"verticalAlignment":"center","align":"wide"} -->
<div class="wp-block-columns are-vertically-aligned-center alignwide">
<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center">
<!-- wp:heading -->
<h2 class="wp-block-heading">Add a heading</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Add your text here. Click to type, or press Enter for a new paragraph.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/' ) ); ?>">Button label</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center">
<!-- wp:image {"sizeSlug":"large","className":"sd-photo-round"} -->
<figure class="wp-block-image size-large sd-photo-round"><img src="<?php echo esc_url( sdc_theme_image( 'desk' ) ); ?>" alt="A writer’s desk with books and wildflowers"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
