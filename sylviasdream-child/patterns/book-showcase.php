<?php
/**
 * Title: Book showcase
 * Slug: sylviasdream-child/book-showcase
 * Categories: sylviasdream
 * Description: Photo beside a short introduction to the bookshop.
 * Keywords: books, shop, showcase
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
<!-- wp:image {"sizeSlug":"large","className":"sd-photo-round"} -->
<figure class="wp-block-image size-large sd-photo-round"><img src="<?php echo esc_url( sdc_theme_image( 'desk' ) ); ?>" alt="A writer’s desk with books and wildflowers"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->
<!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center">
<!-- wp:paragraph {"className":"sd-eyebrow"} -->
<p class="sd-eyebrow">The bookshop</p>
<!-- /wp:paragraph -->
<!-- wp:heading -->
<h2 class="wp-block-heading">Books by Sylvia Suhr</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"sd-lead"} -->
<p class="sd-lead">eBooks delivered instantly by secure download. Paperbacks and Kindle editions through Amazon.</p>
<!-- /wp:paragraph -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( sdc_shop_url() ); ?>">Explore the Books</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns -->
</div>
<!-- /wp:group -->
