<?php
/**
 * Title: Page header
 * Slug: sylviasdream-child/page-header
 * Categories: sylviasdream
 * Description: Photo banner with a small heading, page title and short intro. Use once at the top of a page.
 * Keywords: header, title, banner
 * Viewport Width: 1400
 *
 * @package SylviasDreamChild
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:cover {"url":"<?php echo esc_url( sdc_theme_image( 'bible' ) ); ?>","alt":"","dimRatio":70,"overlayColor":"ink","minHeight":380,"minHeightUnit":"px","align":"full","className":"sd-pagehead-cover","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-cover alignfull sd-pagehead-cover" style="min-height:380px"><span aria-hidden="true" class="wp-block-cover__background has-ink-background-color has-background-dim-70 has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( sdc_theme_image( 'bible' ) ); ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container">
<!-- wp:paragraph {"className":"sd-eyebrow"} -->
<p class="sd-eyebrow">Small heading</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"textColor":"white"} -->
<h1 class="wp-block-heading has-white-color has-text-color">Page title</h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"white","className":"sd-lead"} -->
<p class="has-white-color has-text-color sd-lead">A short introduction to this page.</p>
<!-- /wp:paragraph -->
</div></div>
<!-- /wp:cover -->
